<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    header('Location: ../../auth/login.php');
    exit;
}

include('../config/db.php');

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'update_info') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);

        $query = "UPDATE users SET name = '$name' WHERE email = '$email'";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Information updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update information.";
        }
    } elseif ($action === 'update_password') {
        // Update password
        $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

        // Validate current password
        $query = "SELECT password FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        if (!password_verify($current_password, $user['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = "New password and confirm password do not match.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
            if (mysqli_query($conn, $update_query)) {
                $_SESSION['success'] = "Password updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update password.";
            }
        }
    } else {
        $_SESSION['error'] = "Invalid action.";
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

mysqli_close($conn);

header('Location: ../pages/auth/login.php');
exit;
?>