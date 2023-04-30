<?php
session_start();
if (isset($_SESSION['user'])){
  $clientUserName = $_SESSION['user'];
  // Change ur database info here.
	$servername = "localhost";
	$username = "tnguyen565";
	$password = "tnguyen565";
	$db_name = "tnguyen565";

	$conn = new mysqli($servername, $username, $password, $db_name);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["search_city"])) {
			$search_city = $_POST["search_city"];
			$sql = "SELECT * FROM houses WHERE house_city='$search_city'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo '<div class="card-container">';
					echo "<img src='image.php?filename=" . htmlspecialchars($row['house_image']) . "' width='200' height='150'>";
					echo "<h4>" . $row['house_info'] . "</h4>";
					echo "<p>" . $row['house_city'] . "</p>";
					echo "</div>";
				}
			} else {
				echo "No results found.";
			}
			exit();
		}
	} else {
    $usercheck = "SELECT firstLogIn FROM Users WHERE email = '$clientUserName'";
    $user = $conn->query($usercheck);
    $value = $user->fetch_assoc()['firstLogIn'];
    $sql = "UPDATE Users SET firstLogIn = 'y' WHERE email = '$clientUserName'";
    $conn->query($sql);
		$sql = "SELECT * FROM houses";
		$result = $conn->query($sql);
	}
} else {
	session_destroy();
	header("Location: ./index.html");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="main.css" />
    <script src="./main.js"></script>
  </head>
  <body>
      <div id="popup">
          <h2> Welcome to our really cool website :D </h2>
          <p>We are glad you chose us because we are the coolest</p>
          <a href="#" onclick="toggle()">Continue to Website</a>
      </div>

    <header>
      <div class="title">
        <img class="logo" src="./logo.jpg" alt="Logo" />
        <span>PROPERLY</span>
      </div>
      <nav>
        <a class="nav-item">Home</a>
        <a href="./About.html" class="nav-item">About Us</a>
        <form method="post" action="./logout.php">
          <input
            class="nav-item logout"
            type="submit"
            name="logout"
            value="Logout"
          />
        </form>
      </nav>
    </header>

    <main>
      <div class="search">
				<input id="locationSearch" type="text" placeholder="Search for a location">
  			<button id="searchButton">Search</button>
      </div>
      <div id="photo-container"></div>
    </main>
    <?php if ($value == "n"){
        echo "<script>toggle();</script>";
      }
    ?>
  </body>
</html>

