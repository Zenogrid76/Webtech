<?php
// Include the header
include '../../includes/admin-header.php';

// Connect to the database
include '../../config/db.php';

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $package_id = $_GET['id'];
    $delete_query = "DELETE FROM travel_packages WHERE package_id = $package_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Package deleted successfully.'); window.location.href='manage-package.php';</script>";
    } else {
        echo "<script>alert('Error deleting package.');</script>";
    }
}

// Handle Add/Update Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-package'])) {
    $package_id = $_POST['package-id'];
    $name = mysqli_real_escape_string($conn, $_POST['package-name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $duration = intval($_POST['duration']);
    $included_with = mysqli_real_escape_string($conn, $_POST['included-with']);
    $excluded_by = mysqli_real_escape_string($conn, $_POST['excluded-by']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start-date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end-date']);
    $max_people = intval($_POST['max-people']);

    // Handle Image Upload
    $image_path = $_POST['current-image'];
    if (!empty($_FILES['package-image']['name'])) {
        $target_dir = "../../assets/Images/";
        $target_file = $target_dir . basename($_FILES["package-image"]["name"]);

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["package-image"]["tmp_name"], $target_file)) {
            $image_path = "assets/Images/" . basename($_FILES["package-image"]["name"]);
        } else {
            echo "<p>Error: Unable to upload the file. Please check permissions or try again.</p>";
            exit;
        }
    }

    if ($package_id) {
        // Update Package
        $update_query = "UPDATE travel_packages 
            SET name = '$name', description = '$description', price = $price, 
            duration_days = $duration, package_image = '$image_path', 
            included_with = '$included_with', excluded_by = '$excluded_by', 
            start_date = '$start_date', end_date = '$end_date', max_people = $max_people
            WHERE package_id = $package_id";

        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Package updated successfully.'); window.location.href='manage-package.php';</script>";
        } else {
            echo "<script>alert('Error updating package: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        // Fetch the user_id using session email
        if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $email = $_SESSION['email'];
            $user_query = "SELECT user_id FROM users WHERE email = '$email'";
            $user_result = mysqli_query($conn, $user_query);

            if ($user_result && mysqli_num_rows($user_result) > 0) {
                $user_data = mysqli_fetch_assoc($user_result);
                $created_by = $user_data['user_id'];
            } else {
                echo "<script>alert('Error: Unable to fetch user details. Please try again.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Error: Unauthorized access. Please log in as an admin.');</script>";
            exit;
        }

        // Add New Package
        $insert_query = "INSERT INTO travel_packages 
            (name, description, price, duration_days, package_image, included_with, 
            excluded_by, start_date, end_date, max_people, created_by) 
            VALUES ('$name', '$description', $price, $duration, '$image_path', 
            '$included_with', '$excluded_by', '$start_date', '$end_date', $max_people, $created_by)";

        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Package added successfully.'); window.location.href='manage-package.php';</script>";
        } else {
            echo "<script>alert('Error adding package: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages</title>
    <link rel="stylesheet" href="../../assets/css/manage-packages.css">
</head>

<body>
    <main>
        <!-- Section 1: Show Available Packages -->
        <section id="available-packages">
            <h2>Available Packages</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Package ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM travel_packages LIMIT 5";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$row['package_id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['price']}</td>
                                    <td>{$row['duration_days']}</td>
                                    <td><img src='../../{$row['package_image']}' alt='Package Image' class='package-img'></td>
                                    <td>
                                   <button class='edit-btn' onclick='editPackage(" . json_encode($row) . ")'>Edit</button>
                                        <form action='' method='GET' style='display:inline;'>
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='id' value='{$row['package_id']}'>
                                            <button type='submit' class='delete-btn'>Delete</button>
                                        </form>

                                    </td>
                                  </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No packages available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 2: Add/Edit Package -->
        <section id="add-edit-package">
            <h2 id="form-title">Add New Package</h2>
            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="package-id" name="package-id">
                    <input type="hidden" id="current-image" name="current-image">

                    <label for="package-name">Package Name:</label>
                    <input type="text" id="package-name" name="package-name" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>

                    <label for="duration">Duration (Days):</label>
                    <input type="number" id="duration" name="duration" required>

                    <label for="included-with">Included With:</label>
                    <textarea id="included-with" name="included-with" placeholder="Separate items with a |"></textarea>

                    <label for="excluded-by">Excluded By:</label>
                    <textarea id="excluded-by" name="excluded-by" placeholder="Separate items with a |"></textarea>

                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="start-date" >

                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="end-date" >

                    <label for="max-people">Max People:</label>
                    <input type="number" id="max-people" name="max-people" >

                    <label for="package-image">Package Image:</label>
                    <input type="file" id="package-image" name="package-image" accept="image/*">

                    <div class="form-buttons">
    <button type="submit" id="submit-button" name="update-package">Add Package</button>
    <button type="button" id="clear-button" onclick="clearForm()">Clear Form</button>
</div>

                </form>
            </div>
        </section>
    </main>

    <script src="../../assets/js/admin.js"></script>
    <?php include '../../includes/footer.php'; ?>
</body>

</html>