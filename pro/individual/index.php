<?php
if (!isset($file_access)) die("Direct File Access Denied");
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php'); // Redirect to login if user is not logged in
    exit();
}

// Include database connection
require_once '../conn.php';

// Debugging: Test database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user_id from session
$user_id = $_SESSION['user_id'];

// SQL Query: Fetch user details from the database including gender
$sql = "SELECT name, email, phone, address, gender, loc FROM passenger WHERE id = ?";
$query = $conn->prepare($sql);

if (!$query) {
    die("Query preparation failed: " . $conn->error);
}

$query->bind_param("i", $user_id);

// Execute the query
if (!$query->execute()) {
    die("Query execution failed: " . $query->error);
}

// Bind result variables including gender
$query->bind_result($name, $email, $phone, $address, $gender, $loc);

// Fetch the result
$userDetails = [];
if ($query->fetch()) {
    $userDetails = [
        "Name" => $name,
        "Email" => $email,
        "Phone" => $phone,
        "Address" => $address,
        "Gender" => $gender,
        "Location" => $loc
    ];
} else {
    echo "No data found for user ID: $user_id";
}

$query->close();
?>

<!-- Dashboard Content CSS -->
<style>
    body {
        margin: 0;
        overflow: hidden;
        background-color: #121212; /* Darker background for a more modern look */
        color: #e0e0e0; /* Soft white text for better readability */
        font-family: 'Roboto', sans-serif; /* Modern font */
    }

    .dashboard-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        padding: 20px;
        box-sizing: border-box;
        animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header h2 {
        color: #00ccff; /* Blue neon color */
        font-size: 2.5em; /* Adjusted size */
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        text-shadow: 0 0 20px rgba(0, 204, 255, 0.7); /* Neon glow effect */
        animation: textGlow 1.5s infinite alternate; /* Animation for glow effect */
    }

    @keyframes textGlow {
        0% { text-shadow: 0 0 20px rgba(0, 204, 255, 0.7); }
        100% { text-shadow: 0 0 30px rgba(0, 204, 255, 1); }
    }

    .header p {
        color: #b0b3b8; /* Lighter text for subtitle */
        font-size: 1.2em; /* Smaller size */
        margin-top: 10px;
    }

    .info-container {
        background-color: rgba(30, 30, 30, 0.9); /* Dark and transparent for depth */
        border-radius: 15px;
        padding: 30px; /* Adjusted padding */
        width: 100%; /* Full width */
        max-width: 600px; /* Max width to ensure it fits in the viewport */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .info-container:hover {
        transform: translateY(-5px); /* Lift effect on hover */
        box-shadow: 0 0 40px rgba(0, 204, 255, 0.8); /* Stronger glow on hover */
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin: 15px 0; /* Space between items */
        padding: 10px 0; /* Vertical padding */
        border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Light border for separation */
        position: relative;
    }

    .info-item:last-child {
        border-bottom: none; /* Remove border from last item */
    }

    .info-label {
        font-weight: bold;
        font-size: 18px; /* Adjusted font size */
        color: #00ccff; /* Blue neon color */
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: color 0.2s; /* Smooth color transition */
    }

    .info-value {
        font-size: 20px; /* Adjusted font size */
        color: #f8f9fa;
        text-align: right; /* Align value to the right */
        flex-grow: 1; /* Allow the value to take available space */
    }

    .info-item:hover .info-label {
        color: #0099cc; /* Slightly darker on hover */
    }

    @media (max-width: 768px) {
        .header h2 {
            font-size: 2em; /* Smaller heading on mobile */
        }

        .info-value {
            font-size: 18px; /* Smaller font size on mobile */
        }
    }
</style>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <div class="header">
        <h2>User Dashboard</h2>
        <p>Welcome to your personalized ticketing dashboard</p>
    </div>
    <div class="info-container">
        <?php if (!empty($userDetails)): ?>
            <?php foreach ($userDetails as $key => $value): ?>
                <div class="info-item">
                    <span class="info-label"><?php echo $key; ?></span>
                    <span class="info-value"><?php echo $value; ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="info-item">
                <span class="info-label">Error:</span>
                <span class="info-value">No user details available.</span>
            </div>
        <?php endif; ?>
    </div>
</div>
