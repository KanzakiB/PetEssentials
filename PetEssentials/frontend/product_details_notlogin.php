<?php
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

if (isset($_GET['itemID'])) {
    $itemID = intval($_GET['itemID']);
    
    // Fetch product details
    $productQuery = "
    SELECT 
        p.product_name, 
        p.product_brand, 
        p.product_price, 
        p.product_quantity, 
        p.product_description, 
        c.ptypeID 
    FROM product_item p
    LEFT JOIN product_category c ON p.categoryID = c.productID
    WHERE p.itemID = $itemID";
    
    $productResult = mysqli_query($conn, $productQuery);
    $product = mysqli_fetch_assoc($productResult);

    if ($product) {
        $productName = $product['product_name'];
        $productBrand = $product['product_brand'];
        $productPrice = number_format($product['product_price'], 2);
        $productQuantity = $product['product_quantity'];
        $productDescription = $product['product_description'];
        $productType = $product['ptypeID'];
    }

    // Fetch product variants
    $variantQuery = "
    SELECT variant_name, variant_type, variant_quantity
    FROM product_variant
    WHERE itemID = $itemID";
    $variantResult = mysqli_query($conn, $variantQuery);
    $variants = mysqli_fetch_all($variantResult, MYSQLI_ASSOC);

    // Check if product has variants
    $hasVariants = !empty($variants);

    // Fetch similar products (limit to 7)
    $similarProductsQuery = "
    SELECT p.itemID, p.product_name, p.product_brand, p.product_price, pi.product_picture
    FROM product_item p
    LEFT JOIN product_category c ON p.categoryID = c.productID
    LEFT JOIN product_images pi ON p.itemID = pi.itemID
    WHERE c.ptypeID = $productType AND p.itemID != $itemID
    GROUP BY p.itemID
    LIMIT 7";
    $similarResult = mysqli_query($conn, $similarProductsQuery);
    $similarProducts = mysqli_fetch_all($similarResult, MYSQLI_ASSOC);

    // Fetch product images
    $imageQuery = "SELECT product_picture FROM product_images WHERE itemID = $itemID";
    $imageResult = mysqli_query($conn, $imageQuery);
    $images = mysqli_fetch_all($imageResult, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Happy Tails</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/notlogin_details.css">
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
       <input type="text" id="search-query" placeholder="Search..." onkeyup="searchProducts()">
      
      <!-- Search Results -->
      <div id="search-results" class="search-results"></div>

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

<div class="Navigation-parts">
    <p><a href="landing_page.php">Home</a><i class="fa-solid fa-chevron-right"></i><a href="browse_products.php">Pet Products</a><i class="fa-solid fa-chevron-right"></i><a href="#"><?php echo htmlspecialchars($productName); ?></a></p>
</div>

    <div class="product-details-container">
    <div class="detail-image-container">
        <!-- Main Image -->
        <div class="main-picture">
            <?php if (!empty($images)) { ?>
                <img id="main-image" src="data:image/jpeg;base64,<?php echo base64_encode($images[0]['product_picture']); ?>" alt="main picture of product">
            <?php } else { ?>
                <img id="main-image" src="http://localhost/PetEssentials/frontend/images/default.png" alt="default product image">
            <?php } ?>
        </div>

        <!-- Thumbnail Selection -->
        <div class="selection-picture">
            <button class="arrow left-arrow" onclick="scrollThumbnailsLeft()">
                <i class="fa fa-chevron-left"></i>
            </button>
            <div class="thumbnails-container">
                <?php if (!empty($images)) { ?>
                    <?php foreach ($images as $index => $image) { ?>
                        <img class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                            src="data:image/jpeg;base64,<?php echo base64_encode($image['product_picture']); ?>" 
                            onclick="updateMainImage(this)" 
                            alt="Thumbnail <?php echo $index + 1; ?>">
                    <?php } ?>
                <?php } else { ?>
                    <p>No images available.</p>
                <?php } ?>
            </div>
            <button class="arrow right-arrow" onclick="scrollThumbnailsRight()">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>
    </div>

        <div class="product-action-container">
            <div class="main-action-container">
                <div class="info-container">
                    <p id="product-name"><?php echo htmlspecialchars($productName); ?></p>
                </div>
                <hr class="line-break">
                <div class="other-info">
                    <div class="info-row">
                        <p class="product-brandName">Brand:</p> 
                        <p id="product-brand" class="product-brand"><?php echo htmlspecialchars($productBrand); ?></p>
                    </div>
                    <div class="info-row">
                        <p class="product-priceName">Price:</p> 
                        <p class="product-price">₱<?php echo $productPrice; ?></p>
                    </div>
                    <div class="info-row"> <!--If product is in nutrition this will be Flavor if not then Colors if dont have variant then wala na to-->
                        <?php if ($hasVariants) { ?>
                            <?php if ($productType == 1) { ?>
                                <p class="product-flavorcategory">Flavor:</p>
                            <?php } else { ?>
                                <p class="product-flavorcategory">Color:</p>
                            <?php } ?>
                            <div class="product-flavor-container">
                                <?php foreach ($variants as $variant) { ?>
                                    <button class="type-flavor" onclick="updateQuantity(<?php echo $variant['variant_quantity']; ?>)">
                                        <?php echo htmlspecialchars($variant['variant_name']); ?>
                                    </button>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="info-row"> 
                        <p class="product-quantityName">Quantity:</p> 
                        <p id="productquantity" class="product-quantity" style="<?php echo $hasVariants ? 'margin-bottom: 0;' : 'margin-bottom: 120px;'; ?>"> 
                            <?php 
                            echo $hasVariants ? $variants[0]['variant_quantity'] : $productQuantity; 
                            ?>
                        </p>
                        <div class="choose-quantity">
                            <button id="decrement-btn" class="quantity-btn" onclick="changeQuantity(-1)"> <i class="fa-solid fa-minus"></i> </button>
                            <p id="userproduct-quantity" class="chosen-quantity">0</p>
                            <button id="increment-btn" class="quantity-btn" onclick="changeQuantity(1)"> <i class="fa-solid fa-plus"></i> </button>
                        </div>
                    </div>
                </div>
                <hr class="line-break two">
                <div class="action-btn-container">  
                    <div class="cart-container">
                        <button id="AddCartbtn" class="btn-cart"><i class="fa-solid fa-cart-plus"></i>Add to Cart</button>
                    </div>
                    <div class="buy-container">
                        <button id="Buybtn" class="btn-buy">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-description">
        <div class="title-container">
            <h3>Product Description</h3>
        </div>
        <hr class="line-break-three">
        <div class="description-container">
            <?php
            // Break description into lines
            $lines = explode("\n", htmlspecialchars($productDescription));
            $inList = false;

            foreach ($lines as $line) {
                $trimmedLine = trim($line);

                if (strpos($trimmedLine, '-') === 0) { 
                    if (!$inList) {
                        echo '<ul>'; 
                        $inList = true;
                    }
                    echo '<li>' . substr($trimmedLine, 1) . '</li>';
                } else {
                    if ($inList) {
                        echo '</ul>'; 
                        $inList = false;
                    }
                    echo '<p>' . $line . '</p>';
                }
            }

            if ($inList) {
                echo '</ul>'; 
            }
            ?>
        </div>
    </div>
    <div class="products">
        <div class="p-container">
            <h3>Similar Products</h3>
        </div>
      
        <!-- Product Container -->
        <div class="product-container">
            <div class="row-item">
            <?php if (!empty($similarProducts)) { ?>
                <?php foreach ($similarProducts as $similar) { ?>
                    <div class="product-item" onclick="window.location.href='product_details_notlogin.php?itemID=<?php echo $similar['itemID']; ?>'">
                        <div class="product-image-container">
                            <!-- Display only one image (first image) for each similar product -->
                            <img class="productimg" 
                                 src="data:image/jpeg;base64,<?php echo base64_encode($similar['product_picture']); ?>" 
                                 alt="<?php echo htmlspecialchars($similar['product_name']); ?>" />
                        </div>
                        <div class="product-info-container">
                            <p class="product-name"><?php echo htmlspecialchars($similar['product_name']); ?></p>
                            <p class="product-price">₱<?php echo number_format($similar['product_price'], 2); ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p style="margin-left: 15px;">No similar products found.</p>
            <?php } ?>
            </div>
            <div class="button-container">
                <button id="toLoginPage" onclick="window.location.href='login.php';">Login Now</button>
            </div>
        </div>
    </div>
</div>

<!--Modal-->
<div id="overlay" class="overlay"></div>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <p>You need to log in first to perform this action</p>
    </div>
    <div class="modal-actions">
        <button id="modalOkButton">OK</button>
    </div>
</div>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>

<script src="http://localhost/PetEssentials/frontend/js/notlogin_details.js"></script>
<script>
function updateQuantity(quantity) {
    document.getElementById('productquantity').textContent = quantity;
}

<?php if ($hasVariants && !empty($variants)) { ?>
    updateQuantity(<?php echo $variants[0]['variant_quantity']; ?>);
<?php } else { ?>
    updateQuantity(<?php echo $productQuantity; ?>);
<?php } ?>
</script>

</body>
</html>
