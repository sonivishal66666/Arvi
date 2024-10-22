<?php
if (!isset($file_access)) die("Direct File Access Denied");
$source = 'train';
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

        tbody tr {
            transition: transform 0.3s ease; /* Transition for scaling */
        }

        tbody tr:hover {
            transform: scale(1.02); /* Scale up slightly on hover */
            background-color: transparent; /* Ensure background color does not change */
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
                                <h3 class="card-title">All Buses</h3>
                                <div class='float-right'>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add">
                                        Add New Bus &#128645;
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="example1" style="align-items: stretch;" class="table table-hover w-100 table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bus Name</th>
                                            <th>First Class Seat</th>
                                            <th>Second Class Seat</th>
                                            <th style="width: 30%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $row = $conn->query("SELECT * FROM train");
                                        if ($row->num_rows < 1) echo "No Records Yet";
                                        $sn = 0;
                                        while ($fetch = $row->fetch_assoc()) {
                                            $id = $fetch['id'];
                                        ?>
                                            <tr>
                                                <td><?php echo ++$sn; ?></td>
                                                <td><?php echo $fullname = $fetch['name']; ?></td>
                                                <td><?php echo $fetch['first_seat']; ?></td>
                                                <td><?php echo $fetch['second_seat']; ?></td>
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
                                                            <h4 class="modal-title">Editing <?php echo $fullname; ?></h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="post">
                                                                <input type="hidden" class="form-control" name="id" value="<?php echo $id ?>" required id="">
                                                                <p>Bus Name : <input type="text" class="form-control" name="name" value="<?php echo $fetch['name'] ?>" required minlength="3" id=""></p>
                                                                <p>First Class Capacity : <input type="number" min='0' class="form-control" value="<?php echo $fetch['first_seat'] ?>" name="first_seat" required id=""></p>
                                                                <p>Second Class Capacity : <input type="number" min='0' class="form-control" value="<?php echo $fetch['second_seat'] ?>" name="second_seat" required id=""></p>
                                                                <p>
                                                                    <input class="btn btn-info" type="submit" value="Edit Train" name='edit'>
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
                    <h4 class="modal-title">Add New Bus &#128646;</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <table class="table table-bordered">
                            <tr>
                                <th>Bus Name</th>
                                <td><input type="text" class="form-control" name="name" required minlength="3" id=""></td>
                            </tr>
                            <tr>
                                <th>First Class Capacity</th>
                                <td><input type="number" min='0' class="form-control" name="first_seat" required id=""></td>
                            </tr>
                            <tr>
                                <th>Second Class Capacity</th>
                                <td><input type="number" min='0' class="form-control" name="second_seat" required id=""></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input class="btn btn-info" type="submit" value="Add Train" name='submit'>
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
        $name = $_POST['name'];
        $first_seat = $_POST['first_seat'];
        $second_seat = $_POST['second_seat'];
        if (!isset($name, $first_seat, $second_seat)) {
            alert("Fill Form Properly!");
        } else {
            $conn = connect();
            //Check if train exists
            $check = $conn->query("SELECT * FROM train WHERE name = '$name' ")->num_rows;
            if ($check) {
                alert("Train exists");
            } else {
                $ins = $conn->prepare("INSERT INTO train (name, first_seat, second_seat) VALUES (?,?,?)");
                $ins->bind_param("sss", $name, $first_seat, $second_seat);
                $ins->execute();
                alert("Train Added!");
                load($_SERVER['PHP_SELF'] . "$me");
            }
        }
    }

    if (isset($_POST['edit'])) {
        $name = $_POST['name'];
        $first_seat = $_POST['first_seat'];
        $second_seat = $_POST['second_seat'];
        $id = $_POST['id'];
        if (!isset($name, $first_seat, $second_seat)) {
            alert("Fill Form Properly!");
        } else {
            $conn = connect();
            //Check if train exists
            $check = $conn->query("SELECT * FROM train WHERE name = '$name' ")->num_rows;
            if ($check == 2) {
                alert("Train name exists");
            } else {
                $ins = $conn->prepare("UPDATE train SET name = ?, first_seat = ?, second_seat = ? WHERE id = ?");
                $ins->bind_param("sssi", $name, $first_seat, $second_seat, $id);
                $ins->execute();
                alert("Train Modified!");
                load($_SERVER['PHP_SELF'] . "$me");
            }
        }
    }

    if (isset($_POST['del_train'])) {
        $con = connect();
        $conn = $con->query("DELETE FROM train WHERE id = '" . $_POST['del_train'] . "'");
        if ($con->affected_rows < 1) {
            alert("Train Could Not Be Deleted. This Train Has Been Tied To Another Data!");
            load($_SERVER['PHP_SELF'] . "$me");
        } else {
            alert("Train Deleted!");
            load($_SERVER['PHP_SELF'] . "$me");
        }
    }
    ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
