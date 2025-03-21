<?php
session_start();
include('../db.php');

// Ensure employee is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employee') {
    header('Location: ../login.php');
    exit();
}

// Fetch employee details using employee_id
$employee_id = $_SESSION['employee_id'];
$emp_query = "SELECT name, email, designation, division FROM employees WHERE id = '$employee_id'";
$emp_result = mysqli_query($conn, $emp_query);
$employee = mysqli_fetch_assoc($emp_result);

// Fetching employee-specific statistics
$totalApplied = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM applications WHERE employee_id = '$employee_id'"))['total'];
$totalCompleted = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM applications WHERE employee_id = '$employee_id' AND status = 'completed'"))['total'];

// Check for new notifications
$notification_query = "SELECT COUNT(*) AS new_apps FROM applications WHERE employee_id = '$employee_id' AND status = 'pending'";
$notification_result = mysqli_fetch_assoc(mysqli_query($conn, $notification_query));
$new_notifications = $notification_result['new_apps'];
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Welcome, <?php echo htmlspecialchars($employee['name']); ?>!</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Employee</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Courses Applied</span>
                        <span class="info-box-number"><?php echo $totalApplied; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Courses Completed</span>
                        <span class="info-box-number"><?php echo $totalCompleted; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Details</h4>
                        <p><strong>Designation:</strong> <?php echo htmlspecialchars($employee['designation']); ?></p>
                        <p><strong>Division:</strong> <?php echo htmlspecialchars($employee['division']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>

                        <?php if ($new_notifications > 0): ?>
                            <p><strong>New Course Applications:</strong> <span class="badge bg-danger">Pending (<?php echo $new_notifications; ?>)</span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>
