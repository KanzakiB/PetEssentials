<?php
session_start();
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo "error: not_logged_in";
    exit();
}

$newEmail = trim($_POST['email']);

if (empty($newEmail)) {
    echo "error: email_empty";
    exit();
}

if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    echo "error: invalid_email";
    exit();
}

$query = "SELECT * FROM registered_users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $newEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "error: email_exists";
    exit();
}

$stmt->close();

$registeredID = $_SESSION['id'];
$query = "UPDATE registered_users SET email = ? WHERE registeredID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $newEmail, $registeredID);

if ($stmt->execute()) {
    echo "success: email_updated";
} else {
    echo "error: database_error";
}

$stmt->close();
$conn->close();
?>
