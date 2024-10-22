<?php
if (!isset($file_access)) die("Direct File Access Denied");

$me = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Enhanced Design -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #121212, #1f1f1f);
            color: #e0e0e0;
            overflow-x: hidden; /* Prevent horizontal overflow */
        }

        /* Background Animation */
        @keyframes backgroundAnimation {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }

        .animated-background {
            background: linear-gradient(135deg, rgba(0, 183, 255, 0.1), rgba(0, 183, 255, 0.05));
            animation: backgroundAnimation 10s ease infinite;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5em;
            color: #00b7ff; /* Bright blue for contrast */
            text-shadow: 0 0 10px rgba(0, 183, 255, 0.5);
        }

        /* Button Styles */
        .btn-custom {
            background-color: #1f1f1f; /* Dark button background */
            color: #00b7ff; /* Bright blue text */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        }

        .btn-custom:hover {
            background-color: #00b7ff; /* Light blue on hover */
            color: #121212; /* Dark text on light background */
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 183, 255, 0.5);
        }

        .btn-custom:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 183, 255, 0.8);
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 15px;
            text-align: center; /* Center text in table */
            border-bottom: 1px solid #333; /* Subtle border for rows */
        }

        table th {
            background-color: #1f1f1f; /* Darker header background */
            color: #00b7ff; /* Bright blue text */
        }

        .table-hover tbody tr:hover {
            background-color: white; /* Light blue hover effect */
            cursor: pointer; /* Pointer cursor on hover */
            transform: scale(1.02);
            transition: transform 0.2s;
        }

        /* Modal Styling */
        .modal-content {
            background-color: #1f1f1f; /* Dark modal background */
            color: #e0e0e0; /* Light text color */
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .modal-header {
            border-bottom: 2px solid #00b7ff; /* Bright blue border */
        }

        /* Input Styles */
        .form-control {
            background-color: #2c2c2c; /* Dark input background */
            color: #e0e0e0; /* Light text */
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            outline: none;
            background-color: #00b7ff; /* Light blue background on focus */
            color: #121212; /* Dark text */
            box-shadow: 0 0 5px rgba(0, 183, 255, 0.8);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em; /* Smaller font size on mobile */
            }

            .btn-custom {
                width: 100%; /* Full-width buttons on mobile */
            }

            table th, table td {
                font-size: 14px; /* Smaller font size for better fit */
            }
        }
    </style>
</head>
<body>
    <div class="animated-background"></div> <!-- Background Animation -->

    <div class="content py-5">
        <div class="container">
            <div class="header">
                <h1>Book Tickets</h1>
                <button class="btn btn-custom" onclick="logout()">Logout</button>
            </div>
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <table id="example1" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th>Date/Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row = querySchedule('future');
                            if ($row->num_rows < 1) {
                                echo "<tr><td colspan='5' class='text-center'>Sorry, there are no schedules at the moment! Please visit after some time.</td></tr>";
                            } else {
                                $sn = 0;
                                while ($fetch = $row->fetch_assoc()) {
                                    $db_date = $fetch['date'];
                                    if ($db_date == date('d-m-Y')) {
                                        $db_time = $fetch['time'];
                                        $current_time = date('H:i');
                                        if ($current_time >= $db_time) {
                                            continue;
                                        }
                                    }
                                    $id = $fetch['id']; ?>
                                    <tr>
                                        <td><?php echo ++$sn; ?></td>
                                        <td><?php echo getRoutePath($fetch['route_id']); ?></td>
                                        <td><?php
                                            $array = getTotalBookByType($id);
                                            echo ($max_first = ($array['first'] - $array['first_booked'])), " Seat(s) Available for First Class" . "<hr/>" . ($max_second = ($array['second'] - $array['second_booked'])) . " Seat(s) Available for Second Class";
                                            ?></td>
                                        <td><?php echo $fetch['date'], " / ", formatTime($fetch['time']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#book<?php echo $id ?>">
                                                <i class="fas fa-ticket-alt"></i> Book
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal for booking form -->
                                    <div class="modal fade" id="book<?php echo $id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Book For <?php echo getRoutePath($fetch['route_id']); ?></h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to send booking data to preview.php -->
                                                    <form action="dist/preview.php" method="post">
                                                        <input type="hidden" class="form-control" name="id" value="<?php echo $id ?>" required>
                                                        <p>Number of Tickets:
                                                            <input type="number" min='1' value="1"
                                                                   max='<?php echo $max_first >= $max_second ? $max_first : $max_second ?>'
                                                                   name="number" class="form-control" required>
                                                        </p>
                                                        <p>
                                                            Class: <select name="class" required class="form-control">
                                                                <option value="">-- Select Class --</option>
                                                                <option value="first">First Class (₹<?php echo ($fetch['first_fee']); ?>)</option>
                                                                <option value="second">Second Class (₹<?php echo ($fetch['second_fee']); ?>)</option>
                                                            </select>
                                                        </p>
                                                        <input type="submit" name="submit" class="btn btn-custom" value="Proceed">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            // Show loader during logout process
            const logoutButton = document.querySelector('.btn.btn-custom');
            logoutButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging Out...';
            // Implement the logout functionality here
            // For example, redirect to the logout page:
            // window.location.href = 'logout.php';
        }
    </script>
</body>
</html>