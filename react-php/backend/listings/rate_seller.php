<?php
// Include your database connection code
require_once "../DatabaseConnection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $new_rating = $_POST["rating"];
  
    // Validate user input
    if (!is_numeric($user_id) || $user_id <= 0 || $user_id != intval($user_id) || 
        !is_numeric($new_rating) || $new_rating < 0 || $new_rating > 5) {
        echo json_encode(["success" => false, "message" => "Invalid input."]);
    } else { 
        // Get the current rating for the user from the database
        $conn = DatabaseConnection::getConnection();
        $query = "SELECT rating, count FROM rating WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // Check if the user already has a rating
        if ($result->num_rows > 0) {
            $current_rating = $row['rating'];
            $rating_count = $row['count'];
        } else {
            // If no rating exists, set current rating and count to 0
            $current_rating = 0;
            $rating_count = 0;
        }

        // Calculate the new aggregated rating
        $aggregated_rating = ($current_rating * $rating_count + $new_rating) / ($rating_count + 1);

        // Update the rating table with the new aggregated rating
        if ($result->num_rows > 0) {
            // If rating exists, update the record
            $update_query = "UPDATE rating SET rating = ?, count = count + 1 WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("di", $aggregated_rating, $user_id);
        } else {
            // If no rating exists, insert a new record
            $insert_query = "INSERT INTO rating (user_id, rating, count) VALUES (?, ?, 1)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("id", $user_id, $aggregated_rating);
        }

        // Execute the update or insert query
        $execute_stmt = $result->num_rows > 0 ? $update_stmt : $insert_stmt;
        $update_result = $execute_stmt->execute();

        // Check for success or failure and return a response
        if ($update_result) {
            echo json_encode(["success" => true, "message" => "Rating updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update rating. Please try again later."]);
        }
    }
}
?>
