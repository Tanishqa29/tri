<?php
include '../db.php'; // Database connection

// Check if employee ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request!'); window.location.href='all_employees.php';</script>";
    exit;
}

$id = $_GET['id'];

// Fetch employee details
$query = "SELECT name, designation FROM employees WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Employee not found!'); window.location.href='all_employees.php';</script>";
    exit;
}

$employee = mysqli_fetch_assoc($result);

// Handle Delete Confirmation
if (isset($_POST['confirm_delete'])) {
    $deleteQuery = "DELETE FROM employees WHERE id = $id";

    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Employee deleted successfully!'); window.location.href='all_employees.php';</script>";
    } else {
        echo "<script>alert('Error deleting employee: " . mysqli_error($conn) . "');</script>";
    }
}

?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h2 {
            color: #dc3545;
        }

        p {
            font-size: 16px;
            color: #333;
            margin: 15px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .delete {
            background: #dc3545;
            color: white;
        }

        .delete:hover {
            background: #b02a37;
        }

        .cancel {
            background: #6c757d;
            color: white;
        }

        .cancel:hover {
            background: #545b62;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete <strong><?= $employee['name']; ?></strong> (<?= $employee['designation']; ?>)?</p>

        <form method="POST">
            <button type="submit" name="confirm_delete" class="delete">Yes, Delete</button>
        </form>
        <a href="all_employees.php"><button class="cancel">Cancel</button></a>
    </div>

</body>
</html>

<?php include('footer.php') ?>
