<?php

// Include your database connection code
include_once('../DatabaseConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $listing_id = $_POST["listing_id"];

    // Check if listing_id is a positive integer
    if (!is_numeric($listing_id) || $listing_id <= 0 || $listing_id != intval($listing_id)) {
        echo json_encode(["success" => false, "message" => "Listing ID should be a positive integer."]);
    } else {
        // Get the database connection
        $conn = DatabaseConnection::getConnection();

        // Prepare and execute the SQL statement
        $sql = "DELETE FROM ad WHERE id = ? ";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("i", $listing_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Ad deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete Ad. ". $stmt->error]);
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>
