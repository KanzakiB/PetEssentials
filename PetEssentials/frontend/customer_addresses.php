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

$queryAddresses = "SELECT * FROM customer_address WHERE registeredID = ?";
$stmtAddresses = $conn->prepare($queryAddresses);
$stmtAddresses->bind_param("i", $registeredID);
$stmtAddresses->execute();
$resultAddresses = $stmtAddresses->get_result();
$addresses = $resultAddresses->fetch_all(MYSQLI_ASSOC);

$stmtAddresses->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/customeradd.css">
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
            <div class="nava">
                <i class="fa-solid fa-user"></i>
                <a href="customer_profile.php">Profile</a> 
            </div>
            <div class="nava active">
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
            <h3>My Addresses</h3>
            <button id="newAddress"><i class="fa-solid fa-plus"></i>Add New Address</button>
        </div>
        <hr class="linebr">
        <div class="addresspart scrollableAdd">
            <?php if (!empty($addresses)) : ?>
                <?php foreach ($addresses as $address) : ?>
                    <div class="address-container">
                        <div class="address-info">
                            <h4>Address</h4>
                            <div class="first-info">
                                <p><?php echo htmlspecialchars($address['fullname']); ?></p>
                                <div class="linebetween"></div>
                                <p><?php echo htmlspecialchars($address['phone_no']); ?></p>
                            </div>
                            <div class="sec-info">
                                <p>
                                    <?php 
                                        echo htmlspecialchars($address['House_no']) . " " . 
                                            htmlspecialchars($address['street_name']) . ", " .
                                            htmlspecialchars($address['Barangay']) . ", " .
                                            htmlspecialchars($address['City']) . ", " .
                                            htmlspecialchars($address['Postal_code']);
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="address-btn-container">
                            <button id="editAddress" onclick='openEditAddressModal({
                                id: "<?php echo $address["addressID"]; ?>",
                                fullname: "<?php echo htmlspecialchars($address["fullname"]); ?>",
                                phone_no: "<?php echo htmlspecialchars($address["phone_no"]); ?>",
                                House_no: "<?php echo htmlspecialchars($address["House_no"]); ?>",
                                street_name: "<?php echo htmlspecialchars($address["street_name"]); ?>",
                                Barangay: "<?php echo htmlspecialchars($address["Barangay"]); ?>",
                                City: "<?php echo htmlspecialchars($address["City"]); ?>",
                                Postal_code: "<?php echo htmlspecialchars($address["Postal_code"]); ?>"
                            })'>Edit</button>
                            <button id="deleteAddress" onclick="openDeleteConfirmationModal('<?php echo $address['addressID']; ?>')">Delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No addresses found. Add a new address!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!--Add new address modal-->
<div id="addAddressModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddAddressModal()">&times;</span>
        <h3 class="TitleAddress">New Address</h3>
        <form id="addAddressForm" action="add_address.php" method="POST">
            <div class="rowinput">
                <input type="text" id="fullname" name="fullname" placeholder="Fullname" required>
                <input type="number" id="phone_no" name="phone_no" placeholder="Phone Number" maxlength="11" required>
            </div>
            <div class="rowinput">
                <input type="number" id="House_no" name="House_no" placeholder="House Number" required>                
                <input type="text" id="street_name" name="street_name" placeholder="Street Name" required>                
            </div>
            <div class="rowinput">
                <input type="text" id="Barangay" name="Barangay" placeholder="Barangay" required>
                <input type="text" id="City" name="City" placeholder="City" required>
            </div>
            <div class="rowinput">
                <input type="number" id="Postal_code" name="Postal_code" placeholder="Postal Code" required>
            </div>
            <div class="btnadd-container">
                <button type="submit" id="saveAddressBtn">Save Address</button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="success-modal-content">
        <p>Address added successfully!</p>
        <button id="successAddok" onclick="closeSuccessModal()">OK</button>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditAddressModal()">&times;</span>
        <h3 class="TitleAddress">Edit Address</h3>
        <form id="editAddressForm" action="edit_address.php" method="POST">
            <input type="hidden" id="editAddressId" name="addressID">
            <div class="rowinput">
                <input type="text" id="edit_fullname" name="fullname" placeholder="Fullname" required>
                <input type="number" id="edit_phone_no" name="phone_no" placeholder="Phone Number" maxlength="11" required>
            </div>
            <div class="rowinput">
                <input type="number" id="edit_House_no" name="House_no" placeholder="House Number" required>
                <input type="text" id="edit_street_name" name="street_name" placeholder="Street Name" required>
            </div>
            <div class="rowinput">
                <input type="text" id="edit_Barangay" name="Barangay" placeholder="Barangay" required>
                <input type="text" id="edit_City" name="City" placeholder="City" required>
            </div>
            <div class="rowinput">
                <input type="number" id="edit_Postal_code" name="Postal_code" placeholder="Postal Code" required>
            </div>
            <div class="btnadd-container">
                <button type="submit" id="saveEditAddressBtn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successupdateModal" class="modal">
    <div class="update-modal-content">
        <p>Address updated successfully!</p>
        <button id="successUpdateok" onclick="closeUpdateSuccessModal()">OK</button>
    </div>
</div>

<?php
if (isset($_SESSION['message'])) {
    echo '<script>
        window.onload = function() {
            document.getElementById("successupdateModal").style.display = "block"; 
        }
    </script>';
    unset($_SESSION['message']);
}
?>


<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="modal">
    <div class="confirmation-modal-content">
        <p>Are you sure you want to delete this address?</p>
        <div class="btnadd-container">
            <button id="confirmDeleteBtn">Yes</button>
            <button id="cancelDeleteBtn" onclick="closeDeleteModal()">No</button>
        </div>
    </div>
</div>


<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script src="http://localhost/PetEssentials/frontend/js/customeradd.js" defer></script>

</body>
</html>
