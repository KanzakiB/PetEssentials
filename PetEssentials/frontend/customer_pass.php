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

if (isset($_POST['check_current_password'])) {
    $currentPass = $_POST['check_current_password'];
    $currentPassHash = $user['passw'];  

    if (password_verify($currentPass, $currentPassHash)) {
        echo 'valid';
    } else {
        echo 'invalid';
    }
    exit();  
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPass = $_POST['passw'];
    $newPass = $_POST['new_passw'];
    $confirmPass = $_POST['confirm_passw'];

    $errors = [];

    if (!password_verify($currentPass, $user['passw'])) {
        $errors['current_pass'] = 'Current password is incorrect.';
    }

    if (strlen($newPass) < 8) {
        $errors['new_pass'] = 'Password must be at least 8 characters long.';
    }
    if (!preg_match('/[A-Z]/', $newPass)) {
        $errors['new_pass'] .= ' Password must contain at least one uppercase letter.';
    }
    if (!preg_match('/[0-9]/', $newPass)) {
        $errors['new_pass'] .= ' Password must contain at least one number.';
    }
    if (!preg_match('/[\W_]/', $newPass)) {
        $errors['new_pass'] .= ' Password must contain at least one special character.';
    }

    if ($newPass !== $confirmPass) {
        $errors['confirm_pass'] = 'New password and confirmation do not match.';
    }

    if (!empty($errors)) {
        echo "<script>
                document.getElementById('error-message1').textContent = '" . ($errors['current_pass'] ?? '') . "'; 
                document.getElementById('error-message2').textContent = '" . ($errors['new_pass'] ?? '') . "'; 
                document.getElementById('error-message3').textContent = '" . ($errors['confirm_pass'] ?? '') . "'; 
              </script>";
        exit();
    }

    $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE registered_users SET passw = ? WHERE registeredID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $hashedNewPass, $registeredID);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        $_SESSION['password_updated'] = true;  
    } else {
        echo "<script>alert('Failed to update password. Please try again later.'); window.history.back();</script>";
    }
    

    $updateStmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/customerpass.css">
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
            <div class="nava">
                <i class="fa-solid fa-envelope"></i>
                <a href="email_security.php">Change Email</a> 
            </div>
            <div class="nava active">
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
            <h3>My Password</h3>
            <p>Manage your password for security</p>
        </div>
        <hr class="linebr">
        <div class="pass-change-container">
            <form method="post" id="newPasswordContainer">
                <div class="first-container">
                <label for="current-pass">Current Password</label>
                    <div class="current-container">
                        <input type="password" name="passw" id="currentPass"  required>
                        <span class="current-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>
                    <p class="error-message1" id="error-message1"></p>  <!-- This show an error message if current password is wrong  -->


                <label for="new-pass">New Password</label>
                    <div class="new-container">
                        <input type="password" name="new_passw" id="newPass"  onfocus="showRequirements()" onkeyup="validatePassword()">
                        <span class="new-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>
                    <p class="error-message2" id="error-message2"></p>  <!--This show an error message if the requirements are not met  -->


                <label for="confirm-pass">Confirm New Password</label>
                    <div class="confirm-container">
                        <input type="password" name="confirm_passw" id="confirmPass" >
                        <span class="cfw-icon" style="display: none;"><i class="fas fa-eye"></i></span>
                    </div>
                    <p class="error-message3" id="error-message3"></p>  <!--This show an error message if new password and confirm password do not match -->

                    
                <button type="submit" id="saveNewPass">Save</button>
                </div>
                <div class="sec-container">
                    <!-- Password requirements -->
                    <ul id="requirements" class="requirements" style="display: none;">
                        <li id="length"> Must be at least 8 characters long</li>
                        <li id="capital"> Must have at least one capital letter</li>
                        <li id="number"> Must have at least one number</li>
                        <li id="special"> Must have at least one special character</li>
                    </ul>
                </div>
                
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Password Updated successfully!</p>
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


document.addEventListener("DOMContentLoaded", function () {
    const currentPasswordField = document.getElementById("currentPass");
    const newPasswordField = document.getElementById("newPass");
    const confirmPasswordField = document.getElementById("confirmPass");

    const currentPasswordToggleIconContainer = document.querySelector(".current-container .current-icon");
    const newPasswordToggleIconContainer = document.querySelector(".new-container .new-icon");
    const confirmPasswordToggleIconContainer = document.querySelector(".confirm-container .cfw-icon");

    currentPasswordField.addEventListener("input", function () {
        toggleIcon(currentPasswordField, currentPasswordToggleIconContainer);
        validateCurrentPassword();
    });

    newPasswordField.addEventListener("input", function () {
        toggleIcon(newPasswordField, newPasswordToggleIconContainer);
        validatePassword();
        validateConfirmPassword(); 
    });

    confirmPasswordField.addEventListener("input", function () {
        toggleIcon(confirmPasswordField, confirmPasswordToggleIconContainer);
        validateConfirmPassword(); 
    });

    function toggleIcon(passwordField, iconContainer) {
        if (passwordField.value.length > 0) {
            iconContainer.style.display = "inline";
        } else {
            iconContainer.style.display = "none";
        }
    }

    function validateCurrentPassword() {
        const currentPassword = currentPasswordField.value;
        const errorMessage1 = document.getElementById("error-message1");
        errorMessage1.textContent = ''; 

        if (currentPassword.length > 0) {
            $.ajax({
                url: '',  
                type: 'POST',
                data: { check_current_password: currentPassword },
                success: function(response) {
                    if (response === 'invalid') {
                        errorMessage1.textContent = "Current password is incorrect.";
                    }
                }
            });
        }
    }

    function validatePassword() {
        const password = newPasswordField.value;
        const errorMessage2 = document.getElementById("error-message2");
        errorMessage2.textContent = '';

        if (password.length < 8 || !/[A-Z]/.test(password) || !/\d/.test(password) || !/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            errorMessage2.textContent = "Password requirements not met";
        }
    }

    function validateConfirmPassword() {
        const newPassword = newPasswordField.value;
        const confirmPassword = confirmPasswordField.value;
        const errorMessage3 = document.getElementById("error-message3");
        errorMessage3.textContent = ''; 

        if (confirmPassword !== newPassword) {
            errorMessage3.textContent = "Passwords do not match.";
        }
    }
});

document.getElementById("newPass").addEventListener("focus", function () {
    document.getElementById("requirements").style.display = "block";  // Show requirements
});


document.addEventListener("DOMContentLoaded", function () {
    const currentPasswordField = document.getElementById("currentPass");
    const newPasswordField = document.getElementById("newPass");
    const confirmPasswordField = document.getElementById("confirmPass");

    const currentPasswordToggleIconContainer = document.querySelector(".current-container .current-icon");
    const newPasswordToggleIconContainer = document.querySelector(".new-container .new-icon");
    const confirmPasswordToggleIconContainer = document.querySelector(".confirm-container .cfw-icon");

    currentPasswordField.addEventListener("input", function () {
        if (currentPasswordField.value.length > 0) {
            currentPasswordToggleIconContainer.style.display = "inline";
        } else {
            currentPasswordToggleIconContainer.style.display = "none";
        }
    });

    newPasswordField.addEventListener("input", function () {
        if (newPasswordField.value.length > 0) {
            newPasswordToggleIconContainer.style.display = "inline";
        } else {
            newPasswordToggleIconContainer.style.display = "none";
        }
    });

    confirmPasswordField.addEventListener("input", function () {
        if (confirmPasswordField.value.length > 0) {
            confirmPasswordToggleIconContainer.style.display = "inline";
        } else {
            confirmPasswordToggleIconContainer.style.display = "none";
        }
    });

    // Password field 
    currentPasswordToggleIconContainer.addEventListener("click", function () {
        const icon = currentPasswordToggleIconContainer.querySelector("i");
        if (currentPasswordField.type === "password") {
            currentPasswordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            currentPasswordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });

    // New Password field 
    newPasswordToggleIconContainer.addEventListener("click", function () {
        const icon = newPasswordToggleIconContainer.querySelector("i");
        if (newPasswordField.type === "password") {
            newPasswordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            newPasswordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });

    // Confirm New Password 
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

document.getElementById("newPass").addEventListener("focus", function () {
    document.getElementById("requirements").style.display = "block";  
});

function validatePassword() {
    const password = document.getElementById("newPass").value;
    const requirements = document.querySelectorAll("#requirements li");

    // Length requirement
    if (password.length >= 8) {
        document.getElementById("length").style.color = "green";
    } else {
        document.getElementById("length").style.color = "red";
    }

    // Capital letter requirement
    if (/[A-Z]/.test(password)) {
        document.getElementById("capital").style.color = "green";
    } else {
        document.getElementById("capital").style.color = "red";
    }

    // Number requirement
    if (/\d/.test(password)) {
        document.getElementById("number").style.color = "green";
    } else {
        document.getElementById("number").style.color = "red";
    }

    // Special character requirement
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        document.getElementById("special").style.color = "green";
    } else {
        document.getElementById("special").style.color = "red";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    <?php if (isset($_SESSION['password_updated']) && $_SESSION['password_updated'] === true) : ?>
        const modal = document.getElementById('success-modal');
        modal.style.display = 'block';  

        const okButton = document.getElementById('modal-ok-button');
        okButton.addEventListener('click', function () {
            window.location.href = 'customer_profile.php';  
        });

        <?php unset($_SESSION['password_updated']); ?>
    <?php endif; ?>
});
</script>


</body>
</html>
