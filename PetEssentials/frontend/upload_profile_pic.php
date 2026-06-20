<?php
session_start();
include('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to upload a profile picture.";
    exit();
}

$registeredID = $_SESSION['id'];
$maxFileSize = 2 * 1024 * 1024; // 2MB

if (isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        header("Location: customer_profile.php?status=error");
        exit();
    }

    if ($file['size'] > $maxFileSize) {
        header("Location: customer_profile.php?status=file_too_big");
        exit();
    }

    $fileData = file_get_contents($file['tmp_name']);

    if (empty($fileData)) {
        header("Location: customer_profile.php?status=no_file");
        exit();
    }

    $fileType = mime_content_type($file['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($fileType, $allowedTypes)) {
        header("Location: customer_profile.php?status=invalid_file");
        exit();
    }

    $stmt = $conn->prepare("UPDATE registered_users SET profile_pic = ? WHERE registeredID = ?");
    $stmt->bind_param("bi", $null, $registeredID);
    $stmt->send_long_data(0, $fileData);

    if ($stmt->execute()) {

        $_SESSION['profile_pic'] = 'data:' . $fileType . ';base64,' . base64_encode($fileData);

        header("Location: customer_profile.php?status=success");
        exit();
    } else {
        header("Location: customer_profile.php?status=error");
        exit();
    }

    $stmt->close();
} else {
    header("Location: customer_profile.php?status=no_file");
    exit();
}

$conn->close();
?>
