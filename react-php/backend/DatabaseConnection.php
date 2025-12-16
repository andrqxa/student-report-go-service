<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class DatabaseConnection
{

    public static function getConnection()
    {

        $servername = "localhost";
        $username = "id22107020_marketplace";
        $password = "Marketplace@00";
        $database = "id22107020_marketplace";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}


// $db = new DatabaseConnection();
// $con = $db->getConnection();
