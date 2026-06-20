<?php
// Connection to the database
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['button_login']) && $_POST['button_login'] == 'signin') {
        $login_email = trim($_POST['login_email']);
        $login_pw = trim($_POST['login_pw']);

        // Check if the email exists in the database
        $check_email_sql = "SELECT * FROM registered_users WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->bind_param("s", $login_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_passw = $row['passw'];

            // Verify the password
            if ((strlen($stored_passw) === 60 && password_verify($login_pw, $stored_passw)) || $login_pw === $stored_passw) {
                // Start session and save user details
                session_start();
                $_SESSION['id'] = $row['registeredID'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];

                // Check if a profile picture exists and encode it if needed
                if (!empty($row['profile_pic'])) {
                    $_SESSION['profile_pic'] = 'data:image/jpeg;base64,' . base64_encode($row['profile_pic']);
                } else {
                    $_SESSION['profile_pic'] = 'http://localhost/PetEssentials/frontend/images/customerpic.png';
                }

                // Redirect to product_home.php
                header("Location: product_home.php");
                exit();
            } else {
                echo "<p style='color:red;'>Invalid password. Please try again.</p>";
            }
        } else {
            echo "<p style='color:red;'>No account found with this email. Please register first.</p>";
        }

        $stmt->close();
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
    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">

    <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/login.css">

    <title>Login</title>
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

    <br>
    <div class="container">
        <div class="form-container">
            <form action="" method="POST">
                <div class="title-container">
                    <a id="UserSignup" href="signup.php">Not Yet Registered?<span class="signupA"> Sign up </span></a> <br>
                </div>
                <div class="inputs-container">
                    <label for="email">Email</label>
                    <div class="email-container">
                        <span class="envelope-icon"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="login_email" placeholder="Enter Email" required> <br>
                    </div>

                    <label for="password">Password</label>
                    <div class="password-container">
                        <span class="lock-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="login_pw" placeholder="Enter Password" required> <br>
                        <span class="password-toggle-icon" style="display:none;"><i class="fas fa-eye"></i></span>
                    </div>
                    <a id="Forgot_pw" href="forgot_password.php">Forgot password?</a> <br>
                    <button id="loginbtn" type="submit" name="button_login" value="signin">Login</button>
                </div>
            </form>
        </div>
    </div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>
</body>
</html>
