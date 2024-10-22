<?php
// Database connection (edit with your actual database credentials)
require_once '../conn.php';
session_start(); // Start session to access session variables

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and retrieve user_id from session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in."); // Handle case where user is not logged in
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// SQL query to fetch records for the logged-in user only
$sql = "
    SELECT
        booked.id AS order_id,
        payment.order_amount,
        route.start,
        route.stop,
        booked.seat,
        payment.payment_mode,
        payment.transaction_time,
        payment.transaction_status,
        payment.reference_id,
        passenger.name,
        passenger.email,
        passenger.phone,
        passenger.address,
        passenger.gender
    FROM booked
    LEFT JOIN schedule ON booked.schedule_id = schedule.id
    LEFT JOIN train ON schedule.train_id = train.id
    LEFT JOIN route ON schedule.route_id = route.id
    LEFT JOIN payment ON booked.payment_id = payment.id
    LEFT JOIN passenger ON booked.user_id = passenger.id
    WHERE booked.user_id = ?  -- Filter by the logged-in user's ID
";

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); // Assuming user_id is an integer
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paid Tickets</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (keeping Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for New Aesthetic Design -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #e6e6e6;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Main grid layout for the cards */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        /* Ticket card design */
        .ticket-card {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 255, 229, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: #fff;
        }

        .ticket-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 0 30px rgba(0, 255, 229, 0.8);
        }

        .ticket-card h4 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #00ffe5;
            text-transform: uppercase;
        }

        .ticket-info {
            margin-bottom: 10px;
            font-size: 14px;
            color: #e6e6e6;
        }

        .ticket-info span {
            font-weight: 600;
            color: #00ffe5;
        }

        /* Neon button style */
        .neon-button {
            background-color: #2a2a2a;
            color: #00ffe5;
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 255, 229, 0.7);
            transition: all 0.3s ease;
            margin-top: 10px;
            display: inline-block;
        }

        .neon-button:hover {
            box-shadow: 0 0 20px rgba(0, 255, 229, 1), 0 0 40px rgba(0, 255, 229, 0.8);
            transform: scale(1.05);
        }

        /* Dynamic background */
        .dynamic-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00ffe5, #0033ff, #ff005d);
            background-size: 400% 400%;
            z-index: -1;
            animation: moveBackground 20s ease infinite;
        }

        @keyframes moveBackground {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="dynamic-bg"></div> <!-- Dynamic background -->

    <div class="container py-5">
        <h2 class="text-center neon-text mb-5">Paid Tickets</h2>

        <div class="grid-container">
            <?php
            if ($result->num_rows > 0) {
                // Output each ticket as a card
                while ($row = $result->fetch_assoc()) {
                    $name = htmlspecialchars($row['name'] ?? 'N/A', ENT_QUOTES);
                    $email = htmlspecialchars($row['email'] ?? 'N/A', ENT_QUOTES);
                    $phone = htmlspecialchars($row['phone'] ?? 'N/A', ENT_QUOTES);
                    $address = htmlspecialchars($row['address'] ?? 'N/A', ENT_QUOTES);
                    $gender = htmlspecialchars($row['gender'] ?? 'N/A', ENT_QUOTES);

                    echo '<div class="ticket-card">';
                    echo '<h4>Order ID: ' . $row["order_id"] . '</h4>';
                    echo '<div class="ticket-info"><span>Order Amount:</span> $' . $row["order_amount"] . '</div>';
                    echo '<div class="ticket-info"><span>Route:</span> ' . $row["start"] . ' - ' . $row["stop"] . '</div>';
                    echo '<div class="ticket-info"><span>Seat:</span> ' . $row["seat"] . '</div>';
                    echo '<div class="ticket-info"><span>Payment Mode:</span> ' . $row["payment_mode"] . '</div>';
                    echo '<div class="ticket-info"><span>Transaction Time:</span> ' . $row["transaction_time"] . '</div>';
                    echo '<div class="ticket-info"><span>Transaction Status:</span> ' . $row["transaction_status"] . '</div>';
                    echo '<div class="ticket-info"><span>Reference ID:</span> ' . $row["reference_id"] . '</div>';
                    echo '<button class="neon-button" onclick="printRow(\'' . $row['order_id'] . '\', \'' . $name . '\', \'' . $email . '\', \'' . $phone . '\', \'' . $address . '\', \'' . $gender . '\', \'' . $row["order_amount"] . '\', \'' . $row["start"] . ' - ' . $row["stop"] . '\', \'' . $row["seat"] . '\', \'' . $row["payment_mode"] . '\', \'' . $row["transaction_time"] . '\', \'' . $row["transaction_status"] . '\', \'' . $row["reference_id"] . '\')">Print</button>';
                    echo '</div>';
                }
            } else {
                echo "<div class='alert alert-danger text-center'>No records found</div>";
            }

            // Close the prepared statement and connection
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

<script>
function printRow(orderId, name, email, phone, address, gender, orderAmount, route, seat, paymentMode, transactionTime, transactionStatus, referenceId) {
    // Prepare the content for printing
    var printContent = `
        <div style="text-align: center; font-size: 16px; color: black;">
            <h2>Ticket Details</h2>
            <table style="width: 100%; border-collapse: collapse; margin: auto; font-size: 14px;">
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Order ID</td>
                    <td style="border: 1px solid black; padding: 10px;">${orderId}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Name</td>
                    <td style="border: 1px solid black; padding: 10px;">${name}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Email</td>
                    <td style="border: 1px solid black; padding: 10px;">${email}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Phone</td>
                    <td style="border: 1px solid black; padding: 10px;">${phone}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Address</td>
                    <td style="border: 1px solid black; padding: 10px;">${address}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Gender</td>
                    <td style="border: 1px solid black; padding: 10px;">${gender}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Order Amount</td>
                    <td style="border: 1px solid black; padding: 10px;">${orderAmount}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Route</td>
                    <td style="border: 1px solid black; padding: 10px;">${route}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Seat</td>
                    <td style="border: 1px solid black; padding: 10px;">${seat}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Payment Mode</td>
                    <td style="border: 1px solid black; padding: 10px;">${paymentMode}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Transaction Time</td>
                    <td style="border: 1px solid black; padding: 10px;">${transactionTime}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Transaction Status</td>
                    <td style="border: 1px solid black; padding: 10px;">${transactionStatus}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 10px; font-weight: bold;">Reference ID</td>
                    <td style="border: 1px solid black; padding: 10px;">${referenceId}</td>
                </tr>
            </table>
        </div>
    `;
    
    // Open a new window for printing
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Ticket</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

<!-- Bootstrap 5 JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
