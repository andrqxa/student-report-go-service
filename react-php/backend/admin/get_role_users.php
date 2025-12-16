<?php
// Include your database connection code

require_once '../DatabaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $role_id=$_GET["role_id"];
    

    // Check if student_id is a positive integer
    // if (!is_numeric($patient_id) || $patient_id <= 0 || $patient_id != intval($patient_id)) {
    //     echo json_encode(["success" => false, "message" => "Patient ID should be a positive integer."]);
    // } else {
        $conn = DatabaseConnection::getConnection();

        // Modify the SQL statement to retrieve enrolled courses and instructor name
        $sql= "SELECT u.*, CONCAT(u.first_name, ' ', u.last_name) AS full_name 
        FROM user u       
        WHERE role_id = ?
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $role_id);  // "i" represents an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $users = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "users" => $users]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve users. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


