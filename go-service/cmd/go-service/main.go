package main

import (
	"context"
	"errors"
	"go-service/internal/config"
	"go-service/internal/httpapi"
	"go-service/internal/nodeclient"
	"log/slog"
	"net/http"
	"os"
	"os/signal"
	"syscall"
	"time"
)

func initLogger(level string) *slog.Logger {
	handler := slog.NewJSONHandler(os.Stdout, &slog.HandlerOptions{
		Level: parseLogLevel(level),
	})
	logg := slog.New(handler)
	slog.SetDefault(logg)
	return logg
}

func parseLogLevel(level string) slog.Level {
	switch level {
	case "debug":
		return slog.LevelDebug
	case "warn":
		return slog.LevelWarn
	case "error":
		return slog.LevelError
	default:
		return slog.LevelInfo
	}
}

func main() {

	// Init config
	cfg := config.Load()

	// Init logger
	log := initLogger(cfg.LogLevel)

	log.Info("configuration loaded",
		"addr", cfg.Addr,
		"request_timeout", cfg.RequestTimeout.String(),
		"log_level", cfg.LogLevel,
		"node_api_base_url", cfg.NodeURL,
	)

	// Root context with OS signals for graceful shutdown
	ctx, stop := signal.NotifyContext(context.Background(),
		syscall.SIGINT,
		syscall.SIGTERM,
	)
	defer stop()

	// Initialize Node API client.
	node, err := nodeclient.New(nodeclient.Options{
		BaseURL:        cfg.NodeURL,
		RequestTimeout: cfg.RequestTimeout,
		StrictJSON:     true,
		Logger:         log,
	})
	if err != nil {
		log.Error("failed to initialize node client", "err", err)
		os.Exit(1)
	}

	// Build HTTP router.
	router := httpapi.NewRouter(httpapi.RouterOptions{
		Logger:         log,
		NodeClient:     node,
		RequestTimeout: cfg.RequestTimeout,
	})

	srv := &http.Server{
		Addr:              cfg.Addr,
		Handler:           router,
		ReadHeaderTimeout: 5 * time.Second,
	}

	// Start server.
	go func() {
		log.Info("starting http server", "addr", cfg.Addr)
		if err := srv.ListenAndServe(); err != nil && !errors.Is(err, http.ErrServerClosed) {
			log.Error("http server failed", "err", err)
			stop() // trigger shutdown path
		}
	}()

	// Wait for termination signal.
	<-ctx.Done()
	log.Info("shutdown signal received")

	// Graceful shutdown with timeout.
	shutdownCtx, cancel := context.WithTimeout(context.Background(), 10*time.Second)
	defer cancel()

	if err := srv.Shutdown(shutdownCtx); err != nil {
		log.Error("http server shutdown failed", "err", err)
		_ = srv.Close()
	} else {
		log.Info("http server stopped")
	}
}
