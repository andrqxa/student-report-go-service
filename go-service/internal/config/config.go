package config

import (
	"log/slog"
	"os"
	"time"

	"github.com/joho/godotenv"
)

// Config holds application configuration values
type Config struct {
	Addr           string
	RequestTimeout time.Duration
	LogLevel       string
	NodeURL        string
}

// Load loads configuration from environment variables or .env file.
func Load() *Config {
	// Load .env file if present, ignore error silently
	_ = godotenv.Load()

	return &Config{
		Addr:           getEnv("GO_ADDR", ":8081"),
		RequestTimeout: getDuration("GO_REQUEST_TIMEOUT", 5*time.Second),
		LogLevel:       getEnv("GO_LOG_LEVEL", "info"),
		NodeURL:        getEnv("GO_NODE_API_BASE_URL", "http://localhost:5007"),
	}
}

func getDuration(key string, defaultValue time.Duration) time.Duration {
	if v, ok := os.LookupEnv(key); ok {
		d, err := time.ParseDuration(v)
		if err == nil {
			return d
		}
		slog.Warn("invalid duration",
			"key", key,
			"value", v,
			"default", defaultValue.String(),
		)
	}
	return defaultValue
}

func getEnv(key string, defaultValue string) string {
	if v, ok := os.LookupEnv(key); ok {
		return v
	}
	return defaultValue
}
