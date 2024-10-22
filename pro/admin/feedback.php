<?php
if (!isset($file_access)) die("Direct File Access Denied");
$source = 'train';
$me = "?page=$source";
?>

<div class="content">
    <div class="container-fluid">
        <h3 class="mb-4">All Feedbacks</h3>
        <div class="row">
            <?php
            // Fetching feedbacks from the database
            $row = $conn->query("SELECT * FROM `feedback` ORDER BY response ASC");
            if ($row->num_rows < 1) {
                echo "<p>No Feedbacks Yet</p>";
            } else {
                // Loop through the feedbacks and display them
                while ($fetch = $row->fetch_assoc()) {
                    $id = $fetch['id'];
                    $fullname = getIndividualName($fetch['user_id']);
                    $response = $fetch['response'];
            ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="background-color: #2c2c3e; border: none;">
                    <div class="card-header" style="background-color: #3a3a4e;">
                        <h5 class="card-title"><?php echo $fullname; ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Message: <?php echo $fetch['message']; ?></p>
                        <p class="card-text">Status: <?php echo $response ? $response : "Pending"; ?></p>
                        <div class="text-right">
                            <form method="POST">
                                <?php if ($response == NULL) { ?>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit<?php echo $id ?>">
                                        Reply
                                    </button>
                                <?php } else { ?>
                                    <p>No action</p>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Reply -->
            <div class="modal fade" id="edit<?php echo $id ?>">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="background-color: #1e1e1e;">
                        <div class="modal-header">
                            <h4 class="modal-title">Replying to <?php echo $fullname; ?>'s Message</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" class="form-control" name="id" value="<?php echo $id ?>" required>
                                <p>Reply:</p>
                                <textarea class="form-control" name="reply" required minlength="3"></textarea>
                                <p class="mt-3">
                                    <input class="btn btn-info" type="submit" value="Reply" name='send_reply'>
                                </p>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal -->
            <?php
                } // End of while loop
            } // End of if-else for feedbacks
            ?>
        </div>
    </div>
</div>

<?php
// Handling the reply submission
if (isset($_POST['send_reply'])) {
    $reply = $_POST['reply'];
    $id = $_POST['id'];
    if (replyTo($id, $reply)) {
        echo "<script>alert('Reply sent!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Reply could not be sent!'); window.location='admin.php';</script>";
    }
}
?>
