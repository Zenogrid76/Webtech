<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    header('Location: ../../auth/login.php');
    exit;
}

include('../../config/db.php');

$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Failed to fetch user data.";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../../assets/css/profile.css">
</head>

<body>
    <!-- Header -->
    <?php include('../../includes/user-header.php'); ?>

    <main class="profile-container">
        <h1>User Profile</h1>

        <div class="profile-card">
            <div class="profile-image">
                <img src="../../<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
            </div>

            <form method="POST" action="profile-update.php" class="profile-form">
                <input type="hidden" name="action" value="update_info">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <p id="email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>
        </div>

        <button type="submit" class="btn">Update Information</button>
        </form>

        <form method="POST" action="../../process/profile-update.php" class="password-form">
            <input type="hidden" name="action" value="update_password">
            <h2>Change Password</h2>

            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Update Password</button>
        </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include('../../includes/footer.php'); ?>
</body>

</html>