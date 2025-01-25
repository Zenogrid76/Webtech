<?php
session_start();
include('../config/db.php');

if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'user') {
        header('Location: ../pages/user/user-dashboard.php');
        exit;
    } elseif ($_SESSION['role'] == 'admin') {
        header('Location: ../pages/admin/admin-dashboard.php');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $sql->bind_param("ss", $email, $role);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            if ($role == 'admin' && $user['is_validated'] == 0) {
                echo "<script>alert('Admin account is pending validation.'); window.location.href='../pages/auth/login.php';</script>";
                exit;
            }

            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($role == 'user') {
                header('Location: ../pages/user/user-dashboard.php');
            } elseif ($role == 'admin') {
                header('Location: ../pages/admin/admin-dashboard.php');
            }
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href='../pages/auth/login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid email, password, or role.'); window.location.href='../pages/auth/login.php';</script>";
    }
}

$conn->close();
?>