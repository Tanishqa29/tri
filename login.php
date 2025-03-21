<?php
session_start();
include('db.php'); // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

    if (!empty($email) && !empty($password) && !empty($role)) {
        // Fetch user based on email
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = '$role' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'employee') {
                // Fetch corresponding employee details
                $emp_query = "SELECT id FROM employees WHERE user_id = '" . $user['id'] . "' LIMIT 1";
                $emp_result = mysqli_query($conn, $emp_query);

                if ($emp_result && mysqli_num_rows($emp_result) == 1) {
                    $employee = mysqli_fetch_assoc($emp_result);
                    $_SESSION['employee_id'] = $employee['id'];
                } else {
                    error_log("Employee not found for user_id: " . $user['id']);
                    $error = "Employee details not found.";
                }
            }

            if ($user['role'] == 'admin') {
                header('Location: ./adminLTE/dashboard.php');
                exit();
            } elseif ($user['role'] == 'employee') {
                header('Location: ./employee/dashboard.php');
                exit();
            }
        } else {
            $error = "Invalid Email, Password, or Role";
        }
    } else {
        $error = "All fields are required";
    }
}
?>

<?php include('header.php'); ?>

<section class="bg-light vh-100 d-flex">
    <div class="col-6 col-sm-8 col-md-6 col-lg-4 m-auto">
        <div class="card">
            <div class="card-body">
                <div class="border rounded-circle mx-auto d-flex" style="width:90px;height:90px">
                    <i class="fa fa-user text-dark fa-3x m-auto"></i>
                </div>
                <form action="" method="POST">
                    <h2 class="text-center mt-3">ECMS LOGIN</h2>
                    <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
                    <div class="md-form">
                        <label for="email">Enter Your Drona Mail (Email)</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="example@domain.com" required>
                    </div>
                    <div class="md-form mt-3">
                        <label for="password">Your Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mt-3">
                        <label for="role">Select Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-secondary" name="login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>
