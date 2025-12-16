<?php

// Include your database connection code
require_once '../DatabaseConnection.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $listing_id = $_POST["listing_id"];
        
        $conn = DatabaseConnection::getConnection();
        
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO wishlist (user_id, listing_id) VALUES (?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param('ii', $user_id, $listing_id);

        
        try{
            if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Added to wishlist";
        } 
    }
        catch(Exception $e){
            $response["success"] = false;
            $response["message"] = "Already exists in wishlist!" ;
        }
    

        // Close the database connection
        $stmt->close();
        $conn->close();

    echo json_encode($response);
}
?>