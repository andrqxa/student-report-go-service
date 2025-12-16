<?php
// Include your database connection code
include_once('../DatabaseConnection.php');

$response = array();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (empty($name) || empty($email) || empty($message)) {
        $response = [
            'success' => false,
            'message' => 'All fields are required'
        ];
    } else {
        $conn = DatabaseConnection::getConnection();

        // Prepare the SQL statement to insert a new student
        $sql = "INSERT INTO contact_form (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name,  $email, $message);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "data added successfully.";
        } else {
            $response["success"] = false;
            $response["message"] = "Failed to add entry. ". $stmt->error;
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
} else {
    // If the request method is not POST, return an error
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Send the JSON response
echo json_encode($response);
