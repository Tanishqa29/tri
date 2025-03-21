<?php 
session_start();
include '../db.php'; // Include database connection

// Check if employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Fetch training needs from specific columns
$tniQuery = "SELECT training_need_1, training_need_2, training_need_3 FROM tni WHERE employee_id = $employee_id LIMIT 1";
$tniResult = mysqli_query($conn, $tniQuery);
$tniNeeds = ["", "", ""]; // Default values

if ($row = mysqli_fetch_assoc($tniResult)) {
    $tniNeeds = [
        $row['training_need_1'] ?? "",
        $row['training_need_2'] ?? "",
        $row['training_need_3'] ?? ""
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $need1 = mysqli_real_escape_string($conn, $_POST['tni'][0]);
    $need2 = mysqli_real_escape_string($conn, $_POST['tni'][1]);
    $need3 = mysqli_real_escape_string($conn, $_POST['tni'][2]);

    // Check if record exists
    $checkQuery = "SELECT * FROM tni WHERE employee_id = $employee_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Update existing record
        $updateQuery = "UPDATE tni SET training_need_1 = '$need1', training_need_2 = '$need2', training_need_3 = '$need3' WHERE employee_id = $employee_id";
        mysqli_query($conn, $updateQuery);
    } else {
        // Insert new record
        $insertQuery = "INSERT INTO tni (employee_id, training_need_1, training_need_2, training_need_3) VALUES ($employee_id, '$need1', '$need2', '$need3')";
        mysqli_query($conn, $insertQuery);
    }

    echo "<script>alert('Training Needs Updated!'); window.location.href='my_tni.php';</script>";
}
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Training Needs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Training Needs</h2>
        <form method="POST">
            <div class="form-group">
                <label>Need 1:</label>
                <input type="text" name="tni[]" value="<?= htmlspecialchars($tniNeeds[0]) ?>" required>
            </div>
            <div class="form-group">
                <label>Need 2:</label>
                <input type="text" name="tni[]" value="<?= htmlspecialchars($tniNeeds[1]) ?>" required>
            </div>
            <div class="form-group">
                <label>Need 3:</label>
                <input type="text" name="tni[]" value="<?= htmlspecialchars($tniNeeds[2]) ?>" required>
            </div>
            <button type="submit">Update Training Needs</button>
        </form>
    </div>
</body>
</html>

<?php include('footer.php') ?>

