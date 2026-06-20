<?php
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']); 

    $searchQuery = "
    SELECT p.itemID, p.product_name, p.product_price, pi.product_picture
    FROM product_item p
    LEFT JOIN product_images pi ON p.itemID = pi.itemID
    WHERE p.product_name LIKE '%$query%'
    AND pi.imageID = (
        SELECT MIN(imageID) FROM product_images WHERE itemID = p.itemID
    ) 
    ORDER BY p.date_added DESC
    LIMIT 35
    ";

    $result = mysqli_query($conn, $searchQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $counter = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($counter % 7 === 0) {
                if ($counter > 0) echo '</div>';
                echo '<div class="row-item">';
            }

            $itemID = $row['itemID'];
            $productName = $row['product_name'];
            $productPrice = number_format($row['product_price'], 2);
            $productImage = $row['product_picture'] ? base64_encode($row['product_picture']) : 'default-image.jpg';

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

        if ($counter > 0) echo '</div>';
    } else {
        echo '<p>No products found matching your search.</p>';
    }
}
?>
