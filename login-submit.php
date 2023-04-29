<?php
session_start();

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST["username"];
$password = $_POST["password"];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//check if  exists and the password is correct
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
        
        $_SESSION["user_id"] = $row["id"];

        // Redirecting to the buyer dashboard
        header("Location: welcome.php");
        exit();
    } else {
        // Display an error message to the user
        echo "Invalid password";
    }
} else {
    // Display an error message to the user
    echo "Invalid username";
}

$conn->close();
?>
