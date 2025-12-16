<?php
// Include your database connection code
require_once "../DatabaseConnection.php"; // Replace with your database connection script

$uploaddir = '../uploads/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $id=$_POST["id"]; // this is listing id
    $title = $_POST["title"];
    $price = $_POST["price"];
    $university = $_POST["university"];    
    $content =  $_POST["content"]; 
    $category = $_POST["category"] ; 
   $uploadfile = $_POST['uploadfile'];

   $timestamp = time();
    $output_file = $uploaddir . "/$timestamp.jpeg";
    file_put_contents($output_file, file_get_contents($uploadfile));
    

    if (!is_numeric($id) || $id <= 0 || $id != intval($id)) {
        echo json_encode(["success" => false, "message" => "Listing ID should be a positive integer."]);
    }
 
     else { 

        $query = "UPDATE ad SET title = ?, price = ?, university = ?, content= ?, category_id=?, photo1=? WHERE id = ?";
        
        // Get the database connection
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sissisi", $title, $price, $university, $content, $category, $output_file, $id);
        $result = $stmt->execute();

        // Check for success or failure and return a response
        // if ($result) {
        //     echo json_encode(["success" => true, "message" => "Listing details updated successfully."]);
        // } else {
        //     echo json_encode(["success" => false, "message" => "Failed to update Listing details. Please try again later."]);
        // }
        if ($stmt->affected_rows > 0) {
           
        
            echo json_encode(["success" => true, "message" => "Ad updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update ad. Try again in some time! ". $stmt->error]);
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>
