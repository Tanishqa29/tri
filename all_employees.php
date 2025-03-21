<?php  
include('../db.php');
include('header.php'); 
include('sidebar.php'); 

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch all employees with training records
$query = "SELECT e.id, e.pin_no, e.name, e.designation, e.division, e.dob, e.contact_no, e.email, 
                 t.year, t.training_type, t.course_name, t.institute, t.duration 
          FROM employees e
          LEFT JOIN training_records t ON e.id = t.employee_id";

if (!empty($search)) {
    $query .= " WHERE e.name LIKE '%$search%'";
}

$query .= " ORDER BY e.id, t.year DESC, t.training_type";

$result = $conn->query($query);

// Organizing data for row grouping
$employees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $emp_id = $row['id'];

    if (!isset($employees[$emp_id])) {
        $employees[$emp_id]['details'] = [
            'id' => $emp_id,
            'pin_no' => $row['pin_no'],
            'name' => $row['name'],
            'designation' => $row['designation'],
            'division' => $row['division'],
            'dob' => $row['dob'],
            'contact_no' => $row['contact_no'],
            'email' => $row['email']
        ];
    }
    $employees[$emp_id]['trainings'][] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Employees</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: right;
        }
        .search-bar input {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-bar button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            font-size: 14px;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #f0ad4e;
            color: white;
        }
        .btn-edit:hover {
            background-color: #ec971f;
        }
        .btn-delete {
            background-color: #d9534f;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Employees</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by employee name..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>PIS/PIN No</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Division</th>
                    <th>DOB</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Year</th>
                    <th>Training Type</th>
                    <th>Course Name</th>
                    <th>Institute/Agency</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sr_no = 1;
                foreach ($employees as $employee_id => $data) {
                    $trainings = $data['trainings'];
                    $year_group = [];

                    // Count rows per year for rowspan calculation
                    foreach ($trainings as $record) {
                        $year_group[$record['year']][] = $record;
                    }

                    echo "<tr>
                            <td rowspan='" . count($trainings) . "'>{$sr_no}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['pin_no']}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['name']}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['designation']}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['division']}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['dob']}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['contact_no']}</td>
                            <td rowspan='" . count($trainings) . "'>{$data['details']['email']}</td>";

                    $first_row = true;
                    foreach ($year_group as $year => $records) {
                        $rowspan = count($records);
                        echo "<td rowspan='{$rowspan}'><strong>{$year}</strong></td>";

                        foreach ($records as $index => $training) {
                            if ($index > 0) echo "<tr>";
                            echo "<td>{$training['training_type']}</td>
                                  <td>{$training['course_name']}</td>
                                  <td>{$training['institute']}</td>
                                  <td>{$training['duration']}</td>";

                            // Add actions only once per employee
                            if ($first_row) {
                                echo "<td rowspan='" . count($trainings) . "'>
                                        <a href='edit_employee.php?id={$data['details']['id']}' class='btn btn-edit'>Edit</a>
                                        <a href='delete_employee.php?id={$data['details']['id']}' class='btn btn-delete' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                                      </td>";
                                $first_row = false;
                            }

                            echo "</tr>";
                        }
                    }

                    $sr_no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php include('footer.php'); ?>
