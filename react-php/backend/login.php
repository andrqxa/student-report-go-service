<?php
// Include your database connection code
// Include your database connection code
require_once 'DatabaseConnection.php';

include_once('header.php');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation checks, you can also validate the email format here

    if (empty($email) || empty($password)) {
        $response["success"] = false;
        $response["message"] = "Email and password are required fields.";
    } else {
        $conn = DatabaseConnection::getConnection();

        // Prepare the SQL statement to fetch user data by email
        $sql = "SELECT id, password, role FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Fetch the user's data
            $stmt->bind_result($user_id, $hashed_password,$role);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, set session variables
                session_start();
                $_SESSION["email"] = $email;
                $_SESSION["user_id"] = $user_id;               
                $response["success"] = true;
                $response["message"] = "Login successful.";
                $response["email"] = $email;
                $response["role"] = $role;                
                $response["user_id"] = $user_id;
            } else {
                $response['success'] = false;
                $response['message'] = 'Invalid email or password.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'User not found.';
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
}
?>
