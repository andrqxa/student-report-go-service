package nodeclient

import (
	"context"
	"encoding/json"
	"errors"
	"fmt"
	"io"
	"log/slog"
	"net/http"
	"net/url"
	"strings"
	"time"
)

// Client communicates with the Node.js backend API.
type Client struct {
	baseURL    *url.URL
	httpClient *http.Client
	logger     *slog.Logger
	strictJSON bool
}

// Options defines configuration parameters for the Node API client.
type Options struct {
	BaseURL        string        // BaseURL is the base URL of the Node.js API server
	RequestTimeout time.Duration // RequestTimeout is the maximum duration for HTTP requests
	StrictJSON     bool          // StrictJSON enforces DisallowUnknownFields when decoding JSON
	Logger         *slog.Logger  // Logger for structured logging
}

// New creates a new Node API client with the provided configuration.
func New(opts Options) (*Client, error) {
	if strings.TrimSpace(opts.BaseURL) == "" {
		return nil, errors.New("nodeclient: BaseURL is required")
	}

	u, err := url.Parse(opts.BaseURL)
	if err != nil {
		return nil, fmt.Errorf("nodeclient: invalid BaseURL: %w", err)
	}

	timeout := opts.RequestTimeout
	if timeout <= 0 {
		timeout = 10 * time.Second
	}

	logger := opts.Logger
	if logger == nil {
		logger = slog.Default()
	}

	return &Client{
		baseURL:    u,
		httpClient: &http.Client{Timeout: timeout},
		logger:     logger,
		strictJSON: opts.StrictJSON,
	}, nil
}

// GetStudent fetches student details by ID from the Node backend.
func (c *Client) GetStudent(ctx context.Context, id string) (Student, error) {
	if strings.TrimSpace(id) == "" {
		return Student{}, errors.New("nodeclient: student id is required")
	}

	rel := &url.URL{Path: fmt.Sprintf("/api/v1/students/%s", url.PathEscape(id))}
	endpoint := c.baseURL.ResolveReference(rel)

	c.logger.Debug("nodeclient request",
		"method", "GET",
		"url", endpoint.String(),
	)

	req, err := http.NewRequestWithContext(ctx, http.MethodGet, endpoint.String(), nil)
	if err != nil {
		return Student{}, fmt.Errorf("nodeclient: create request: %w", err)
	}

	req.Header.Set("Accept", "application/json")
	req.Header.Set("User-Agent", "go-nodeclient/1.0")

	resp, err := c.httpClient.Do(req)
	if err != nil {
		c.logger.Error("nodeclient request failed",
			"url", endpoint.String(),
			"error", err,
		)
		return Student{}, fmt.Errorf("nodeclient: request failed: %w", err)
	}
	defer resp.Body.Close()

	c.logger.Debug("nodeclient response",
		"status", resp.StatusCode,
		"url", endpoint.String(),
	)

	if resp.StatusCode < 200 || resp.StatusCode >= 300 {
		return Student{}, c.handleErrorResponse(resp)
	}

	var s Student
	dec := json.NewDecoder(resp.Body)

	if c.strictJSON {
		dec.DisallowUnknownFields()
	}

	if err := dec.Decode(&s); err != nil {
		return Student{}, fmt.Errorf("nodeclient: decode response: %w", err)
	}

	c.logger.Info("nodeclient student fetched",
		"student_id", id,
	)
	return s, nil
}

// GetStudentWithRetry attempts to fetch a student with exponential backoff retry logic.
func (c *Client) GetStudentWithRetry(ctx context.Context, id string, maxRetries int) (Student, error) {
	var lastErr error

	for attempt := 0; attempt <= maxRetries; attempt++ {
		if attempt > 0 {
			backoff := time.Duration(attempt) * time.Second
			c.logger.Info("nodeclient retry attempt",
				"attempt", attempt,
				"max_retries", maxRetries,
				"backoff", backoff.String(),
			)

			select {
			case <-time.After(backoff):
			case <-ctx.Done():
				return Student{}, ctx.Err()
			}
		}

		student, err := c.GetStudent(ctx, id)
		if err == nil {
			return student, nil
		}

		lastErr = err

		if !isRetryable(err) {
			c.logger.Error("nodeclient non-retryable error",
				"error", err,
			)
			break
		}
	}

	return Student{}, fmt.Errorf("nodeclient: failed after %d attempts: %w", maxRetries+1, lastErr)
}

// handleErrorResponse creates a typed error based on the HTTP status code.
func (c *Client) handleErrorResponse(resp *http.Response) error {
	const maxErrBody = 8 << 10
	body, readErr := io.ReadAll(io.LimitReader(resp.Body, maxErrBody))
	if readErr != nil {
		return &HTTPError{
			StatusCode: resp.StatusCode,
			Err:        fmt.Errorf("failed to read error body: %w", readErr),
		}
	}

	bodyStr := strings.TrimSpace(string(body))

	var baseErr error
	switch resp.StatusCode {
	case http.StatusNotFound:
		baseErr = ErrNotFound
	case http.StatusTooManyRequests:
		baseErr = ErrRateLimited
	case http.StatusBadRequest:
		baseErr = ErrBadRequest
	case http.StatusUnauthorized:
		baseErr = ErrUnauthorized
	case http.StatusForbidden:
		baseErr = ErrForbidden
	case http.StatusInternalServerError, http.StatusBadGateway, http.StatusServiceUnavailable:
		baseErr = ErrServerError
	default:
		if resp.StatusCode >= 400 && resp.StatusCode < 500 {
			baseErr = ErrBadRequest
		} else {
			baseErr = ErrServerError
		}
	}

	return &HTTPError{
		StatusCode: resp.StatusCode,
		Body:       bodyStr,
		Err:        baseErr,
	}
}

// Close closes the underlying HTTP client's idle connections.
func (c *Client) Close() {
	c.httpClient.CloseIdleConnections()
}
