<?php
@session_start();
$file_access = true;
include '../conn.php';
include 'session.php';
include '../constants.php';

if (@$_GET['page'] == 'print' && isset($_GET['print'])) printClearance($_GET['print']);

$fullname = getIndividualName($_SESSION['user_id'], $conn);
if (isset($_GET['error'])) {
    echo "<script>alert('Payment could not be initialized! Network Error!'); window.location = 'individual.php?page=reg';</script>";
    exit;
}
$page = @$_GET['page'] ?: 'default'; // Default page

// Include page content based on the selected page
switch ($page) {
    case 'reg':
        $contentFile = 'individual/reg.php'; // Include your new booking page
        break;
    case 'paid':
        $contentFile = 'individual/paid.php'; // Include your view bookings page
        break;
    case 'feedback':
        $contentFile = 'individual/feedback.php'; // Include your feedback page
        break;
    case 'logout':
        // Handle logout logic here if needed
        session_destroy();
        header('Location: signin.php'); // Redirect to login page after logout
        exit;
    default:
        $contentFile = 'individual/index.php'; // Include your default content or dashboard
        break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo SITE_NAME, ' - Passenger\'s Account' ?></title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <!-- Custom CSS for enhancements -->
    <style>
        body {
            background-color: #1d1f21;
            color: #f8f9fa;
            transition: background-color 0.5s ease;
        }

        .main-header.navbar {
            background: linear-gradient(90deg, #1d1f21, #333);
            border-bottom: 1px solid #555;
            transition: background-color 0.5s ease;
        }

        .main-sidebar {
            background: #1d1f21;
            transition: background-color 0.5s ease;
        }

        .nav-link {
            color: #f8f9fa;
            transition: background-color 0.3s ease, transform 0.3s ease;
            padding: 15px 20px;
            border-radius: 8px;
        }

        .nav-link:hover {
            background-color: #555;
            transform: translateY(-3px);
        }

        .brand-link,
        .brand-text {
            color: #f8f9fa !important;
            transition: color 0.3s ease;
        }

        .brand-link:hover {
            color: #ffc107;
        }

        .sidebar .user-panel img {
            border-radius: 50%;
            border: 2px solid #f8f9fa;
            transition: transform 0.3s ease;
        }

        .sidebar .user-panel img:hover {
            transform: scale(1.1);
        }

        .sidebar .nav-icon {
            animation: iconBounce 1s infinite alternate;
        }

        @keyframes iconBounce {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-5px);
            }
        }

        .content-wrapper {
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            transition: background-color 0.5s ease;
        }

        h1 {
            color: #f8f9fa;
            transition: color 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .btn,
        .nav-link {
            border-radius: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:hover,
        .nav-link:hover {
            transform: scale(1.05);
        }

        .loader {
            position: fixed;
            z-index: 1000;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.7);
            visibility: hidden;
            transition: visibility 0.3s ease;
        }

        .loader .spinner-border {
            width: 5rem;
            height: 5rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-active .loader {
            visibility: visible;
        }

        /* Red logout animation */
        .logout-animation {
            position: fixed;
            z-index: 1001;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 2rem;
            transition: opacity 0.5s ease;
        }

        .logout-active {
            display: flex;
            opacity: 1;
        }

        /* Hover Effects for buttons */
        .btn:hover::before {
            transform: scale(1.5);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            transition: transform 0.4s ease;
            z-index: -1;
            transform: scale(0);
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">

    <div class="loader">
        <div class="spinner-border text-light"></div>
    </div>

    <!-- Red Logout Animation -->
    <div class="logout-animation" id="logoutAnimation">
        <div class="spinner-border text-light" style="width: 5rem; height: 5rem;"></div>
    </div>

    <div class="wrapper loading-container">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="navbar-nav">
                    <a class="nav-link" href="#"><?php echo SITE_NAME ?></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="individual.php" class="brand-link">
                <span class="brand-text font-weight-light"><?php echo date("D d, M y"); ?></span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                    <img src="pfp.webp" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $fullname; ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="individual.php" class="nav-link <?php echo ($page == 'default') ? 'active' : '';?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>My Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="individual.php?page=reg" class="nav-link <?php echo ($page == 'reg') ? 'active' : '';?>">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>New Booking</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="individual.php?page=paid" class="nav-link <?php echo ($page == 'paid') ? 'active' : '';?>">
                                <i class="fa fa-book nav-icon"></i>
                                <p>View Bookings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="individual.php?page=feedback" class="nav-link <?php echo ($page == 'feedback') ? 'active' : '';?>">
                                <i class="fa fa-comments nav-icon"></i>
                                <p>Feedback</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="logoutBtn">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php include $contentFile; // Include dynamic content here ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="background-color: #1d1f21; color: white;">
    <div class="footer-content">
        <div class="footer-left">
            <strong>&copy; <?php echo date("Y"); ?> <a href="#" class="footer-link" style="color: white;"><?php echo SITE_NAME; ?></a>.</strong> All rights reserved.
        </div>
        <div class="footer-right">
            <a href="#" class="footer-link" style="color: white;">Privacy Policy</a> | <a href="#" class="footer-link" style="color: white;">Terms of Service</a>
        </div>
    </div>
</footer>


    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Custom JavaScript for handling logout -->
    <script>
        $(document).ready(function () {
            $("#logoutBtn").click(function (e) {
                e.preventDefault();
                // Show the logout animation
                $("#logoutAnimation").addClass("logout-active");
                $(".loader").addClass("loading-active"); // Show the loader

                // Perform logout operation (e.g., redirecting after a delay)
                setTimeout(function () {
                    window.location.href = 'individual.php?page=logout';
                }, 2000); // Adjust delay as needed
            });
        });
    </script>
</body>

</html>
