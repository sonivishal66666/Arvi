<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" type="image/png" href="head.png">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <link rel="stylesheet" href="style.css">
  <title>BUS TICKET</title>
  <style>
    •⁠  ⁠{
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  font-family: 'Arial', sans-serif;
}
html, body {
    background-color: #011a45; /* Fallback background color */
    margin: 0;
    padding: 0;
    height: 100%; /* Ensures both html and body take up full height */
    overflow-x: hidden; /* Prevent horizontal scrolling */
    overflow-y: auto; /* Prevent vertical scrolling */

}
body {
  background: url('images/2.jpeg') no-repeat center center fixed;
  background-size: cover;
            color: black;

            background-repeat: no-repeat;
            margin: 0;
            padding: 0;

            background-position: center;
            background-repeat: no-repeat;
            color: #f1f1f1;
            line-height: 1.6;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 20px; /* Adjusted padding for smaller screens */
  background: #111111;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  width: 100%;
  position: fixed; /* Make navbar stick to the top */
  top: 0;
  z-index: 1000; /* Ensure the navbar stays above content */
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

nav .links {
  display: flex;
}

nav .links .link {
  margin: 0 15px;
}

nav .links a {
  color: white;
  text-decoration: none;
  font-size: 1.2rem;
  transition: color 0.3s ease;
}

nav .links a:hover {
  font-style: oblique;
}

nav .buttons {
  display: flex;
  gap: 10px;
  margin-right: 30px;
}

nav .button {
  padding: 10px 20px;
  border-radius: 5px;
  border: none;
  outline: none;
  font-size: 1.1rem;
  font-weight: bolder;
  color: white;
  background-color: rgb(23, 158, 199);
  cursor: pointer;
}

nav .button:hover {
  transform: scale(1.05);
  border: 2px solid rgb(213, 221, 230);
  box-shadow: 0 0 10px rgb(206, 210, 214);
  color: rgb(220, 227, 234);
  transform: translateY(-3px);
  background-color: transparent;
  box-shadow: 0 4px 10px rgb(251, 245, 245);
}

section {
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  z-index: 2;
  margin: 0; /* Remove any margin that may add extra space */
  padding: 0; /* Remove extra padding */
}

.content {
  text-align: center;
  padding: 20px;
  border-radius: 15px; /* Rounded corners */
  
  transition: transform 0.2s ease;
}

.content:hover {
  transform: scale(1.02); /* Slight scale effect on hover */
}

.content h1 {
  font-size: 2.5rem;
  margin-bottom: 20px;
  
}

.content button {
  width: 60%; /* Increased button width */
  height: 50px; /* Increased height */
  border-radius: 10px;
  background: rgb(7, 113, 219);
  border: none;
  outline: none;
  color: white;
  cursor: pointer;
  font-size: 1.2rem;
  font-weight: bold;
  transition: transform 0.2s ease, background-color 0.3s ease;
}

.content button:hover {
  transform: scale(1.05);
  background-color: transparent;
  border: 2px solid rgb(213, 221, 230);
  color: rgb(11, 11, 12);
}

/* Global reset */
body, html {
    margin: 0;
    padding: 0;
}

/* About Us Section - Futuristic UI */
.about-us-section {
    background: #111111; /* Dark background for modern look */
    color: #f0f0f0; /* Light text color */
    text-align: center; /* Center-align text */
    padding: 50px 20px; /* Increased padding for balanced spacing */
    border-radius: 15px; /* Rounded corners for smooth UI */
    margin: 50px auto; /* Centrally aligned */
    max-width: 1000px; /* Max width to control layout */
    transition: transform 0.4s, box-shadow 0.4s; /* Smooth hover animation */
}

.about-us-section:hover {
    transform: translateY(-10px); /* Lift effect on hover */
    box-shadow: 0 15px 50px rgba(0, 255, 127, 0.3); /* Enhance neon glow on hover */
}

/* Heading styling */
.about-us-section h2 {
    font-family: 'Orbitron', sans-serif; /* Futuristic font */
    font-size: 3rem; /* Large heading */
    color: #00ffb3; /* Neon green heading for a modern look */
    letter-spacing: 2px; /* Spacing for futuristic feel */
    margin-bottom: 20px; /* Balanced margin */
    text-transform: uppercase; /* All caps for bold style */
}

/* Paragraph styling */
.about-us-section p {
    font-family: 'Roboto', sans-serif; /* Clean, modern font */
    font-size: 1.2rem; /* Comfortable font size for readability */
    line-height: 1.7; /* Increased line height for readability */
    color: #ccc; /* Soft gray for text */
    margin: 20px 0; /* Space between paragraphs */
    max-width: 900px; /* Limit text width for readability */
    margin-left: auto;
    margin-right: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-us-section {
        padding: 30px 15px; /* Adjust padding for smaller screens */
    }
    
    .about-us-section h2 {
        font-size: 2.5rem; /* Smaller heading on mobile */
    }
    
    .about-us-section p {
        font-size: 1.1rem; /* Slightly smaller text on mobile */
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

/* General Layout */
.services-section {
    background: transparent; /* Dark background for consistency */
    padding: 50px 20px; /* Consistent padding */
    color: #f0f0f0;
    text-align: center;
    border-radius: 15px; /* Smooth, rounded corners */
    margin: 50px auto; /* Centered alignment */
    max-width: 1000px; /* Same max-width as About Us section */
    transition: transform 0.4s, box-shadow 0.4s;
}



.section-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.5rem;
    margin-bottom: 50px;
    color: #fff;
    letter-spacing: 2px;
}

.services-grid {
    display: flex;
    justify-content: space-around;
    gap: 40px;
    flex-wrap: wrap;
}

.service-item {
    flex: 1;
    max-width: 300px;
    padding: 20px;
    background: #1b1b1b;
    border-radius: 10px;
    transition: transform 0.4s, box-shadow 0.4s;
}

.service-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0, 255, 127, 0.2);
}

.icon-container {
    padding: 15px;
    margin-bottom: 20px;
}

.service-icon {
    width: 80px;
    filter: drop-shadow(0px 0px 10px #00ffb3);
    transition: filter 0.3s;
}

.service-item:hover .service-icon {
    filter: drop-shadow(0px 0px 20px #00ffb3);
}

.service-title {
    font-size: 1.5rem;
    font-family: 'Orbitron', sans-serif;
    margin: 20px 0;
    color: #00ffb3;
}

.service-description {
    font-size: 1rem;
    line-height: 1.5;
    color: #ccc;
}

.service-btn {
    display: inline-block;
    padding: 10px 25px;
    margin-top: 20px;
    background: #00ffb3;
    color: #111;
    font-family: 'Orbitron', sans-serif;
    text-decoration: none;
    border-radius: 50px;
    transition: background 0.3s, box-shadow 0.3s;
}

.service-btn:hover {
    background: #00ffa5;
    box-shadow: 0 0 15px #00ffb3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .services-grid {
        flex-direction: column;
        gap: 20px;
    }

    .service-item {
        max-width: 100%;
    }
}

/* Main Content Section - Consistent with About Us Section */
.main-content {
    background: transparent; /* Dark background for a modern look */
    color: #w; /* Light text color */
    padding: 50px 20px; /* Consistent padding */
    margin: 50px auto; /* Center aligned with spacing */
    max-width: 1000px; /* Same max-width as About Us section */
    text-align: center; /* Center the content */
    transition: transform 0.4s, box-shadow 0.4s; /* Smooth animations */
}



.main-content h1 {
    font-family: 'Orbitron', sans-serif; /* Futuristic font */
    font-size: 2.5rem; /* Large heading size */
    color: white; /* Neon green text for modern feel */
    letter-spacing: 2px; /* Spaced-out letters for futuristic touch */
    margin-bottom: 20px; /* Space below the heading */
}

.main-content button {
    padding: 15px 30px; /* Consistent button padding */
    background: #00ffb3; /* Neon green button */
    border: none; /* No border for a clean look */
    border-radius: 50px; /* Rounded button for modern feel */
    font-family: 'Orbitron', sans-serif; /* Futuristic button font */
    color: #111; /* Dark text on button */
    font-size: 1.2rem; /* Larger button text */
    cursor: pointer;
    transition: background 0.3s, box-shadow 0.3s;
}

.main-content button:hover {
    background: #00ffa5; /* Slightly lighter neon on hover */
    box-shadow: 0 0 20px #00ffb3; /* Neon glow effect on hover */
}

/* Responsive Design */
@media (max-width: 768px) {
    .main-content {
        padding: 30px 15px; /* Adjusted padding for smaller screens */
    }

    .main-content h1 {
        font-size: 2rem; /* Smaller heading for mobile */
    }

    .main-content button {
        font-size: 1rem; /* Smaller button text for mobile */
    }
}



  </style>

</head>
<body>

  <!-- Navigation Section -->
  <nav class="navbar">
    <!-- Logo Section -->
    <div class="logo">
      <img src="images/logo.png" alt="ARVIS">
      
    </div>

    <!-- Links Section -->
    <div class="links">
      <div class="link" data-aos="fade-left" data-aos-duration="1200"><a href="index.php">Home</a></div>
      <div class="link" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100"><a href="#aboutus">About</a></div>
      <div class="link" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200"><a href="#services">Services</a></div>
      <div class="link" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="600"><a href="#contact">Contact</a></div>
    </div>

    <!-- Buttons Section -->
    <div class="buttons">
    <a href="pro/individual_reg.php" style="text-decoration: none;">
    <button class="button" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="800">Sign Up</button>
</a>     
<a href="pro/signin.php" style="text-decoration: none;">
        <button class="button" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="800">Sign In</button>
    </a>
    <a href="pro/adminsignin.php" style="text-decoration: none;">

<button class="button" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="800">Admin</button> <!-- Admin button added -->
</a>
    </div>
  </nav>

  <section class="main-content">
    <div class="content">
        <h1 data-aos="fade-left" data-aos-duration="2000" data-aos-delay="900" style="margin-bottom: 20px;">
        Your Ultimate Destination for Booking Everything

</h1>
        <a href="index2.php" style="text-decoration: none;">
            <button data-aos="zoom-in" data-aos-duration="2900" data-aos-delay="1200">
                GET Your TICKET
            </button>
        </a>
    </div>
</section>


  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>


<section class="about-us-section" id="aboutus">
    <div class="content">
        <h2>About Us</h2>
        <p>
        Welcome to Arvis, your trusted platform for seamless ticket booking experiences. Whether you're planning a vacation, attending a concert, or catching a movie, Arvis makes the process simple, fast, and secure.

</p>
        <p>
        At Arvis, we believe that booking tickets should be effortless and enjoyable. Our platform is built on cutting-edge serverless architecture, ensuring speed, reliability, and real-time updates. With just a few clicks, you can secure tickets for events, transportation, or any adventure on your list.
        </p>
        <p>
        Our mission is to redefine convenience and provide a hassle-free booking experience. We focus on delivering excellent service by offering a user-friendly interface, multiple payment options, and a commitment to security. With a cloud-based system, Arvis offers flexibility, so you can book tickets anytime, anywhere.        </p>
    </div>
</section>


<section id="services" class="services-section">

    <div class="container">
        <h2 class="section-title">Our Services</h2>
        <div class="services-grid">
            <div class="service-item">
                <div class="icon-container">
                </div>
                <h3 class="service-title">Seamless Travel Solutions</h3>
                <p class="service-description">Train Journeys: Enjoy scenic train travel with easy booking for routes, sleeper cabins, and family compartments.</p>
                <p class="service-description">Book flights effortlessly with exclusive deals on last-minute tickets and multi-city itineraries. </p>
                <p class="service-description">Bus Travel: Choose from luxury coaches to budget options with real-time tracking and onboard amenities.</p>
                <p class="service-description">Event Experiences: Access tickets for concerts, theater, and festivals, complete with VIP packages, early bird discounts, and special seating..</p>

                <p class="service-description"></p>


            </div>
            <div class="service-item">
                <div class="icon-container">
                </div>
                <h3 class="service-title">24/7 Customer Support</h3>
                <p class="service-description">Travel with peace of mind! Our dedicated customer support team is available around the clock to assist you with bookings, changes, or inquiries—no matter where you are.                </p>
                <p class="service-description">Whether you need help with booking, changes to your itinerary, or navigating our platform, our friendly representatives are just a message away.</p>
                <p class="service-description">We also offer multilingual support to cater to our diverse customer base, making it easy for travelers from different backgrounds to access the help they need.</p>
            </div>
            <div class="service-item">
                <div class="icon-container">
                </div>
                <h3 class="service-title">User Dashboard</h3>
                
                <p class="service-description">Access your account and manage your bookings effortlessly.</p>
                <p class="service-description">Seamlessly manage your bookings with our intuitive User Dashboard where you can view upcoming trips, modify reservations, explore personalized recommendations, and access quick links to all your travel needs</p>
                <p class="service-description">all designed to enhance your travel experience and keep everything organized at your fingertips.</p>
            </div>
        </div>
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
                    <li style="text-align: left;"><a href="index2.php#faq">FAQs</a></li>
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
                        <li style="text-align: right;"><a href="#aboutus">Our Story</a></li>
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