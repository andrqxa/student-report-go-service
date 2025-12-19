package httpapi

import (
	"go-service/internal/nodeclient"
	"log/slog"
	"net/http"
	"time"

	"github.com/go-chi/chi/v5"
	"github.com/go-chi/chi/v5/middleware"
)

// RouterOptions defines dependencies for HTTP router initialization.
type RouterOptions struct {
	Logger         *slog.Logger
	NodeClient     *nodeclient.Client
	RequestTimeout time.Duration
}

// NewRouter builds the HTTP router for the Go service.
func NewRouter(opts RouterOptions) http.Handler {
	r := chi.NewRouter()

	// Baseline middlewares.
	r.Use(middleware.RequestID)
	r.Use(middleware.Recoverer)
	r.Use(middleware.RealIP)

	// Lightweight structured request logging.
	r.Use(newSlogRequestLogger(opts.Logger))

	h := &reportHandler{
		log:            opts.Logger,
		nodeClient:     opts.NodeClient,
		requestTimeout: opts.RequestTimeout,
	}

	r.Route("/api/v1", func(r chi.Router) {
		r.Get("/students/{id}/report", h.getStudentReport)
	})

	return r
}

func newSlogRequestLogger(log *slog.Logger) func(http.Handler) http.Handler {
	if log == nil {
		log = slog.Default()
	}

	return func(next http.Handler) http.Handler {
		return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
			ww := middleware.NewWrapResponseWriter(w, r.ProtoMajor)
			start := time.Now()

			next.ServeHTTP(ww, r)

			log.Info("http_request",
				"method", r.Method,
				"path", r.URL.Path,
				"status", ww.Status(),
				"bytes", ww.BytesWritten(),
				"duration_ms", time.Since(start).Milliseconds(),
				"request_id", middleware.GetReqID(r.Context()),
			)
		})
	}
}
