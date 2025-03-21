<?php
include '../db.php'; // Database connection
?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Needs Identification</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        #searchInput {
            width: 50%;
            padding: 10px;
            margin: 10px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: rgb(123, 83, 233);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Training Needs Identification</h2>
    <input type="text" id="searchInput" placeholder="Search by name, training need, course...">
    <table>
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Division</th>
                <th>Training Needs</th>
                <th>Year</th>
                <th>Training Type</th>
                <th>Course Name</th>
                <th>Institute/Agency</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody id="tniTable">
            <?php
            $sql = "SELECT e.id AS emp_id, e.name, e.designation, e.division, 
                           tr.year, tr.training_type, tr.course_name, tr.institute, tr.duration, 
                           t.training_need_1, t.training_need_2, t.training_need_3
                    FROM tni t 
                    JOIN employees e ON t.employee_id = e.id
                    JOIN training_records tr ON tr.employee_id = e.id
                    ORDER BY e.id, tr.year DESC"; 

            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                $sr_no = 1;
                $employee_records = [];
                $rowspan_counts = [];

                while ($row = $result->fetch_assoc()) {
                    $emp_id = $row['emp_id'];
                    if (!isset($employee_records[$emp_id])) {
                        $employee_records[$emp_id] = [];
                        $rowspan_counts[$emp_id] = 0;
                    }
                    $employee_records[$emp_id][] = $row;
                    $rowspan_counts[$emp_id]++;
                }

                foreach ($employee_records as $emp_id => $records) {
                    $first_row = true;
                    $sr_no_span = count($records);
                    $year_counts = array_count_values(array_column($records, 'year'));

                    foreach ($records as $index => $row) {
                        echo "<tr data-emp-id='" . $emp_id . "'>";

                        // Sr No, Name, Designation, Division should span all rows of the employee
                        if ($first_row) {
                            echo "<td rowspan='$sr_no_span'>" . $sr_no++ . "</td>";
                            echo "<td rowspan='$sr_no_span'>" . $row['name'] . "</td>";
                            echo "<td rowspan='$sr_no_span'>" . $row['designation'] . "</td>";
                            echo "<td rowspan='$sr_no_span'>" . $row['division'] . "</td>";

                            // Training Needs should span all rows and combine three columns
                            $training_needs = [];
                            if (!empty($row['training_need_1'])) $training_needs[] = $row['training_need_1'];
                            if (!empty($row['training_need_2'])) $training_needs[] = $row['training_need_2'];
                            if (!empty($row['training_need_3'])) $training_needs[] = $row['training_need_3'];

                            echo "<td rowspan='$sr_no_span'>" . implode(', ', $training_needs) . "</td>";
                            $first_row = false;
                        }

                        // Year should be rowspan only when the year changes
                        if ($index == 0 || $row['year'] !== $records[$index - 1]['year']) {
                            $year_count = $year_counts[$row['year']];
                            echo "<td rowspan='$year_count'><strong>" . $row['year'] . "</strong></td>";
                        }

                        // Training details
                        echo "<td>" . $row['training_type'] . "</td>";
                        echo "<td>" . $row['course_name'] . "</td>";
                        echo "<td>" . $row['institute'] . "</td>";
                        echo "<td>" . $row['duration'] . "</td>";
                        echo "</tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='10'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let searchValue = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tniTable tr');

            let matchingEmployeeIds = new Set();

            // First pass: Identify matching employees
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                if (text.includes(searchValue)) {
                    let empId = row.getAttribute('data-emp-id'); 
                    if (empId) {
                        matchingEmployeeIds.add(empId);
                    }
                }
            });

            // Second pass: Show all records of matching employees
            rows.forEach(row => {
                let empId = row.getAttribute('data-emp-id');
                if (empId && matchingEmployeeIds.has(empId)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

<?php include('footer.php') ?>
