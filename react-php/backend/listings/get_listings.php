<?php
// Include your database connection code

require_once '../DatabaseConnection.php';
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $category = $_GET["category"];
    
        $conn = DatabaseConnection::getConnection();

        if ($category === "All Ads") {
            $sql = "SELECT a.*, a.id AS ad_id FROM ad a WHERE a.status = 'Available' ORDER BY a.posting_date DESC";
        } else {
        $sql= "SELECT a.*,a.id AS ad_id, c.* FROM ad a INNER JOIN category c ON a.category_id = c.id
        WHERE c.name = ? AND a.status = 'Available' ORDER BY a.posting_date DESC";
        }
        
        $stmt = $conn->prepare($sql);
        if ($category !== "All Ads") {
        $stmt->bind_param("s", $category); 
    }
     
        if ($stmt->execute()) {
            // Fetch the results
            $result = $stmt->get_result();
            $listings = $result->fetch_all(MYSQLI_ASSOC);

            
            // // // Encode photo data to Base64
            // foreach ($listings as &$listing) {
            //     $listing['photo1'] = base64_encode($listing['photo1']);
                
            // }

            echo json_encode(["success" => true, "listings" => $listings]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve listings. Please try again later."]);
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    // }
}
?>


