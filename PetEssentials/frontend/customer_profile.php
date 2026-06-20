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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/customerp.css">
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
            <div class="nava active">
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
            <h3>My Profile</h3>
            <p>Manage and protect  your account</p>
        </div>
        <hr class="linebr">
        <div class="profilephoto-display">
            <div class="firstdiv">
                <img id="profilephoto" src="<?php echo $profilePic; ?>" alt="profile-picture">
                <div class="customer-info-container">
                    <p><?php echo htmlspecialchars($user['username']); ?></p> 
                    <p><?php echo htmlspecialchars($user['fname']); ?> <?php echo htmlspecialchars($user['lname']); ?></p>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <div class="button-upload-container" id="uploadButtonContainer">
                <form action="upload_profile_pic.php" method="post" enctype="multipart/form-data">
                        <div class="file-upload">
                            <input type="file" id="fileInput" name="profile_pic" accept="image/*">
                            <label for="fileInput">Choose File</label>                                
                        </div> 
                    <br><br>
                    <button class="upload-btn" type="submit">Update </button>
                </form>
            </div>
        </div>
        <div class="personal-info-container">
            <form id="customerinfo" method="post" action="update_info.php">
                <div class="rowinfo">
                    <div>
                        <label for="username">Username</label> 
                        <input type="text" name="username" id="customer-user" maxlength="7" value="<?php echo htmlspecialchars($user['username']); ?>"> 
                    </div>
                    <div>
                        <label for="firstname">Firstname</label> 
                        <input type="text" name="fname" id="customer-fname" value="<?php echo htmlspecialchars($user['fname']); ?>">  
                    </div>
                    <div>
                        <label for="lastname">Lastname</label>
                        <input type="text" name="lname" id="customer-lname" value="<?php echo htmlspecialchars($user['lname']); ?>"> 
                    </div>
                </div>
                <label for="email">Email</label> 
                <input type="email" name="email" id="customer-email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>  

                <label for="phonenum">Phone Number</label>  
                <input type="number" name="phone_no" id="customer-phone" value="<?php echo htmlspecialchars($user['phone_no']); ?>"> 
                <div class="btn-cont">
                    <button type="submit" id="saveinfo">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--modal for profile picture-->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <p id="modal-message"></p>
        <button onclick="closeModal()" id="okbtn">OK</button>
    </div>
</div>

<!-- Modal for Profile Information Update -->
<div id="updateModalinfo" class="modal">
    <div class="modal-content">
        <p id="modal-message-update"></p>
        <button onclick="closeinfoModal()" id="okbtn">OK</button>
    </div>
</div>


<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script>

function redirectToProfile() {
    window.location.href = "customer_profile.php";
}

function closeModal() {
    document.getElementById('updateModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const status = params.get('status');

    //profile pic update modal
    if (status) {
        const modal = document.getElementById('updateModal');
        const message = document.getElementById('modal-message');

        if (status === 'success') {
            message.textContent = 'Profile Picture Updated Successfully!';
        } else if (status === 'error') {
            message.textContent = 'Failed to update profile picture.';
        } else if (status === 'invalid_file') {
            message.textContent = 'Invalid file type. Please upload an image.';
        } else if (status === 'no_file') {
            message.textContent = 'No file selected. Please choose a file to upload.';
        } else if (status === 'file_too_big') {
            message.textContent = 'The file is too big. Please upload an image smaller than 2MB.';
        }

        modal.style.display = 'flex';

        window.history.replaceState({}, document.title, window.location.pathname);
    }

    const infoUpdateStatus = params.get('info_update_status');

    //information update modal
    if (infoUpdateStatus) {
        const infoUpdateModal = document.getElementById('updateModalinfo');
        const infoUpdateMessage = document.getElementById('modal-message-update');

        if (infoUpdateStatus === 'success') {
            infoUpdateMessage.textContent = 'Information Updated successfully!';
        } else if (infoUpdateStatus === 'error') {
            infoUpdateMessage.textContent = 'Failed to update your information.';
        } else if (infoUpdateStatus === 'invalid_input') {
            infoUpdateMessage.textContent = 'Please ensure all required fields are filled correctly.';
        }

        infoUpdateModal.style.display = 'flex';
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

function closeinfoModal() {
    document.getElementById('updateModalinfo').style.display = 'none';
}
</script>


</body>
</html>
