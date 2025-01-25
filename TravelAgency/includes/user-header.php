<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include($_SERVER['DOCUMENT_ROOT'] . '/travelagency/config/db.php');

$profile_picture = '/travelagency/assets/Images/default-profile.png';
$user_name = 'User';

if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);

    $query = "SELECT name, profile_picture FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (!empty($user['profile_picture'])) {
            $profile_picture = '/travelagency/' . $user['profile_picture'];
        } else {
            $profile_picture = $profile_picture;
        }
        $user_name = $user['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travello</title>
    <link rel="stylesheet" href="/travelagency/assets/css/user-header.css">
</head>
<body>
<header>
    <!-- Logo Section -->
    <div class="logo">
            <img src="/travelagency/assets/Images/logo.png" alt="Logo">
        </div>

        <!-- Navigation Menu -->
        <nav class="navbar">
            <a href="/travelagency/pages/user/user-dashboard.php">Packages</a>
            <a href="/travelagency/pages/user/contact.php">Contact Us</a>
        </nav>

        <!-- Profile and Logout Section -->
        <div class="user-info">
            <a href="/travelagency/pages/user/profile.php">
                <img src="<?php echo $profile_picture; ?>" alt="User Profile" class="user-profile">
            </a>
            <a href="/travelagency/pages/auth/logout.php" class="logout-btn">Log Out</a>
        </div>
</header>
</body>
</html>