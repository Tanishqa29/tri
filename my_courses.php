<?php
session_start();
include('../db.php');

// Ensure employee is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employee') {
    header('Location: ../login.php');
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch employee details
$emp_query = "SELECT name FROM employees WHERE id = '$employee_id'";
$emp_result = mysqli_query($conn, $emp_query);
$employee = mysqli_fetch_assoc($emp_result);

// Fetch Training History (Last 3 Years)
$training_query = "
    SELECT year, training_type, course_name, duration, institute
    FROM training_records 
    WHERE employee_id = '$employee_id' 
    ORDER BY year DESC 
    LIMIT 3";
$training_result = mysqli_query($conn, $training_query);

// Fetch Course Applications
$app_query = "
    SELECT c.course_name, c.course_description, c.duration, c.training_type, c.location, a.status
    FROM applications a
    JOIN courses c ON a.course_id = c.id
    WHERE a.employee_id = '$employee_id'
    ORDER BY a.status DESC, c.duration ASC";  // Replaced c.start_date with c.duration
$app_result = mysqli_query($conn, $app_query);

// Check for new notifications
$notification_query = "SELECT COUNT(*) AS new_apps FROM applications WHERE employee_id = '$employee_id' AND status = 'pending'";
$notification_result = mysqli_fetch_assoc(mysqli_query($conn, $notification_query));
$new_notifications = $notification_result['new_apps'];
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<style>
  h2 {
    text-align: center;
  }
</style>

<div class="container mt-4">
    <h2>Courses Details 
        <?php if ($new_notifications > 0): ?>
            <span class="badge bg-danger" style="font-size: 0.8rem;">New (<?php echo $new_notifications; ?>)</span>
        <?php endif; ?>
    </h2>

   <!-- Training History Table -->
<h4 class="mt-4">My Training History</h4>
<div class="table-responsive">
    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Year</th>
                <th>Training Type</th>
                <th>Course Name</th>
                <th>Duration</th>
                <th>Institute</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($training_result) > 0) {
                $training_data = [];
                while ($row = mysqli_fetch_assoc($training_result)) {
                    $training_data[$row['year']][] = $row;
                }

                foreach ($training_data as $year => $trainings) {
                    $rowspan = count($trainings);
                    $first = true;

                    foreach ($trainings as $training) {
                        echo "<tr>";
                        if ($first) {
                            echo "<td rowspan='{$rowspan}'><b>" . htmlspecialchars($year) . "</b></td>";
                            $first = false;
                        }
                        echo "<td>" . htmlspecialchars($training['training_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($training['course_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($training['duration']) . "</td>";
                        echo "<td>" . htmlspecialchars($training['institute']) . "</td>";
                        echo "</tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='5'>No training history available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


    <!-- Course Applications Table -->
    <h4 class="mt-4">My Course Applications</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Training type</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($app_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($app_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_description']); ?></td>
                            <td><?php echo htmlspecialchars($row['duration']); ?></td>
                            <td><?php echo htmlspecialchars($row['training_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php elseif ($row['status'] == 'rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No course applications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>
