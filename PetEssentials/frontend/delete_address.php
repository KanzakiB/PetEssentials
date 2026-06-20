<?php
if (isset($_POST['addressID'])) {
    include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

    $addressID = $_POST['addressID'];
    
    $query = "DELETE FROM customer_address WHERE addressID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $addressID);
    
    if ($stmt->execute()) {
        echo "Address deleted successfully!";
    } else {
        echo "Error deleting address.";
    }

    $stmt->close();
    $conn->close();
}
?>
