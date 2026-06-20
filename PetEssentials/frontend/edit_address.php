<?php
session_start();
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $addressId = $_POST['addressID'];
    $fullname = $_POST['fullname'];
    $phone_no = $_POST['phone_no'];
    $House_no = $_POST['House_no'];
    $street_name = $_POST['street_name'];
    $Barangay = $_POST['Barangay'];
    $City = $_POST['City'];
    $Postal_code = $_POST['Postal_code'];

    $query = "UPDATE customer_address SET fullname = ?, phone_no = ?, House_no = ?, street_name = ?, Barangay = ?, City = ?, Postal_code = ? WHERE addressID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $fullname, $phone_no, $House_no, $street_name, $Barangay, $City, $Postal_code, $addressId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Address updated successfully!';
        header('Location: customer_addresses.php');
    } else {
        echo "Error updating address: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
