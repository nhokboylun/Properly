<?php
session_start();
if (isset($_SESSION['user']) && isset( $_GET['house_id'])) {
  $house_id = $_GET['house_id'];
  // Change ur database info here.
  $servername = "localhost";
  $username = "tnguyen565";
  $password = "tnguyen565";
  $db_name = "tnguyen565";

  $conn = new mysqli($servername, $username, $password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if (!($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['house_id']))){
    $sql = "SELECT * FROM houses WHERE id='$house_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $image = $row['house_image'];
      $info = $row['house_info'];
      $city = $row['house_city'];
      $price = $row['house_price'];
      $bedRoom = $row['house_room'];
      $bathRoom = $row['house_bathroom'];
      $squareFt = $row['house_square'];
      $year = $row['house_built'];
      $parking = $row['house_parking'];
      $description = $row['house_description'];
    } else {
      echo "<script>alert('House id not found. Some error happened.')</script>";
      echo "<p>You are being redirected to the main page.</p>";
      echo "<meta http-equiv='refresh' content='2;url=./main.php'>";
      mysqli_close($conn);
    }
  }

  // This will handle whenever user click on add to wishlist, it will add to database
  else if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $house_id_to_check = $_POST['house_id'];
    $user_email = $_SESSION['user'];
  
    // Fetch the current wishlist value for the user
    $get_wishlist_sql = "SELECT wishList FROM Users WHERE email = '$user_email'";


    $wishlist_result = $conn->query($get_wishlist_sql);
    if ($wishlist_result->num_rows > 0) {
      $wishlist_row = $wishlist_result->fetch_assoc();
      $wishlist = $wishlist_row['wishList'];
  
      // Check if the house ID is already in the wishlist
      $wishlist_array = explode(",", $wishlist);
  
      if ($action === "check") {
        // Check if the house is in the wishlist and return the result
        echo in_array($house_id_to_check, $wishlist_array) ? "true" : "false";
      } else if ($action === "add") {
        if (!in_array($house_id_to_check, $wishlist_array)) {
          // Append the house ID to the wishlist string
          $wishlist .= empty($wishlist) ? $house_id_to_check : ",$house_id_to_check";
      
          // Update the wishlist column for the user
          $update_wishlist_sql = "UPDATE Users SET wishList = '$wishlist' WHERE email = '$user_email'";
          if ($conn->query($update_wishlist_sql) === TRUE) {
            $_SESSION['wishlist'] = $wishlist;
            echo "success";
          } else {
            echo "error";
          }
        } else {
          echo "House is already in the wishlist";
        }         
      } else if ($action === "remove") {
        if (in_array($house_id_to_check, $wishlist_array)) {
          // Remove the house ID from the wishlist array
          $wishlist_array = array_diff($wishlist_array, [$house_id_to_check]);
  
          // Convert the array back to a string and update the wishlist column for the user
          $wishlist = implode(",", $wishlist_array);
          $update_wishlist_sql = "UPDATE Users SET wishList = '$wishlist' WHERE email = '".$user_email."'";

          if ($conn->query($update_wishlist_sql) === TRUE) {
            $_SESSION['wishlist'] = $wishlist;
            echo "success";
          } else {
            echo "error";
          }
          
        } else {
          echo "House is not in the wishlist";
        }
      }
    } else {
      echo "User not found";
    }
    exit();
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
    <link rel="stylesheet" href="houseInfo.css" />
  </head>
  <body>
    <header>
      <div class="title">
        <img class="logo" src="./logo.jpg" alt="Logo" />
        <span>PROPERLY</span>
      </div>
      <nav>
        <a href="./main.php" class="nav-item">Home</a>
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
    <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="houseInfo.css" />
  </head>
  <body>
    <header>
      <div class="title">
        <img class="logo" src="./logo.jpg" alt="Logo" />
        <span>PROPERLY</span>
      </div>
      <nav>
        <a href="./main.php" class="nav-item">Home</a>
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
    <div class="clearfix">
      <div class="box">
            <img src="<?php echo $image; ?>"/>
      </div>
    <div class="box" style="background-color:white">
    <dl>
    <dt>House Information</dt>  
      <dd><?php echo $info; ?></dd>  
    <dt>City</dt>  
      <dd><?php echo $city; ?></dd>  
    <dt>City</dt>  
      <dd><?php echo $city; ?></dd>  
    <dt>Price</dt>  
      <dd>$<?php echo $price; ?></dd>  
    <dt>Bedroom/Bath</dt>  
      <dd>Bedroom's: <?php echo $bedRoom; ?> Bathroom's: <?php echo $bathRoom; ?></dd>  
    <dt>Size</dt>  
      <dd><?php echo $squareFt; ?> Square Feet</dd>  
    <dt>Age</dt>  
      <dd>Built in: <?php echo $year; ?></dd>  
    <dt>Parking</dt>  
      <dd><?php echo $parking; ?></dd>  
    <dt>House Description</dt>  
      <dd><?php echo $description; ?></dd>  
  </dl>
    </div>
    </div>
    <button id = "wishlist-btn">Add to wish list</button>



    <script>
      let wishlistBtn = document.getElementById("wishlist-btn");
      let houseInWishlist = false;

      function checkHouseInWishlist() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "houseInfo.php?house_id=<?php echo $house_id; ?>", true);

        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            houseInWishlist = xhr.responseText === "true";
            updateWishlistButton();
          }
        };
        xhr.send("action=check&house_id=<?php echo $house_id; ?>");
      }

      function updateWishlistButton() {
        let buttonText = houseInWishlist ? "Remove from wish list" : "Add to wish list";
        wishlistBtn.innerHTML = buttonText;
      }

      wishlistBtn.addEventListener("click", function() {
        houseInWishlist = !houseInWishlist;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "houseInfo.php?house_id=<?php echo $house_id; ?>", true);

        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Update the button text
            updateWishlistButton();
          }
        };
        xhr.send("action=" + (houseInWishlist ? "add" : "remove") + "&house_id=<?php echo $house_id; ?>");
      });

      checkHouseInWishlist();

    </script>
  </body>