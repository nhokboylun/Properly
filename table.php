<?php
$host= "localhost";
$user= "ychoi43";
$pass= "ychoi43";
$dbname= "ychoi43";

$conn= new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error){
	echo "Could not connect to server\n";
	die("Connection failed: " . $conn->connect_error);
}
    
$sql = "CREATE TABLE houses (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
house_image VARCHAR(250) NOT NULL,
house_info VARCHAR(250) NOT NULL,
house_city VARCHAR(250) NOT NULL)";
if ($conn->query($sql) === TRUE) {
	echo "table houses created successfully";
} else {
	echo "error creating table: " . $conn->error;
}
$conn->close();
?>