package httpapi

import (
	"context"
	"errors"
	"fmt"
	"go-service/internal/nodeclient"
	"go-service/internal/pdf"
	"log/slog"
	"net/http"
	"strings"
	"time"

	"github.com/go-chi/chi/v5"
)

type reportHandler struct {
	log            *slog.Logger
	nodeClient     *nodeclient.Client
	requestTimeout time.Duration
}

func (h *reportHandler) getStudentReport(w http.ResponseWriter, r *http.Request) {
	if h.log == nil {
		h.log = slog.Default()
	}

	id := strings.TrimSpace(chi.URLParam(r, "id"))
	if id == "" {
		writeJSONError(w, http.StatusBadRequest, "student id is required")
		return
	}

	ctx, cancel := context.WithTimeout(r.Context(), h.requestTimeout)
	defer cancel()

	student, err := h.nodeClient.GetStudent(ctx, id)
	if err != nil {
		h.writeNodeError(w, r, id, err)
		return
	}

	pdfBytes, err := pdf.Generate(student)
	if err != nil {
		h.log.Error("pdf_generation_failed", "student_id", id, "err", err)
		writeJSONError(w, http.StatusInternalServerError, "failed to generate pdf")
		return
	}

	filename := fmt.Sprintf(`attachment; filename="student-%s-report.pdf"`, id)
	w.Header().Set("Content-Type", "application/pdf")
	w.Header().Set("Content-Disposition", filename)
	w.WriteHeader(http.StatusOK)

	_, _ = w.Write(pdfBytes)
}

func (h *reportHandler) writeNodeError(w http.ResponseWriter, r *http.Request, id string, err error) {
	status := http.StatusBadGateway
	msg := "upstream error"

	switch {
	case errors.Is(err, context.DeadlineExceeded):
		status = http.StatusGatewayTimeout
		msg = "upstream timeout"
	case errors.Is(err, context.Canceled):
		status = http.StatusGatewayTimeout
		msg = "request canceled"
	case errors.Is(err, nodeclient.ErrNotFound):
		status = http.StatusNotFound
		msg = "student not found"
	case errors.Is(err, nodeclient.ErrRateLimited):
		status = http.StatusServiceUnavailable
		msg = "upstream rate limited"
	case errors.Is(err, nodeclient.ErrUnauthorized), errors.Is(err, nodeclient.ErrForbidden):
		status = http.StatusBadGateway
		msg = "upstream auth failed"
	case errors.Is(err, nodeclient.ErrBadRequest):
		status = http.StatusBadGateway
		msg = "upstream bad request"
	case errors.Is(err, nodeclient.ErrUpstream):
		status = http.StatusBadGateway
		msg = "upstream error"
	}

	h.log.Warn("node_request_failed",
		"student_id", id,
		"status", status,
		"mapped_error", msg,
		"err", err,
	)

	writeJSONError(w, status, msg)
}
