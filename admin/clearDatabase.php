<?php
	
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=web_database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //  echo "Connected successfully";
    }
	catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$sql = "DELETE FROM json_data";
$conn->prepare($sql)->execute();

$sql = "DELETE FROM json_extra_data";
$conn->prepare($sql)->execute();
?>
