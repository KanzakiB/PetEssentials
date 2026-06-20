<?php
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Happy Tails</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/browse_products.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<header>
  <div class="logo">
      <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" onclick="window.location.href='landing_page.php';">
  </div>
  <div class="search-container">
      <!-- Search Input -->
      <input type="text" id="search-query" placeholder="Search by product name..." onkeyup="searchProducts()">

      
      <!-- Category Dropdown -->
    <!--  <select id="category-dropdown">
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
      </select> -->

      <!-- Search Icon -->
      <button type="button" class="search-icon-btn">
          <i class="fa fa-search"></i>
      </button>
  </div>
  
  <div class="header-btn-container">
      <button id="btn-login" onclick="window.location.href='login.php';">Login</button>
      <button id="btn-signup" onclick="window.location.href='signup.php';">Sign up</button>
  </div>
</header>

<div class="main-container">
  <div class="slider-container">
      <div class="slider">
          <div class="slide">
              <img src="http://localhost/PetEssentials/frontend/images/catspaws.png" alt="Cat Banner">
          </div>
          <div class="slide">
              <img src="http://localhost/PetEssentials/frontend/images/dogspaws.png" alt="Dog Banner">
          </div>
          <div class="slide">
              <img src="http://localhost/PetEssentials/frontend/images/paws.png" alt="Paws Banner">
          </div>
      </div>
      <!-- Navigation Buttons -->
      <button class="slider-btn left-btn" onclick="moveSlide(-1)">&#10094;</button>
      <button class="slider-btn right-btn" onclick="moveSlide(1)">&#10095;</button>
  </div>
  <div class="Navigation-parts">
    <p><a href="landing_page.php">Home</a><i class="fa-solid fa-chevron-right"></i><a href="#">Pet Products</a></p>
  </div>
  <div class="products">
      <div class="sort-container">
            <h3>Selection of Pet Products</h3>
      </div>
      
      <!-- Product Container -->
      <div class="product-container">
        <?php
        // Query to fetch only the first 35 products along with their first image
        $query = "
        SELECT p.itemID, p.product_name, p.product_price, pi.product_picture
        FROM product_item p
        LEFT JOIN product_images pi ON p.itemID = pi.itemID
        WHERE pi.imageID = (
            SELECT MIN(imageID) FROM product_images WHERE itemID = p.itemID
        )
        ORDER BY p.date_added DESC
        LIMIT 35
        ";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $counter = 0; // Track the number of items in the row

            while ($row = mysqli_fetch_assoc($result)) {
                // Start a new row after 7 items
                if ($counter % 7 === 0) {
                    if ($counter > 0) echo '</div>'; // Close the previous row
                    echo '<div class="row-item">'; // Start a new row
                }

                // Fetch product data
                $itemID = $row['itemID'];
                $productName = $row['product_name'];
                $productPrice = number_format($row['product_price'], 2);

                // Handle the product image (if any)
                $productImage = $row['product_picture'] ? base64_encode($row['product_picture']) : 'default-image.jpg'; // Handle missing image

                // Display the product
                echo '
                    <div class="product-item" onclick="window.location.href=\'product_details_notlogin.php?itemID=' . $itemID . '\'">
                        <input type="hidden" value="' . $itemID . '" />
                        <div class="product-image-container">
                            <img class="productimg" src="data:image/jpeg;base64,' . $productImage . '" alt="' . $productName . '" />
                        </div>
                        <div class="prduct-info-container">
                            <p class="product-name">' . htmlspecialchars(mb_strimwidth($productName, 0, 25, '...')) . '</p>
                            <p class="product-price">₱' . $productPrice . '</p>
                        </div>
                    </div>
                ';


                $counter++;
            }

            // Close the last row
            if ($counter > 0) echo '</div>';
        } else {
            echo '<p>No products found.</p>';
        }
        ?>
      </div>
      <div class="button-container">
        <button id="toLoginPage" onclick="window.location.href='login.php';">Login to see more</button>
      </div>
  </div>
</div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script src="http://localhost/PetEssentials/frontend/js/browse.js" defer></script>

</body>
</html>
