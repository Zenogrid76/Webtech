<?php
session_start();

if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'user') {
        header('Location: ../user/user-dashboard.php');
        exit;
    } elseif ($_SESSION['role'] == 'admin') {
        header('Location: ../admin/admin-dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travello Anywhere</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>

<body>
    <div id="form-container">
        <form id="login-form" method="POST" action="../../process/login-process.php">
            <h2>Login</h2>
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <br>
            <label for="role">Role</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="signup.php" id="switch-to-signup">Register Now</a></p>
        </form>
    </div>
</body>

</html>