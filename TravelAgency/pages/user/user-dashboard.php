<?php
    session_start();

    if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
        header('Location: ../../auth/login.php');
        exit;
    }

    include('../../config/db.php');
    include('../../includes/user-header.php');

    $sql = "SELECT * FROM travel_packages";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/user-dashboard.css">
</head>

<body>

    <!-- Main Content -->
    <main>
        <h1>Available Packages</h1>
        <div class="packages-container">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='package-card'>
                        <div class='package-image'>
                            <img src='../../" . htmlspecialchars($row['package_image']) . "' alt='" . htmlspecialchars($row['name']) . "'>
                        </div>
                        <div class='package-details'>
                            <h3>" . htmlspecialchars($row['name']) . "</h3>
                            <p>" . htmlspecialchars($row['description']) . "</p>
                            <p><strong>Price: ৳" . number_format($row['price'], 2) . "</strong></p>
                            <div class='package-buttons'>
                                <button onclick=\"window.location.href='package-details.php?id=" . $row['package_id'] . "'\">Details</button>
                                <button onclick=\"window.location.href='book-package.php?id=" . $row['package_id'] . "'\">Book Now</button>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>No packages available at the moment.</p>";
            }
            ?>
        </div>
    </main>

    <!-- Footer Area -->
    <?php
        include '../../includes/footer.php';
    ?>
</body>

</html>

<?php
    $conn->close();
?>