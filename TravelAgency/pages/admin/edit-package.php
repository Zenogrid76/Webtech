<?php
session_start();
include '../../config/db.php';

// Check if the admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch the package details for editing
if (isset($_GET['id'])) {
    $package_id = intval($_GET['id']);
    $query = "SELECT * FROM travel_packages WHERE package_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
} else {
    header('Location: admin-dashboard.php');
    exit;
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $package_image = $package['package_image'];

    // Handle file upload
    if (!empty($_FILES['package_image']['name'])) {
        $target_dir = "../../assets/Images/packages/";
        $file_name = time() . "_" . basename($_FILES['package_image']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['package_image']['tmp_name'], $target_file)) {
            $package_image = "assets/Images/packages/" . $file_name;
        }
    }

    // Update the database
    $query = "UPDATE travel_packages SET name = ?, description = ?, price = ?, package_image = ? WHERE package_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdsi", $name, $description, $price, $package_image, $package_id);

    if ($stmt->execute()) {
        header('Location: admin-dashboard.php?success=Package updated successfully');
    } else {
        header('Location: edit-package.php?id=' . $package_id . '&error=Failed to update package');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css">
</head>
<body>
<?php include '../../includes/admin-header.php'; ?>

<section id="edit-package">
    <h2>Edit Package</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Location Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($package['name']); ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($package['description']); ?></textarea>

        <label for="price">Price (৳):</label>
        <input type="number" name="price" id="price" step="0.01" value="<?php echo htmlspecialchars($package['price']); ?>" required>

        <label for="package_image">Package Image:</label>
        <input type="file" name="package_image" id="package_image" accept="image/*">
        <img src="<?php echo '../../' . $package['package_image']; ?>" alt="Current Image" class="current-img">

        <button type="submit">Update Package</button>
    </form>
</section>

</body>
</html>
