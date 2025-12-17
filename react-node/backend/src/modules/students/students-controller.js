const asyncHandler = require("express-async-handler");
const {
    getAllStudents,
    addNewStudent,
    getStudentDetail,
    setStudentStatus,
    updateStudent,
} = require("./students-service");

const handleGetAllStudents = asyncHandler(async (req, res) => {
    const result = await getAllStudents(req.query);
    res.status(200).json(result);
});

const handleGetStudentDetail = asyncHandler(async (req, res) => {
    const { id } = req.params;
    const result = await getStudentDetail(id);
    res.status(200).json(result);
});

// For Problem 7 you don't need these, but returning 501 avoids "hanging" requests.
const handleAddStudent = asyncHandler(async (_req, res) => {
    res.status(501).json({ error: "Not implemented for Problem 7" });
});

const handleUpdateStudent = asyncHandler(async (_req, res) => {
    res.status(501).json({ error: "Not implemented for Problem 7" });
});

const handleStudentStatus = asyncHandler(async (_req, res) => {
    res.status(501).json({ error: "Not implemented for Problem 7" });
});

module.exports = {
    handleGetAllStudents,
    handleGetStudentDetail,
    handleAddStudent,
    handleStudentStatus,
    handleUpdateStudent,
};
