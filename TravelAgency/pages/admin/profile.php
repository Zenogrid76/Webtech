<?php
session_start();
include '../../config/db.php';

// Include the admin header
include '../../includes/admin-header.php';

// Check if the admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch admin details
if (!isset($_SESSION['email'])) {
    die("Session email is not set. Please log in again.");
}

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("No user data found for the given email.");
}

// Get success/error messages
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../../assets/css/adminprofile.css"> <!-- Profile-specific CSS -->
</head>
<body>
    <div class="profile-container">
        <!-- Pop-up Notification -->
        <?php if ($success || $error): ?>
                <div class="popup-notification <?php echo $error ? 'error' : ''; ?>" id="popup-notification">
                    <?php echo htmlspecialchars($success ?: $error); ?>
                </div>
                <script>
                    // Display the pop-up notification
                    const notification = document.getElementById('popup-notification');
                    notification.style.display = 'block';

                    // Hide the notification after 3 seconds and remove query parameters
                    setTimeout(() => {
                        notification.style.display = 'none';
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }, 3000);
                </script>
            <?php endif; ?>


        

        <!-- Fullscreen Top Section -->
        <div class="top-section">
            <div class="profile-header">
                <div class="profile-pic">
                    <img src="<?php echo '../../' . ($user['profile_picture'] ?? 'assets/Images/default-profile.png'); ?>" alt="Profile Picture">
                    <form method="POST" action="update-admin-profile.php" enctype="multipart/form-data">
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                        <button type="submit" name="update_picture">Upload New</button>
                    </form>
                </div>
                <h1>Hello,<?php echo htmlspecialchars($user['name'] ?? 'Unknown User'); ?>!</h1>
            </div>
        </div>

        <!-- Bottom Section: Profile and Password -->
        <div class="bottom-section">
            <!-- Left Section: Profile Details -->
            <div class="left-section">
                <div class="profile-details">
                    <h2>Edit Profile</h2>
                    <form method="POST" action="update-admin-profile.php">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>

                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>

                        <label for="phone">Phone:</label>
                        <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">

                        <label for="address">Address:</label>
                        <textarea name="address" id="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>

                        <button type="submit" name="update_profile" class="update-btn">Update Profile</button>
                    </form>
                </div>
            </div>

            <!-- Right Section: Update Password -->
            <div class="right-section">
                <h2>Update Password</h2>
                <form method="POST" action="update-admin-profile.php">
                    <label for="current_password">Current Password:</label>
                    <input type="password" name="current_password" id="current_password" required>

                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" id="new_password" required>

                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>

                    <button type="submit" name="update_password" class="update-btn">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/js/admin.js"></script> <!-- Include JavaScript file -->
</body>

<div class="add-admin-section">
    <h2>Add New Admin</h2>
    <div class="admin-form-container">
        <form id="add-admin-form" action="add-admin-process.php" method="POST" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" required>

            <label for="address">Address</label>
            <textarea name="address" id="address" required></textarea>

            <label for="profile_picture">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>

            <button type="submit">Add Admin</button>
        </form>
    </div>
</div>



</html>
