<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include($_SERVER['DOCUMENT_ROOT'] . '/travelagency/config/db.php');

$profile_picture = '/travelagency/assets/Images/default-profile.png';
$user_name = 'Admin';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $query = "SELECT name, profile_picture FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $profile_picture = !empty($user['profile_picture']) ? '/travelagency/' . $user['profile_picture'] : $profile_picture;
        $user_name = $user['name'];    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access</title>
    <link rel="stylesheet" href="/travelagency/assets/css/headerstyle.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="/travelagency/assets/Images/logo.png" alt="Logo">
        </div>
        <nav class="navbar">
            <a href="/travelagency/pages/admin/admin-dashboard.php">Dashboard</a>
            <a href="/travelagency/pages/admin/manage-package.php">Manage Packages</a>
            <a href="/travelagency/pages/admin/admin-dashboard.php#reviews">Reviews</a>
            <a href="/travelagency/pages/auth/logout.php" class="logout-btn">Log Out</a>
        </nav>

        <div class="admin-info">
            <a href="/travelagency/pages/admin/profile.php">
                <img src="<?php echo $profile_picture; ?>" alt="Admin Profile" class="user-profile">
            </a>
            
            <button class="admin-role-btn" onclick="window.location.href='/travelagency/pages/admin/profile.php';">
                <?php echo htmlspecialchars($user_name); ?>
            </button>
        </div>
    </header>
</body>

</html>