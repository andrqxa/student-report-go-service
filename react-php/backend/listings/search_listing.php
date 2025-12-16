<?php
// Include your database connection code

require_once '../DatabaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search_term=$_GET["search_term"];
 
        $conn = DatabaseConnection::getConnection();

        // $sql= "SELECT a.*, c.name, u.*, a.id as listing_id 
        // FROM ad a JOIN category c ON a.category_id = c.id
        // JOIN user u ON a.user_id = u.id
        // WHERE a.id = ?
        // ";

        $sql="SELECT a.*, c.name, u.*, a.id AS ad_id 
        FROM ad a JOIN category c ON a.category_id = c.id
        JOIN user u ON a.user_id = u.id
        WHERE title LIKE ?";
        
        $stmt = $conn->prepare($sql);
        $search_param = "%" . $search_term . "%";
        $stmt->bind_param("s", $search_param);  // "i" represents an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $search_results = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["success" => true, "search_results" => $search_results]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve search results. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


