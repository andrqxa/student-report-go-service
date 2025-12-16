<?php
// Include your database connection code
require_once '../DatabaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {


    // Get the database connection
    $conn = DatabaseConnection::getConnection();

    // Prepare and execute the SQL statement to select users with the specified role_id
    $sql = "SELECT * FROM contact_form";
    $stmt = $conn->prepare($sql);
    

    // Execute the statement
    $stmt->execute();

    // Fetch and store the results
    $result = $stmt->get_result();
    $users = array();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Return the results as JSON
    echo json_encode(["success" => true, "entries" => $users]);
}
?>
