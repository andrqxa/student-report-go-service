<?php
// Include your database connection code
include_once('../DatabaseConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $password = $_POST["password"]; // New field for password

    // Check if user_id is a positive integer
    if (!is_numeric($user_id) || $user_id <= 0 || $user_id != intval($user_id)) {
        echo json_encode(["success" => false, "message" => "User ID should be a positive integer."]);
    }
    // Check if the password meets the length requirement
    elseif (strlen($password) < 8) {
        echo json_encode(["success" => false, "message" => "Password should be at least 8 characters long."]);
    } else {

        // Validate and hash the password before updating
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Get the database connection
        $conn = DatabaseConnection::getConnection();

        // Prepare the SQL statement to update the user's password
        $sql = "UPDATE user SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);

        $stmt->execute();

        // Check the number of affected rows
        $affectedRows = $stmt->affected_rows;

        // Handle success and error messages
        $response = array();

        if ($affectedRows > 0) {

            $response["success"] = true;
            $response["message"] = "Password updated successfully.";
        } else {
            $response["success"] = false;
            $response["message"] = "Failed to update password. " . $stmt->error;
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
}
?>
