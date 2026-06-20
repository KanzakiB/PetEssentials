<?php
include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

// Initialize error and success messages
$error_message = '';
$success_message = '';
$signup_email = '';
$signup_username = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $signup_email = $_POST['signup_email'];
    $signup_pw = $_POST['signup_pw'];
    $confirm_signup_pw = $_POST['confirm_signup_pw'];
    $signup_username = $_POST['username'];

    // Check if the username length is less than or equal to 7 characters
    if (strlen($signup_username) > 7) {
        $error_message = 'Username must be 7 characters or less.';
    } else {
        // Validate password requirements
        $lengthValid = strlen($signup_pw) >= 8;
        $capitalValid = preg_match('/[A-Z]/', $signup_pw);
        $numberValid = preg_match('/[0-9]/', $signup_pw);
        $specialValid = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $signup_pw);

        // Check if all password requirements are met
        if ($lengthValid && $capitalValid && $numberValid && $specialValid) {
            // Check if passwords match
            if ($signup_pw === $confirm_signup_pw) {
                // Check if the email already exists
                $email_check_sql = "SELECT * FROM registered_users WHERE email='$signup_email'";
                $email_check_result = mysqli_query($conn, $email_check_sql);

                if (mysqli_num_rows($email_check_result) > 0) {
                    $error_message = 'Email already exists. Please use a different email.';
                } else {
                    // Set the default profile picture
                    $defaultProfilePicPath = 'C:\XAMPP\htdocs\PetEssentials\frontend\images\customerpic.png';
                    $defaultProfilePic = file_get_contents($defaultProfilePicPath); // Get the binary data of the image

                    // Hash the password before saving it to the database
                    $hashed_pw = password_hash($signup_pw, PASSWORD_DEFAULT); // Hash the password

                    // Insert data into the database
                    $sql = "INSERT INTO registered_users (username, email, passw, profile_pic) VALUES ('$signup_username', '$signup_email', '$hashed_pw', ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $defaultProfilePic);  // Bind the binary data for profile picture
                    if (mysqli_stmt_execute($stmt)) {
                        $success_message = 'Signup successful!';
                        // Clear the form inputs upon successful signup
                        $signup_email = '';  // Clear email after success
                        $signup_username = '';  // Clear username after success
                    } else {
                        $error_message = 'Error: ' . mysqli_error($conn);
                    }
                }
            } else {
                $error_message = 'Passwords do not match. Please try again.';
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
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/signup.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">


    <title>Sign up</title>
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
            <form action="" method="POST" oninput="checkPasswordMatch()">
                <div class="title-container">
                    <a id="UserLogin" href="login.php">Already have an account? <span class="LoginA">Login</span></a> <br>
                </div>
            
                <div class="inputs-container">
                    <label for="username">Username</label>
                    <div class="username-container">
                        <span class="user-icon"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="username" placeholder="Enter Username" value="<?= htmlspecialchars($signup_username); ?>" maxlength="7" required> <br>
                    </div>
                    <label for="email">Email</label>
                    <div class="email-container">
                        <span class="email-envelope-icon"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="signup_email" placeholder="Enter Email" value="<?= htmlspecialchars($signup_email); ?>" required> <br>                          
                    </div>
                    <label for="password">Password</label>
                    <div class="password-container">
                        <span class="password-lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="signup_pw" name="signup_pw" placeholder="Enter Password" onfocus="showRequirements()" onkeyup="validatePassword()" required> <br>
                        <span class="pw-signup-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>
                    <label for="confirm-password">Confirm Password</label>
                    <div class="confirm-container">
                        <span class="password-lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="confirm_signup_pw" name="confirm_signup_pw" placeholder="Confirm Password" required> <br>
                        <span class="cwp-signup-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>
                
                    <!-- Error and Success messages -->
                    <div id="error-message" class="error-message">
                        <?= htmlspecialchars($error_message); ?>
                    </div>
                    <div id="success-message" class="success-message">
                        <?= htmlspecialchars($success_message); ?>
                    </div>

                    <!-- Password mismatch message -->
                    <div id="password-mismatch" style="color: red; display: none;">
                        Passwords do not match.
                    </div>

                    <!-- Password requirements -->
                    <ul id="requirements" class="requirements">
                        <li id="length"> Must be at least 8 characters long</li>
                        <li id="capital"> Must have at least one capital letter</li>
                        <li id="number"> Must have at least one number</li>
                        <li id="special"> Must have at least one special character</li>
                    </ul>

                    <!-- Submit button -->
                    <button id="signupbtn" type="submit">Register</button>
                </div>
            </form>

        </div>
    </div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

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

            // Check if password meets requirements
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

        document.addEventListener("DOMContentLoaded", function () {
            const passwordField = document.getElementById("signup_pw");
            const confirmPasswordField = document.getElementById("confirm_signup_pw");

            const passwordToggleIconContainer = document.querySelector(".password-container .pw-signup-icon");
            const confirmPasswordToggleIconContainer = document.querySelector(".confirm-container .cwp-signup-icon");

            // Ensure toggle icons display correctly when typing
            passwordField.addEventListener("input", function () {
                if (passwordField.value.length > 0) {
                    passwordToggleIconContainer.style.display = "inline";
                } else {
                    passwordToggleIconContainer.style.display = "none";
                }
            });

            confirmPasswordField.addEventListener("input", function () {
                if (confirmPasswordField.value.length > 0) {
                    confirmPasswordToggleIconContainer.style.display = "inline";
                } else {
                    confirmPasswordToggleIconContainer.style.display = "none";
                }
            });

            // Password field toggle functionality
            passwordToggleIconContainer.addEventListener("click", function () {
                const icon = passwordToggleIconContainer.querySelector("i");
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });

            // Confirm Password field toggle functionality
            confirmPasswordToggleIconContainer.addEventListener("click", function () {
                const icon = confirmPasswordToggleIconContainer.querySelector("i");
                if (confirmPasswordField.type === "password") {
                    confirmPasswordField.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    confirmPasswordField.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });
        });
    </script>
</body>
</html>
