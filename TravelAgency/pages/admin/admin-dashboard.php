<?php
session_start();
include '../../config/db.php';

// Check if the admin is logged in and has the correct role
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php'); // Redirect to login if not authorized
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-deal'])) {
    $package_id = $_POST['package_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $image_path = $_POST['current_image'];
    if (!empty($_FILES['package_image']['name'])) {
        $target_dir = "../../assets/uploads/";
        $target_file = $target_dir . basename($_FILES["package_image"]["name"]);
        if (move_uploaded_file($_FILES["package_image"]["tmp_name"], $target_file)) {
            $image_path = "assets/uploads/" . basename($_FILES["package_image"]["name"]);
        }
    }

    $update_query = "UPDATE travel_packages SET name = ?, description = ?, price = ?, package_image = ? WHERE package_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_path, $package_id);

    if ($stmt->execute()) {
        echo "<script>alert('Deal updated successfully!'); window.location.href='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to update deal.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css">
</head>

<body>
    <?php include '../../includes/admin-header.php'; ?>

    <section id="manage-deals">
        <h2>Manage Special Deals</h2>
        <div class="deals-container">
            <?php
            // Fetch a maximum of 3 deals from the database
            $query = "SELECT package_id, name, description, price, package_image FROM travel_packages LIMIT 3";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                    <div class="deal-card">
                        <!-- Package Image -->
                        <img src="<?php echo '../../' . $row['package_image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="deal-img">
                        <!-- Location Name -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="package_id" value="<?php echo $row['package_id']; ?>">
                            <input type="hidden" name="current_image" value="<?php echo $row['package_image']; ?>">

                            <label for="name-<?php echo $row['package_id']; ?>">Name:</label>
                            <input type="text" id="name-<?php echo $row['package_id']; ?>" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

                            <label for="description-<?php echo $row['package_id']; ?>">Description:</label>
                            <textarea id="description-<?php echo $row['package_id']; ?>" name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>

                            <label for="price-<?php echo $row['package_id']; ?>">Price:</label>
                            <input type="number" id="price-<?php echo $row['package_id']; ?>" name="price" value="<?php echo $row['price']; ?>" required>

                            <label for="package_image-<?php echo $row['package_id']; ?>">Image:</label>
                            <input type="file" id="package_image-<?php echo $row['package_id']; ?>" name="package_image" accept="image/*">

                            <button type="submit" name="update-deal" class="edit-btn">Update</button>
                        </form>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p>No special deals found!</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="available-packages">
        <div class="table-wrapper">
            <div class="table-container">
                <h2>Available Packages</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM travel_packages LIMIT 5";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
                                <td>৳" . number_format($row['price'], 2) . "</td>
                                <td>
                                    <button type='button' class='details-btn' onclick=\"window.location.href='manage-package.php?id={$row['package_id']}';\">Details</button>
                                </td>
                            </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No packages available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>



    <section id="reviews">
        <div class="reviews-wrapper">
            <h2>Recent Customer Reviews</h2>
            <?php
            // Prepare the SQL query to fetch the last 3 reviews with user details
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

            // Execute the query using MySQLi
            if ($stmt = $conn->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if reviews exist
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Construct the image path using the profile_picture field
                        $imagePath = '../../' . htmlspecialchars($row['profile_picture']);
                        $imageSrc = file_exists($imagePath) ? $imagePath : 'assets/Images/default-profile.png'; // Fallback to default

                        echo "
                    <div class='review-card'>
                        <div class='reviewer-info'>
                            <img src='$imageSrc' alt='Reviewer Image' class='reviewer-img'>
                            <h3>" . htmlspecialchars($row['reviewer_name']) . "</h3>
                        </div>
                        <p class='review-text'>" . htmlspecialchars($row['review_text']) . "</p>
                    </div>
                    ";
                    }
                } else {
                    echo "<p>No reviews found.</p>";
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "<p>Error executing query: " . $conn->error . "</p>";
            }
            ?>
        </div>
    </section>

    <?php
    include '../../includes/footer.php';
    ?>
</body>

</html>