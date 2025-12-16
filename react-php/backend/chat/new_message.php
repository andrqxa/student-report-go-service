<?php
// Include your database connection code
require_once "../DatabaseConnection.php"; // Replace with your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // print_r($_POST);
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body);
    // print_r($data);
    $sender_id = $data->sender_id;
    $receiver_id = $data->receiver_id;
    $listing_id= $data->listing_id;
    $content = $data->content;

    // Check if sender_id, receiver_id, and content are valid
    if (
        !is_numeric($sender_id) || $sender_id <= 0 || $sender_id != intval($sender_id) ||
        !is_numeric($receiver_id) || $receiver_id <= 0 || $receiver_id != intval($receiver_id) ||
        !is_numeric($listing_id) || $listing_id <= 0 || $listing_id != intval($listing_id) ||
        empty($content)
    ) {
        echo json_encode(["success" => false, "message" => "Invalid sender ID, receiver ID, listing ID or content."]);
    } else {
        // Insert the message into the "message" table
        $query = "INSERT INTO message (sender_id, receiver_id, listing_id, content) VALUES (?, ?, ?, ?)";
        
        // Get the database connection
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiis", $sender_id, $receiver_id, $listing_id, $content);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Message sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send the message. ". $stmt->error]);
        }
    }
}
?>
