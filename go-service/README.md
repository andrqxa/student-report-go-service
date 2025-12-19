# Go Student Report Service (Problem 7)

Standalone Go microservice that generates a **PDF report** for a student by
consuming the existing **Node.js backend API**.

## Endpoint

- `GET /api/v1/students/{id}/report`

The service fetches student data from the Node backend:

- `GET /api/v1/students/:id`

and returns a generated PDF as a downloadable file.

## Requirements

- Go 1.22+ (or the Go version used in this repository)
- Docker + Docker Compose (for local Node + Postgres dependencies)

> The Go service does **not** connect to the database directly.
> PostgreSQL is started only to support the Node backend locally.

## Why `seed_db` exists in `go-service/`

The original repository instructions reference `seed_db/` SQL files required to
start the Node.js backend, but those files are not present in the provided source.

To make **Problem 7 reproducible and self-contained**, a minimal schema and seed
data are included under:

- `go-service/seed_db/`

They are intentionally limited to the fields required by the Node endpoint used
by this Go service:

- `GET /api/v1/students/:id`

## Quick start (local)

Run everything from the `go-service/` directory.

### 1) Configure environment

```bash
cp .env.example .env
````

### 2) Start local dependencies (Postgres + Node backend)

```bash
make deps-up
```

Optional:

* View logs: `make deps-logs`
* Stop deps: `make deps-down`
* Reset DB (drops volumes): `make deps-reset`

### 3) Run the Go service

```bash
make run
```

By default, the service listens on:

* `GO_SERVICE_ADDR=:8081`

### 4) Generate a PDF report

```bash
curl -fsS "http://localhost:8081/api/v1/students/1/report" -o report.pdf
```

## Configuration

Local development is configured via `.env`.
A reference `.env.example` is provided.

All credentials in `.env.example` are **development-only** and must not be used
in production.

## Dev-only auth bypass (for local testing)

The Node backend enforces auth/CSRF on most endpoints.
For local integration testing of Problem 7, auth checks for the **student read**
endpoint can be disabled via:

```bash
DISABLE_AUTH=true
```

This is strictly **dev-only** and does not change the Go service design, which
treats the Node backend as an external dependency.

## Project layout

```text
go-service/
  cmd/go-service/            # main package
  internal/
    config/                  # env configuration loader
    httpapi/                 # chi router + handlers
    nodeclient/              # Node API client + error mapping
    pdf/                     # PDF generation
  seed_db/                   # minimal SQL schema + seed for local Node backend
  docker-compose.yml         # local Postgres + Node backend
  Makefile                   # build/run/deps helpers
```

## Notes

* The generated PDF is created in-memory and returned as the HTTP response.
* The Go service avoids direct DB access by design (per task requirements).
