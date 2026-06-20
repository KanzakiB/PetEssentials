<?php
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']); 

    $searchQuery = "
    SELECT p.itemID, p.product_name
    FROM product_item p
    WHERE p.product_name LIKE '%$query%' 
    LIMIT 10";

    $result = mysqli_query($conn, $searchQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $itemID = $row['itemID'];
            $productName = htmlspecialchars($row['product_name']);

            echo '
                <div class="search-item" onclick="window.location.href=\'product_details_notlogin.php?itemID=' . $itemID . '\'">
                    <p class="product-name">' . $productName . '</p>
                </div>
            ';
            $count++;
        }

        if ($count >= 8) {
            echo '<div class="more-results">End of results...</div>';
        }
    } else {
        echo '<p>No products found matching your search.</p>';
    }
}
?>
