<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Happy Tails</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/landing_page.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/PetEssentials/frontend/css/footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body>

<header>
  <div class="logo">
    <img src="http://localhost/PetEssentials/frontend/images/logo.png" id="imglogo" alt="logo" onclick="window.location.href='landing_page.php';">
  </div>
  <nav>
    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#featured-products-section">Product</a></li>
      <li><a href="#product-cateogies-section">Categories</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </nav>
  <div class="header-btn-container">
    <button id="btn-login"  onclick="window.location.href='login.php';">Login</button>
    <button id="btn-signup" onclick="window.location.href='signup.php';">Sign up</button>
  </div>
</header>

<section class="hero" id="home">
    <div class="hero-container">
        <div class="hero-text">
            <h1>Love Your Pets</h1>
            <p>Explore our wide range of products for happy, healthy pets</p>
            <div class="btn-shop-container">
              <button class="btn-shop" onclick="scrollToSection('featured-products-section')">Shop Now</button>
            </div>
        </div>
        <div class="herobg-container">
            <img src="http://localhost/PetEssentials/frontend/images/heropet.png" id="imghero" alt="dogcat">
        </div>
    </div>
</section>

<section class="featured-section" id="featured-products-section">
  <h1>Featured Products</h1>
  <p>Choose what you think is best!</p>
  <div class="features">
    <div class="features-container">
      <img class="productimg" src="http://localhost/PetEssentials/frontend/images/drydogfood1.png" alt="dry dog food">
      <h3>Dry Dog Food</h3>
      <p>Dry dog food provides balanced nutrition for energy, muscle health, and a shiny coat.</p>
    </div>
    <div class="features-container">
      <img class="productimg" src="http://localhost/PetEssentials/frontend/images/drydogfood2.png" alt="dry dog food">
      <h3>Dry Dog Food</h3>
      <p>Dry dog food provides balanced nutrition for energy, muscle health, and a shiny coat.</p>
    </div>
    <div class="features-container">
      <img class="productimg" src="http://localhost/PetEssentials/frontend/images/drydogfood3.png" alt="dry dog food">
      <h3>Dry Dog Food</h3>
      <p>Dry dog food provides balanced nutrition for energy, muscle health, and a shiny coat.</p>
    </div>
    <div class="features-container">
      <img class="productimg" src="http://localhost/PetEssentials/frontend/images/drycatfood1.png" alt="cat dog food">
      <h3>Dry Cat Food</h3>
      <p>Dry cat food offers protein-rich nutrition for energy, digestion, and a healthy coat.</p>
    </div>
    <div class="features-container">
      <img class="productimg" src="http://localhost/PetEssentials/frontend/images/drycatfood2.png" alt="cat dog food">
      <h3>Dry Cat Food</h3>
      <p>Dry cat food offers protein-rich nutrition for energy, digestion, and a healthy coat.</p>
    </div>
  </div>

  <div class="link-container">
    <a id="products" href="browse_products.php">Go to Shop <i class="fa-solid fa-arrow-right"></i></a>
  </div>

</section>

<section class="categories-section" id="product-cateogies-section">
  <div>
    <h2>Explore Categories</h2>
    <p>Discover products your pets will love, from nutritious food to playful toys and grooming essentials.</p>
    <Br>
  </div>
  <div class="category-container">
    <div class="first-category-container">
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/petfood.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Nutrition</h1>
            <p>Nutritious, high-quality food crafted to meet the specific health and dietary needs of pets at every stage of life.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/petgroom.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Hygiene</h1>
            <p>Easy-to-use grooming essentials that ensure pets stay clean, healthy, and looking their best.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/petbed.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Bedding</h1>
            <p>Comfortable, supportive, and cozy bedding options that provide pets with a perfect spot to relax and rest.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/petbowl.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Feeding Accessories</h1>
            <p>Stylish and practical feeding solutions designed to make mealtime easy, tidy, and enjoyable for pets and owners.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/pettoys.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Toys</h1>
            <p>Fun, stimulating toys that encourage physical activity, mental engagement, and happy playtime for pets.</p>
          </div>
        </div>
    </div>
    <div class="category-line"></div>
    <div class="second-category-container">
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/pettrain.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Training Essentials</h1>
            <p>Helpful tools and accessories to support pet training, manage behavior, and build better communication.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/pettravel.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Travel Accessories</h1>
            <p>Convenient travel gear to ensure pets are safe, secure, and comfortable during trips and outings.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/petclean.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Cleaning Supplies</h1>
            <p>Efficient cleaning products to keep homes fresh, tidy, and free of pet-related messes and odors.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/petwardrobe.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Wardrobe</h1>
            <p>Stylish and functional clothing that keeps pets comfortable, protected, and ready for any occasion.</p>
          </div>
        </div>
        <div class="row-category">
          <div>
            <img src="http://localhost/PetEssentials/frontend/images/pethealth.png" class="icon-category" alt="dogcat">
          </div>
          <div class="cat-info">
            <h1>Health & Wellness</h1>
            <p>Carefully selected products to support the overall health, happiness, and well-being of pets.</p>
          </div>
        </div>
    </div>
  </div>
</section>

<section class="contact-section" id="contact">
  <h2>Contact Us</h2>
  <p>We’d love to hear from you!</p>
    
  <div class="contact-container">
    <div class="form-container">
    <form class="contact-form" id="contact-form" method="post" action="send_email.php">
        <input id="contact-name" name="name" type="text" placeholder="Your Name" required><br>
        <input id="contact-email" name="email" type="email" placeholder="Your Email" required><br>
        <textarea id="contact-message" name="message" placeholder="Your Message" required></textarea><br>
        <button class="btn-send" type="submit">Send</button>
    </form>

    </div>
    <div class="dog-container">
      <img src="http://localhost/PetEssentials/frontend/images/contactdog.png" id="contactdog" alt="dog">
    </div>
  </div>
  
</section>

<?php
  include('C:\XAMPP\htdocs\PetEssentials\frontend\footer.php');
?>



<script src="http://localhost/PetEssentials/frontend/js/landing_page.js" defer></script>

</body>
</html>
