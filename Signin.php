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
                house_city VARCHAR(250) NOT NULL),
                house_price VARCHAR(250) NOT NULL),
                house_room VARCHAR(250) NOT NULL),
                house_bathroom VARCHAR(250) NOT NULL),
                house_square VARCHAR(250) NOT NULL),
                house_built VARCHAR(250) NOT NULL),
                house_parking VARCHAR(250) NOT NULL)";
                if (!$conn->query($sql) === TRUE) {
                    echo "error creating table: " . $conn->error;
                    exit();
                } 

                $conn = new mysqli($servername, $username, $password, $db_name);
                if ($conn->connect_error) {
                    echo "Could not connect to server\n";
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "INSERT IGNORE INTO houses (house_image, house_info, house_city, house_price, house_room, house_bathroom, house_sqaure, house_built, house_parking)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);

                $houses = array(
                    array("./img/augusta1.jpg", "525 Gary Glen Dr", "Augusta", "369,900", "4", "3", "2,393", "2018 built", "parking avaliable"),
                    array("./img/augusta2.jpg", "1032 6th Ave", "Augusta", "138,000", "3", "2", "1,108", "2015 built", "parking avaliable"),
                    array("./img/augusta3.jpg", "2106 Summer Hill Ln", "Augusta", "400,000", "3", "3", "2,676", "1983 built", "parking avaliable"),
                    array("./img/augusta4.jpg", "327 E Shoreline Dr", "Augusta", "715,000", "4", "4", "3,335", "2007 built", "parking avaliable"),
                    array("./img/augusta5.jpg", "3533 Granite Way", "Augusta", "750,000", "5", "4", "4,731", "2000 built", "parking avaliable"),
                    array("./img/augusta6.jpg", "2627 Helen St", "Augusta", "899,000", "4", "5", "4,300", "1903 built", "parking avaliable"),

                    array("./img/atlanta1.jpg", "134 Whitefoord Ave NE", "Atlanta", "769,900", "4", "3", "2,497", "1930 built", "parking avaliable"),
                    array("./img/atlanta2.jpg", "137 Park Dr", "Atlanta", "1,350,000", "6", "5", "4,000", "2016 built", "parking avaliable"),
                    array("./img/atlanta3.jpg", "3252 Indian Valley Trl", "Atlanta", "550,000", "4", "3", "3,512", "1969 built", "parking avaliable"),
                    array("./img/atlanta4.jpg", "1789 Vicki Ln SE", "Atlanta", "342,500", "3", "2", "1,430", "1956 built", "parking avaliable"),
                    array("./img/atlanta5.jpg", "1536 Sylvester Cir SE", "Atlanta", "750,000", "4", "4", "2,760", "2017 built", "parking avaliable"),
                    array("./img/atlanta6.jpg", "1070 Mercer St SE", "Atlanta", "825,000", "3", "3", "2,518", "2004 built", "parking avaliable"),

                    array("./img/athens1.jpg", "835 Ansbeth Way", "Athens", "410,000", "3", "3", "2,302", "2016 built", "parking avaliable"),
                    array("./img/athens2.jpg", "124 Pin Oak Ct", "Athens", "585,000", "5", "3", "4,934", "1985 built", "parking avaliable"),
                    array("./img/athens3.jpg", "2576 Ryland Hills Dr", "Athens", "700,000", "8", "4", "4,294", "2007 built", "parking avaliable"),
                    array("./img/athens4.jpg", "445 Cedar Creek Dr", "Athens", "330,000", "3", "2", "1,720", "1983 built", "parking avaliable"),
                    array("./img/athens5.jpg", "637 Huntington C-2 Rd Unit C-2", "Athens", "219,900", "2", "3", "1,168", "2000 built", "parking avaliable"),
                    array("./img/athens6.jpg", "1161 Ruby Way", "Athens", "449,000", "3", "2", "2,438", "2004 built", "parking avaliable"),

                    array("./img/roswell1.jpg", "4154 Edinburgh Trl NE", "Roswell", "650,000", "4", "3", "3,135", "1978 built", "parking avaliable"),
                    array("./img/roswell2.jpg", "12225 King Cir", "Roswell", "1,795,000", "5", "5", "3,785", "2017 built", "parking avaliable"),
                    array("./img/roswell3.jpg", "3641 Childers Way NE", "Roswell", "799,000", "6", "4", "4,440", "1995 built", "parking avaliable"),
                    array("./img/roswell4.jpg", "565 Meadowglen Trl", "Roswell", "625,000", "4", "3", "3,100", "1975 built", "parking avaliable"),
                    array("./img/roswell5.jpg", "302 Highland Ct", "Roswell", "410,000", "3", "3", "2,096", "1996 built", "parking avaliable"),
                    array("./img/roswell6.jpg", "130 Arrowood Ct", "Roswell", "679,000", "4", "4", "2,241", "1970 built", "parking avaliable"),

                    array("./img/alpharetta1.jpg", "172 Pinetree Cir Unit 1", "Alpharetta", "3,621,500", "5", "7", "5,003", "2023 built", "parking avaliable"),
                    array("./img/alpharetta2.jpg", "525 Blue Heron Way", "Alpharetta", "2,500,000", "6", "8", "8,917", "2004 built", "parking avaliable"),
                    array("./img/alpharetta3.jpg", "215 Pinetree Cir", "Alpharetta", "3,697,500", "5", "7", "5,003", "2023 built", "parking avaliable"),
                    array("./img/alpharetta4.jpg", "770 Barnesley Ln Unit X", "Alpharetta", "1,250,000", "6", "6", "6,691", "2001 built", "parking avaliable"),
                    array("./img/alpharetta5.jpg", "14732 Taylor Valley Way", "Alpharetta", "1,400,000", "6", "7", "4,580", "2004 built", "parking avaliable"),
                    array("./img/alpharetta6.jpg", "876 3rd St", "Alpharetta", "1,235,000", "3", "4", "2,448", "2014 built", "parking unavaliable")
                );

                foreach ($houses as $house) {
                    mysqli_stmt_bind_param($stmt, "sss", $house[0], $house[1], $house[2], $house[3], $house[4], $house[5], $house[6], $house[7], $house[8]);

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