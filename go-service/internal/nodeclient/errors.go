package nodeclient

import (
	"context"
	"errors"
	"fmt"
	"net/http"
)

// Sentinel errors for common HTTP status codes.
var (
	// ErrNotFound indicates the requested resource was not found (404).
	ErrNotFound = errors.New("resource not found")
	// ErrRateLimited indicates the rate limit has been exceeded (429).
	ErrRateLimited = errors.New("rate limit exceeded")
	// ErrBadRequest indicates a malformed or invalid request (400).
	ErrBadRequest = errors.New("bad request")
	// ErrUnauthorized indicates authentication is required (401).
	ErrUnauthorized = errors.New("unauthorized")
	// ErrForbidden indicates the request is not allowed (403).
	ErrForbidden = errors.New("forbidden")
	// ErrServerError indicates a server-side error occurred (5xx).
	ErrServerError = errors.New("server error")
)

// HTTPError represents an HTTP error response with status code and body details.
// It wraps a base error that can be checked using errors.Is().
type HTTPError struct {
	StatusCode int
	Body       string
	Err        error
}

// Error returns a formatted string representation of the HTTP error.
func (e *HTTPError) Error() string {
	if e.Body != "" {
		return fmt.Sprintf("http %d: %s: %s", e.StatusCode, e.Err, e.Body)
	}
	return fmt.Sprintf("http %d: %s", e.StatusCode, e.Err)
}

// Unwrap returns the underlying error for use with errors.Is() and errors.As().
func (e *HTTPError) Unwrap() error {
	return e.Err
}

// IsNotFound checks if an error represents a 404 Not Found response.
func IsNotFound(err error) bool {
	return errors.Is(err, ErrNotFound)
}

// IsRateLimited checks if an error represents a 429 Too Many Requests response.
func IsRateLimited(err error) bool {
	return errors.Is(err, ErrRateLimited)
}

// IsClientError checks if an error represents a 4xx client error response.
func IsClientError(err error) bool {
	return errors.Is(err, ErrBadRequest) ||
		errors.Is(err, ErrUnauthorized) ||
		errors.Is(err, ErrForbidden) ||
		errors.Is(err, ErrNotFound)
}

// isRetryable determines if a request should be retried based on the error type.
func isRetryable(err error) bool {
	if errors.Is(err, ErrServerError) || errors.Is(err, ErrRateLimited) {
		return true
	}

	var httpErr *HTTPError
	if errors.As(err, &httpErr) {
		return httpErr.StatusCode >= 500 || httpErr.StatusCode == http.StatusTooManyRequests
	}

	if errors.Is(err, context.DeadlineExceeded) || errors.Is(err, context.Canceled) {
		return false
	}

	return true
}
