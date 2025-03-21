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
    <!-- <form class="form-inline mx-auto" method="GET" action="search_results.php">
        <div class="input-group">
            <input class="form-control border-right-0" type="search" name="query" placeholder="Search..." aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fas fa-user-circle"></i> <!-- User Icon -->
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item-text font-weight-bold">
                    Admin <!-- Display Logged-in Employee Name -->
                </span>
                <div class="dropdown-divider"></div>
                <a href="../index.php" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->


  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin</span>
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
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Employees<i class="fas fa-angle-left right"></i> </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./add_employee.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create new employee</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./all_employees.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View All employees</p>
                </a>
              </li>
        </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>Courses<i class="fas fa-angle-left right"></i> </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./create_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create new course</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./view_courses.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View All Courses</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview menu-open">
            <a href="tni.php" class="nav-link ">
              <i class="nav-icon fas fa-clipboard"></i>
              <p>TNI </p>
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
