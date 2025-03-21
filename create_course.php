<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $training_type = trim($_POST['training_type']);
    $course_name = trim($_POST['course_name']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);
    $location = trim($_POST['location']);
    

    // Validate input
    if (empty($training_type) || empty($course_name) || empty($description) || empty($location) || empty($duration)) {
        $error_message = "All fields are required!";
    } else {
        // Prepare SQL query to prevent SQL injection
        $sql = "INSERT INTO courses (training_type, course_name, course_description, location, duration) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $training_type, $course_name, $description, $location, $duration);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Course created successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
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
    <title>Create New Course</title>
    <style>
        /* Container style */
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form style */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, textarea, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Styling for date inputs */
        input[type="date"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Alert messages */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Create New Course</h2>

    <!-- Display error or success message -->
    <?php if (isset($error_message)): ?>
        <div class="alert error"><?= $error_message ?></div>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <div class="alert success"><?= $success_message ?></div>
    <?php endif; ?>

    <!-- Course creation form -->
    <form method="POST" action="create_course.php">
    <!-- <label for="training_type">Training Type</label>
        <select id="training_type" name="training_type" required>
            <option value="" disabled selected>Select training type</option>
            <option value="Online">Online</option>
            <option value="Offline">Offline</option>
            <option value="Hybrid">Hybrid</option>
        </select> -->

        <label for="training_type">Training Type</label>
        <input type="text" id="training_type" name="training_type" placeholder="Training type" required>

        <label for="course_name">Course Name</label>
        <input type="text" id="course_name" name="course_name" placeholder="Enter course name" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" placeholder="Enter course description" required></textarea>

        <label for="location">Location</label>
        <input type="text" id="location" name="location" placeholder="Enter course location" required>

        <label for="duration">Duration </label>
        <input type="text" id="duration" name="duration" placeholder="Enter duration (11.11.2024 - 13.11.2024)" required>

        <button type="submit">Create Course</button>
    </form>
</div>

</body>
</html>

<?php include('footer.php') ?>
