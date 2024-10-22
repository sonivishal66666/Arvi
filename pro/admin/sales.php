<?php
if (!isset($file_access)) die("Direct File Access Denied");
$source = 'payment';
require_once '../../conn.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="mb-4">All Payments</h3>
                <div class="row">
                    <?php
                    // Updated query to match the new payment table structure
                    $pay = $conn->query("SELECT payment.*, schedule.id as schedule_id, schedule.date as date, schedule.time as time FROM schedule INNER JOIN payment ON schedule.id = payment.order_id"); // Assuming order_id is used to join
                    $sn = 0;

                    while ($val = $pay->fetch_assoc()) {
                        $id = $val['schedule_id']; // Using the new alias for the schedule ID
                        $array = getTotalBookByType($id);
                        $sn++;
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: #2c2c3e; border: none;">
                            <div class="card-header" style="background-color: #3a3a4e;">
                                <h5 class="card-title"><?php echo getRoutePath($val['route_id']); ?></h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Date: <?php echo $val['date'] . " - " . formatTime($val['time']); ?></p>
                                <p class="card-text">Order Amount: $ <?php echo $val['order_amount']; ?></p>
                                <p class="card-text">Transaction Status: <?php echo htmlspecialchars($val['transaction_status']); ?></p>
                                <p class="card-text">Payment Mode: <?php echo htmlspecialchars($val['payment_mode']); ?></p>
                                <p class="card-text">Message: <?php echo htmlspecialchars($val['message']); ?></p>
                                <p class="card-text">
                                    <?php
                                    echo (($array['first'] - $array['first_booked'])) . " Seat(s) Available for First Class<br/>" . 
                                         ($array['second'] - $array['second_booked']) . " Seat(s) Available for Second Class";
                                    ?>
                                </p>
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
</div>
