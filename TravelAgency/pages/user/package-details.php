<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    header('Location: ../../auth/login.php');
    exit;
}

include('../../config/db.php');

$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM travel_packages WHERE package_id = $package_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $package = mysqli_fetch_assoc($result);
    $per_day_cost = $package['price'] / $package['duration_days'];
} else {
    echo "<script>alert('Package not found!'); window.location.href='user-dashboard.php';</script>";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Details</title>
    <link rel="stylesheet" href="../../assets/css/package-details.css">
    <script>
        // Passing PHP variables to JavaScript
        const packageDetails = {
            packageId: <?php echo json_encode($package_id); ?>,
            perDayCost: <?php echo json_encode($per_day_cost); ?>,
            maxPeople: <?php echo json_encode($package['max_people']); ?>,
            basePrice: <?php echo json_encode($package['price']); ?>,
            packageDuration: <?php echo json_encode($package['duration_days']); ?>
        };
    </script>
    <script src="../../assets/js/package-details.js"></script>
</head>

<body>
    <?php include('../../includes/user-header.php'); ?>

    <main>
        <h1 id="package-title">Selected Package: <?php echo htmlspecialchars($package['name']); ?></h1>
        <div class="details-container">
            <div class="included-section">
                <h2>Included With</h2>
                <ul>
                    <?php
                    $included_items = explode('|', $package['included_with']);
                    foreach ($included_items as $item) {
                        echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="excluded-section">
                <h2>Excluded By</h2>
                <ul>
                    <?php
                    $excluded_items = explode('|', $package['excluded_by']);
                    foreach ($excluded_items as $item) {
                        echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="trip-details">
            <h2>Trip Details</h2>
            <p><strong>Start Date:</strong> <?php echo date("Y-m-d"); ?></p>
            <p><strong>Package Duration:</strong> <?php echo htmlspecialchars($package['duration_days']) . " Days"; ?></p>
            <p><strong>Max People:</strong> <?php echo htmlspecialchars($package['max_people']); ?></p>
            <p><strong>Base Price:</strong> ৳<span id="base-price"><?php echo number_format($package['price'], 2); ?></span></p>

            <form id="customize-form">
                <label for="days">Days of Stay:</label>
                <input type="number" id="days" name="days" value="1" min="1" required>

                <label for="people">Number of People:</label>
                <input type="number" id="people" name="people" value="1" min="1" max="<?php echo htmlspecialchars($package['max_people']); ?>" required>

                <p><strong>Total Price:</strong> ৳<span id="total-price"><?php echo number_format($package['price'], 2); ?></span></p>

                <button type="button" id="book-now" onclick="bookPackage()">Book Now</button>
            </form>
        </div>

        <div class="reviews-section">
            <h2>Reviews</h2>
            <div id="reviews-container">
                <?php
                $reviews_query = "SELECT r.comment, u.name, u.profile_picture 
                                  FROM reviews r 
                                  JOIN users u ON r.user_id = u.user_id 
                                  WHERE r.package_id = $package_id 
                                  ORDER BY r.created_at DESC";
                $reviews_result = mysqli_query($conn, $reviews_query);

                if ($reviews_result && mysqli_num_rows($reviews_result) > 0) {
                    while ($row = mysqli_fetch_assoc($reviews_result)) {
                        echo "
                            <div class='review-card'>
                                <img src='../../" . htmlspecialchars($row['profile_picture']) . "' alt='" . htmlspecialchars($row['name']) . "'>
                                <div class='review-content'>
                                    <p class='reviewer-name'>" . htmlspecialchars($row['name']) . "</p>
                                    <p>" . htmlspecialchars($row['comment']) . "</p>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<p>No reviews yet. Be the first to review!</p>";
                }
                ?>
            </div>

            <form id="review-form">
                <textarea id="review-text" name="review" placeholder="Write a review..." required></textarea>
                <button type="button" id="submit-review" onclick="submitReview()">Submit Review</button>
            </form>
        </div>
    </main>

    <?php include('../../includes/footer.php'); ?>
</body>

</html>