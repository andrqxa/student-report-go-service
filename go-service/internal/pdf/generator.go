package pdf

import (
	"bytes"
	"fmt"
	"go-service/internal/nodeclient"
	"time"

	"github.com/jung-kurt/gofpdf"
)

// Generate builds a PDF student report and returns it as bytes.
func Generate(s nodeclient.Student) ([]byte, error) {
	pdf := gofpdf.New("P", "mm", "A4", "")
	pdf.SetMargins(20, 20, 20)
	pdf.AddPage()

	pdf.SetFont("Arial", "B", 16)
	pdf.Cell(0, 10, "Student Report")
	pdf.Ln(12)

	pdf.SetFont("Arial", "", 11)

	writeRow := func(label, value string) {
		pdf.CellFormat(50, 8, label, "", 0, "", false, 0, "")
		pdf.CellFormat(0, 8, value, "", 1, "", false, 0, "")
	}

	writeRow("Name:", s.Name)
	writeRow("Email:", s.Email)
	writeRow("Class:", fmt.Sprintf("%s %s", s.Class, s.Section))
	writeRow("Roll:", fmt.Sprintf("%d", s.Roll))
	writeRow("Phone:", s.Phone)
	writeRow("Gender:", s.Gender)
	writeRow("Date of birth:", formatDate(s.Dob))
	writeRow("Father:", s.FatherName)
	writeRow("Father phone:", s.FatherPhone)

	pdf.Ln(8)
	pdf.SetFont("Arial", "I", 9)
	pdf.Cell(0, 6, fmt.Sprintf("Generated at %s", time.Now().Format(time.RFC3339)))

	var buf bytes.Buffer
	if err := pdf.Output(&buf); err != nil {
		return nil, err
	}

	return buf.Bytes(), nil
}

func formatDate(t time.Time) string {
	if t.IsZero() {
		return ""
	}
	return t.Format("2006-01-02")
}
