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
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/customerpurchase.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
            <div class="nava">
                <i class="fa-solid fa-envelope"></i>
                <a href="email_security.php">Change Email</a> 
            </div>
            <div class="nava">
                <i class="fa-solid fa-lock"></i>
                <a href="password_security.php">Change Password</a> 
            </div>
            <div class="nava active">
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
        <!-- Tab Section -->
        <div class="tabs">
            <div class="tab active" data-target="#all">All</div>
            <div class="tab" data-target="#completed">Completed</div>
            <div class="tab" data-target="#pending">Pending</div>
            <div class="tab" data-target="#cancelled">Cancelled</div>
        </div>

        <!-- Tab Content Section -->
        <div class="tab-content active" id="all">
            <p>No Orders Yet</p>
        </div>
        <div class="tab-content" id="completed">
            <p>No Orders Yet</p>
        </div>
        <div class="tab-content" id="pending">
            <p>No Orders Yet</p>
        </div>
        <div class="tab-content" id="cancelled">
            <p>No Orders Yet</p>
        </div>
    </div>
</div>


<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script>
function redirectToProfile() {
    window.location.href = "customer_profile.php";
}
// jQuery for Tabs
$(document).ready(function () {
    $('.tab').click(function () {
        $('.tab').removeClass('active');
        $(this).addClass('active');

        $('.tab-content').removeClass('active');
        $($(this).data('target')).addClass('active');
    });
});
</script>


</body>
</html>
