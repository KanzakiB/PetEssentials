<?php
    // Connection to database
    include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');
    require 'C:\XAMPP\htdocs\PetEssentials\Mail\PHPMailer\class.phpmailer.php';
    require 'C:\XAMPP\htdocs\PetEssentials\Mail\PHPMailer\class.smtp.php';

    // For Sending Otp
    function sendOtp($email, $otp) {
        $mail = new PHPMailer;
        $mail->SMTPDebug = 2;  
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aera.montefalco33@gmail.com';  
        $mail->Password   = 'cmcw pnwe fqxx meih';    
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('aera.montefalco33@gmail.com', 'The Happy Tails');
        $mail->addAddress($email);    // sino makaka receive $email in the database used

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is <b>$otp</b>. Please use this to complete your password reset.";

        if ($mail->send()) {
            return true;
        } else {
            echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
            return false;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['send_otp_button'])) {
            $send_email = $_POST['send_email'];

            $check_email_sql = "SELECT * FROM registered_users WHERE email='$send_email'";
            $check_email_result = $conn->query($check_email_sql);

            if ($check_email_result->num_rows > 0) 
            {
                $otp = rand(100000, 999999); 

                if (sendOtp($send_email, $otp)) 
                {
                    session_start();
                    $_SESSION['otp'] = $otp;
                    $_SESSION['otp_email'] = $send_email;
                    header("Location: confirm_otp.php?email=" . urlencode($send_email));
                    exit();
                } else 
                {
                    echo "<p style='color:red;'>Failed to send OTP. Please try again later.</p>";
                }
            } else 
            {
                echo "<p style='color:red;'>Email does not exist in our records.</p>";
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/forgot_password.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">

    <title>Forgot Password</title>
</head>
<body>

    <header>
        <div class="logo">
            <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" onclick="window.location.href='landing_page.php';" >
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
            <form id="forgotPasswordForm" action="" method="POST">
                <div class="title-container">
                        <h3>FORGOT PASSWORD</h3>
                </div>
                <div class="inputs-container">
                    <label for="email">Email</label>
                    <div class="email-container">
                        <span class="envelope-icon"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="send_email" placeholder="Enter Email" required> <br>
                    </div>
                    <div class="button-container">
                        <button id="goback" type="button" name="backtoUserLogin" onclick="window.location.href='login.php';">Back</button>
                        <button id="tootp" type="submit" name="send_otp_button">Send</button>
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