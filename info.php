<?php
$host= "localhost";
$user= "ychoi43";
$pass= "ychoi43";
$dbname= "ychoi43";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo "Could not connect to server\n";
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO houses (house_image, house_info, house_city) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

$houses = array(
    array("augusta1.jpg", "525 Gary Glen Dr", "Augusta"),
    array("augusta2.jpg", "1032 6th Ave", "Augusta"),
    array("augusta3.jpg", "2106 Summer Hill Ln", "Augusta"),
    array("augusta4.jpg", "327 E Shoreline Dr", "Augusta"),
    array("augusta5.jpg", "3533 Granite Way", "Augusta"),
    array("augusta6.jpg", "2627 Helen St", "Augusta"),

    array("atlanta1.jpg", "134 Whitefoord Ave NE", "Atlanta"),
    array("atlanta2.jpg", "137 Park Dr", "Atlanta"),
    array("atlanta3.jpg", "3252 Indian Valley Trl", "Atlanta"),
    array("atlanta4.jpg", "1789 Vicki Ln SE", "Atlanta"),
    array("atlanta5.jpg", "1536 Sylvester Cir SE", "Atlanta"),
    array("atlanta6.jpg", "1070 Mercer St SE", "Atlanta"),

    array("athens1.jpg", "835 Ansbeth Way", "Athens"),
    array("athens2.jpg", "124 Pin Oak Ct", "Athens"),
    array("athens3.jpg", "2576 Ryland Hills Dr", "Athens"),
    array("athens4.jpg", "445 Cedar Creek Dr", "Athens"),
    array("athens5.jpg", "637 Huntington C-2 Rd Unit C-2", "Athens"),
    array("athens6.jpg", "1161 Ruby Way", "Athens"),

    array("roswell1.jpg", "4154 Edinburgh Trl NE", "Roswell"),
    array("roswell2.jpg", "12225 King Cir", "Roswell"),
    array("roswell3.jpg", "3641 Childers Way NE", "Roswell"),
    array("roswell4.jpg", "565 Meadowglen Trl", "Roswell"),
    array("roswell5.jpg", "302 Highland Ct", "Roswell"),
    array("roswell6.jpg", "130 Arrowood Ct", "Roswell"),

    array("alpharetta1.jpg", "172 Pinetree Cir Unit 1", "Alpharetta"),
    array("alpharetta2.jpg", "525 Blue Heron Way", "Alpharetta"),
    array("alpharetta3.jpg", "215 Pinetree Cir", "Alpharetta"),
    array("alpharetta4.jpg", "770 Barnesley Ln Unit X", "Alpharetta"),
    array("alpharetta5.jpg", "14732 Taylor Valley Way", "Alpharetta"),
    array("alpharetta6.jpg", "876 3rd St", "Alpharetta")
);

foreach ($houses as $house) {
    $house_image = file_get_contents($house[0]);
    mysqli_stmt_bind_param($stmt, "bss", $house_image, $house[1], $house[2]);
    if (mysqli_stmt_execute($stmt)) {
        echo "Data inserted successfully into the table<br>";
    } else {
        echo "Error inserting data into the table: " . mysqli_error($conn) . "<br>";
    }
}

mysqli_stmt_close($stmt);
$conn->close();

foreach ($houses as $house) {
    echo "<tr><td><img src='image.php?filename=" . $house[0] . "' width='200' height='150'></td><td>" . $house[1] . "</td><td>" . $house[2] . "</td></tr>";
}
?>