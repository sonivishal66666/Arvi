<?php
if (!isset($file_access)) die("Direct File Access Denied");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Section</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Neon and Animations -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a; /* Dark background */
            color: #f1f1f1; /* Light text */
        }

        .callout-info {
            background-color: #2a2a2a;
            border-left-color: #5a67d8;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .feedback-message {
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
            border: 1px solid #218838;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
            border: 1px solid #c82333;
        }

        .card-header {
            background-color: #28a745 !important;
            color: white !important;
        }

        .btn-primary {
            background-color: #4c51bf;
            border-color: #4c51bf;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #28a745;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        table {
            background-color: #2a2a2a;
            color: white;
        }

        thead th {
            background-color: #333;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #333;
        }

        textarea.form-control {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            transition: border-color 0.3s ease;
        }

        textarea.form-control:focus {
            border-color: #5a67d8;
            outline: none;
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translateY(-50px);
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<!-- Content Section -->
<section class="content py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Callout Info -->
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Info:</h5>
                    We always want to hear from you! Replied to within 24 hours.
                </div>

                <!-- Feedback Message Container -->
                <div id="feedback-message" class="feedback-message"></div>

                <!-- Feedbacks List -->
                <div class="card">
                    <div class="card-header alert-success">
                        <h5 class="card-title"><b>List of all Feedbacks</b></h5>
                        <div class="float-end">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add">
                                Send New Feedback
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark table-striped" id='example1'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Your Comment</th>
                                    <th>Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 0;
                                $query = getFeedbacks();
                                while ($row = $query->fetch_assoc()) {
                                    $sn++;
                                    echo "<tr>
                                    <td>$sn</td>
                                    <td>" . htmlspecialchars($row['message'], ENT_QUOTES) . "</td>
                                    <td>" . ($row['response'] == NULL ? '-- No Response Yet --' : htmlspecialchars($row['response'], ENT_QUOTES)) . "</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<!-- Modal for Sending Feedback -->
<div class="modal fade" id="add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send New Feedback</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                Type Message: 
                                <textarea name="message" required minlength="10" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="sendFeedback" class="btn btn-success" value="Send">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['sendFeedback'])) {
    $msg = $_POST['message'];
    $send = sendFeedback($msg);
    
    if ($send) {
        echo "<div class='alert alert-success'>Feedback sent! We will get back to you.</div>";
    } else {
        echo "<div class='alert alert-danger'>Feedback could not be sent! Try again!</div>";
    }
}
?>

</body>
</html>
