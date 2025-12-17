package nodeclient

import "time"

// Student represents the response returned by the Node.js backend
// for GET /api/v1/students/:id.
type Student struct {
	ID                 string    `json:"id"`
	Name               string    `json:"name"`
	Email              string    `json:"email"`
	SystemAccess       bool      `json:"systemAccess"`
	Phone              string    `json:"phone"`
	Gender             string    `json:"gender"`
	Dob                time.Time `json:"dob"`
	Class              string    `json:"class"`
	Section            string    `json:"section"`
	Roll               int       `json:"roll"`
	FatherName         string    `json:"fatherName"`
	FatherPhone        string    `json:"fatherPhone"`
	MotherName         string    `json:"motherName"`
	MotherPhone        string    `json:"motherPhone"`
	GuardianName       string    `json:"guardianName"`
	GuardianPhone      string    `json:"guardianPhone"`
	RelationOfGuardian string    `json:"relationOfGuardian"`
	CurrentAddress     string    `json:"currentAddress"`
	PermanentAddress   string    `json:"permanentAddress"`
	AdmissionDate      time.Time `json:"admissionDate"`
	ReporterName       string    `json:"reporterName"`
}
