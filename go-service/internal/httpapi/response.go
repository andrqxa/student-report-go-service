package httpapi

import (
	"encoding/json"
	"net/http"
)

// writeJSONError writes an error response as {"error":"..."}.
func writeJSONError(w http.ResponseWriter, status int, msg string) {
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	w.WriteHeader(status)

	_ = json.NewEncoder(w).Encode(map[string]string{
		"error": msg,
	})
}
