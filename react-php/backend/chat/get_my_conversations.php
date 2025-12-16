<?php
// Include your database connection code

require_once "../DatabaseConnection.php"; // Replace with your database connection script

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = $_GET["user_id"];

    // Check if user_id and user2_id are valid
    if (
        !is_numeric($user_id) || $user_id <= 0 || $user_id != intval($user_id)
        
    ) {
        echo json_encode(["success" => false, "message" => "Invalid user IDs."]);
    } else {
        // Query the "message" table to get all messages between user1 and user2
        $query = "SELECT distinct sender_id, CONCAT(user.first_name,' ',user.last_name) as sender_name, listing_id, receiver_id, ad.title FROM message inner join ad on ad.id = listing_id inner join user on sender_id = user.id where receiver_id = ? or sender_id = ? " ;
            
        // and listing_id in (select id from ad where user_id =?)
        
        // Get the database connection
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id,$user_id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $chats = [];

            while ($row = $result->fetch_assoc()) {
                $chats[] = $row;
            }

            echo json_encode(["success" => true, "chats" => $chats]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve chats. Please try again later."]);
        }
    }
}
?>
