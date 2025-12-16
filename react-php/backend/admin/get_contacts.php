<?php
// Include your database connection code

require_once '../DatabaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
        $conn = DatabaseConnection::getConnection();

        // Modify the SQL statement to retrieve enrolled courses and instructor name
        $sql= "SELECT * 
        FROM contact
        ";
        
        $stmt = $conn->prepare($sql);
        // $stmt->bind_param("i", $user_id);  // "i" represents an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $messages = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "messages" => $messages]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve messages. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


