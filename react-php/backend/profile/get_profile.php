<?php
require_once "../DatabaseConnection.php"; // Include your database connection code

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = $_GET["user_id"];

    // Check if user_id is a positive integer
    if (!is_numeric($user_id) || $user_id <= 0 || $user_id != intval($user_id)) {
        echo json_encode(["success" => false, "message" => "User ID should be a positive integer."]);
    } else {
        // Get user details by user_id
        $conn = DatabaseConnection::getConnection(); // Replace with your database connection code

        // Define the SQL query to retrieve user details
        $sql = "SELECT u.*, u.address AS address ,CONCAT(u.first_name, ' ', u.last_name) AS full_name, CONCAT(u.address, ', ', u.city, ', ', u.state, ', ', u.pincode) AS full_address
        FROM user AS u
        WHERE u.id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the user details
        $userDetails = $result->fetch_assoc();

        if ($userDetails) {
            echo json_encode(["success" => true, "user" => $userDetails]);
        } else {
            echo json_encode(["success" => false, "message" => "User not found with the specified User ID."]);
        }

        $stmt->close();
        $conn->close();
    }
}
?>
