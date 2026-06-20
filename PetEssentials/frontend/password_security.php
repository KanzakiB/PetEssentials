<?php
session_start();
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');
require 'C:\XAMPP\htdocs\PetEssentials\Mail\PHPMailer\class.phpmailer.php';
require 'C:\XAMPP\htdocs\PetEssentials\Mail\PHPMailer\class.smtp.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); 
    exit();
}

$registeredID = $_SESSION['id'];

$profilePic = isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'http://localhost/PetEssentials/frontend/images/customerpic.png';

$query = "SELECT * FROM registered_users WHERE registeredID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $registeredID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_verification_email'])) {
    $_SESSION['email_verification_sent'] = true; 
    $verificationLink = "http://localhost/PetEssentials/frontend/customer_pass.php"; 

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aera.montefalco33@gmail.com';
    $mail->Password = 'cmcw pnwe fqxx meih'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('aera.montefalco33@gmail.com', 'The Happy Tails');
    $mail->addAddress($user['email']);

    $mail->isHTML(true);
    $mail->Subject = 'Email Verification for Update';
    $mail->Body = "Please verify your email by clicking the button below:<br><br>
        <a href='$verificationLink' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: #e8696b; text-decoration: none; border-radius: 10px;'>Verify Email</a>";

    if ($mail->send()) {
        echo "<script>alert('Verification email sent successfully! Please check your email.');</script>";
    } else {
        echo "<script>alert('Failed to send verification email. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/security.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<header>
  <div class="logo">
      <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" onclick="window.location.href='product_home.php';">
  </div>
  <div class="search-container">
      <h3>Security Check</h3>
  </div>
  
  <div class="header-btn-container">
      <div class="profile-container" onclick="redirectToProfile()">
        <img id="profile-image" src="<?php echo $profilePic; ?>" alt="profile-picture">
        <p id="customer-username"><?php echo htmlspecialchars($user['username']); ?></p>
      </div>
  </div>
</header>

<div class="main-container">
    <div class="profile-info-container">
        <img src="http://localhost/PetEssentials/frontend/images/security.png" alt="security">
        <p>For security reasons, we require identity verification to update your password.</p>
        <form method="POST">
            <button id="sendEmail" name="send_verification_email">Send Verification Email</button>
        </form>
    </div>
</div>


<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script>
function redirectToProfile() {
    window.location.href = "customer_profile.php";
}

</script>


</body>
</html>
