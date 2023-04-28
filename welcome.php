<?php
$host= "localhost";
$user= "ychoi43";
$pass= "ychoi43";
$dbname= "ychoi43";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_city = $_POST["search_city"];
  $sql = "SELECT * FROM houses WHERE house_city='$search_city'";
  $result = $conn->query($sql);
}

?>

<html>
	<link rel="stylesheet" type="text/css" href="style.css">
	<body>

	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="search_city">Search for a city:</label>
		<input type="text" id="search_city" name="search_city">
		<input type="submit" value="Search">
	</form>

	<?php
	if (isset($result) && $result->num_rows > 0) {
		echo "<div class='housebox'>";
		while($row = $result->fetch_assoc()) {
	        echo "<a href='" . htmlspecialchars($row['house_info']) . ".php'>";
	        echo "<img src='image.php?filename=" . htmlspecialchars($row['house_image']) . "' width='200' height='150'>";
	        echo "<h4>" . $row['house_info'] . "</h4>";
	        echo "<p>" . $row['house_city'] . "</p>";
	        echo "</a>";
	    }
	    echo "</div>";
	}
	else {
		echo "No results found.";
	}
	$conn->close();
	?>

	</body>
</html>
