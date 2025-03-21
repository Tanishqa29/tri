<?php   
session_start();
include('../db.php');

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle Approve/Reject actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
  $action = $_POST['action'];

  // Fetch course_id of selected application
  $course_query = "SELECT course_id FROM applications WHERE id = '$application_id'";
  $course_result = mysqli_query($conn, $course_query);
  $course_row = mysqli_fetch_assoc($course_result);
  $course_id = $course_row['course_id'];

  if ($action == 'approve') {
      // Approve selected application
      mysqli_query($conn, "UPDATE applications SET status = 'approved' WHERE id = '$application_id'");

      // Reject all other applications for the same course
      mysqli_query($conn, "UPDATE applications SET status = 'rejected' WHERE id != '$application_id' AND course_id = '$course_id'");
  } elseif ($action == 'reject') {
      // Only reject the specific application
      mysqli_query($conn, "UPDATE applications SET status = 'rejected' WHERE id = '$application_id'");
  }

  header('Location: view_courses.php');
  exit();
}

// Search & Sorting
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch all distinct courses
$course_query = "SELECT id, course_name FROM courses WHERE course_name LIKE '%$search%' ORDER BY created_at DESC";
$course_result = mysqli_query($conn, $course_query);

?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<style>
    .container {
        max-width: 1300px;
        margin: 20px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1, h2 { text-align: center; margin-top: 20px; }
    .course-section { margin-bottom: 40px; }
    .table th { background-color: #7B53E9; color: white; }
    .search-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    .search-container input {
        width: 300px;
        padding: 8px;
        border: 2px solid #7B53E9;
        border-radius: 5px;
        outline: none;
    }
    .search-container button {
        margin-left: 10px;
        padding: 8px 16px;
        background: #7B53E9;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .search-container button:hover {
        background: #5A39C2;
    }
</style>

<div class="container mt-6">
    <h1>Manage Course Applications</h1>

    <!-- Search Bar -->
    <form method="GET" action="view_courses.php" class="search-container">
        <input type="text" name="search" placeholder="Search by name or course..." value="<?php echo htmlspecialchars($search); ?>" required>
        <button type="submit">Search</button>
        <a href="view_courses.php" class="btn btn-secondary">Reset</a>
    </form>

    <?php 
    $course_number = 1;
    while ($course = mysqli_fetch_assoc($course_result)): ?>
        <div class="course-section">
            <h2><?php echo $course_number++ . ". " . htmlspecialchars($course['course_name']); ?></h2>

            <div class="table-responsive-md">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Division</th>
                            <th>Year</th>
                            <th>Training Course Name</th>
                            <th>Institute</th>
                            <th>Training Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $course_id = $course['id'];
                        $application_query = "
                            SELECT a.id AS application_id, e.id AS emp_id, e.name, e.designation, e.division, 
                                   a.status, t.year, t.course_name AS training_course, 
                                   t.institute, t.duration AS training_duration
                            FROM applications a
                            JOIN employees e ON a.employee_id = e.id
                            LEFT JOIN training_records t ON a.employee_id = t.employee_id
                            WHERE a.course_id = '$course_id'
                            ORDER BY e.id, t.year DESC";
                        $result = mysqli_query($conn, $application_query);

                        $sr_no = 1;
                        $employee_records = [];
                        $rowspan_counts = [];

                        while ($row = mysqli_fetch_assoc($result)) {
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
                                echo "<tr>";

                                if ($first_row) {
                                    echo "<td rowspan='$sr_no_span'>" . $sr_no++ . "</td>";
                                    echo "<td rowspan='$sr_no_span'>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td rowspan='$sr_no_span'>" . htmlspecialchars($row['designation']) . "</td>";
                                    echo "<td rowspan='$sr_no_span'>" . htmlspecialchars($row['division']) . "</td>";
                                    $first_row = false;
                                }

                                // Group by Year with rowspan
                                if ($index == 0 || $row['year'] !== $records[$index - 1]['year']) {
                                    $year_count = $year_counts[$row['year']];
                                    echo "<td rowspan='$year_count'><strong>" . htmlspecialchars($row['year']) . "</strong></td>";
                                }

                                echo "<td>" . htmlspecialchars($row['training_course']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['institute']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['training_duration']) . "</td>";

                                // Status column
                                echo "<td>";
                                if ($row['status'] == 'approved') {
                                    echo "<span class='badge bg-success'>Approved</span>";
                                } elseif ($row['status'] == 'rejected') {
                                    echo "<span class='badge bg-danger'>Rejected</span>";
                                } else {
                                    echo "<span class='badge bg-warning'>Pending</span>";
                                }
                                echo "</td>";

                                // Actions (Approve/Reject)
                                echo "<td>";
                                if (isset($row['application_id']) && strtolower($row['status']) == 'pending') {
                                    echo "<form method='POST' style='display:flex; gap:5px;'>
                                            <input type='hidden' name='application_id' value='{$row['application_id']}'>
                                            <button type='submit' name='action' value='approve' class='btn btn-success btn-sm'>Approve</button>
                                            <button type='submit' name='action' value='reject' class='btn btn-danger btn-sm'>Reject</button>
                                          </form>";
                                } else {
                                    echo "<em>N/A</em>";
                                }
                                echo "</td>";
                                
                                echo "</tr>";
                            }
                        }

                        if (empty($employee_records)) {
                            echo "<tr><td colspan='10' class='text-center'>No applications found for this course.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include('footer.php'); ?>
