# ────────────────────────────────────────────────────────────────
# Go Student PDF Service — Makefile
# ────────────────────────────────────────────────────────────────

BINARY_NAME := go-service
CMD         := ./cmd/go-service
BIN_DIR     := bin
BINARY      := $(BIN_DIR)/$(BINARY_NAME)

COMPOSE     := docker compose -f docker-compose.yml

.PHONY: all build run clean test fmt lint deps tidy vendor \
        deps-up deps-down deps-reset deps-logs check-node

all: build

## Build binary into ./bin (keeps repo clean)
build:
	@echo "▶ Building $(BINARY)..."
	@mkdir -p $(BIN_DIR)
	@go build -o $(BINARY) $(CMD)

## Run application
run:
	@echo "▶ Running..."
	@go run $(CMD)

## Run tests
test:
	@echo "▶ Running tests..."
	@go test ./... -cover

## Format code
fmt:
	@echo "▶ Formatting..."
	@go fmt ./...

## Lint (requires golangci-lint installed locally)
lint:
	@echo "▶ Linting..."
	@golangci-lint run

## Tidy dependencies (safe default)
tidy:
	@echo "▶ go mod tidy..."
	@go mod tidy

## Vendor dependencies (optional)
vendor:
	@echo "▶ Vendoring..."
	@go mod vendor

deps: tidy

## Clean build artifacts
clean:
	@echo "▶ Cleaning..."
	@rm -rf $(BIN_DIR)

# ────────────────────────────────────────────────────────────────
# Docker dependencies: Postgres + Node backend
# (docker-compose.yml is located in go-service/)
# ────────────────────────────────────────────────────────────────

deps-up:
	@echo "▶ Starting Postgres + Node backend..."
	@$(COMPOSE) up --build -d

deps-down:
	@echo "▶ Stopping dependencies..."
	@$(COMPOSE) down

deps-reset:
	@echo "▶ Resetting dependencies (drops volumes)..."
	@$(COMPOSE) down -v
	@$(COMPOSE) up --build -d

deps-logs:
	@$(COMPOSE) logs -f --tail=200

check-node:
	@echo "▶ Checking Node backend..."
	@curl -fsS --max-time 3 http://127.0.0.1:5007/api/v1/students/1 >/dev/null \
	  && echo "OK" || (echo "FAILED"; exit 1)
