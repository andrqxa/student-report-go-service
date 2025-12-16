<?php
// Include your database connection code and other necessary functions
require_once 'DatabaseConnection.php';
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
header('Content-Type: application/json'); // Set the content type to JSON

function generateRandomPassword($length = 10) {
    // Generate random bytes
    $randomBytes = random_bytes($length);

    // Convert random bytes to a hexadecimal string
    $randomString = bin2hex($randomBytes);

    return $randomString;
}

// Log file path
// $logFilePath = 'debug.log';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email parameter is set
    if (isset($_POST["email"])) {
        $email = $_POST["email"];

        // Validate the email address here (e.g., check if it's in a valid format and exists in your database)
        $conn = DatabaseConnection::getConnection();

        // Check if the email exists in the database (replace 'users' with your actual users table name)
        $sql = "SELECT * FROM user WHERE email = ?";
        
        // Execute the SQL statement with the email as a parameter
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Fetch the user data
            $user = $result->fetch_assoc();

            // Generate a new password
            $newPassword = generateRandomPassword(); // Implement a function to generate a random password

            // Update the user's password in the database
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hash the new password
            $updatePasswordSql = "UPDATE user SET password = ? WHERE email = ?";
            
            // Execute the SQL statement with the hashed password and email as parameters
            $updatePasswordStmt = $conn->prepare($updatePasswordSql);
            $updatePasswordStmt->bind_param("ss", $hashedPassword, $email);
            $updatePasswordStmt->execute();

            // Send the new password via email
            $subject = "New Password";
            $message = "Your new password: $newPassword";
            $headers = "From: eg@example.com";

            // Override SMTP settings
            ini_set('SMTP', 'smtp.example.com');
            ini_set('smtp_port', '587');

            if (mail($email, $subject, $message, $headers)) {
                echo json_encode(["success" => true, "message" => "New password sent to your email."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send the new password. Please try again later."]);
            }
        } else {
            // Email address not found
            echo json_encode(["success" => false, "message" => "Email address not found."]);
        }

        // Close the database connection
        $conn->close();
    } else {
        // Email parameter not provided
        echo json_encode(["success" => false, "message" => "Email parameter not provided."]);
    }
} else {
    // Invalid request method
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

// Log debug information
file_put_contents($logFilePath, json_encode($_POST) . PHP_EOL, FILE_APPEND);
file_put_contents($logFilePath, json_encode($_SERVER) . PHP_EOL, FILE_APPEND);
file_put_contents($logFilePath, json_encode($_REQUEST) . PHP_EOL, FILE_APPEND);
