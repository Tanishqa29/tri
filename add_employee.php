<?php  
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $division = trim($_POST['division']);
    $pin_no = trim($_POST['pin_no']);
    $dob = trim($_POST['dob']);
    $email = trim($_POST['email']);
    $contact_no = trim($_POST['contact_no']);

    // Training Needs (Ensuring they exist before inserting)
    $training_need_1 = isset($_POST['training_need_1']) ? trim($_POST['training_need_1']) : null;
    $training_need_2 = isset($_POST['training_need_2']) ? trim($_POST['training_need_2']) : null;
    $training_need_3 = isset($_POST['training_need_3']) ? trim($_POST['training_need_3']) : null;

    // Check if PIN No already exists
    $check_query = "SELECT pin_no FROM employees WHERE pin_no = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $pin_no);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<p class='error'>Error: PIN No already exists!</p>";
    } else {
        // Insert into employees table
        $query = "INSERT INTO employees (name, designation, division, pin_no, dob, email, contact_no) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $name, $designation, $division, $pin_no, $dob, $email, $contact_no);

        if ($stmt->execute()) {
            $employee_id = $stmt->insert_id;

            // Insert Training History for multiple years
            $training_query = "INSERT INTO training_records (employee_id, year, training_type, course_name, institute, duration) VALUES (?, ?, ?, ?, ?, ?)";
            $training_stmt = $conn->prepare($training_query);

            $years = ['2024', '2023', '2022'];
            foreach ($years as $year) {
                if (isset($_POST["course_name_{$year}"])) {
                    foreach ($_POST["course_name_{$year}"] as $index => $course_name) {
                        $training_type = $_POST["training_type_{$year}"][$index];
                        $institute = $_POST["institute_{$year}"][$index];
                        $duration = $_POST["duration_{$year}"][$index];

                        $training_stmt->bind_param("isssss", $employee_id, $year, $training_type, $course_name, $institute, $duration);
                        $training_stmt->execute();
                    }
                }
            }

            // Insert Training Needs only if at least one is filled
            if ($training_need_1 || $training_need_2 || $training_need_3) {
                $tni_query = "INSERT INTO tni (employee_id, training_need_1, training_need_2, training_need_3) 
                              VALUES (?, ?, ?, ?)";
                $tni_stmt = $conn->prepare($tni_query);
                $tni_stmt->bind_param("isss", $employee_id, $training_need_1, $training_need_2, $training_need_3);
                $tni_stmt->execute();
            }

            echo "<p class='success'>Employee added successfully!</p>";
            echo "<script>alert('New employee details are added');</script>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>


<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2, h4 {
            text-align: center;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-row {
            display: flex;
            gap: 20px;
        }
        .form-row .form-group {
            flex: 1;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        /* Training History Styling */
.training-container {
    margin-bottom: 20px;
}

h5 {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
}

.training-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    display: none; /* Initially Hidden */
}

.training-table th, .training-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.training-table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.training-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.training-table input {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.add-row, .remove-row {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.remove-row {
    background-color: #dc3545;
}

.add-row:hover {
    background-color: #218838;
}

.remove-row:hover {
    background-color: #c82333;
}

    </style>
    <!-- JavaScript for Dynamic Rows -->
        <script>
           document.addEventListener("DOMContentLoaded", function () {
    // Expand/Collapse Training Year Sections
    document.querySelectorAll("h5").forEach(title => {
        title.addEventListener("click", function () {
            let table = this.nextElementSibling;
            table.style.display = (table.style.display === "none" || table.style.display === "") ? "table" : "none";
        });
    });

    // Event delegation for dynamically added rows
    document.body.addEventListener("click", function (event) {
        if (event.target.classList.contains("add-row")) {
            let year = event.target.getAttribute("data-year");
            let table = document.getElementById("training-" + year);
            let newRow = document.createElement("tr");

            newRow.innerHTML = `
                <td><input type="text" name="training_type_${year}[]"></td>
                <td><input type="text" name="course_name_${year}[]"></td>
                <td><input type="text" name="institute_${year}[]"></td>
                <td><input type="text" name="duration_${year}[]"></td>
                <td><button type="button" class="remove-row">X</button></td>
            `;

            table.appendChild(newRow);
        }

        if (event.target.classList.contains("remove-row")) {
            event.target.closest("tr").remove();
        }
    });
});

        </script>
</head>
<body>
    <div class="container">
        <h2>Add New Employee</h2>
        <form action="" method="POST">
            
            <!-- Basic Information -->
            <h4>Basic Information</h4>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Designation:</label><input type="text" name="designation" required></div>
                <div class="form-group"><label>Division:</label><input type="text" name="division" required></div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>PIN No:</label><input type="text" name="pin_no" required></div>
                <div class="form-group"><label>Email:</label><input type="email" name="email" required></div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Date of Birth:</label><input type="date" name="dob" required></div>
                <div class="form-group"><label>Contact No:</label><input type="text" name="contact_no" required></div>
            </div>

            <h4>Training History (Last 3 Years)</h4>

<!-- Training History Container -->
<div class="training-container">

    <!-- 2024 Section -->
    <h5>2024</h5>
    <table id="training-2024" class="training-table">
        <tr>
            <th>Training Type</th>
            <th>Course Name</th>
            <th>Institute</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>
        <tr>
            <td><input type="text" name="training_type_2024[]"></td>
            <td><input type="text" name="course_name_2024[]"></td>
            <td><input type="text" name="institute_2024[]"></td>
            <td><input type="text" name="duration_2024[]"></td>
            <td><button type="button" class="add-row" data-year="2024">+</button></td>
        </tr>
    </table>

    <!-- 2023 Section -->
    <h5>2023</h5>
    <table id="training-2023" class="training-table">
        <tr>
            <th>Training Type</th>
            <th>Course Name</th>
            <th>Institute</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>
        <tr>
            <td><input type="text" name="training_type_2023[]"></td>
            <td><input type="text" name="course_name_2023[]"></td>
            <td><input type="text" name="institute_2023[]"></td>
            <td><input type="text" name="duration_2023[]"></td>
            <td><button type="button" class="add-row" data-year="2023">+</button></td>
        </tr>
    </table>

    <!-- 2022 Section -->
    <h5>2022</h5>
    <table id="training-2022" class="training-table">
        <tr>
            <th>Training Type</th>
            <th>Course Name</th>
            <th>Institute</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>
        <tr>
            <td><input type="text" name="training_type_2022[]"></td>
            <td><input type="text" name="course_name_2022[]"></td>
            <td><input type="text" name="institute_2022[]"></td>
            <td><input type="text" name="duration_2022[]"></td>
            <td><button type="button" class="add-row" data-year="2022">+</button></td>
        </tr>
    </table>

</div>


<h4>Training Needs</h4>
<input type="text" name="training_need_1" placeholder="Training Need 1">
<input type="text" name="training_need_2" placeholder="Training Need 2">
<input type="text" name="training_need_3" placeholder="Training Need 3">

            <button type="submit">Add Employee</button>
        </form>
    </div>
</body>
</html>

<?php include('footer.php') ?>
