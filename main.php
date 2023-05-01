<?php
session_start();
if (isset($_SESSION['user'])) {
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
    }
  } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["priceFrom"]) && isset($_GET["priceTo"]) && isset($_GET["bedrooms"]) && isset($_GET["bathrooms"]) && isset($_GET["yearBuiltFrom"]) && isset($_GET["yearBuiltTo"]) && isset($_GET["parking"])) {
      $priceFrom = $_GET["priceFrom"];
      $priceTo = $_GET["priceTo"];
      $bedrooms = $_GET["bedrooms"];
      $bathrooms = $_GET["bathrooms"];
      $yearBuiltFrom = $_GET["yearBuiltFrom"];
      $yearBuiltTo = $_GET["yearBuiltTo"];
      $parking = $_GET["parking"];
      $parking = strtolower($parking[0]);
      $sql = "SELECT * FROM houses WHERE house_price > $priceFrom AND house_price < $priceTo AND house_room = $bedrooms AND house_bathroom = $bathrooms AND house_built < $yearBuiltTo AND house_built > $yearBuiltFrom AND house_parking = '$parking'";
    }
  }

  if (isset($sql)) {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '<a href="houseInfo.php">';
        echo '<div class="card-container">';
        echo "<img src='image.php?filename=" . htmlspecialchars($row['house_image']) . "' width='200' height='150'>";
        echo "<h4>" . $row['house_info'] . "</h4>";
        echo "<p>" . $row['house_city'] . "</p>";
        echo "<p>$" . $row['house_price'] . "</p>";
        echo "</div>";
        echo '</a>';
      }
    } else {
      echo '<p style="text-align:center;grid-column:1/-1;"> No results found. </p>';
    }
    exit();
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
      <h1>Welcome Aboard!</h1>
      <p>🎉 Congratulations on joining our fantastic community! 🎉</p>
      <p>Here, you'll find the most amazing properties tailored to your preferences. Browse through our selection, and don't hesitate to reach out if you have any questions. We're here to help you find your dream home! 🏡</p>
      <p>
          At Properly, we're committed to making your home buying experience as
          easy, efficent, and stress-free as possible.
        </p>
        <p>
          Our team of very experience real estate professionals has decades of
          comvined experience in the industry.
        </p>
        <p>We put our dedication to helping you finding your perfect home!</p>
        <p>
          To do this, we believe that exceptional customer service is the key to
          a successful home buying experience.
        </p>
        <p>
          We're always here to help you navigate the process, answer any
          questions you may have, and ensure that you feeel supported
        </p>
        <p>
          While are other competitors may have a well known and larger platforms
          compared to ours, Properly sets itself apart
        </p>
        <p>
          by offering a seamless, user-friendly experience that's focused on
          helping you find the perfect home in the most effiencet and effective
          way.
        </p>
        <p>
          Our commitment to transparency, integrity and exceptional customer
          service make us the ideal choice for anyone looking to buy a home with
          confidence and ease.
        </p>
        <p>So why not look for your dream home in the most proper way!!!!</p>
        <p></p>
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
				<input id="locationSearch" type="text" placeholder="Search based on City">
  			<button id="searchButton">Search</button>
        <button id="searchWithFilterButton" onclick="toggleFilterForm()">Search With Filter</button>
        <div id="filterFormContainer" style="display: none;">
          <form id="filterForm" method="get">
            <label for="priceFrom">Price range:</label>
            <div class="range">
              <input type="number" id="priceFrom" name="priceFrom" placeholder="From" min="0" required>
              <label for="priceTo">to</label>
              <input type="number" id="priceTo" name="priceTo" placeholder="To" min="0" required>
            </div>
            <label for="bedrooms">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" placeholder="Number of bedrooms" min="1" required>
            <label for="bathrooms">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" placeholder="Number of bathrooms" min="1" required>
            <label>Year built:</label>
            <div class="range">
              <input type="number" id="yearBuiltFrom" name="yearBuiltFrom" placeholder="From" min="0" required>
              <label for="yearBuiltTo">to</label>
              <input type="number" id="yearBuiltTo" name="yearBuiltTo" placeholder="To" min="0" required>
            </div>
            <div class="parking-options">
              <div>
                <label for="hasParking">Has parking</label>
                <input type="radio" id="hasParking" name="parking" value="yes" selected required>
              </div>
              <div>
                <label for="noParking">Does not have parking</label>
                <input type="radio" id="noParking" name="parking" value="no" required>
              </div>
            </div>
            <div class="centered-button-container">
              <button class="centered-button" type="submit">Search</button>
            </div>
          </form>
        </div>
      </div>
      <div id="photo-container"></div>
    </main>
    <?php if ($value == "n"){
        echo "<script>toggle();</script>";
      }
    ?>
  </body>
</html>

