<?php
@session_start();
$file_access = true;
include '../conn.php';
include 'admin_session.php';
include '../constants.php';
if (@$_GET['page'] == 'print' && isset($_GET['code'])) {
    printClearance($_GET['code']);
}
if (@$_GET['page'] == 'report' && isset($_GET['id'])) {
    printReport($_GET['id']);
}

$fullname = "System Administrator";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <title><?php echo SITE_NAME, ' - ', ucwords($_SESSION['category']); ?></title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        /* Custom styles for loading spinner */
        #loadingScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Sidebar styles */
        .main-sidebar {
            background-color: #212529; /* Darker background for the sidebar */
        }
        

        .nav-link.active {
            background-color: #212529; /* Active link color */
        }

        .nav-link {
            transition: background-color 0.3s, transform 0.2s; /* Smooth transition */
        }

        .nav-link:hover {
            background-color: blue; /* Blue hover color */
            transform: scale(1.05); /* Scale effect on hover */
            color: blue; /* Text color on hover */
        }

        /* Footer styles */
        .main-footer {
            background-color: #212529; /* Black footer background */
            color: #fff; /* White text color for the footer */
        }

        /* Content Wrapper Styles */
        .content-wrapper {
            background-color: #212529; /* Dark background for content area */
            color: #fff; /* White text color for content */
        }

        /* Header styles */
        .content-header {
            background-color: #212529; /* Dark background for header */
            color: #fff; /* White text color for header */
        }

        h1.m-0 {
            color: #fff; /* White color for header text */
        }
    </style>
</head>
<body class="hold-transition sidebar-mini" style="background-color: #212529;">
    <div class="wrapper">

        <!-- Navbar -->
        <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark" style="background-color: #343a40;">
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

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-success elevation-4">
            <a href="admin.php" class="brand-link">
                <span class="brand-text font-weight-light"><?php echo date("D d, M y"); ?></span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="images/trainlg.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Admin</a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="admin.php" class="nav-link <?php if (!isset($_GET['page'])) echo 'active'; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=users" class="nav-link <?php echo (@$_GET['page'] == 'users') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=dynamic" class="nav-link <?php echo (@$_GET['page'] == 'dynamic') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-calendar-day"></i>
                                <p>Schedules</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=route" class="nav-link <?php echo (@$_GET['page'] == 'route') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-route"></i>
                                <p>Routes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=train" class="nav-link <?php echo (@$_GET['page'] == 'train') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-train"></i>
                                <p>Buses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=report" class="nav-link <?php echo (@$_GET['page'] == 'report') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-file-pdf"></i>
                                <p>Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=feedback" class="nav-link <?php echo (@$_GET['page'] == 'feedback') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-mail-bulk"></i>
                                <p>Feedbacks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            
                        </li>
                        <li class="nav-item">
                            <a href="admin.php?page=logout" class="nav-link" onclick="showLoading()">
                                <i class="nav-icon fas fa-power-off"></i>
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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        <h1 class="m-0" style="color: yellow;">Administrator Dashboard</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <?php
            if (!isset($_GET['page']))
                include 'admin/index.php';
            elseif ($_GET['page'] == 'dynamic')
                include 'admin/dynamic_schedule.php';
            elseif ($_GET['page'] == 'report')
                include 'admin/report.php';
            elseif ($_GET['page'] == 'train')
                include 'admin/train.php';
            elseif ($_GET['page'] == 'users')
                include 'admin/users.php';
            elseif ($_GET['page'] == 'route')
                include 'admin/route.php';
            elseif ($_GET['page'] == 'logout') {
                @session_destroy();
                echo "<script>alert('You are being logged out'); window.location='../';</script>";
                exit;
            } elseif ($_GET['page'] == 'payment')
                include 'admin/sales.php';
            elseif ($_GET['page'] == 'feedback')
                include 'admin/feedback.php';
            elseif ($_GET['page'] == 'search')
                include 'admin/search.php';
            else
                include 'admin/index.php';
            ?>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo SITE_NAME; ?></a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.1.0
            </div>
        </footer>

        <!-- Loading Screen -->
        <div id="loadingScreen">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <script>
        function showLoading() {
            document.getElementById('loadingScreen').style.display = 'flex'; // Show loading screen
            setTimeout(function() {
                window.location.href = 'admin.php?page=logout'; // Redirect to logout
            }, 2000); // Adjust time as needed
        }
    </script>
</body>
</html>
