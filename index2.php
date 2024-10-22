<?php
// Database connection
require_once 'conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch unique 'start' cities from route table
$startQuery = "SELECT DISTINCT start FROM route";
$startResult = $conn->query($startQuery);
$startCities = [];
if ($startResult->num_rows > 0) {
    while ($row = $startResult->fetch_assoc()) {
        $startCities[] = $row['start'];
    }
}

// Fetch unique 'stop' cities from route table
$stopQuery = "SELECT DISTINCT stop FROM route";
$stopResult = $conn->query($stopQuery);
$stopCities = [];
if ($stopResult->num_rows > 0) {
    while ($row = $stopResult->fetch_assoc()) {
        $stopCities[] = $row['stop'];
    }
}

// Handle form submission and query routes and trains
$routes = [];
$availableTrains = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fromCity = $_POST['from-city'];
    $toCity = $_POST['to-city'];
    $journeyDate = $_POST['journey-date']; // This can be used for date filtering if needed

    // Query for available routes between selected cities
    $stmt = $conn->prepare("SELECT * FROM route WHERE start = ? AND stop = ?");
    $stmt->bind_param('ss', $fromCity, $toCity); // 'ss' for two strings
    $stmt->execute();
    $result = $stmt->get_result();
    $routes = $result->fetch_all(MYSQLI_ASSOC);

    // If routes are found, fetch trains for each route
    if (!empty($routes)) {
        foreach ($routes as $route) {
            $trainQuery = "SELECT * FROM train"; // You can add conditions if needed
            $trainResult = $conn->query($trainQuery);
            $availableTrains = array_merge($availableTrains, $trainResult->fetch_all(MYSQLI_ASSOC));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 <link rel="icon" type="image/png" href="head.png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArVis - Ticket Booking</title>
    <style>
         





        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        html, body {
    background-color: #111111; /* Fallback background color */
    margin: 0;
    padding: 0;
    height: 100%; /* Ensures both html and body take up full height */
    overflow-x: hidden; /* Prevent horizontal scrolling */
}

body {
    background: url('images/2.jpeg') no-repeat center center fixed;
    background-size: cover;
    color: #f1f1f1; /* Text color */
    line-height: 1.6;
    min-height: 100vh; /* Ensures body covers full viewport height */
}

        body {
            background: url('images/2.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: black;

            background-repeat: no-repeat;
            margin: 0;

            background-position: center;
            background-repeat: no-repeat;
            color: #f1f1f1;
            line-height: 1.6;
        }
        

        /* NAVBAR */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background: #111111;
            border-radius: 10px;
            position: relative;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        nav .logo {
            display: flex;
            align-items: center;
        }

        nav .logo img {
            height: 50px;
            margin-right: 10px;
            margin-left: 30px;

        }

        nav .logo span {
            font-size: 1.8rem; /* Increased font size */
            color: #f1f1f1;
            font-weight: 700;
        }

        nav .links {
            display: flex;
            gap: 30px;
        }

        nav .links a {
            color: #f1f1f1;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav .links a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .buttons a {
            text-decoration: none;
        }

        .button {
            padding: 12px 24px;
            border-radius: 30px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background-color: #4A87C4;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #1ACDE1;
            transform: translateY(-3px);
        }

        /* HERO SECTION */
        .hero {
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.65);
            border-radius: 15px;
            margin: 30px 50px;
        }

        .hero h1 {
            font-size: 3rem;
            color: #f1f1f1;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        .hero p {
            font-size: 1.5rem;
            color: #d1d1d1;
            max-width: 600px;
            margin: 0 auto 40px auto;
        }

        .hero a {
            text-decoration: none;
        }

        .hero .cta-button {
            background-color: #035afc;
            color: white;
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .hero .cta-button:hover {
            background-color: #0261db;
            transform: translateY(-3px);
        }


        .scrollable-offers-section {
    width: 100%; /* Full width of the viewport */
    min-height: 100vh; /* Ensure it covers the entire viewport height */
    background: none;
    position: relative;
    padding: 20px 0;
    color: white;
    z-index: 10;
    overflow: hidden;
}

.offers-section {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    width: 100%;
    height: auto;
    position: relative;
    animation: moveBanner 30s linear infinite; /* Keeps the banner moving */
    padding: 20px;
    background: rgba(0, 0, 0, 0.3); /* Semi-transparent background */
    border-radius: 10px;
    overflow: hidden; /* Hide overflow for smooth appearance */
}

.offer-container {
    display: flex;
    position: relative;
    flex-wrap: nowrap;
    padding-right: 100px; /* Ensure space at the end */
    /* Dynamically adjust width based on number of cards (example: 6 cards) */
    width: calc(450px * 6 + 40px * 5); /* Update this based on the number of cards */
    /* For 6 cards: 450px width per card + 40px margin between each */
}

.offer {
    flex: 0 0 auto;
    width: 450px; /* Set card width */
    margin-right: 40px;
    background: rgba(255, 255, 255, 0.2);
    padding: 30px;
    border-radius: 10px;
    backdrop-filter: blur(10px); /* Blur effect for a modern look */
    transition: transform 0.3s ease;
}

.offer:hover {
    transform: scale(1.05); /* Slight scaling effect on hover */
}

.offer h3 {
    margin: 0;
    font-size: 24px;
    color: #fff;
}

.offer p {
    margin-top: 10px;
    font-size: 16px;
    color: #e6e6e6;
}

.offer .cta {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #fff;
    color: #0066cc;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.offer .cta:hover {
    background-color: #00ccff;
    color: #fff;
}

/* Continuous scrolling animation */
@keyframes moveBanner {
    0% {
        transform: translateX(90%);
    }
    100% {
        transform: translateX(-90%);
    }
}

/* Hide scrollbar for webkit browsers (Chrome, Safari) */
.offers-section::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for other browsers */
.offers-section {
    -ms-overflow-style: none; /* Internet Explorer 10+ */
    scrollbar-width: none; /* Firefox */
}




        /* BOOKING SECTION */
          /* BOOKING SECTION */
    .scrollable-section {
        height: 100vh; /* Full height of the viewport */
        overflow-y: auto; /* Enables vertical scrolling */
        background-image: url('busbg.webp'); /* Background image */
        background-size: cover; /* Cover the entire section */
        background-attachment: fixed; /* Keep background fixed */
        display: flex;
        align-items: center; /* Center content vertically */
        justify-content: center; /* Center content horizontally */
    }
    .booking-section {
        padding: 100px 20px;
        background-color: transparent;
        margin: 50px 0;
        border-radius: 15px;
        max-width: 900px;
        margin: 0 auto;
        box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.1);
    }
    .booking-section:hover {
        transform: scale(1.02); /* Slight zoom on hover */
    }

    .booking-section h2 {
        text-align: center;
        padding: 2px 20px;
        background-color: rgba(0, 0, 0, 100); /* Light blue with 50% transparency */
        margin: 10px 0;
        border-radius: 15px;
        max-width: 900px;
        margin: 0 auto;
        box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.1);
    }

    /* Booking Form */
    #booking-form {
        display: flex;
        gap: 20px;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.6); /* Dark transparent background */
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.7); /* Subtle shadow */
        flex-wrap: wrap; /* Allow wrapping for better mobile support */
    }

    /* Dropdown & Date Picker */
    .dropdown,
    .date-picker {
        padding: 10px 15px; /* Reduce padding slightly */
        border-radius: 12px;
        border: 2px solid transparent;
        background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent */
        color: white;
        font-size: 18px;
        width: 220px;
        height: 50px;
        outline: none;
        transition: border-color 0.4s, box-shadow 0.4s;
        text-shadow: 0 0 10px rgba(0, 255, 255, 0.8); /* Neon glow */
        opacity: 0.9;
    }

    .dropdown::placeholder,
    .date-picker::placeholder {
        color: rgba(255, 255, 255, 0.9); /* Make placeholder visible */
        opacity: 1;
    }
    .dropdown:focus,
    .date-picker:focus {
        border-color: #00e6e6; /* Neon border on focus */
        box-shadow: 0 0 20px rgba(0, 230, 230, 0.8); /* Glow effect */
    }
    .dropdown option,
    .date-picker option {
        background-color: #101010;
        color: white;
    }

    /* Submit Button */
    .submit-btn {
        background-color: #00e6e6; /* Neon blue */
        color: black;
        border: none;
        padding: 15px 30px;
        border-radius: 30px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0px 0px 20px rgba(0, 230, 230, 0.7); /* Glowing button */
    }
    .submit-btn:hover {
        background-color: #00ffb2; /* Lighter neon effect on hover */
        transform: translateY(-5px); /* Lift on hover */
    }
    /* Enhanced Date Picker */
    .date-picker {
        position: relative;
    }

    .date-picker::-webkit-calendar-picker-indicator {
        filter: invert(1); /* Make the calendar icon fit dark mode */
        background-size: 80%;
        cursor: pointer;
    }
    .train-section {
    background-color: #1c1c1c;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6);
    max-width: 800px;
    margin: auto;
    color: #fff;
    text-align: center;
}

.train-heading {
    font-family: 'Roboto', sans-serif;
    font-size: 28px;
    color: #00ffcc;
    margin-bottom: 20px;
}

.train-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #2b2b2b;
    border-radius: 10px;
    overflow: hidden;
}

.train-table th, .train-table td {
    padding: 15px;
    text-align: center;
    color: #fff;
    border-bottom: 1px solid #444;
}

.train-table th {
    background-color: #00ffcc;
    font-size: 18px;
}

.train-table tr:hover {
    background-color: #333;
    transform: scale(1.02);
    transition: all 0.3s ease;
}

.train-table td {
    font-size: 16px;
}

.book-now-btn {
    background-color: #00ffcc;
    color: #1c1c1c;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.book-now-btn:hover {
    background-color: #00b3a0;
    transform: scale(1.05);
}

.no-train-message {
    font-size: 18px;
    color: #ff6666;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .train-table th, .train-table td {
        font-size: 14px;
        padding: 10px;
    }

    .book-now-btn {
        padding: 8px 15px;
        font-size: 14px;
    }
}


    .date-picker::-webkit-inner-spin-button,
    .date-picker::-webkit-clear-button {
        display: none; /* Hide spin buttons */
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        #booking-form {
            flex-direction: column;
            gap: 10px;
        }

        .dropdown,
        .date-picker {
            width: 100%;
        }

        .submit-btn {
            width: 100%;
        }
    }



/* FOOTER */
.scrollable-footer-section {
    height: 0vh; /* Full height of the viewport */
    overflow-y: auto; /* Enables vertical scrolling */
    background-image: url('busbg.webp'); /* Background image */
    background-size: cover; /* Cover the entire section */
    background-attachment: fixed; /* Keep background fixed */
    padding: 10px 0; /* Add some padding for aesthetics */
    width: 100%;


    display: flex;
    position: relative; /* Make sure it's positioned correctly */

    align-items: center; /* Center content vertically */
    justify-content: center; /* Center content horizontally */
}

.footer-container {
    margin: 0 auto; /* Center the content */
    padding: 10px 20px; /* Add side padding */
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    flex-direction: column;
    align-items: center; /* Center the content */
}


footer {
    background-color: #111111; /* Darker blue for a modern look */
    color: #ffffff;
    padding:  5px; /* Reduced padding */
    font-family: 'Arial', sans-serif;
    margin-bottom: -20px;
    border-top: 5px solid #1ACDE1; /* Light border for definition */
}

footer .footer-columns {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px 0; /* Padding around footer columns */
}

.footer-columns {
    display: flex;
    justify-content: space-between; /* Space out the columns */
    width: 100%; /* Make the columns full width */
}

footer .column h3 {
    color: #1ACDE1; /* Lighter header color for visibility */
    margin-bottom: 10px; /* Reduced margin */
    font-size: 1.2rem; /* Adjusted font size */
    text-transform: uppercase; /* Uppercase for boldness */
    letter-spacing: 1px; /* Increased letter spacing */
}

footer .column ul {
    list-style-type: none;
    padding-left: 0; /* Remove default padding */
}

footer .column ul li {
    margin-bottom: 5px; /* Reduced margin for compactness */
}

footer .column ul li a {
    color: #f1f1f1;
    text-decoration: none;
    transition: color 0.3s; /* Hover effect */
}

footer .column ul li a:hover {
    color: #1ACDE1; /* Change color on hover */
    text-decoration: underline; /* Underline on hover */
}

footer .social-icons a {
    margin: 0 8px; /* Reduced margin */
    font-size: 1.5rem;
    color: #f1f1f1;
    transition: color 0.3s; /* Hover effect */
}

footer .social-icons a:hover {
    color: #1ACDE1; /* Change color on hover */
}
.column {
    flex: 1; /* Allow columns to grow */
    margin: 0 10px; /* Reduced column margins */
}

/* Footer Newsletter */
.newsletter {
    margin: 10px 0; /* Reduced margin */
    text-align: center; /* Center the newsletter text */
}

.newsletter h3 {
    color: #1ACDE1; /* Lighter color for the newsletter header */
    margin-bottom: 10px; /* Reduced margin */
}

.newsletter form {
    display: flex;
    justify-content: center;
    gap: 8px; /* Reduced gap */
}
.footer-bottom {
    text-align: center; /* Center footer bottom text */
    margin-top: 10px; /* Reduced margin */
    width: 100%; /* Ensure it covers the full width */
}
.social-icons a {
    margin: 0 10px; /* Space out social icons */
    font-size: 24px; /* Size of social icons */
}

.newsletter input[type="email"] {
    padding: 10px; /* Adjusted padding */
    border-radius: 20px; /* Rounded edges */
    border: 1px solid #1ACDE1; /* Lighter border color */
    outline: none;
    font-size: 0.9rem; /* Adjusted font size */
    transition: border-color 0.3s; /* Transition for border color */
}

.newsletter input[type="email"]:focus {
    border-color: #fff; /* White border on focus */
}

.newsletter button {
    padding: 10px 20px; /* Adjusted padding */
    background-color: #1ACDE1; /* Button color */
    color: white;
    border: none;
    border-radius: 20px; /* Rounded edges */
    font-size: 0.9rem; /* Adjusted font size */
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.newsletter button:hover {
    background-color: #0DA4B6; /* Darker shade on hover */
    transform: translateY(-2px); /* Slight lift effect */
}

footer p {
    margin: 5px 0; /* Reduced margin */
    color: #d1d1d1; /* Lighter text color for footer text */
}


body {
  font-family: 'Arial', sans-serif;
  background-color: #f9f9f9;  /* Light background */
  color: #333;  /* Dark text color */
}

.faq-section {
  max-width: 900px;
  margin: 40px auto;
  padding: 50px;
}

h1 {
  text-align: center;
  font-size: 32px;
  margin-bottom: 20px;
  color: white;  /* Ensure dark text color for visibility */
  border: 2px solid #ddd;  /* Light border around the heading */
  padding: 10px;
  border-radius: 10px;  /* Rounded corners for a smooth look */
  background-color: #111111;  /* White background for the heading */
}

.faq-tabs {
  display: flex;
  color: #111111;
  justify-content: space-around;
  border-bottom: 2px solid #ddd;
}

.tab-link {
  font-size: 18px;
  background: none;
  border: 2px solid #ddd;  /* Light border around the heading */
  padding: 10px 20px;
  background-color: #111111;  /* White background for the heading */

  cursor: pointer;
  border-radius: 10px;  /* Rounded corners for a smooth look */
  color: white;  /* Light gray text color */
  position: relative;
}

.tab-link.active {
  color: #d9232d;  /* Highlight active tab with a red color */
  font-weight: bold;
}

.tab-link.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 100%;
  height: 2px;
  background: #d9232d;
}

.faq-item {
  border: 1px solid #ddd;
  border-radius: 8px;
  margin: 10px 0;
  width: 100%;
  padding: 15px;
  background-color: #111111;  /* White background for FAQ items */
  color: white;  /* Dark text color for better visibility */
}

.faq-item h3 {
  display: flex;
  justify-content: space-between;
  cursor: pointer;
  font-size: 18px;
  margin: 0;
  color: white;  /* Dark color for the question text */
}

.toggle-icon {
  font-size: 20px;
  color: #666;  /* Gray color for the toggle icon */
}

.faq-answer {
  display: none;
  margin-top: 10px;
  color: white;  /* Dark gray color for the answer text */
  font-size: 16px;
}

.faq-item.active .faq-answer {
  display: block;
}

.faq-item.active .toggle-icon {
  transform: rotate(45deg);
}



 </style>
</head>

<body>
    <nav>
        <div class="logo">
            <img src="images/logo.png" alt="ArVis Logo">
        </div>
        <div class="links">
            <a href="index.php">Home</a>
            <a href="index.php#aboutus">About</a>
            <a href="index.php#services">Services</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="buttons">
            <a href="pro/individual_reg.php">
                <button class="button">Sign Up</button>
            </a>
            <a href="pro/signin.php">
                <button class="button">Sign In</button>
            </a>
        </div>
    </nav>

    <!-- Offer Section -->
    <section class="scrollable-offers-section">
        <div class="offers-section">
            <div class="offer-container">
                <div class="offer">
                    <h3>Discount on Early Bookings</h3>
                    <p>Get 20% off when you book your tickets 30 days in advance!</p>
                </div>
                <div class="offer">
                    <h3>Group Discounts</h3>
                    <p>Book for a group of 10 or more and get an additional 15% off!</p>
                </div>
                <div class="offer">
                    <h3>Special Weekend Offers</h3>
                    <p>Enjoy a flat rate on all weekend journeys this month!</p>
                </div>
                <div class="offer">
                    <h3>Free Snacks!</h3>
                    <p>Complimentary snacks on selected routes when you book online!</p>
                </div>
            </div>
        </div>

        <!-- Booking Section (moved below the offer section) -->
        <div class="booking-section">
            <h2>Bus Booking</h2>
            <!-- HTML Form -->
            <form id="booking-form" method="POST">
                <select class="dropdown" id="from-city" name="from-city" required>
                    <option value="">Pick-up Location</option>
                    <?php foreach ($startCities as $startCity): ?>
                        <option value="<?php echo htmlspecialchars($startCity); ?>">
                            <?php echo htmlspecialchars($startCity); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="dropdown" id="to-city" name="to-city" required>
                    <option value="">Drop Location</option>
                    <?php foreach ($stopCities as $stopCity): ?>
                        <option value="<?php echo htmlspecialchars($stopCity); ?>">
                            <?php echo htmlspecialchars($stopCity); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input class="date-picker" type="date" id="journey-date" name="journey-date" required>
                <button class="submit-btn" type="submit">Search Bus</button>
            </form>

            <!-- Display Results -->
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="train-section">
        <h3 class="train-heading">Available Bus</h3>
        <?php if (!empty($routes) && !empty($availableTrains)): ?>
            <table class="train-table">
                <thead>
                    <tr>
                        <th>Train Name</th>
                        <th>First Class Seats</th>
                        <th>Second Class Seats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($availableTrains as $train): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($train['name']); ?></td>
                            <td><?php echo htmlspecialchars($train['first_seat']); ?></td>
                            <td><?php echo htmlspecialchars($train['second_seat']); ?></td>
                            <td>
                                <a href="pro/signin.php" class="book-now-btn">Book Now</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-train-message">No Bus available for the selected route.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>


        </div>

 

        <section class="faq-section">
        <div id="faq">

  <h1>FAQs related to Bus Tickets Booking</h1>
  <div class="faq-tabs">
    <button class="tab-link active" data-tab="general">General</button>
    <button class="tab-link" data-tab="ticket-related">Ticket-related</button>
    <button class="tab-link" data-tab="payment">Payment</button>
    <button class="tab-link" data-tab="cancellation">Cancellation & Refund</button>
    <button class="tab-link" data-tab="insurance">Insurance</button>
  </div>

  <div class="faq-content" id="general">
    <div class="faq-item">
      <h3>What is the purpose of the Online Ticketing System?  <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>The Online Ticketing System allows users to purchase tickets for various events and services, providing a seamless booking experience without the need for a physical ticketing office.</p>
      </div>
    </div>
    <div class="faq-item">
      <h3>How do I create an account?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>You can create an account by clicking on the "Sign Up" button on our homepage. Fill in your details, verify your email, and you'll be ready to book tickets!</p>
      </div>
    </div>

    <div class="faq-item">
      <h3>Is my personal information secure?

      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Yes, we prioritize your privacy and security. All personal information is encrypted and stored securely, following industry best practices.
</p>
      </div>
    </div>
  </div>

  <!-- Other tabs content -->
  <div class="faq-content" id="ticket-related" style="display: none;">
    <!-- FAQ items for Ticket-related -->
    <div class="faq-item">
      <h3>How can I view my purchased tickets? <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>After logging into your account, navigate to the "My Bookings" section to view and manage all your purchased tickets..</p>
      </div>
    </div>

    <div class="faq-item">
      <h3>Can I transfer my ticket to someone else?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Yes, tickets can be transferred to another individual. Please contact our customer support with the necessary details to facilitate the transfer.</p>
      </div>
    </div>


    <div class="faq-item">
      <h3>What should I do if I lose my ticket?

      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>If you lose your ticket, please contact our support team with your booking reference. We can assist you in reissuing your ticket.
</p>
      </div>
    </div>


  </div>
  <div class="faq-content" id="payment" style="display: none;">

  <div class="faq-item">
      <h3>What payment methods do you accept?

      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>We accept various payment methods, including credit/debit cards, PayPal, and other digital wallets to provide you with flexible payment options.

</p>
      </div>
    </div>

    <div class="faq-item">
      <h3>Will I receive a receipt for my purchase?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Yes, after completing your purchase, you will receive a confirmation email containing your receipt and ticket details.</p>
      </div>
    </div>


    <div class="faq-item">
      <h3>Is it safe to make payments on your site?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Absolutely! We use SSL encryption and adhere to PCI DSS standards to ensure that all payment transactions are secure.</p>
      </div>
    </div>


  </div>
  <div class="faq-content" id="cancellation" style="display: none;">

  <div class="faq-item">
      <h3>What is your cancellation policy?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>You can cancel your ticket up to 24 hours before the event for a full refund. Cancellations made within 24 hours are subject to our refund policy.</p>
      </div>
    </div>

    <div class="faq-item">
      <h3>How can I request a refund?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>To request a refund, please navigate to the "My Tickets" section in your account and select the ticket you wish to cancel. Follow the prompts to initiate your refund request.</p>
      </div>
    </div>

    <div class="faq-item">
      <h3>When will I receive my refund?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Refunds are processed within 5-7 business days after approval. The time it takes for the funds to reflect in your account may vary based on your bank or payment provider.</p>
      </div>
    </div>


  </div>
  <div class="faq-content" id="insurance" style="display: none;">

  <div class="faq-item">
      <h3>Do you offer ticket insurance?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Yes, we provide ticket insurance options during the checkout process. This insurance covers unforeseen circumstances that may prevent you from attending the event.

</p>
      </div>
    </div>


    <div class="faq-item">
      <h3>What does the ticket insurance cover?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>Ticket insurance typically covers event cancellations, illness, and other emergencies that might hinder your attendance. Please review the specific terms during purchase.</p>
      </div>
    </div>


    <div class="faq-item">
      <h3>How can I file a claim for ticket insurance?
      <span class="toggle-icon">+</span></h3>
      <div class="faq-answer">
        <p>To file a claim, contact our customer support with the details of your situation and your policy number. We will guide you through the claims process.</p>
      </div>
    </div>


     <script>
        document.querySelectorAll('.faq-item h3').forEach((faq) => {
  faq.addEventListener('click', () => {
    faq.parentNode.classList.toggle('active');
  });
});

document.querySelectorAll('.tab-link').forEach((tab) => {
  tab.addEventListener('click', function () {
    document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
    this.classList.add('active');

    const tabContent = document.querySelectorAll('.faq-content');
    tabContent.forEach(content => content.style.display = 'none');
    document.getElementById(this.getAttribute('data-tab')).style.display = 'block';
  });
});

     </script>
  </div>
  
</section>




   
    
 <!-- FOOTER SECTION -->
 <footer class="footer">
 <div id="contact">

</div>
        <div class="footer-container">
            <div class="footer-columns">
                <div class="column">
                    <h3>Customer Support</h3>
                    <ul>
                    <li style="text-align: left;"><a href="#faq">FAQs</a></li>
                    <li style="text-align: left;"><a href="policy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="column">
                    <h3 style="text-align:center;">Connect with Us</h3>
                    <div class="social-icons" style="text-align: center;">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="column">
                <h3 style="text-align: right;">About Us</h3>
                    <ul>
                        <li style="text-align: right;"><a href="index.php#aboutus">Our Story</a></li>
                        
                    </ul>
                </div>
            </div>
            <div class="newsletter">
                <h3>Subscribe to Our Newsletter</h3>
                <form>
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Arvis. All rights reserved.</p>
                <p>Contact Us: <a href="mailto:support@booking.com">support@booking.com</a></p>
            </div>
        </div>
    </footer>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>



</body>

</html>
