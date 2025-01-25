<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'user') {
        header('Location: ../user/user-dashboard.php');
        exit;
    } elseif ($_SESSION['role'] == 'admin') {
        header('Location: ../admin/admin-dashboard.php');
        exit;
    }
}

$signupSuccess = false;
if (isset($_SESSION['signup_success']) && $_SESSION['signup_success'] === true) {
    $signupSuccess = true;
    unset($_SESSION['signup_success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travello Anywhere - Signup</title>
    <link rel="stylesheet" href="../../assets/css/signup.css">
</head>

<body>
    <?php if ($signupSuccess): ?>
        <div class="notification" id="success-notification">
            Successfully signed up! Redirecting...
        </div>
        <script>
            const notification = document.getElementById('success-notification');
            notification.classList.add('show');

            setTimeout(() => {
                window.location.href = "login.php";
            }, 2000);
        </script>
    <?php endif; ?>

    <div id="form-container">
        <form id="signup-form" action="../../process/register-process.php" method="POST" enctype="multipart/form-data">
            <h2>Register</h2>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <br>
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" required>
            <br>
            <label for="address">Address</label>
            <textarea name="address" id="address" required></textarea>
            <br>
            <label for="profile_picture">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>
            <br>
            <input type="hidden" name="role" value="user">
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php" id="switch-to-login">Login Here</a></p>
        </form>
    </div>
</body>

</html>