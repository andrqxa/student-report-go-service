<?php
require_once "../DatabaseConnection.php"; // Include your database connection code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    // $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_BCRYPT) : null;
    $phone_number = $_POST["phone"]; // New field for phone number
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $pincode = $_POST["pincode"];
    $university = $_POST["university"];

    // Add any additional validation you need for the input fields

    // Check if user_id is a positive integer
    if (!is_numeric($user_id) || $user_id <= 0 || $user_id != intval($user_id)) {
        echo json_encode(["success" => false, "message" => "User ID should be a positive integer."]);
    }
      // Validation checks
     if (strlen($first_name) < 2 || strlen($last_name) < 2) {
        $response["success"] = false;
        $response["message"] = "First name and last name should be at least 2 characters each.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["success"] = false;
        $response["message"] = "Email should be in a valid email format.";
    } elseif (strlen($phone_number) != 10 || !ctype_digit($phone_number)) {
        $response["success"] = false;
        $response["message"] = "Phone should be exactly 10 digits and contain only numbers.";
    }
    else {
        $conn = DatabaseConnection::getConnection(); // Replace with your database connection code

        // Define the SQL query to update user details including phone number
        // if ($password === null) {
        $sql = "UPDATE user
                SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, city = ?, state = ?, pincode = ?, university = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $first_name, $last_name, $email,  $phone_number, $address, $city, $state, $pincode, $university, $user_id);
        // } else {
        //     $sql = "UPDATE user
        //             SET first_name = ?, last_name = ?, email = ?, password = ?, phone = ?, address = ?, city = ?, state = ?, pincode = ?, university = ?
        //             WHERE id = ?";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("ssssssssssi", $first_name, $last_name, $email, $password, $phone_number, $address, $city, $state, $pincode, $university, $user_id);
        // }

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User details updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update user details. Please try again later."]);
        }

        $stmt->close();
        $conn->close();
    }
}
?>
