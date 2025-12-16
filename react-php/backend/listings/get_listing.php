<?php
// Include your database connection code

require_once '../DatabaseConnection.php';
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $listing_id=$_GET["listing_id"];
 
        $conn = DatabaseConnection::getConnection();

        $sql= "SELECT a.*, c.name, u.*, a.id as listing_id, a.user_id as ad_id, r.rating 
        FROM ad a JOIN category c ON a.category_id = c.id
        JOIN user u ON a.user_id = u.id
        LEFT JOIN rating r ON a.user_id = r.user_id
        
        WHERE a.id = ?
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listing_id);  // "i" represents an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $listing = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "listing" => $listing]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve listing. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


