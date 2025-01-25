<?php
session_start();
include '../../config/db.php';

// Check if the user is an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = 'admin'; // Fixed role as 'admin'

    // Handle profile picture upload
    $profile_picture = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../../assets/Images/profiles/";
        $file_name = time() . "_" . basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $profile_picture = "assets/Images/profiles/" . $file_name;
        } else {
            header('Location: profile.php?error=Failed to upload profile picture');
            exit;
        }
    }

    // Insert new admin into the database
    $query = "INSERT INTO users (name, email, password, phone, address, profile_picture, role, is_validated) 
              VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $name, $email, $password, $phone, $address, $profile_picture, $role);

    if ($stmt->execute()) {
        header('Location: profile.php?success=New admin added successfully');
    } else {
        header('Location: profile.php?error=Failed to add new admin');
    }
    exit;
}
?>
