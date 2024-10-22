<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in!");
}

// Include the database connection
require_once '../../conn.php';

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Capture booking data
    $class = $_POST['class'];
    $number = $_POST['number'];
    $schedule_id = $_POST['id'];

    // Check for valid input
    if ($number < 1) {
        die("Invalid Number");
    }

    // Determine which fee to retrieve based on the class
    $fee_column = ($class === 'first') ? 'first_fee' : 'second_fee';

    // Fetch route from the database
    $route_query = "SELECT start, stop FROM route WHERE id = (SELECT route_id FROM schedule WHERE id = ?)";
    $fee_query = "SELECT $fee_column FROM schedule WHERE id = ?";

    // Prepare and bind parameters for the route query
    if ($stmt = $conn->prepare($route_query)) {
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $stmt->bind_result($start, $stop);
        $stmt->fetch();
        $stmt->close();

        // Combine start and stop for the route description
        $route = $start . " to " . $stop;
        $_SESSION['route'] = $route;

    } else {
        die("Failed to fetch route.");
    }

    // Prepare and bind parameters for the fee query
    if ($stmt = $conn->prepare($fee_query)) {
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $stmt->bind_result($fee);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Failed to fetch fee.");
    }

    // Calculate totals
    $total = $fee * $number; // Use the retrieved fee
    $vat = ceil($total * 0.01);
    $grand_total = $total + $vat;

    // Store the data in session for later use
    $_SESSION['amount'] = $grand_total;
    $_SESSION['schedule'] = $schedule_id;
    $_SESSION['no'] = $number;
    $_SESSION['class'] = $class;

    // Cashfree Payment Gateway Integration
    $appId = ""; // Replace with your Cashfree App ID
    $secretKey = ""; // Replace with your Cashfree Secret Key
    $orderId = "ORDER_" . time(); // Unique Order ID
    $orderAmount = $grand_total;
    $orderCurrency = "INR";
    $orderNote = "Booking for " . $route;
    $customerName = "John Doe"; // Replace with dynamic customer data
    $customerEmail = "johndoe@test.com"; // Replace with dynamic customer data
    $customerPhone = "9999999999"; // Replace with dynamic customer data
    $returnUrl = "http://localhost:3000/train/pro/dist/response.php"; // Change to your response URL
    $notifyUrl = "http://yourwebsite.com/path/to/notify.php"; // Change to your notify URL (optional)

    // Build the POST data array
    $postData = array( 
        "appId" => $appId, 
        "orderId" => $orderId, 
        "orderAmount" => $orderAmount, 
        "orderCurrency" => $orderCurrency, 
        "orderNote" => $orderNote, 
        "customerName" => $customerName, 
        "customerPhone" => $customerPhone, 
        "customerEmail" => $customerEmail,
        "returnUrl" => $returnUrl, 
        "class" => $class, 
        "notifyUrl" => $notifyUrl
    );

    // Sort the postData array
    ksort($postData);

    // Create signature data string
    $signatureData = "";
    foreach ($postData as $key => $value) {
        $signatureData .= $key . $value;
    }

    // Generate signature
    $signature = hash_hmac('sha256', $signatureData, $secretKey, true);
    $signature = base64_encode($signature);

    // Determine the payment URL (TEST or PROD)
    $mode = "TEST"; // Change to "PROD" for production
    $paymentUrl = ($mode === "PROD") ? "https://www.cashfree.com/checkout/post/submit" : "https://test.cashfree.com/billpay/checkout/post/submit";
} else {
    die("No booking details provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212; /* Solid dark background */
            color: #e0e0e0;
            margin: 0;
            font-family: 'Roboto', sans-serif; /* Example futuristic font */
        }

        .container {
    height: 20%; /* Change from 100vh to auto for a more flexible height */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 5px;
    box-sizing: border-box;
}

.card {
    background-color: #1e1e1e;
    border-radius: 12px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
    padding: 20px; /* Decrease padding from 40px to 20px */
    width: 100%;
    max-width: 600px; /* Keep max-width as is */
    margin: 10px 0;
    transition: transform 0.3s, box-shadow 0.3s;
}

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.7);
        }

        .card-header {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #00b300;
            border-bottom: 2px solid #00b300;
            padding-bottom: 10px;
        }

        .warning {
            background-color: red; /* Orange background */
            color: #fff; /* White text */
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .info span {
            font-size: 1.1rem;
        }

        .info span:first-child {
            font-weight: bold;
            color: #00b300;
        }

        .total-info {
            font-weight: bold;
            font-size: 1.3rem;
            text-align: center;
            margin: 20px 0;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 25px;
            border: none;
            color: #fff;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            position: relative;
            overflow: hidden;
            transition: color 0.3s, box-shadow 0.3s, transform 0.3s;
            font-size: 1.1rem;
        }

        .btn-success {
            background-color: #00b300;
            box-shadow: 0 4px 15px rgba(0, 179, 0, 0.5);
        }

        .btn-success:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 179, 0, 0.7);
        }

        .btn-danger {
            background-color: #dc3545;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.5);
        }

        .btn-danger:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.7);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="warning">Warning: Please ensure that you are logged in freshly without refreshing the site.</div>
            <div class="card-header">Booking Preview</div>
            <div class="info">
                <span><strong>Tickets:</strong></span>
                <span><?php echo $number, " Ticket", $number > 1 ? 's' : ''; ?></span>
            </div>
            <div class="info">
                <span><strong>Route:</strong></span>
                <span><?php echo $route; ?></span>
            </div>
            <div class="info">
                <span><strong>Class:</strong></span>
                <span><?php echo ucwords($class); ?></span>
            </div>
            <div class="info">
                <span><strong>Total:</strong></span>
                <span>₹<?php echo $total; ?></span>
            </div>
            <div class="info">
                <span><strong>VAT:</strong></span>
                <span>₹<?php echo $vat; ?></span>
            </div>
            <div class="total-info">
                <span>Grand Total:</span>
                <span>₹<?php echo $grand_total; ?></span>
            </div>

            <!-- Form to handle payment request to Cashfree -->
            <form action="<?php echo $paymentUrl; ?>" method="post" name="frm1">
                <input type="hidden" name="appId" value="<?php echo $appId; ?>">
                <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
                <input type="hidden" name="orderAmount" value="<?php echo $orderAmount; ?>">
                <input type="hidden" name="orderCurrency" value="<?php echo $orderCurrency; ?>">
                <input type="hidden" name="orderNote" value="<?php echo $orderNote; ?>">
                <input type="hidden" name="customerName" value="<?php echo $customerName; ?>">
                <input type="hidden" name="customerPhone" value="<?php echo $customerPhone; ?>">
                <input type="hidden" name="customerEmail" value="<?php echo $customerEmail; ?>">
                <input type="hidden" name="returnUrl" value="<?php echo $returnUrl; ?>">
                <input type="hidden" name="notifyUrl" value="<?php echo $notifyUrl; ?>">
                <input type="hidden" name="class" value="<?php echo $class; ?>"> 
                <input type="hidden" name="signature" value="<?php echo $signature; ?>">
                
                <button type="submit" class="btn btn-success">Proceed to Payment</button>
            </form>

            <a href="javascript:history.back()" class="btn btn-danger">Go Back</a> 
        </div>
    </div>
</body>
</html>
