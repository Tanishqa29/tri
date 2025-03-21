<?php
session_start();
include('../db.php'); // Database connection file

// Ensure employee is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employee') {
    header('Location:../login.php');
    exit();
}

// Fetch available courses
$query = "SELECT * FROM courses ORDER BY created_at DESC";
$courses = mysqli_query($conn, $query);
if (!$courses) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle course application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $employee_id = $_SESSION['employee_id'];

    // Check if already applied
    $check_query = "SELECT * FROM applications WHERE course_id = '$course_id' AND employee_id = '$employee_id'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) == 0) {
        $apply_query = "INSERT INTO applications (course_id, employee_id, status) VALUES ('$course_id', '$employee_id', 'pending')";
        mysqli_query($conn, $apply_query);
        $success_message = "Successfully applied for the course.";
    } else {
        $error_message = "You have already applied for this course.";
    }
}
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<div class="container-fluid p-4">
    <h2 class="mb-4">New Courses</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"> <?php echo $success_message; ?> </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"> <?php echo $error_message; ?> </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Sr. No.</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Training Type</th>
                    <th>Location</th>
                    <th>Duration</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($courses) > 0): ?>
                    <?php $count = 1; while ($row = mysqli_fetch_assoc($courses)): ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_description']); ?></td>
                            <td><?php echo htmlspecialchars($row['training_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['duration']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="apply" class="btn btn-success btn-sm">Apply</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No new courses available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php') ?>
