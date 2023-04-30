<?php
session_start();
if (isset($_SESSION['user'])){
  // Change ur database info here.
	$servername = "localhost";
	$username = "wgula1";
	$password = "wgula1";
	$db_name = "wgula1";

	$conn = new mysqli($servername, $username, $password, $db_name);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usercheck = "SELECT firstLogIn FROM Users WHERE email ='".session['user']."'";
    $user = $conn->query($usercheck);
    if ($user == "n") {
      toggle();
      $update =  "UPDATE Users SET firstLogin = 'y' WHERE email = '".session['user']."'";
      if ($conn->query($update) === TRUE) {
        echo "Record updated successfully";
      } else {
        echo "Error updating record: " . $conn->error;
      }
      
    }

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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="main.css" />
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
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
    <script src="./main.js"></script>
  </body>
</html>

