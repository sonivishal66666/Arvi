<?php
if (!isset($file_access)) die("Direct File Access Denied");
$source = 'report';
$me = "?page=$source";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Ticketing System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            background-color: #1e1e2f;
            color: #ffffff;
            font-family: 'YourChosenFont', sans-serif; /* Replace with your chosen font */
        }

        /* Container Styles */
        .container-fluid {
            padding: 20px;
        }

        /* Card Styles */
        .card {
            background-color: #2c2c3e; /* Dark background for the card */
            border: none; /* Remove default border */
            border-radius: 10px;
            margin-bottom: 20px; /* Spacing between cards */
            transition: transform 0.3s; /* Animation on hover */
        }

        .card:hover {
            transform: scale(1.05); /* Scale effect on hover */
        }

        .card-header {
            background-color: #3a3a4e; /* Darker header background */
            font-weight: bold;
        }

        /* Button Styles */
        .btn {
            background-color: #4caf50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #5cb85c;
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal-content {
            background-color: #2c2c3e;
            border-radius: 10px;
        }

        .modal-header,
        .modal-body {
            border: none;
        }

        /* Dynamic Background Animation */
        @keyframes gradient {
            0% { background-color: #1e1e2f; }
            50% { background-color: #2c2c3e; }
            100% { background-color: #1e1e2f; }
        }

        body {
            animation: gradient 10s ease infinite;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .btn {
                width: 100%; /* Make buttons full width on smaller screens */
            }
        }
    </style>
</head>
<body>

<div class="content">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mb-4">All Schedules</h3>
                    <div class="row">
                        <?php
                        $row = $conn->query("SELECT * FROM schedule ORDER BY id DESC");
                        if ($row->num_rows < 1) echo "<p>No Records Yet</p>";
                        while ($fetch = $row->fetch_assoc()) {
                            $id = $fetch['id'];
                        ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Schedule #<?php echo $id; ?>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo getTrainName($fetch['train_id']); ?></h5>
                                    <p class="card-text">Route: <?php echo getRoutePath($fetch['route_id']); ?></p>
                                    <p class="card-text">Date: <?php echo $fetch['date']; ?></p>
                                    <p class="card-text">Time: <?php echo formatTime($fetch['time']); ?></p>
                                    <p class="card-text">First Class Charge: <?php echo $fetch['first_fee']; ?></p>
                                    <p class="card-text">Second Class Charge: <?php echo $fetch['second_fee']; ?></p>
                                    <a href="admin.php?page=report&id=<?php echo $id; ?>" class="btn btn-success">View</a>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add New Schedule Modal -->
<div class="modal fade" id="add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" align="center">
            <div class="modal-header">
                <h4 class="modal-title">Add New Schedule &#128649;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            Train : <select class="form-control" name="train_id" required>
                                <option value="">Select Train</option>
                                <?php
                                $con = connect()->query("SELECT * FROM train");
                                while ($row = $con->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            Route : <select class="form-control" name="route_id" required>
                                <option value="">Select Route</option>
                                <?php
                                $con = connect()->query("SELECT * FROM route");
                                while ($row = $con->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . getRoutePath($row['id']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            First Class Charge : <input class="form-control" type="number" name="first_fee" required>
                        </div>
                        <div class="col-sm-6">
                            Second Class Charge : <input class="form-control" type="number" name="second_fee" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            Date : <input class="form-control" onchange="check(this.value)" type="date" name="date" required>
                        </div>
                        <div class="col-sm-6">
                            Time : <input class="form-control" type="time" name="time" required>
                        </div>
                    </div>
                    <hr>
                    <input type="submit" name="submit" class="btn btn-success" value="Add Schedule">
                </form>

                <script>
                function check(val) {
                    val = new Date(val);
                    var age = (Date.now() - val) / 31557600000;
                    if (age > 0) {
                        alert("Past/Current Date not allowed");
                        document.querySelector('input[name="date"]').value = "";
                        return false;
                    }
                }
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Your existing PHP code for handling form submissions and database interactions remains unchanged here.

if (isset($_POST['submit'])) {
    $route_id = $_POST['route_id'];
    $train_id = $_POST['train_id'];
    $first_fee = $_POST['first_fee'];
    $second_fee = $_POST['second_fee'];
    $date = $_POST['date'];
    $date = formatDate($date);
    $time = $_POST['time'];
    if (!isset($route_id, $train_id, $first_fee, $second_fee, $date, $time)) {
        alert("Fill Form Properly!");
    } else {
        $conn = connect();
        $ins = $conn->prepare("INSERT INTO `schedule`(`train_id`, `route_id`, `date`, `time`, `first_fee`, `second_fee`) VALUES (?,?,?,?,?,?)");
        $ins->bind_param("iissii", $train_id, $route_id, $date, $time, $first_fee, $second_fee);
        $ins->execute();
        alert("Schedule Added!");
        load($_SERVER['PHP_SELF'] . "$me");
    }
}

if (isset($_POST['submit2'])) {
    $route_id = $_POST['route_id'];
    $train_id = $_POST['train_id'];
    $first_fee = $_POST['first_fee'];
    $second_fee = $_POST['second_fee'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $every = $_POST['every'];
    $time = $_POST['time'];
    if (!isset($route_id, $train_id, $first_fee, $second_fee, $date, $time)) {
        alert("Fill Form Properly!");
    } else {
        $from_date = formatDate($from_date);
        $to_date = formatDate($to_date);
        $startDate = $from_date;
        $endDate = $to_date;
        $conn = connect();
        if ($every == 'Day') {
            for ($i = strtotime($startDate); $i <= strtotime($endDate); $i = strtotime('+1 day', $i)) {
                $date = date('d-m-Y', $i);
                $ins = $conn->prepare("INSERT INTO `schedule`(`train_id`, `route_id`, `date`, `time`, `first_fee`, `second_fee`) VALUES (?,?,?,?,?,?)");
                $ins->bind_param("iissii", $train_id, $route_id, $date, $time, $first_fee, $second_fee);
                $ins->execute();
            }
        } else {
            for ($i = strtotime($every, strtotime($startDate)); $i <= strtotime($endDate); $i = strtotime('+1 week', $i)) {
                $date = date('d-m-Y', $i);
                $ins = $conn->prepare("INSERT INTO `schedule`(`train_id`, `route_id`, `date`, `time`, `first_fee`, `second_fee`) VALUES (?,?,?,?,?,?)");
                $ins->bind_param("iissii", $train_id, $route_id, $date, $time, $first_fee, $second_fee);
                $ins->execute();
            }
        }
        alert("Schedules Added!");
        load($_SERVER['PHP_SELF'] . "$me");
    }
}

if (isset($_POST['edit'])) {
    $route_id = $_POST['route_id'];
    $train_id = $_POST['train_id'];
    $first_fee = $_POST['first_fee'];
    $second_fee = $_POST['second_fee'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $id = $_POST['id'];
    if (!isset($route_id, $train_id, $first_fee, $second_fee, $date, $time)) {
        alert("Fill Form Properly!");
    } else {
        $conn = connect();
        $ins = $conn->prepare("UPDATE `schedule` SET `train_id`=?,`route_id`=?,`date`=?,`time`=?,`first_fee`=?,`second_fee`=? WHERE id = ?");
        $ins->bind_param("iissiii", $train_id, $route_id, $date, $time, $first_fee, $second_fee, $id);
        $ins->execute();
        alert("Schedule Modified!");
        load($_SERVER['PHP_SELF'] . "$me");
    }
}

if (isset($_POST['del_train'])) {
    $con = connect();
    $conn = $con->query("DELETE FROM schedule WHERE id = '" . $_POST['del_train'] . "'");
    if ($con->affected_rows < 1) {
        alert("Schedule Could Not Be Deleted. This Route Has Been Tied To Another Data!");
        load($_SERVER['PHP_SELF'] . "$me");
    } else {
        alert("Schedule Deleted!");
        load($_SERVER['PHP_SELF'] . "$me");
    }
}
?>
