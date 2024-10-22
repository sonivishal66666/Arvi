<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Ticketing System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.5s ease;
            margin: 0;
        }

        /* Animated Particle Background */
        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(18, 18, 18, 0.8);
            overflow: hidden;
            z-index: -1;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-30px);
            }
            100% {
                transform: translateY(0);
            }
        }

        .container-fluid {
            padding: 20px;
        }

        .card {
            background-color: #1e1e1e;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.6);
        }

        .card-header {
            background: linear-gradient(90deg, cyan, black);
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header h3 {
            z-index: 1;
            position: relative;
            color: black;
            font-weight: 500;
            font-size: 24px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead {
            background: #333;
            position: relative;
            z-index: 2;
            transition: background-color 0.3s;
        }

        .table thead:hover {
            background: rgba(0, 123, 255, 0.5);
        }

        .table th, .table td {
            padding: 20px;
            text-align: left;
            border-bottom: 1px solid #444;
            transition: background-color 0.3s, transform 0.3s;
        }

        .table tbody tr {
            position: relative;
            z-index: 1;
        }

        .table tbody tr:nth-child(even) {
            background-color: #1f1f1f;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.7);
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.3s;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.2);
            transition: width 0.5s, height 0.5s, top 0.5s, left 0.5s;
            border-radius: 50%;
            z-index: 0;
            transform: translate(-50%, -50%) scale(0);
        }

        .btn:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transform: translateY(-3px);
        }

        .btn:hover::after {
            width: 400%;
            height: 400%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-content {
            background-color: #1e1e1e;
            color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal-header {
            border-bottom: 1px solid #444;
        }

        .glow-button {
            box-shadow: 0 0 5px #007bff, 0 0 10px #007bff, 0 0 15px #007bff;
            transition: transform 0.3s;
        }

        .glow-button:hover {
            transform: scale(1.05);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #222;
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s;
            cursor: pointer;
        }

        .fab:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .table, .modal-body {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="background-animation">
        <div class="particle" style="top: 20%; left: 20%; width: 10px; height: 10px; animation-delay: 0s;"></div>
        <div class="particle" style="top: 50%; left: 50%; width: 15px; height: 15px; animation-delay: 1s;"></div>
        <div class="particle" style="top: 70%; left: 30%; width: 20px; height: 20px; animation-delay: 2s;"></div>
        <div class="particle" style="top: 30%; left: 70%; width: 12px; height: 12px; animation-delay: 3s;"></div>
        <div class="particle" style="top: 80%; left: 10%; width: 8px; height: 8px; animation-delay: 4s;"></div>
    </div>
    <div class="content">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Registered Users</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $row = connect()->query("SELECT * FROM passenger ORDER BY id DESC");
                                            if ($row->num_rows < 1) echo "<tr><td colspan='6'>No Records Yet</td></tr>";
                                            $sn = 0;
                                            while ($fetch = $row->fetch_assoc()) {
                                                $id = $fetch['id']; 
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$sn; ?></td>
                                                    <td><?php echo ($fetch['name']); ?></td>
                                                    <td><?php echo ($fetch['email']); ?></td>
                                                    <td><?php echo ($fetch['phone']); ?></td>
                                                    <td><img src="<?php echo "uploads/" . ($fetch['loc']); ?>" class="img img-rounded" width="80" height="80" /></td>
                                                    <td>
                                                        <?php if ($fetch['status'] == 0) { ?>
                                                            <a href="admin.php?page=users&status=1&id=<?php echo $id; ?>">
                                                                <button onclick="return confirm('You are about allowing this user to login to their account.')" type="submit" class="btn glow-button">Enable Account</button>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="admin.php?page=users&status=0&id=<?php echo $id; ?>">
                                                                <button onclick="return confirm('You are about denying this user access to their account.')" type="submit" class="btn glow-button">Disable Account</button>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Floating Action Button -->
        <button class="fab" data-toggle="modal" data-target="#add">
            <i class="fas fa-plus"></i>
        </button>

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
                                    Train : <select class="form-control" name="train_id" required id="">
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
                                    Route : <select class="form-control" name="route_id" required id="">
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
                                    First Class Charge : <input class="form-control" type="number" name="first_fee" required id="">
                                </div>
                                <div class="col-sm-6">
                                    Second Class Charge : <input class="form-control" type="number" name="second_fee" required id="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    Date : <input class="form-control" onchange="check(this.value)" type="date" name="date" required id="date">
                                </div>
                                <div class="col-sm-6">
                                    Time : <input class="form-control" type="time" name="time" required id="">
                                </div>
                            </div>
                            <hr>
                            <input type="submit" name="submit" class="btn glow-button" value="Add Schedule">
                        </form>

                        <script>
                        function check(val) {
                            val = new Date(val);
                            var age = (Date.now() - val) / 31557600000;
                            var formDate = document.getElementById('date');
                            if (age > 0) {
                                alert("Past/Current Date not allowed");
                                formDate.value = "";
                                return false;
                            }
                        }
                        </script>
                    </div>
                </div>
            </div>
        </div>





<div class="modal fade" id="add2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" align="center">
            <div class="modal-header">
                <h4 class="modal-title">Add Range Schedule &#128649;
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            Train : <select class="form-control" name="train_id" required id="">
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
                            Route : <select class="form-control" name="route_id" required id="">
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
                            From Date : <input class="form-control" onchange="check(this.value)" type="date"
                                name="from_date" required>
                        </div>
                        <div class="col-sm-6">
                            End Date : <input class="form-control" onchange="check(this.value)" type="date"
                                name="to_date" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"> Every :
                            <select class="form-control" name="every">
                                <option value="Day">Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="col-sm-6">

                            Time : <input class="form-control" type="time" name="time" required id="">
                        </div>
                    </div>
                    <hr>
                    <input type="submit" name="submit2" class="btn btn-success" value="Add Schedule"></p>
                </form>

                <script>
                function check(val) {
                    val = new Date(val);
                    var age = (Date.now() - val) / 31557600000;
                    var formDate = document.getElementById('date');
                    if (age > 0) {
                        alert("You are using a past/current date!");
                        val.value = "";
                        return false;
                    }
                }
                </script>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php

if (isset($_POST['submit'])) {
    $route_id = $_POST['route_id'];
    $train_id = $_POST['train_id'];
    $first_fee = $_POST['first_fee'];
    $second_fee = $_POST['second_fee'];
    $date = $_POST['date'];
    $date = formatDate($date);
    // die($date);
    // $endDate = date('Y-m-d' ,strtotime( $data['automatic_until'] ));
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





        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div>
</body>
</html>
