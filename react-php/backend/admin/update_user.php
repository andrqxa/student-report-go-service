<?php
// Include your database connection code
include_once('../DatabaseConnection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $pincode = $_POST["pincode"];
    $university = $_POST["university"];
    $rating = $_POST["rating"];

    // Check if user_id is a positive integer
    if (!is_numeric($user_id) || $user_id <= 0 || $user_id != intval($user_id)) {
        echo json_encode(["success" => false, "message" => "User ID should be a positive integer."]);
    }
    // Check if first name and last name meet the length requirement
    elseif (strlen($first_name) < 2 || strlen($last_name) < 2) {
        echo json_encode(["success" => false, "message" => "First name and last name should be at least 2 characters long."]);
    } elseif (strlen($phone) != 10 || !ctype_digit($phone)) {
        $response["success"] = false;
        $response["message"] = "Phone should be exactly 10 digits and contain only numbers.";
    }
    // Check if the email is in a valid format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
    }
    // Check if the password meets the length requirement
    else {

      
        $conn = DatabaseConnection::getConnection();

        // Prepare the SQL statement to update the student's information
        $sql = "UPDATE user SET first_name = ?, last_name = ?, phone = ?, email = ?, address= ?, city=?, state=?, pincode=?, university=?, rating=? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssdi", $first_name, $last_name, $phone, $email, $address, $city, $state, $pincode, $university, $rating, $user_id);

        $stmt->execute();

        // Check the number of affected rows
        $affectedRows = $stmt->affected_rows;

        // Handle success and error messages
        $response = array();

        if ($affectedRows > 0) {

            $response["success"] = true;
            $response["message"] = "User information updated successfully.";
        } else {
            $response["success"] = false;
            $response["message"] = "Failed to update user information. " . $stmt->error;
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
}
?>