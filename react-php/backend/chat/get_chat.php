<?php

require_once "../DatabaseConnection.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user1_id = $_GET["user1_id"];
    $user2_id = $_GET["user2_id"];

    if (
        !is_numeric($user1_id) || $user1_id <= 0 || $user1_id != intval($user1_id) ||
        !is_numeric($user2_id) || $user2_id <= 0 || $user2_id != intval($user2_id) 
    ) {
        echo json_encode(["success" => false, "message" => "Invalid user IDs."]);
    } else {
        // Query the "message" table to get all messages between user1 and user2
        $query = "SELECT * FROM message WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC";
        
        // Get the database connection
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiii", $user1_id, $user2_id, $user2_id, $user1_id, );
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $messages = [];

            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            echo json_encode(["success" => true, "messages" => $messages]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve messages. Please try again later."]);
        }
    }
}
?>
