<?php
// Include your database connection code
require_once "../DatabaseConnection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $listing_id = $_POST["listing_id"];
  $status = $_POST["status"];
  
    if (!is_numeric($listing_id) || $listing_id <= 0 || $listing_id != intval($listing_id)) {
        echo json_encode(["success" => false, "message" => "Listing ID should be a positive integer."]);
    } 
    
     else { 

        $query = "UPDATE ad SET status = ? WHERE id = ?";
        
        // Get the database connection
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $listing_id);
        $result = $stmt->execute();

        // Check for success or failure and return a response
        if ($result) {
            echo json_encode(["success" => true, "message" => "Updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update details. Please try again later."]);
        }
    }
}
?>
