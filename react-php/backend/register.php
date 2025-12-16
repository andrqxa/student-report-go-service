<?php

// Include your database connection code
require_once 'DatabaseConnection.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $phone = $_POST["phone"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $address = $_POST["address"];    
    $pincode = $_POST["pincode"];
    $university = $_POST["university"];
    
    $role= $_POST["role"];
    
        
    // Validation checks
    if (strlen($first_name) < 2 || strlen($last_name) < 2) {
        $response["success"] = false;
        $response["message"] = "First name and last name should be at least 2 characters each.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["success"] = false;
        $response["message"] = "Email should be in a valid email format.";
    } elseif (strlen($phone) != 10 || !ctype_digit($phone)) {
        $response["success"] = false;
        $response["message"] = "Phone should be exactly 10 digits and contain only numbers.";
    } elseif (strlen($pincode) != 5 || !ctype_digit($pincode)) {
        $response["success"] = false;
        $response["message"] = "Pin Code should be exactly 5 digits and contain only numbers.";
    } else {
        
        $conn = DatabaseConnection::getConnection();
        
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO user (first_name, last_name, email, password, phone, city, state, address, pincode, university,role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param('sssssssssss', $first_name, $last_name, $email, $password, $phone, $city, $state, $address, $pincode, $university, $role);

        
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Registration successful.";
        } else {
            $response["success"] = false;
            $response["message"] = "Registration failed. ". $stmt->error; ;
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
}
?>