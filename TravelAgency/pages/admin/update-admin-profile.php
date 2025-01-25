<?php
session_start();
include '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email']; // Email from session

    // Handle Profile Picture Upload
    if (isset($_POST['update_picture'])) {
        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "../../assets/Images/profiles/";
            $file_name = time() . "_" . basename($_FILES['profile_picture']['name']);
            $target_file = $target_dir . $file_name;

            // Ensure the directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Move the uploaded file
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = "assets/Images/profiles/" . $file_name;

                // Update the profile picture in the database
                $query = "UPDATE users SET profile_picture = ? WHERE email = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $profile_picture, $email);

                if ($stmt->execute()) {
                    header('Location: profile.php?success=Profile picture updated successfully');
                } else {
                    header('Location: profile.php?error=Failed to update profile picture');
                }
            } else {
                header('Location: profile.php?error=Failed to upload profile picture');
            }
        } else {
            header('Location: profile.php?error=No file selected');
        }
        exit;
    }

    // Update Profile Details
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);

        $profile_picture = null;
        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "../../assets/Images/profiles/";
            $file_name = time() . "_" . basename($_FILES['profile_picture']['name']);
            $target_file = $target_dir . $file_name;

            // Ensure the directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = "assets/Images/profiles/" . $file_name;
            }
        }

        // Update profile in the database
        if ($profile_picture) {
            $query = "UPDATE users SET name = ?, phone = ?, address = ?, profile_picture = ? WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $name, $phone, $address, $profile_picture, $email);
        } else {
            $query = "UPDATE users SET name = ?, phone = ?, address = ? WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $name, $phone, $address, $email);
        }

        if ($stmt->execute()) {
            header('Location: profile.php?success=Profile updated successfully');
        } else {
            header('Location: profile.php?error=Failed to update profile');
        }
        exit;
    }

    // Update Password
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Fetch current password
        $query = "SELECT password FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                $query = "UPDATE users SET password = ? WHERE email = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $hashed_password, $email);

                if ($stmt->execute()) {
                    header('Location: profile.php?success=Password updated successfully');
                } else {
                    header('Location: profile.php?error=Failed to update password');
                }
            } else {
                header('Location: profile.php?error=New passwords do not match');
            }
        } else {
            header('Location: profile.php?error=Incorrect current password');
        }
        exit;
    }
}
?>