<?php
include '../db.php'; // Include database connection

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the employee's name from the database
    $query = "SELECT name FROM employees WHERE user_id = $user_id LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $employee_name = $row['name']; // Store name
    } else {
        $employee_name = "Unknown User"; // Default if not found
    }
} else {
    $employee_name = "Guest"; // If session not set
}
?>

<?include ('header.php') ?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../index.php" class="nav-link">Home</a>
        </li>
        
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3 position-relative" method="GET" action="">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" name="query" id="searchBox"
                placeholder="Search Employee..." aria-label="Search"
                value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div> -->

        <!-- Search Results Dropdown (Hidden by Default) -->
        <div id="searchResults" class="list-group position-absolute bg-white shadow-sm rounded w-100" style="top: 38px; display: none; z-index: 1050;">
            <?php if (!empty($search_results)): ?>
                <?php foreach ($search_results as $result): ?>
                    <a href="employee_profile.php?id=<?php echo $result['id']; ?>" class="list-group-item list-group-item-action">
                        <?php echo htmlspecialchars($result['name']) . " (" . htmlspecialchars($result['designation']) . ")"; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fas fa-user-circle"></i> <!-- User Icon -->
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item-text font-weight-bold">
                    <?= $employee_name; ?> <!-- Display Logged-in Employee Name -->
                </span>
                <div class="dropdown-divider"></div>
                <a href="profile.php" class="dropdown-item">
                    <i class="fas fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- JavaScript for Search Dropdown -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchBox = document.getElementById("searchBox");
        const searchResults = document.getElementById("searchResults");

        searchBox.addEventListener("input", function () {
            if (this.value.length > 1) {
                searchResults.style.display = "block"; // Show results
            } else {
                searchResults.style.display = "none"; // Hide results
            }
        });

        document.addEventListener("click", function (e) {
            if (!searchBox.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = "none"; // Hide results when clicking outside
            }
        });
    });
</script>


  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">
                <i class="nav-icon fas fa-user"></i> <?php echo $employee_name; ?>
            </span>
        </a>


    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="dashboard.php" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p> Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="./my_profile.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Profile </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="./my_courses.php" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>My Courses </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="./new_course.php" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>New Courses </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="./my_tni.php" class="nav-link">
              <i class="nav-icon fas fa-clipboard"></i>
              <p>My TNI </p>
            </a>
          </li>
          <li class="nav-item has-treeview menu-open">
            <a href="../index.php" class="nav-link ">
              <i class="nav-icon fas fa-check"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
