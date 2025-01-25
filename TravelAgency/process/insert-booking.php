<?php
session_start();
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Unauthorized access.');
}

if (!isset($_SESSION['email'])) {
    exit('User session invalid. Please log in.');
}

$email = $_SESSION['email'];

// Fetch user_id
$user_query = "SELECT user_id FROM users WHERE email = '$email'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result || mysqli_num_rows($user_result) === 0) {
    exit('Failed to fetch user information. Please try again.');
}

$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['user_id'];

$package_id = intval($_POST['package_id']);
$days = intval($_POST['days']);
$people = intval($_POST['people']);
$total_price = floatval($_POST['total_price']);
$start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
$end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

// Insert booking into the DB
$insert_query = "
    INSERT INTO bookings (user_id, package_id, start_date, end_date, total_price, status, created_at)
    VALUES ('$user_id', '$package_id', '$start_date', '$end_date', '$total_price', 'confirmed', NOW())
";

if (mysqli_query($conn, $insert_query)) {
    echo "Booking successful! Booking ID: " . mysqli_insert_id($conn);
} else {
    echo "Failed to confirm booking. Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>