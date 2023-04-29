<!DOCTYPE html>
<html>

<head>
  <title>Verification</title>
  <link rel="stylesheet" type="text/css" href="./Verification.css" />
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
      function formatPhoneNumber(event) {
        const codeNumber = event.target.value.replace(/\D/g, "");
        event.target.value = codeNumber;
      }
  </script>
</head>

<body>
  <?PHP
  include 'SendMail.php';
  if (isset($_POST['submitFromForgotPassword']) || isset($_POST['submit']) || isset($_POST['Verify'])) {
    /* Connect to database */
    ob_start(); //This is optional. require by our specific website host only.
    if(isset($_POST['submit'])){
      // Change ur database info here.
      $servername = "localhost";
      $username = "root";
      $password = "Heoboy123$%^";

      // Create connection
      $conn = new mysqli($servername, $username, $password);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Create database if not exist
      $sqlCreate = "CREATE DATABASE IF NOT EXISTS Property_Check";
      if (!$conn->query($sqlCreate)) {
        echo "Error: " . $sqlCreate . "<br>" . $conn->error;
      }
    
      $conn->select_db("Property_Check");
      // Create Users table if not exist
      $sqlCreateTable = "CREATE TABLE IF NOT EXISTS Users (
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        email VARCHAR(255) PRIMARY KEY NOT NULL,
        password VARCHAR(255) NOT NULL,
        phonenumber VARCHAR (14) NOT NULL,
        EmailStatus SMALLINT DEFAULT 0,
        Code INT(6) DEFAULT NULL
      )";
      if (!$conn->query($sqlCreateTable)) {
        echo "Error: " . $sqlCreateTable . "<br>" . $conn->error;
    }

      // Create database to store key for sending mail
      $sqlCreateTable = "CREATE TABLE IF NOT EXISTS google_oauth (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        provider VARCHAR(255) NOT NULL,
        provider_value TEXT NOT NULL UNIQUE
      )";
      if (!$conn->query($sqlCreateTable)) {
        echo "Error: " . $sqlCreateTable . "<br>" . $conn->error;
      }
      // Prepare the SQL statement
      $sqlInsert = "
        INSERT INTO google_oauth (provider, provider_value)
        SELECT ?, ? FROM DUAL
        WHERE NOT EXISTS (SELECT * FROM google_oauth WHERE provider = ? AND provider_value = ?)
      ";

      // Initialize the prepared statement
      $stmt = $conn->prepare($sqlInsert);

      // Bind the values to the prepared statement
      $provider1 = "google"; 
      $provider_value1 = "wakhvafehnvafzlh";

      $provider2 = "google"; 
      $provider_value2 = "qkmvyfkrmaqdjqrg"; 

      $stmt->bind_param("ssss", $provider1, $provider_value1, $provider1, $provider_value1);
      $stmt->execute();

      $stmt->bind_param("ssss", $provider2, $provider_value2, $provider2, $provider_value2);
      $stmt->execute();

    } 
          
    // Change ur database info here.
    $servername = "localhost";
    $username = "root";
    $password = "Heoboy123$%^";
    $db_name = "Property_Check";
    $conn = new mysqli($servername, $username, $password, $db_name);
    if ($conn->connect_error) {
      die("connection failed" . $conn->connect_error);
    }
    if (isset($_POST['submitFromForgotPassword'])) {
      $emailReset = $_POST['emailFromForgotPassword'];
      $sqlCheck = "SELECT email FROM Users WHERE email=?";
      $stmt = $conn->prepare($sqlCheck);
      $stmt->bind_param("s", $emailReset);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows == 0) {
        ob_end_flush();
        mysqli_close($conn);
        echo "<script>alert('The email address you input is not registered yet. Please Try Again!')</script>";
        echo "<p>You are being redirected to the previous page.</p>";
        echo "<meta http-equiv='refresh' content='2;url=./ForgotPassword.html'>";
      }
      $code = str_pad(rand(800000, 999999), 6, '0', STR_PAD_LEFT);
      while (true) {
        $sqlCheck = "SELECT Code FROM Users WHERE Code = $code";
        $result = $conn->query($sqlCheck);
        if ($result->num_rows == 0) {
          break;
        }
        $code = str_pad(rand(800000, 999999), 6, '0', STR_PAD_LEFT);
      }
      $id = rand(1, 2);
      $sqlInputResetCode = "SELECT provider_value FROM google_oauth WHERE id = $id;";
      $resultInRow = $conn->query($sqlInputResetCode);
      $resultInValue =  $resultInRow->fetch_assoc();
      $Tran = $resultInValue['provider_value'];
      sendmail($emailReset, $code, $Tran);
      $sqlInputResetCode = "UPDATE Users SET Code = $code WHERE email = '$emailReset';";
      $conn->query($sqlInputResetCode);
    }

    /* User input verification code*/
    if (isset($_POST['Verify'])) {
      $verify = $_POST['VerificationCode'];
      $sqlcompare = "SELECT Code FROM Users WHERE Code = $verify";
      $resultVerify = $conn->query($sqlcompare);
      if ($resultVerify->num_rows === 1) {
        if ($verify < 800000 && $verify > 0) {
          $sqlActive = "UPDATE Users SET EmailStatus = '1' WHERE Code = $verify;";
          $sqlDelete = "UPDATE Users SET Code= NULL WHERE Code = $verify;";
          $conn->query($sqlActive);
          $conn->query($sqlDelete);
          ob_end_flush();
          mysqli_close($conn);
          header("Location: ./index.html");
          exit();
        } else if ($verify >= 800000) {
          $sql = "SELECT email FROM Users WHERE Code = $verify";
          $resultInRow = $conn->query($sql);
          $resultInValue =  $resultInRow->fetch_assoc();
          $email = $resultInValue['email'];
          ob_end_flush();
          mysqli_close($conn);
          session_start();
          $_SESSION['email']=$email;
          $_SESSION['verify']=$verify*2;
          header("Location: ./ResetPassword.php");
          exit();
        }
      } else {
        $codeDelete = $_POST['CodeToDelete'];
        if ($codeDelete < 800000 && $codeDelete > 0) {
          echo '
          <div class="container">
          <div class="login-container Verification-container">
            <h1>Failed !</h1>
            <p>
              You put the wrong code. Please hit the link below to Sign Up again.
            </p>
            <a href=./SignUp.html>Sign Up</a>
          </div>
          <div class="image-container">
            <img src="./Logo.png" alt="Placeholder Image" />
          </div>
        </div> 
      </body>
    </html>
          ';
          $sqlDeletee = "DELETE FROM Users WHERE Code = $codeDelete;";
          $conn->query($sqlDeletee);
          ob_end_flush();
          mysqli_close($conn);
          exit();
        } else {
          echo '
          <div class="container">
          <div class="login-container Verification-container">
            <h1>Failed !</h1>
            <p>
              You put the wrong code. Please hit the link below so you can start over.
            </p>
            <a href = ./ForgotPassword.html>Start Over</a>
          </div>
          <div class="image-container">
            <img src="./logo.jpg" alt="Placeholder Image" />
          </div>
        </div> 
      </body>
    </html>';
          $sqlResetFailed = "UPDATE Users SET Code= NULL WHERE Code = $codeDelete;";
          $conn->query($sqlResetFailed);
          ob_end_flush();
          mysqli_close($conn);
          exit();
        }
      }
    }
    /* User come from sign up page. Store data into database*/
    if (isset($_POST['submit'])) {
      $email_c = $_POST['email'];
      $sqlCheck = "SELECT email FROM Users WHERE email=?";
      $stmt = $conn->prepare($sqlCheck);
      $stmt->bind_param("s", $email_c);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows == 1) {
        ob_end_flush();
        mysqli_close($conn);
        echo "<script>alert('The email address you input is already registered. Please use another email!')</script>";
        echo "<p>You are being redirected to the previous page.</p>";
        echo "<meta http-equiv='refresh' content='2;url=./SignUp.html'>";
      }
      $code = str_pad(rand(1, 799999), 6, '0', STR_PAD_LEFT);
      while (true) {
        $sqlCheck = "SELECT Code FROM Users WHERE Code = $code";
        $result = $conn->query($sqlCheck);
        if ($result->num_rows == 0) {
          break;
        }
        $code = str_pad(rand(1, 799999), 6, '0', STR_PAD_LEFT);
      }
      $firstname_c =  $_POST['firstname'];
      $lastname_c =  $_POST['lastname'];
      $password_c = password_hash($_POST['pass'], PASSWORD_DEFAULT);
      $tel_c = $_POST['phonenumber'];
      $sql = "INSERT INTO Users (firstname,lastname,email,password,phonenumber,Code) VALUES ('$firstname_c', '$lastname_c', '$email_c', '$password_c', '$tel_c', '$code')";
      mysqli_query($conn, $sql);
      $id = rand(1, 2);
      $sqlInputResetCode = "SELECT provider_value FROM google_oauth WHERE id = $id;";
      $resultInRow = $conn->query($sqlInputResetCode);
      $resultInValue =  $resultInRow->fetch_assoc();
      $Tran = $resultInValue['provider_value'];
      sendmail($_POST['email'], $code, $Tran);
    }
    
    ob_end_flush();
    mysqli_close($conn);
  } else {
    header("Location: ./index.html");
  }
  ?>
  <div class="container">
    <div class="login-container Verification-container">
      <ion-icon name="checkmark-circle-outline"></ion-icon>
      <h1>Success !</h1>
      <p>
        An email has been sent to your email address. Please check your inbox
        for an email from our company and then put your verification code down below.
      </p>
      <form class="Verification-form" name="Verification" action="Verification.php" method="POST">
        <input style="width: 226px" type="text" name="VerificationCode" placeholder="Verification Code" maxlength="6" oninput="formatPhoneNumber(event)" required/>
        <input type="submit" id="btn-ForgotPassword" name="Verify" value="Verify">
        <input type="hidden" name="CodeToDelete" value="<?PHP echo $code; ?>">
      </form>
    </div>
    <div class="image-container">
      <img src="./logo.jpg" alt="Placeholder Image" />
    </div>
  </div>
</body>

</html>