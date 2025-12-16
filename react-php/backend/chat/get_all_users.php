<?php
// Include your database connection code
require_once "../DatabaseConnection.php"; // Replace with your database connection script

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $current_user_id = $_GET["user_id"];

    // Check if current_user_id is valid
    if (!is_numeric($current_user_id) || $current_user_id <= 0 || $current_user_id != intval($current_user_id)) {
        echo json_encode(["success" => false, "message" => "Invalid user ID."]);
    } else {
        // Query the "user" table to get a list of all users except the current user with their names and role names
        $query = "SELECT u.id, u.first_name, u.last_name, r.role_name 
                  FROM user u
                  INNER JOIN role r ON u.role_id = r.id
                  WHERE u.id != ?";
        
        // Get the database connection
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $current_user_id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            echo json_encode(["success" => true, "users" => $users]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve user list. Please try again later."]);
        }
    }
}
?>
