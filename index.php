<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACEM, Nashik</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Light blue background */
        body {
            background-color: #d0e8ff; /* Light blue */
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            color: #000;
        }

        /* Navbar styling */
        .navbar {
            background: rgba(0, 0, 0, 0.7);
        }

        /* Centered content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* ACEM Logo */
        .acem-logo {
            width: 80px; /* Adjust logo size */
            height: auto;
            margin-right: 10px;
        }

        /* Footer */
        .footer {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="DRDO_Seal.png" alt="ACEM Logo" class="acem-logo"> <!-- Replace with your logo file -->
                <span class="fw-bold">ACEM, Nashik</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="display-1 fw-bold d-flex align-items-center">
            <!-- Replace with your logo file -->
            Advanced Centre for Energetic Materials (ACEM), Nashik
        </h1>
        <p class="lead">Welcome to ACEM, Nashik – A Place of Excellence in Engineering & Management.</p>
    </div>

    <!-- Footer -->
    <footer class="footer text-center">
        <p>Email: contact@acemnashik.edu.in | Phone: +91 12345 67890 | Address: Nashik, Maharashtra, India</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
