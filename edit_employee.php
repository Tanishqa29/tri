<?php
include '../db.php'; // Database connection
$id = $_GET['id'];

// Fetch employee details
$query = "SELECT * FROM employees WHERE id = $id";
$result = mysqli_query($conn, $query);
$employee = mysqli_fetch_assoc($result);

// Fetch training history
$training_query = "SELECT * FROM training_records WHERE employee_id = $id ORDER BY year DESC";
$training_result = mysqli_query($conn, $training_query);
$training_data = [];
while ($row = mysqli_fetch_assoc($training_result)) {
    $training_data[$row['year']][] = $row;
}

// Fetch training needs
$tni_query = "SELECT * FROM tni WHERE employee_id = $id";
$tni_result = mysqli_query($conn, $tni_query);
$tni = mysqli_fetch_assoc($tni_result);
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 20px;
    padding: 20px;
}

h2, h3, h5 {
    color: #333;
}

form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

input, textarea {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.training-container {
    margin-top: 20px;
}

.training-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.training-table th, .training-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.training-table th {
    background: #007bff;
    color: white;
}

button {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 4px;
}

button:hover {
    background: #0056b3;
}

    </style>
    <script>
        function addTrainingRow(year) {
            var table = document.getElementById("training-" + year);
            var newRow = document.createElement("tr");
            newRow.innerHTML = `
                <td><input type="text" name="training_type_${year}[]" placeholder="Training Type"></td>
                <td><input type="text" name="course_name_${year}[]" placeholder="Course Name"></td>
                <td><input type="text" name="institute_${year}[]" placeholder="Institute"></td>
                <td><input type="text" name="duration_${year}[]" placeholder="Duration"></td>
                <td><button type="button" onclick="this.parentElement.parentElement.remove()">-</button></td>
            `;
            table.appendChild(newRow);
        }
    </script>
</head>
<body>

<h2>Edit Employee Details</h2>
<form action="edit_employee.php" method="POST">
    <input type="hidden" name="id" value="<?= $employee['id']; ?>">

    <h3>Basic Information</h3>
    <input type="text" name="name" value="<?= htmlspecialchars($employee['name']); ?>" required>
    <input type="text" name="designation" value="<?= htmlspecialchars($employee['designation']); ?>" required>
    <input type="text" name="division" value="<?= htmlspecialchars($employee['division']); ?>" required>
    <input type="text" name="pin_no" value="<?= htmlspecialchars($employee['pin_no']); ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($employee['email']); ?>" required>
    <input type="text" name="contact_no" value="<?= htmlspecialchars($employee['contact_no']); ?>" required>
    <input type="date" name="dob" value="<?= htmlspecialchars($employee['dob']); ?>" required>

    <!-- Training History Container -->
    <div class="training-container">
        <?php $years = [2024, 2023, 2022]; ?>
        <?php foreach ($years as $year): ?>
            <h5><?= $year; ?></h5>
            <table id="training-<?= $year; ?>" class="training-table">
                <tr>
                    <th>Training Type</th>
                    <th>Course Name</th>
                    <th>Institute</th>
                    <th>Duration</th>
                    <th>Action</th>
                </tr>
                <?php if (isset($training_data[$year])): ?>
                    <?php foreach ($training_data[$year] as $row): ?>
                        <tr>
                            <td><input type="text" name="training_type_<?= $year; ?>[]" value="<?= htmlspecialchars($row['training_type']); ?>"></td>
                            <td><input type="text" name="course_name_<?= $year; ?>[]" value="<?= htmlspecialchars($row['course_name']); ?>"></td>
                            <td><input type="text" name="institute_<?= $year; ?>[]" value="<?= htmlspecialchars($row['institute']); ?>"></td>
                            <td><input type="text" name="duration_<?= $year; ?>[]" value="<?= htmlspecialchars($row['duration']); ?>"></td>
                            <td><button type="button" onclick="this.parentElement.parentElement.remove()">-</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
            <button type="button" onclick="addTrainingRow(<?= $year; ?>)">+ Add Training</button>
        <?php endforeach; ?>
    </div>

    <h3>Training Needs</h3>
    <textarea name="training_need_1" rows="2"><?= isset($tni['training_need_1']) ? htmlspecialchars($tni['training_need_1']) : ''; ?></textarea>
    <textarea name="training_need_2" rows="2"><?= isset($tni['training_need_2']) ? htmlspecialchars($tni['training_need_2']) : ''; ?></textarea>
    <textarea name="training_need_3" rows="2"><?= isset($tni['training_need_3']) ? htmlspecialchars($tni['training_need_3']) : ''; ?></textarea>

    <button type="submit">Update Employee</button>
</form>

</body>
</html>

<?php include('footer.php'); ?>
