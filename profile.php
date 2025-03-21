<?php
include('../db.php'); // Database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ./profile.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details from the users table
$query = "SELECT * FROM users WHERE id = $admin_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
} else {
    echo "Admin not found!";
    exit();
}
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        .profile-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="profile-container">
            <img src="<?= !empty($admin['profile_pic']) ? 'uploads/' . $admin['profile_pic'] : 'uploads/default.png' ?>" class="profile-img" alt="Admin Profile Picture">
            <h3><?= $admin['name']; ?></h3>
            <p><strong>Role:</strong> <?= $admin['role']; ?></p>
            <p><strong>Email:</strong> <?= $admin['email']; ?></p>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
</body>
</html>

<?php include('footer.php') ?>
