<?php
// Include your database connection code

require_once '../DatabaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
        $conn = DatabaseConnection::getConnection();

        $sql= "SELECT * FROM category";
        
        $stmt = $conn->prepare($sql);
     
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $categories = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "categories" => $categories]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve categories. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


