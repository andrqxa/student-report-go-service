<?php
// Include your database connection code

require_once '../DatabaseConnection.php';


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id=$_GET["user_id"];
 
        $conn = DatabaseConnection::getConnection();

        $sql= "SELECT rating FROM rating        
        WHERE user_id = ?
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);  // "i" represents an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $rating = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "rating" => $rating]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve rating. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


