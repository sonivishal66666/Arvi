<?php
if (!isset($file_access)) die("Direct File Access Denied");
$source = 'route';
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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
    <style>
        /* Dark mode theme */
        body,
        .modal-content,
        .card {
            background-color: #1e1e2f;
            color: #ffffff;
            font-family: 'Roboto', sans-serif; /* Futuristic font */
        }

        /* Table Styling */
        table {
            background-color: #2b2b3d;
            border: 1px solid #3e3e4f;
            color: #ffffff;
        }

        thead th {
            background-color: #3a3a5e;
            border-bottom: 2px solid #6e6e91;
        }

        tbody tr:hover {
            background-color: #44475a; /* Changed to a lighter dark color */
            transition: background-color 0.3s ease;
        }

        /* Button Styling */
        .btn {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-info {
            background-color: #008cba;
            border: none;
        }

        .btn-info:hover {
            background-color: #005f75;
            box-shadow: 0 0 15px #00bfff;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
            box-shadow: 0 0 15px #ff4d4d;
        }

        .modal-header {
            border-bottom: 1px solid #6e6e91;
        }

        /* Glow effect for modal */
        .modal-content {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .modal-lg {
                max-width: 90%;
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
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">All Routes</h3>
                                <div class='float-right'>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add">
                                        Add New Route &#128645;
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="example1" style="align-items: stretch;" class="table table-hover w-100 table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $row = $conn->query("SELECT * FROM route");
                                        if ($row->num_rows < 1) echo "No Records Yet";
                                        $sn = 0;
                                        while ($fetch = $row->fetch_assoc()) {
                                            $id = $fetch['id'];
                                        ?>
                                            <tr>
                                                <td><?php echo ++$sn; ?></td>
                                                <td><?php echo $fetch['start']; ?></td>
                                                <td><?php echo $fetch['stop'];
                                                        $fullname = $fetch['start'] . " to " . $fetch['stop']; ?></td>
                                                <td>
                                                    <form method="POST">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit<?php echo $id ?>">
                                                            Edit
                                                        </button> -
                                                        <input type="hidden" class="form-control" name="del_train" value="<?php echo $id ?>" required id="">
                                                        <button type="submit" onclick="return confirm('Are you sure about this?')" class="btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="edit<?php echo $id ?>">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Editing <?php echo $fullname; ?> &#128642;</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="post">
                                                                <input type="hidden" class="form-control" name="id" value="<?php echo $id ?>" required id="">
                                                                <p>From : <input type="text" class="form-control" value="<?php echo $fetch['start'] ?>" name="start" required id=""></p>
                                                                <p>To : <input type="text" class="form-control" value="<?php echo $fetch['stop'] ?>" name="stop" required id=""></p>
                                                                <p>
                                                                    <input class="btn btn-info" type="submit" value="Edit Route" name='edit'>
                                                                </p>
                                                            </form>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" align="center">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Route &#128649;</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <table class="table table-bordered">
                            <tr>
                                <th>From</th>
                                <td><input type="text" class="form-control" name="start" required id=""></td>
                            </tr>
                            <tr>
                                <th>To</th>
                                <td><input type="text" class="form-control" name="stop" required id=""></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input class="btn btn-info" type="submit" value="Add Route" name='submit'>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        $start = $_POST['start'];
        $stop = $_POST['stop'];
        if (!isset($stop, $start)) {
            alert("Fill Form Properly!");
        } else {
            $conn = connect();
            $ins = $conn->prepare("INSERT INTO route (start, stop) VALUES (?, ?)");
            $ins->bind_param("ss", $start, $stop);
            $ins->execute();
            alert("Route Added!");
            load($_SERVER['PHP_SELF'] . "$me");
        }
    }

    if (isset($_POST['edit'])) {
        $start = $_POST['start'];
        $stop = $_POST['stop'];
        $id = $_POST['id'];
        if (!isset($start, $stop)) {
            alert("Fill Form Properly!");
        } else {
            $conn = connect();
            $ins = $conn->prepare("UPDATE route SET start = ?, stop = ? WHERE id = ?");
            $ins->bind_param("ssi", $start, $stop, $id);
            $ins->execute();
            alert("Route Modified!");
            load($_SERVER['PHP_SELF'] . "$me");
        }
    }

    if (isset($_POST['del_train'])) {
        $con = connect();
        $conn = $con->query("DELETE FROM route WHERE id = '" . $_POST['del_train'] . "'");
        if ($con->affected_rows < 1) {
            alert("Route Could Not Be Deleted. This Route Has Been Tied To Another Data!");
            load($_SERVER['PHP_SELF'] . "$me");
        } else {
            alert("Route Deleted!");
            load($_SERVER['PHP_SELF'] . "$me");
        }
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
