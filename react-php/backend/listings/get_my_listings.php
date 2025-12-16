<?php
// Include your database connection code

require_once '../DatabaseConnection.php';
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');


    $user_id=$_GET["user_id"];
 
        $conn = DatabaseConnection::getConnection();

        $sql= "SELECT a.*, a.id AS ad_id, c.name, u.* 
        FROM ad a JOIN category c ON a.category_id = c.id
        JOIN user u ON a.user_id = u.id
        WHERE a.user_id = ?
        ORDER BY a.posting_date DESC
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);  // "i" represents an integer

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

?>


