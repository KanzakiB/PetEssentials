<?php
session_start();

include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

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


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/customeremail.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<header>
  <div class="logo">
      <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" onclick="window.location.href='product_home.php';">
  </div>
  <div class="search-container">
      <!-- Search Input -->
      <input type="text" id="search-query" placeholder="Search by product name..." onkeyup="searchProducts()">

      
      <!-- Category Dropdown -->
    <select id="category-dropdown">
          <option value="all">All Categories</option>
          <option value="Nutrition">Nutrition</option>
          <option value="Hygiene">Hygiene</option>
          <option value="Bedding">Bedding</option>
          <option value="Feeding">Feeding Accessories</option>
          <option value="Toys">Toys</option>
          <option value="Training">Training Essentials</option>
          <option value="Travel">Travel Accessories</option>
          <option value="Cleaning">Cleaning Supplies</option>
          <option value="Wardrobe">Wardrobe</option>
          <option value="Health">Health and Wellness</option>
      </select> 

      <!-- Search Icon -->
      <button type="button" class="search-icon-btn">
          <i class="fa fa-search"></i>
      </button>
  </div>
  
  <div class="header-btn-container">
      <div class="cart-container">
        <i class="fa-solid fa-cart-shopping cart-icon"></i>
      </div>
      <div class="profile-container" onclick="redirectToProfile()">
        <img id="profile-image" src="<?php echo $profilePic; ?>" alt="profile-picture">
        <p id="customer-username"><?php echo htmlspecialchars($user['username']); ?></p>
      </div>
  </div>
</header>

<div class="main-container">
    <div class="profile-nav-container">
        <div class="profilepic-con">
            <img id="profilenav" src="<?php echo $profilePic; ?>" alt="profile-picture">
            <p id="customername"><?php echo htmlspecialchars($user['username']); ?></p>
        </div>
        <hr class="linebr">
        <div class="profnav-con">
            <div class="nava">
                <i class="fa-solid fa-user"></i>
                <a href="customer_profile.php">Profile</a> 
            </div>
            <div class="nava">
                <i class="fa-solid fa-location-dot"></i>
                <a href="customer_addresses.php">Addresses</a>
            </div>
            <div class="nava active">
                <i class="fa-solid fa-envelope"></i>
                <a href="email_security.php">Change Email</a> 
            </div>
            <div class="nava">
                <i class="fa-solid fa-lock"></i>
                <a href="password_security.php">Change Password</a> 
            </div>
            <div class="nava">
                <i class="fa-solid fa-clipboard-list"></i>
                <a href="customer_purchase.php">My Purchase</a>
            </div>
            <div class="nava">
                <i class="fa-solid fa-right-from-bracket"></i>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="profile-info-container">
        <div class="titleprofile">
            <h3>My Email</h3>
            <p>Manage your email account</p>
        </div>
        <hr class="linebr">
        <div class="change-container">
            <form method="post" id="newEmailContainer">
                <label for="email">New Email</label> <Br> <bR>
                    <div class="email-container">
                        <input type="email" name="email" id="newEmail" placeholder="Enter New Email">
                    </div>
                    <p class="error-message" id="error-message"></p> 

                <button type="submit" id="saveNewEmail">Save</button>
            </form>
        </div>
    </div>
</div>


<!-- Success Modal -->
<div id="success-modal" class="modal">
    <div class="modal-content">
        <p>Email Updated successfully!</p>
        <button id="modal-ok-button">OK</button>
    </div>
</div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script>
function redirectToProfile() {
    window.location.href = "customer_profile.php";
}
$(document).ready(function () {
    $("#newEmailContainer").submit(function (event) {
        event.preventDefault(); 

        var email = $("#newEmail").val();

        $.ajax({
            url: 'edit_email.php', 
            type: 'POST',
            data: { email: email },
            success: function (response) {
                const errorMessage = $("#error-message");
                errorMessage.text(""); 

                if (response.indexOf("error") !== -1) {
                    if (response === "error: not_logged_in") {
                        errorMessage.text("You must be logged in to change your email.");
                    } else if (response === "error: email_empty") {
                        errorMessage.text("Please enter a valid email address.");
                    } else if (response === "error: invalid_email") {
                        errorMessage.text("The email address is invalid.");
                    } else if (response === "error: email_exists") {
                        errorMessage.text("This email address is already used.");
                    } else if (response === "error: database_error") {
                        errorMessage.text("There was an error updating your email.");
                    }
                } else if (response.indexOf("success") !== -1) {
                    $("#success-modal").fadeIn();

                    $("#modal-ok-button").on("click", function () {
                        window.location.href = "customer_profile.php";
                    });
                }
            }
        });
    });
});

</script>


</body>
</html>
