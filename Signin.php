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
                $sql = "CREATE TABLE houses (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    house_image VARCHAR(250) NOT NULL,
                    house_info VARCHAR(250) NOT NULL,
                    house_city VARCHAR(250) NOT NULL,
                    house_price BIGINT UNSIGNED NOT NULL,
                    house_room SMALLINT UNSIGNED NOT NULL,
                    house_bathroom SMALLINT UNSIGNED NOT NULL,
                    house_square INT UNSIGNED NOT NULL,
                    house_built INT UNSIGNED NOT NULL,
                    house_parking CHAR(1) NOT NULL,
                    house_description VARCHAR(1000) NOT NULL
                )";
                
                if ($conn->query($sql) !== TRUE) {
                    echo "Error creating table: " . $conn->error;
                    exit();
                }

                $conn = new mysqli($servername, $username, $password, $db_name);
                if ($conn->connect_error) {
                    echo "Could not connect to server\n";
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "INSERT IGNORE INTO houses (house_image, house_info, house_city, house_price, house_room, house_bathroom, house_square, house_built, house_parking, house_description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);

                $houses = array(
                    array("./img/augusta1.jpg", "525 Gary Glen Dr", "Augusta", "369900", "4", "3", "2393", "2018", "y", "This modern 4-bedroom house offers a spacious living area, a contemporary kitchen, and a large backyard perfect for families."),
                    array("./img/augusta2.jpg", "1032 6th Ave", "Augusta", "138000", "3", "2", "1,108", "2015", "y", "A cozy 3-bedroom home with a recently updated kitchen, comfortable living space, and a lovely garden."),
                    array("./img/augusta3.jpg", "2106 Summer Hill Ln", "Augusta", "400000", "3", "3", "2,676", "1983", "y", "A charming house with a bright and open living area, a large modern kitchen, and a beautiful outdoor space."),
                    array("./img/augusta4.jpg", "327 E Shoreline Dr", "Augusta", "715000", "4", "4", "3,335", "2007", "y", "Luxurious lakeside property with stunning views, an expansive living area, and a modern gourmet kitchen."),
                    array("./img/augusta5.jpg", "3533 Granite Way", "Augusta", "750000", "5", "4", "4,731", "2000", "y", "Elegant 5-bedroom home featuring a spacious living area, a high-end kitchen, and a beautifully landscaped backyard."),
                    array("./img/augusta6.jpg", "2627 Helen St", "Augusta", "899000", "4", "5", "4,300", "1903", "y", "Stunning historic property with modern upgrades, a spacious living area, and a large backyard perfect for entertaining."),
                    // Atlanta houses...
                    array("./img/atlanta1.jpg", "134 Whitefoord Ave NE", "Atlanta", "769900", "4", "3", "2,497", "1930", "y", "Beautifully renovated historic home with modern amenities, an open living space, and a charming backyard."),
                    array("./img/atlanta2.jpg", "137 Park Dr", "Atlanta", "1350000", "6", "5", "4000", "2016", "y", "Gorgeous 6-bedroom house featuring a gourmet kitchen, a spacious living area, and a stunning outdoor space with a pool."),
                    array("./img/atlanta3.jpg", "3252 Indian Valley Trl", "Atlanta", "550000", "4", "3", "3512", "1969", "y", "Spacious 4-bedroom home with a large living area, an updated kitchen, and a beautiful backyard with a deck."),
                    array("./img/atlanta4.jpg", "1789 Vicki Ln SE", "Atlanta", "342500", "3", "2", "1,430", "1956", "y", "Charming mid-century home with modern updates, a cozy living space, and a lovely backyard perfect for relaxing."),
                    array("./img/atlanta5.jpg", "1536 Sylvester Cir SE", "Atlanta", "750000", "4", "4", "2,760", "2017", 
                    "y", "Contemporary 4-bedroom home with an open-concept living area, a stylish kitchen, and a private backyard oasis."),
                    array("./img/atlanta6.jpg", "1070 Mercer St SE", "Atlanta", "825000", "3", "3", "2,518", "2004", "y", "Modern 3-bedroom townhouse with high-end finishes, a spacious living area, and a rooftop terrace with city views."),
                    // Athens houses...
                    array("./img/athens1.jpg", "835 Ansbeth Way", "Athens", "410000", "3", "3", "2,302", "2016", "y", "Beautiful 3-bedroom home with an open floor plan, a modern kitchen, and a serene backyard with a patio."),
                    array("./img/athens2.jpg", "124 Pin Oak Ct", "Athens", "585000", "5", "3", "4,934", "1985", "y", "Expansive 5-bedroom home with a large living area, an updated kitchen, and a stunning backyard perfect for entertaining."),
                    array("./img/athens3.jpg", "2576 Ryland Hills Dr", "Athens", "700000", "8", "4", "4,294", "2007", "y", "Impressive 8-bedroom estate with a spacious living area, a chef's kitchen, and a resort-style backyard."),
                    array("./img/athens4.jpg", "445 Cedar Creek Dr", "Athens", "330000", "3", "2", "1,720", "1983", "y", "Charming 3-bedroom ranch with an inviting living space, a renovated kitchen, and a peaceful backyard with a deck."),
                    array("./img/athens5.jpg", "637 Huntington C-2 Rd Unit C-2", "Athens", "219900", "2", "3", "1,168", "2000", "y", "Cozy 2-bedroom townhome with an open living area, a functional kitchen, and a private patio."),
                    array("./img/athens6.jpg", "1161 Ruby Way", "Athens", "449000", "3", "2", "2,438", "2004", "y", "Lovely 3-bedroom home with a bright living area, a modern kitchen, and a spacious backyard with a pergola."),
                    // Roswell houses...
                    array("./img/roswell1.jpg", "4154 Edinburgh Trl NE", "Roswell", "650000", "4", "3", "3,135", "1978", "y", "Spacious 4-bedroom home with a comfortable living area, an updated kitchen, and a beautiful backyard with a pool."),
                    array("./img/roswell2.jpg", "12225 King Cir", "Roswell", "1795000", "5", "5", "3,785", "2017", "y", "Luxurious 5-bedroom estate with a stunning living area, a gourmet kitchen, and a resort-like backyard with a pool and spa."),
                    array("./img/roswell3.jpg", "3641 Childers Way NE", "Roswell", "799000", "6", "4", "4,440", "1995", "y", "Elegant 6-bedroom home with a grand living area, a chef's kitchen, and a beautifully landscaped backyard."),
                    array("./img/roswell4.jpg", "2010 Heathermere Ln", "Roswell", "475000", "4", "3", "2,812", "1988", "y", "Charming 4-bedroom house with a warm living area, an updated kitchen, and a tranquil backyard with a deck."),
                    array("./img/roswell5.jpg", "855 Crab Orchard Dr", "Roswell", "550000", "3", "3", "2,346", "1993", "y", "Delightful 3-bedroom home with a spacious living area, a modern kitchen, and a private backyard with a patio."),
                    array("./img/roswell6.jpg", "1205 Canton St", "Roswell", "1250000", "5", "4", "4,500", "2018", "y", "Stunning 5-bedroom property with an open-concept living area, a gourmet kitchen, and a beautiful outdoor space with a fireplace."),
                    // Savannah houses...
                    array("./img/savannah1.jpg", "51 E Victory Dr", "Savannah", "1100000", "6", "6", "5,750", "1928", "y", "Grand historic property with elegant living spaces, a gourmet kitchen, and a lush garden perfect for entertaining."),
                    array("./img/savannah2.jpg", "401 E 45th St", "Savannah", "1050000", "4", "4", "3,800", "1912", "y", "Magnificent 4-bedroom historic home with luxurious living areas, a chef's kitchen, and a charming courtyard."),
                    array("./img/savannah3.jpg", "605 E 52nd St", "Savannah", "665000", "4", "3", "2,858", "1930", "y", "Beautifully restored 4-bedroom house with modern amenities, an inviting living area, and a spacious backyard."),
                    array("./img/savannah4.jpg", "1102 E 38th St", "Savannah", "425000", "3", "3", "1,850", "1935", "y", "Charming 3-bedroom bungalow with a cozy living area, a renovated kitchen, and a delightful backyard with a patio."),
                    array("./img/savannah5.jpg", "208 E 64th St", "Savannah", "625000", "3", "2", "2,113", "1952", "y", "Stylish 3-bedroom mid-century home with a bright living area, a modern kitchen, and a serene backyard with a pool."),
                    array("./img/savannah6.jpg", "1023 E 34th St", "Savannah", "350000", "2", "2", "1,300", "1910", "y", "Charming 2-bedroom cottage with a warm living area, an updated kitchen, and a lovely backyard perfect for relaxation."),
                );

                foreach ($houses as $house) {
                    mysqli_stmt_bind_param($stmt, "sssisiiiss", $house[0], $house[1], $house[2], $house[3], $house[4], $house[5], $house[6], $house[7], $house[8], $house[9]);
                    
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