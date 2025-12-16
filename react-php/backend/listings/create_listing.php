<?php
// Include your database connection code
require_once '../DatabaseConnection.php';
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

$uploaddir = '../uploads/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $user_id= $_POST["user_id"];    
   $title = $_POST["title"];
   $price = $_POST["price"]; 
   $university = $_POST["university"]; 
   $content = $_POST["content"];
   $category = $_POST["category"];
  
//    print_r($_POST['uploadfile']);
   $uploadfile = $_POST['uploadfile'];

//    $photo1 = $_FILES["uploadfile"]["name"];
//    $tempname1 = $_FILES["uploadfile"]["tmp_name"];
//    $folder1 = "./image/" . $photo1;

   // Create the directory if it doesn't exist
    // if (!file_exists(dirname($folder1))) {
    //     mkdir(dirname($folder1), 0777, true); // Creates directory recursively
    // }
    // chmod(dirname($folder1), 0777);

    $timestamp = time();
    $output_file = $uploaddir . "/$timestamp.jpeg";
    file_put_contents($output_file, file_get_contents($uploadfile));

    $conn = DatabaseConnection::getConnection();
        

        // Fetch phone and email from the user table based on the provided user_id
        $sqlFetchUser = "SELECT phone, email FROM user WHERE id = ?";
        $stmtFetchUser = $conn->prepare($sqlFetchUser);
        $stmtFetchUser->bind_param("i", $user_id);
        $stmtFetchUser->execute();
        $stmtFetchUser->bind_result($phone, $email);
        $stmtFetchUser->fetch();
        $stmtFetchUser->close();

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO ad (user_id, title, price, university, content, contact, email, category_id, photo1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isissssis", $user_id, $title, $price, $university, $content, $phone, $email, $category, $output_file );
       
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // move_uploaded_file($tempname1, $folder1);
            // move_uploaded_file("testcrimson", $uploaddir);
            // move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
        
            echo json_encode(["success" => true, "message" => "Ad posted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to post ad. Try again in some time! ". $stmt->error]);
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
}
?>
