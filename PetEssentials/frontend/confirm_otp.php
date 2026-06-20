
<?php
    // Connection to database
    include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

    session_start(); // Start 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['for_otp'])) {
            $entered_otp = $_POST['for_otp'];

            // Check if the entered OTP matches the one in the session
            if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
                // If OTP is correct, redirect to login.php
                header("Location: reset_password.php");
                exit();
            } else {
                // IF OTP is incorrect, show an error message
                $error_message = "Wrong OTP. Please try again.";
            }
        }
    }
    
    // Display error message if OTP is incorrect
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/confirm_otp.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">

    <title>Verify OTP</title>
</head>
<body>

    <header>
        <div class="logo">
            <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" onclick="window.location.href='landing_page.php';">
        </div>
        <nav>
            <ul>
            <li><a href="landing_page.php">Home</a></li>
            <li><a href="landing_page.php #featured-products-section">Product</a></li>
            <li><a href="landing_page.php #product-cateogies-section">Categories</a></li>
            <li><a href="landing_page.php #contact">Contact</a></li>
            </ul>
        </nav>
        <div class="header-btn-container">
            <button id="btn-login"  onclick="window.location.href='login.php';">Login</button>
            <button id="btn-signup" onclick="window.location.href='signup.php';">Sign up</button>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <form action="" method="POST">
                <div class="title-container">
                        <h3>VERIFY OTP CODE</h3>
                </div>
                <div class="inputs-container">
                    <label for="otp">Enter OTP Code</label>
                    <div class="otp-container">
                        <span class="key-icon"><i class="fa-solid fa-key"></i></span>
                        <input type="number" name="for_otp" placeholder="Enter OTP" required> <br>
                    </div>
                    <div class="button-container">
                        <button id="goback" type="submit" onclick="window.location.href='forgot_password.php';">Back</button> 
                        <button id="verifyotp" type="submit">Verify</button> 
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>
</body>
</html>

