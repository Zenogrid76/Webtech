<?php
session_start();

// Redirect users/admins to their respective dashboards if logged in
if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: pages/admin/admin-dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'user') {
        header('Location: pages/user/user-dashboard.php');
        exit;
        // include 'includes/user-header.php';
    }
}
?>
<script src="assets/js/homepage.js"></script>

<head>
    <link rel="stylesheet" href="assets/css/home.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body>
    <?php
    include 'includes/header.php';
    ?>
    <section id="banner">
        <img src="assets/Images/banner.png" alt="Travel Banner" class="banner-img">
        <div class="banner-content">
            <div class="text-backdrop">
                <h1>Explore the World with Us</h1>
                <p>Unforgettable experiences, stunning destinations, and the best packages tailored for you.</p>
                <button class="banner-btn" onclick="window.location.href='pages/auth/login.php';">Learn More</button>
            </div>
        </div>
    </section>

    <section id="special-deals">
        <h2>Special Deals</h2>
        <div class="deals-container">
            <?php
            include 'config/db.php';

            $query = "SELECT * FROM travel_packages LIMIT 3";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $short_description = strlen($row['description']) > 25 ? substr($row['description'], 0, 25) . "..." : $row['description'];

                    echo "<div class='deal-card'>
                        <img src='" . $row['package_image'] . "' alt='" . htmlspecialchars($row['name']) . "' class='deal-img'>
                        <h3>" . htmlspecialchars($row['name']) . "</h3>
                        <p>" . htmlspecialchars($short_description) . "</p>
                        <p><strong>৳" . number_format($row['price'], 2) . "</strong></p>
                        <button class='deal-btn' onclick=\"window.location.href='pages/auth/login.php';\">Learn More</button>
                    </div>";
                }
            } else {
                echo "<p>No special deals available at the moment.</p>";
            }
            ?>
        </div>
    </section>


    <section id="testimonials">
        <div class="second-banner">
            <img src="assets/Images/second-banner.jpg" alt="Background Image" class="testimonials-bg">
        </div>

        <!-- Testimonials Content -->
        <div class="testimonials-content">
            <h3>Why Choose Us</h3>
            <h2>Top reviews from our clients</h2>
            <div class="carousel">
                <?php
                $query = "
                SELECT 
                    r.comment AS review_text,
                    u.name AS reviewer_name,
                    u.profile_picture AS profile_picture
                FROM reviews r
                JOIN users u ON r.user_id = u.user_id
                ORDER BY r.created_at DESC
                LIMIT 3
            ";

                if ($stmt = $conn->prepare($query)) {
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        $active = true; 
                        while ($row = $result->fetch_assoc()) {
                           
                            $imagePath =   htmlspecialchars($row['profile_picture']);
                            $imageSrc = file_exists($imagePath) ? $imagePath : 'assets/Images/default-profile.png';

                            echo "
                        <div class='review-box " . ($active ? 'active' : '') . "'>
                            <blockquote>
                                <i class='fa fa-quote-left'></i>
                                " . htmlspecialchars($row['review_text']) . "
                            </blockquote>
                            <div class='reviewer-info'>
                                <img src='$imageSrc' alt='Reviewer' class='reviewer-img'>
                                <div>
                                    <p><strong>" . htmlspecialchars($row['reviewer_name']) . "</strong></p>
                                    <p>Verified Customer</p>
                                </div>
                            </div>
                        </div>
                        ";
                            $active = false;
                        }
                    } else {
                        echo "<p>No reviews found.</p>";
                    }

                    $stmt->close();
                } else {
                    echo "<p>Error executing query: " . $conn->error . "</p>";
                }
                ?>
            </div>
            <div class="navigation">
                <button class="nav-btn" id="prev-btn">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <button class="nav-btn" id="next-btn">
                    <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
        </section>


        <?php
        // Include the header
        include 'includes/footer.php';
        ?>
</body>

</html>