<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../../assets/css/contact.css">

    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php include('../../includes/user-header.php'); ?>

    <!-- Banner Section -->
    <section class="banner">
        <img src="../../assets/images/banner.png" alt="Contact Us Banner">
        <h1>Contact Us</h1>
    </section>

    <!-- Contact Section -->
    <section class="contact-container">
        <div class="content">
            <!-- Left Container -->
            <div class="left-container">
                <h2>Contact Information</h2>
                <p><strong>Address:</strong> 123 Somewhere, Dhaka, Bangladesh</p>
                <p><strong>Phone:</strong> +880 123 456 789</p>
                <p><strong>Email:</strong> info@example.com</p>
                <h3>Locations</h3>
                <ul>
                    <li>Location 1: Dhaka, Bangladesh</li>
                    <li>Location 2: Chittagong, Bangladesh</li>
                    <li>Location 3: Sylhet, Bangladesh</li>
                </ul>
            </div>

            <!-- Right Container -->
            <div class="right-container">
                <h2>Follow Us</h2>
                <div class="social-icons">
                    <a href="https://www.x.com/" class="social-icon twitter" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/" class="social-icon instagram" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/" class="social-icon linkedin" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include('../../includes/footer.php'); ?>
</body>

</html>
