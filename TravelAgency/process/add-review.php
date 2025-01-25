<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['email'])) {
    http_response_code(403);
    exit('Unauthorized access.');
}

include('../config/db.php');

// Get POST data
$package_id = intval($_POST['package_id']);
$review = mysqli_real_escape_string($conn, trim($_POST['review']));
$user_email = $_SESSION['email'];

// user ID and profile picture
$user_query = "SELECT user_id, name, profile_picture FROM users WHERE email = '$user_email'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user = mysqli_fetch_assoc($user_result);
    $user_id = $user['user_id'];
    $name = htmlspecialchars($user['name']);
    $profile_picture = htmlspecialchars($user['profile_picture']);

    // Insert into DB
    $insert_query = "INSERT INTO reviews (package_id, user_id, comment, created_at) VALUES ($package_id, $user_id, '$review', NOW())";
    if (mysqli_query($conn, $insert_query)) {
        echo "
            <div class='review-card'>
                <img src='../../$profile_picture' alt='$name'>
                <div class='review-content'>
                    <p class='reviewer-name'>$name</p>
                    <p>" . htmlspecialchars($review) . "</p>
                </div>
            </div>
        ";
    } else {
        echo "ERROR";
    }
} else {
    echo "ERROR";
}
mysqli_close($conn);
?>