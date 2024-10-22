<?php
session_start();

// Check if user_id exists in session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 'N/A'; // Default if not available
}

// Retrieve route from session
$route = isset($_SESSION['route']) ? $_SESSION['route'] : 'N/A'; // Check if route is set in the session
$schedule_id = isset($_SESSION['schedule']) ? $_SESSION['schedule'] : 'N/A';
$class = isset($_SESSION['class']) ? $_SESSION['class'] : 'N/A'; // Check if route is set in the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Your secret key from Cashfree
$secretkey = "cfsk_ma_test_7708f9040827cfbe4fd2facd556295f1_0c498ade"; // Replace with your actual secret key

// Variable to track payment save status
$paymentSaved = false;

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data from Cashfree response
    $orderId = $_POST["orderId"];
    $orderAmount = $_POST["orderAmount"];
    $referenceId = $_POST["referenceId"];
    $txStatus = $_POST["txStatus"];
    $paymentMode = $_POST["paymentMode"];
    $txMsg = $_POST["txMsg"];
    $txTime = $_POST["txTime"];
    $signature = $_POST["signature"];

    // Prepare the data string to verify signature
    $data = $orderId . $orderAmount . $referenceId . $txStatus . $paymentMode . $txMsg . $txTime;

    // Generate the hash_hmac for comparison
    $hash_hmac = hash_hmac('sha256', $data, $secretkey, true);
    $computedSignature = base64_encode($hash_hmac);

    // Include the database connection
    require_once '../../conn.php'; // Ensure you have the correct path for the database connection

    // Check if the signature matches
    if ($signature == $computedSignature) {
        // Signature matched. Process the payment

        // 1. Insert data into the 'payment' table
        $paymentQuery = "INSERT INTO payment (order_amount, reference_id, transaction_status, payment_mode, message, transaction_time, order_id, user_id) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($paymentQuery);
        $stmt->bind_param('dssssssi', $orderAmount, $referenceId, $txStatus, $paymentMode, $txMsg, $txTime, $orderId, $user_id);
        

        

        if ($stmt->execute()) {
            // Get the inserted payment_id
            $payment_id = $stmt->insert_id;

            // Free the result of the previous statement
            $stmt->free_result();

            // 2. Check if user_id exists in passenger table
            $userCheckQuery = "SELECT id FROM passenger WHERE id = ?";
            $stmt = $conn->prepare($userCheckQuery);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User ID exists in passenger table, proceed with the booking
                $code = "BOOKING_CODE"; // Replace with actual booking code
                $no = ''; // Replace with actual seat number (can be a string, or valid seat number if needed)
                
                function generateRandomSeat() {
                    // Generate a random letter from A to I
                    $letter = chr(rand(65, 73)); // 65 = A, 73 = I
                    // Generate a random number from 1 to 100
                    $number = rand(1, 100);
                    return $letter . $number; // Combine letter and number
                }

                $seat = generateRandomSeat(); // Call the function to generate a random seat
                $date = date('Y-m-d'); // Current date

                // Insert data into the 'booked' table with the payment_id
                $bookedQuery = "INSERT INTO booked (schedule_id , payment_id, user_id, code, class, no, seat, date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt->prepare($bookedQuery);
                $stmt->bind_param('iiississ', $schedule_id, $payment_id, $user_id, $code, $class, $no, $seat, $date);
                
                if ($stmt->execute()) {
                    $paymentSaved = true;
                } else {
                    echo "Error storing booking details: " . $conn->error;
                }
            } else {
                echo "User ID does not exist. Please log in.";
            }

            // Free the result after use
            $result->free();
        } else {
            echo "Error storing payment details: " . $conn->error;
        }
    } else {
        echo "Invalid signature.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cashfree - PG Response Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #121212; /* Dark background */
            color: #ffffff; /* White text */
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
        }

        h1 {
            color: #00b7ff; /* Neon blue for headings */
            margin-bottom: 30px;
            text-align: center;
            animation: fadeIn 1s;
            font-size: 2.5rem; /* Increased font size for better visibility */
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .panel {
            background-color: #1c1c1c; /* Darker panel background */
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 183, 255, 0.6);
            animation: slideIn 0.5s;
            transition: transform 0.3s;
        }

        .panel:hover {
            transform: scale(1.02);
        }

        .panel-heading {
            background-color: #1f1f1f;
            color: #00b7ff;
            font-weight: bold;
            font-size: 1.5em;
            border-radius: 10px 10px 0 0;
            padding: 15px;
            text-align: center; /* Centering the heading */
            letter-spacing: 1px; /* Adding letter spacing for a futuristic touch */
        }

        .table {
            background-color: #2c2c2c; /* Darker table background */
            border-radius: 10px;
            overflow: hidden;
            margin-top: 15px;
        }

        .table th, .table td {
            color: #ffffff;
            padding: 15px;
            text-align: left;
        }

        .table th {
            background-color: #00b7ff; /* Neon blue for headers */
            color: #000; /* Black text for better contrast */
        }

        .btn-primary {
            background-color: #00b7ff; /* Neon blue */
            border-color: #00b7ff;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: #007bb5; /* Darker blue on hover */
            transform: translateY(-2px); /* Subtle lift effect */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Payment Gateway Response</h1>    

    <div class="container"> 
        <?php if ($paymentSaved) { ?>
        <div class="panel panel-success">
            <div class="panel-heading">Payment Details Stored and Verified Successfully</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td>User ID</td>
                        <td><?php echo htmlspecialchars($user_id ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Class</td>
                        <td><?php echo htmlspecialchars($class ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Route</td>
                        <td><?php echo htmlspecialchars($route); ?></td>
                    </tr>
                    <tr>
                        <td>Seat</td>
                        <td><?php echo htmlspecialchars($seat); ?></td>
                    </tr>
                    <tr>
                        <td>Schedule ID</td>
                        <td><?php echo htmlspecialchars($schedule_id); ?></td>
                    </tr>
                    <tr>
                        <td>Order ID</td>
                        <td><?php echo htmlspecialchars($orderId); ?></td>
                    </tr>
                    <tr>
                        <td>Order Amount</td>
                        <td><?php echo htmlspecialchars($orderAmount); ?></td>
                    </tr>
                    <tr>
                        <td>Reference ID</td>
                        <td><?php echo htmlspecialchars($referenceId); ?></td>
                    </tr>
                    <tr>
                        <td>Transaction Status</td>
                        <td><?php echo htmlspecialchars($txStatus); ?></td>
                    </tr>
                    <tr>
                        <td>Payment Mode</td>
                        <td><?php echo htmlspecialchars($paymentMode); ?></td>
                    </tr>
                    <tr>
                        <td>Message</td>
                        <td><?php echo htmlspecialchars($txMsg); ?></td>
                    </tr>
                    <tr>
                        <td>Transaction Time</td>
                        <td><?php echo htmlspecialchars($txTime); ?></td>
                    </tr>
                    </tbody>
                </table>

                <!-- Go to User Dashboard Button -->
                <div class="text-center">
                    <a href="http://localhost:3000/train/pro/individual.php?page=paid" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Go to User Dashboard
                    </a>
                </div>
           </div>
        </div>
        <?php } else { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">Error Saving Payment Details</div>
            <div class="panel-body">
                <p>There was an error verifying the payment or storing it in the database.</p>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>
