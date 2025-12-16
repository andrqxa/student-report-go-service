<?php

// Include your database connection code
require_once 'DatabaseConnection.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
   
        
    // Validation checks
    if (strlen($name) < 2 || strlen($email) < 2) {
        $response["success"] = false;
        $response["message"] = "Name should be at least 2 characters each.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["success"] = false;
        $response["message"] = "Email should be in a valid email format.";
    } 
     else {
        
        $conn = DatabaseConnection::getConnection();
        
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO contact (name, email, message) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param('sss', $name, $email,$message );

        
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Message sent successfully.";
        } else {
            $response["success"] = false;
            $response["message"] = "Message sent failed. ". $stmt->error; ;
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
}
?>