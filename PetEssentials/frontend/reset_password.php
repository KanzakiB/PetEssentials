<?php
include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');
session_start(); // Start the session

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Check if user is logged in and allowed to reset password
if (!isset($_SESSION['otp_email'])) {
    // If the session does not have 'otp_email', redirect to login page
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $new_password = $_POST['reset_pw'];
    $confirm_password = $_POST['confirm_reset_pw'];
    $email = $_SESSION['otp_email']; // Use the email from the session

    // Validate password requirements
    $lengthValid = strlen($new_password) >= 8;
    $capitalValid = preg_match('/[A-Z]/', $new_password);
    $numberValid = preg_match('/[0-9]/', $new_password);
    $specialValid = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password);

    // Check if all password requirements are met
    if ($lengthValid && $capitalValid && $numberValid && $specialValid) {
        // Check if passwords match
        if ($new_password === $confirm_password) {
            // Directly use the new password without hashing
            $sql = "UPDATE registered_users SET passw='$new_password' WHERE email='$email'";

            if (mysqli_query($conn, $sql)) {
                // Clear the OTP session variables after successful reset
                unset($_SESSION['otp']);
                unset($_SESSION['otp_email']);
                $success_message = 'Password reset successfully!';
            } else {
                $error_message = 'Error updating password: ' . mysqli_error($conn);
            }
        } else {
            $error_message = 'Passwords do not match. Please try again.';
        }
    } else {
        $error_message = 'Please make sure your new password meets all the requirements.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/reset_pass.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">

    <title>Reset Password</title>
    <style>
        .requirements {
            display: none;
            list-style-type: none;
            padding: 0;
        }
        .requirements li {
            color: red;
        }
        .requirements li.valid {
            color: green;
        }
        .error-message {
            color: red;
            font-weight: bold;
            display: <?= !empty($error_message) ? 'block' : 'none'; ?>;
        }
        .success-message {
            margin-bottom: 15px;
            color: green;
            font-weight: bold;
            display: <?= !empty($success_message) ? 'block' : 'none'; ?>;
        }
        .additional-message {
            color: #000000;
            font-weight: bold;
            display: <?= !empty($success_message) ? 'block' : 'none'; ?>;
        }
        /* Ensure links are styled to be clickable */
        .additional-message a {
            color: #e8696b;
            text-decoration: none;
        }
        .additional-message a:hover {
            text-decoration: none;
        }
    </style>
    
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
            <form action="" method="POST" oninput="checkPasswordMatch()" onsubmit="return checkRequirements()">
                <div class="title-container">
                        <h3>RESET PASSWORD</h3>
                        <?php if (!empty($success_message)): ?>
                            <div id="additional-message" class="additional-message">
                                You can now <a href="login.php" id="LoginLink">login</a>
                            </div>
                        <?php endif; ?>
                </div>
                <div class="inputs-container">
                    <label for="resetpass">New Password</label>
                    <div class="resetpass-container">
                        <span class="password-lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="reset_pw" name="reset_pw" placeholder="Enter New Password" onfocus="showRequirements()" onkeyup="validatePassword()" required> <br>
                        <span class="pw-signup-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>

                    <label for="confirmresetpass">Confirm New Password</label>
                    <div class="resetconfirmpass-container">
                        <span class="password-lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="confirm_reset_pw" name="confirm_reset_pw" placeholder="Confirm New Password" required> <br>
                        <span class="cwp-signup-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>
                </div>

                <div id="error-message" class="error-message">
                    <?= htmlspecialchars($error_message); ?>
                </div>

                <div id="password-mismatch" style="color: red; display: none;">
                    Passwords do not match.
                </div>

                <ul id="requirements" class="requirements">
                    <li id="length"> Must be at least 8 characters long</li>
                    <li id="capital"> Must have at least one capital letter</li>
                    <li id="number"> Must have at least one number</li>
                    <li id="special"> Must have at least one special character</li>
                </ul>

                <button id="btnresetpass" type="submit">Reset Password</button>

                <?php if (!empty($success_message)): ?>
                    <div id="success-message" class="success-message">
                        <?= htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>
    


    <script>
        function showRequirements() {
            document.getElementById("requirements").style.display = "block";
            document.getElementById("error-message").style.display = "none";
        }

        function validatePassword() {
            var password = document.getElementById("reset_pw").value;
            var lengthRequirement = document.getElementById("length");
            var capitalRequirement = document.getElementById("capital");
            var numberRequirement = document.getElementById("number");
            var specialRequirement = document.getElementById("special");

            // Check if password meets requirements
            lengthRequirement.classList.toggle("valid", password.length >= 8);
            capitalRequirement.classList.toggle("valid", /[A-Z]/.test(password));
            numberRequirement.classList.toggle("valid", /[0-9]/.test(password));
            specialRequirement.classList.toggle("valid", /[!@#$%^&*(),.?":{}|<>]/.test(password));
        }

        function checkPasswordMatch() {
            var password = document.getElementById("reset_pw").value;
            var confirmPassword = document.getElementById("confirm_reset_pw").value;
            var mismatchMessage = document.getElementById("password-mismatch");

            if (password !== confirmPassword) {
                mismatchMessage.style.display = "block";
            } else {
                mismatchMessage.style.display = "none";
            }
        }

        function checkRequirements() {
            var password = document.getElementById("reset_pw").value;
            var lengthValid = password.length >= 8;
            var capitalValid = /[A-Z]/.test(password);
            var numberValid = /[0-9]/.test(password);
            var specialValid = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            // Show error message if requirements are not met
            var errorMessage = document.getElementById("error-message");
            if (lengthValid && capitalValid && numberValid && specialValid) {
                errorMessage.style.display = "none";
                return true;
            } else {
                errorMessage.style.display = "block";
                return false;
            }
        }
    </script>
</body>
</html>