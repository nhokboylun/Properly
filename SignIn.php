<?php
if (isset($_POST['submitFromSignIn'])) {
    ob_start();
    // Change ur database info here.
    $servername = "localhost";
	$username = "tnguyen565";
	$password = "tnguyen565";
	$db_name = "tnguyen565";
    $conn = new mysqli($servername, $username, $password, $db_name);
    if ($conn->connect_error) {
        die("connection failed" . $conn->connect_error);
    }
    $sql = "SHOW TABLES LIKE 'Users'";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
        $username_c = $_POST["username"];
        $password_c = $_POST["password"]; 
        $sqlCheckMatchPassword = "SELECT password FROM Users WHERE email ='$username_c'";
        $resultCheckMatchPassword = $conn->query($sqlCheckMatchPassword);
        $db_password = $resultCheckMatchPassword->fetch_assoc()['password'];
        $sql = "SELECT email, password FROM Users WHERE email = '$username_c' AND EmailStatus = 1";
        $result = $conn->query($sql);
        if ($result->num_rows === 1 && password_verify($password_c, $db_password)) {
            // Create table if not exists
            $conn = new mysqli($servername, $username, $password, $db_name);
            if ($conn->connect_error){
                echo "Could not connect to server\n";
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SHOW TABLES LIKE 'houses'";
            $result = $conn->query($sql);
            $tableExist = ($result->num_rows == 1);
            
            if (!$tableExist){
                $sql = "CREATE TABLE IF NOT EXISTS houses (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                house_image VARCHAR(250) NOT NULL,
                house_info VARCHAR(250) NOT NULL,
                house_city VARCHAR(250) NOT NULL)";
                if (!$conn->query($sql) === TRUE) {
                    echo "error creating table: " . $conn->error;
                    exit();
                } 

                $conn = new mysqli($servername, $username, $password, $db_name);
                if ($conn->connect_error) {
                    echo "Could not connect to server\n";
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "INSERT IGNORE INTO houses (house_image, house_info, house_city) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);

                $houses = array(
                    array("./img/augusta1.jpg", "525 Gary Glen Dr", "Augusta"),
                    array("./img/augusta2.jpg", "1032 6th Ave", "Augusta"),
                    array("./img/augusta3.jpg", "2106 Summer Hill Ln", "Augusta"),
                    array("./img/augusta4.jpg", "327 E Shoreline Dr", "Augusta"),
                    array("./img/augusta5.jpg", "3533 Granite Way", "Augusta"),
                    array("./img/augusta6.jpg", "2627 Helen St", "Augusta"),

                    array("./img/atlanta1.jpg", "134 Whitefoord Ave NE", "Atlanta"),
                    array("./img/atlanta2.jpg", "137 Park Dr", "Atlanta"),
                    array("./img/atlanta3.jpg", "3252 Indian Valley Trl", "Atlanta"),
                    array("./img/atlanta4.jpg", "1789 Vicki Ln SE", "Atlanta"),
                    array("./img/atlanta5.jpg", "1536 Sylvester Cir SE", "Atlanta"),
                    array("./img/atlanta6.jpg", "1070 Mercer St SE", "Atlanta"),

                    array("./img/athens1.jpg", "835 Ansbeth Way", "Athens"),
                    array("./img/athens2.jpg", "124 Pin Oak Ct", "Athens"),
                    array("./img/athens3.jpg", "2576 Ryland Hills Dr", "Athens"),
                    array("./img/athens4.jpg", "445 Cedar Creek Dr", "Athens"),
                    array("./img/athens5.jpg", "637 Huntington C-2 Rd Unit C-2", "Athens"),
                    array("./img/athens6.jpg", "1161 Ruby Way", "Athens"),

                    array("./img/roswell1.jpg", "4154 Edinburgh Trl NE", "Roswell"),
                    array("./img/roswell2.jpg", "12225 King Cir", "Roswell"),
                    array("./img/roswell3.jpg", "3641 Childers Way NE", "Roswell"),
                    array("./img/roswell4.jpg", "565 Meadowglen Trl", "Roswell"),
                    array("./img/roswell5.jpg", "302 Highland Ct", "Roswell"),
                    array("./img/roswell6.jpg", "130 Arrowood Ct", "Roswell"),

                    array("./img/alpharetta1.jpg", "172 Pinetree Cir Unit 1", "Alpharetta"),
                    array("./img/alpharetta2.jpg", "525 Blue Heron Way", "Alpharetta"),
                    array("./img/alpharetta3.jpg", "215 Pinetree Cir", "Alpharetta"),
                    array("./img/alpharetta4.jpg", "770 Barnesley Ln Unit X", "Alpharetta"),
                    array("./img/alpharetta5.jpg", "14732 Taylor Valley Way", "Alpharetta"),
                    array("./img/alpharetta6.jpg", "876 3rd St", "Alpharetta")
                );

                foreach ($houses as $house) {
                    mysqli_stmt_bind_param($stmt, "sss", $house[0], $house[1], $house[2]);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error inserting data into the table: " . mysqli_error($conn) . "<br>";
                    }
                }
                mysqli_stmt_close($stmt);
            }

            $conn->close();
            ob_end_flush();
            session_start();
            $_SESSION['user'] = $username_c;
            header("Location: ./main.php");
            exit();
        }
        echo "<script>alert('Either username or password or both is incorrect. Or email is not activate. Please try again!')</script>";
        echo "<p>You are being redirected to the login page.</p>";
        echo "<meta http-equiv='refresh' content='2;url=./index.html'>";
    } else {
        echo "<script>alert('You have not sign up. Please sign up first.')</script>";
        echo "<p>You are being redirected to the sign up page.</p>";
        echo "<meta http-equiv='refresh' content='2;url=./SignUp.html'>";
    }
    ob_end_flush();
    mysqli_close($conn);
} else {
    header("Location: ./index.html");
    exit();
}
?>
