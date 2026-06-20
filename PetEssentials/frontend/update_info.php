<?php
session_start();
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? null;
    $fname = $_POST['fname'] ?? null;
    $lname = $_POST['lname'] ?? null;
    $phone_no = $_POST['phone_no'] ?? null;
    $registeredID = $_SESSION['id'];

    $query = "UPDATE registered_users SET username = ?, fname = ?, lname = ?, phone_no = ? WHERE registeredID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $username, $fname, $lname, $phone_no, $registeredID);

    if ($stmt->execute()) {
        header("Location: customer_profile.php?info_update_status=success");
    } else {
        header("Location: customer_profile.php?info_update_status=error");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
