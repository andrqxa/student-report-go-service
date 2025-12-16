<?php
// Include your database connection code

require_once '../DatabaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id=$_GET["user_id"];

        $conn = DatabaseConnection::getConnection();

        // Modify the SQL statement to retrieve enrolled courses and instructor name
        $sql= "SELECT w.*, a.* 
        FROM wishlist w JOIN ad a ON w.listing_id = a.id
        WHERE w.user_id = ? ORDER BY a.posting_date DESC";      
    
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);  // "i" represents an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $wishlist = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "wishlist" => $wishlist]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve wishlist. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


