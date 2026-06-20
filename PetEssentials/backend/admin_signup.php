<?php
include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

$error_message = '';
$success_message = '';
$signup_email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $signup_email = $_POST['signup_email'];
    $signup_pw = $_POST['signup_pw'];
    $confirm_signup_pw = $_POST['confirm_signup_pw'];

    if ($signup_pw !== $confirm_signup_pw) {
        $error_message = 'Passwords do not match. Please try again.';
    } else {
        $lengthValid = strlen($signup_pw) >= 8;
        $capitalValid = preg_match('/[A-Z]/', $signup_pw);
        $numberValid = preg_match('/[0-9]/', $signup_pw);
        $specialValid = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $signup_pw);

        if ($lengthValid && $capitalValid && $numberValid && $specialValid) {
            $email_check_sql = "SELECT * FROM admin_account WHERE admin_email='$signup_email'";
            $email_check_result = mysqli_query($conn, $email_check_sql);

            if (mysqli_num_rows($email_check_result) > 0) {
                $error_message = 'Email already exists. Please use a different email.';
            } else {
                $hashed_pw = password_hash($signup_pw, PASSWORD_DEFAULT);

                $sql = "INSERT INTO admin_account (admin_email, admin_passw) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $signup_email, $hashed_pw);
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = 'Signup successful! Redirecting to login page...';

                    header("Location: admin_login.php");
                    exit();
                } else {
                    $error_message = 'Error: ' . mysqli_error($conn);
                }
            }
        } else {
            $error_message = 'Please make sure your password meets all the requirements.';
        }
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
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/backend/css/admin_signup.css">
    <title>Admin Sign up</title>
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
            color: green;
            font-weight: bold;
            display: <?= !empty($success_message) ? 'block' : 'none'; ?>;
        }
        #password-mismatch {
            color: red;
            display: none; /* Initially hide the message */
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" >
    </div>
    
    <div class="container">
        <div class="form-container">
            <div class="admin-title-container">
                <h3>ADMIN SIGN UP</h3>
                <a id="ToLogin" href="admin_login.php">Already have an account? <Span id="loginA">Login</Span></a> <br>
            </div>
            <form action="" method="POST" oninput="checkPasswordMatch()">
                <div class="inputs-container">
                        <!-- Email field -->
                    <label for="admin-email">Email</label>
                    <div class="email-container">
                        <span class="email-envelope-icon"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="signup_email" placeholder="Enter Email" value="<?= htmlspecialchars($signup_email); ?>" required> <br>
                    </div>
                    
                    <!-- Password fields -->
                    <label for="admin-password">Password</label>
                    <div class="password-container">
                        <span class="password-lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="signup_pw" name="signup_pw" placeholder="Enter Password" onfocus="showRequirements()" onkeyup="validatePassword()" required> 
                        <span class="password-toggle" onclick="togglePassword('signup_pw')"><i class="fas fa-eye" id="eye-icon"></i></span><br>
                    </div>

                    <!-- Password requirements -->
                    <ul id="requirements" class="requirements">
                        <li id="length"> Must be at least 8 characters long</li>
                        <li id="capital"> Must have at least one capital letter</li>
                        <li id="number"> Must have at least one number</li>
                        <li id="special"> Must have at least one special character</li>
                    </ul>


                    <label for="admin-confirm-password">Confirm Password</label>
                    <div class="confirmpassword-container">
                        <span class="password-lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="confirm_signup_pw" name="confirm_signup_pw" placeholder="Confirm Password" required>
                        <span class="password-toggle" onclick="togglePassword('confirm_signup_pw')"><i class="fas fa-eye" id="confirm-eye-icon"></i></span><br>
                    </div>

                    <!-- Error and Success messages -->
                    <div id="error-message" class="error-message">
                        <?= htmlspecialchars($error_message); ?>
                    </div>
                    <div id="success-message" class="success-message">
                        <?= htmlspecialchars($success_message); ?>
                    </div>

                    <!-- Password mismatch message -->
                    <div id="password-mismatch">
                        Passwords do not match.
                    </div>

                    <!-- Submit button -->
                    <button class="btn-admin-signup" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
    
    <br>

    <script>
        function showRequirements() {
            document.getElementById("requirements").style.display = "block";
        }

        function validatePassword() {
            var password = document.getElementById("signup_pw").value;
            var lengthRequirement = document.getElementById("length");
            var capitalRequirement = document.getElementById("capital");
            var numberRequirement = document.getElementById("number");
            var specialRequirement = document.getElementById("special");

            lengthRequirement.classList.toggle("valid", password.length >= 8);
            capitalRequirement.classList.toggle("valid", /[A-Z]/.test(password));
            numberRequirement.classList.toggle("valid", /[0-9]/.test(password));
            specialRequirement.classList.toggle("valid", /[!@#$%^&*(),.?":{}|<>]/.test(password));
        }

        function checkPasswordMatch() {
            var password = document.getElementById("signup_pw").value;
            var confirmPassword = document.getElementById("confirm_signup_pw").value;
            var mismatchMessage = document.getElementById("password-mismatch");

            if (password !== confirmPassword) {
                mismatchMessage.style.display = "block";
            } else {
                mismatchMessage.style.display = "none";
            }
        }

        function togglePassword(id) {
            var passwordField = document.getElementById(id);
            var icon = id === 'signup_pw' ? document.getElementById('eye-icon') : document.getElementById('confirm-eye-icon');
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        document.getElementById("confirm_signup_pw").addEventListener("focus", function () {
            checkPasswordMatch();
        });
    </script>
</body>
</html>
