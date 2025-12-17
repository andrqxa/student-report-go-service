package main

import (
	"context"
	"go-service/internal/config"
	"log/slog"
	"os"
	"os/signal"
	"syscall"
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
		os.Interrupt,
		syscall.SIGINT,
		syscall.SIGTERM,
	)
	defer stop()

	// Wait for termination signal
	<-ctx.Done()
	log.Info("shutdown signal received")
}
