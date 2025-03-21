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
$emp_query = "SELECT name, designation, division, pin_no, dob, email, contact_no FROM employees WHERE id = '$employee_id' LIMIT 1";
$emp_result = mysqli_query($conn, $emp_query);
$employee = mysqli_fetch_assoc($emp_result);

if (!$employee) {
    die("Employee details not found.");
}
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<div class="container mt-5" style="margin-left: 150px; max-width: 800px;">
    <h2 class="text-center mb-4" style="font-weight: bold; color: #2c3e50;">My Profile</h2>
    <div class="card shadow-lg p-4" style="border-radius: 15px; background: linear-gradient(145deg, #ffffff, #f0f0f0); box-shadow: 5px 5px 15px #d1d1d1, -5px -5px 15px #ffffff;">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['name']); ?></p>
        <p><strong>Designation:</strong> <?php echo htmlspecialchars($employee['designation']); ?></p>
        <p><strong>Division:</strong> <?php echo htmlspecialchars($employee['division']); ?></p>
        <p><strong>PIN Number:</strong> <?php echo htmlspecialchars($employee['pin_no']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($employee['dob']); ?></p>
        <p><strong>Email (Drona Mail):</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($employee['contact_no']); ?></p>
    </div>
</div>

<?php include('footer.php') ?>
