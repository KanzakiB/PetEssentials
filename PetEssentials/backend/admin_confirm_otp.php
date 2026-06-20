<?php
    include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['for_otp'])) {
            $entered_otp = $_POST['for_otp'];

            if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
                header("Location: admin_reset_password.php");
                exit();
            } else {
                $error_message = "Wrong OTP. Please try again.";
            }
        }
    }
    
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
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/backend/css/admin_confirmotp.css">

    <title>Verify OTP</title>
</head>
<body>
    <div class="logo">
        <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" >
    </div>
    <br>
    
    <div class="container">
        <div class="form-container">
            <form action="" method="POST">
                <div class="title-container">
                    <h3>VERIFY CODE</h3>
                </div>
                <div class="inputs-container">
                    <label for="otp">Enter OTP Code</label>
                    <div class="otp-container">
                        <span class="key-icon"><i class="fa-solid fa-key"></i></span>
                        <input type="number" name="for_otp" placeholder="Enter OTP" maxlength="6" required> <br>
                    </div>
                    <div class="button-container">
                        <button id="btnotpback" onclick="goBack()" type="submit">Back</button>
                        <button id="btnverify" type="submit">Verify</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = 'admin_forgot_password.php' ;
        }

    </script>
</body>
</html>

