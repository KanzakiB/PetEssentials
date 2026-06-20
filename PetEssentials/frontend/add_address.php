<?php
session_start();

include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$registeredID = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $phone_no = $_POST['phone_no'];
    $House_no = $_POST['House_no'];
    $street_name = $_POST['street_name'];
    $Barangay = $_POST['Barangay'];
    $City = $_POST['City'];
    $Postal_code = $_POST['Postal_code'];

    $query = "INSERT INTO customer_address (registeredID, fullname, phone_no, House_no, street_name, Barangay, City, Postal_code)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ississsi", $registeredID, $fullname, $phone_no, $House_no, $street_name, $Barangay, $City, $Postal_code);

    if ($stmt->execute()) {
        echo "<script>
                sessionStorage.setItem('addressAdded', 'true');
                window.location.href = 'customer_addresses.php';
              </script>";
    } else {
        $_SESSION['error_message'] = "Failed to add address. Please try again.";
        header("Location: customer_addresses.php");
    }

    $stmt->close();
    $conn->close();
}
?>
