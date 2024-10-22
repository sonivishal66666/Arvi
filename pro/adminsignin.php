<?php
session_start();
require_once '../conn.php';
$file = "admin";

$cur_page = 'signup';
include 'includes/inc-header.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (!isset($email, $password)) {
?>
<script>
    alert("Ensure you fill the form properly.");
</script>
<?php
    } else {
        // Check for login
        $password = md5($password);
        $check = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $check->bind_param("ss", $email, $password);
        if (!$check->execute()) die("Form Filled With Error");
        $res = $check->get_result();
        $no_rows = $res->num_rows;
        if ($no_rows ==  1) {
            $row = $res->fetch_assoc();
            $id = $row['id'];
            session_regenerate_id(true);
            $_SESSION['category'] = "super";
            $_SESSION['admin'] = $id;
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const successDiv = document.createElement("div");
        successDiv.innerHTML = "Access Granted!";
        successDiv.style.position = "fixed";
        successDiv.style.top = "50%";
        successDiv.style.left = "50%";
        successDiv.style.transform = "translate(-50%, -50%)";
        successDiv.style.padding = "20px";
        successDiv.style.backgroundColor = "#28a745"; // Green color
        successDiv.style.color = "#fff";
        successDiv.style.borderRadius = "5px";
        successDiv.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.5)";
        successDiv.style.zIndex = "1000";
        document.body.appendChild(successDiv);
        setTimeout(() => {
            window.location = "admin.php";
        }, 2000);
    });
</script>
<?php
        } else { ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const errorDiv = document.createElement("div");
    errorDiv.innerHTML = "Access Denied.";
    errorDiv.style.position = "fixed";
    errorDiv.style.top = "50%";
    errorDiv.style.left = "50%";
    errorDiv.style.transform = "translate(-50%, -50%)";
    errorDiv.style.padding = "20px";
    errorDiv.style.backgroundColor = "#dc3545"; // Red color
    errorDiv.style.color = "#fff";
    errorDiv.style.borderRadius = "5px";
    errorDiv.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.5)";
    errorDiv.style.zIndex = "1000";
    document.body.appendChild(errorDiv);
    setTimeout(() => {
        errorDiv.style.opacity = 0; // Fade out effect
        setTimeout(() => {
            document.body.removeChild(errorDiv);
        }, 500);
    }, 3000);
});
</script>
<?php
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #0f0c29, #302b63, #24243e); /* Gradient background */
            color: white; /* White text */
            font-family: 'Poppins', sans-serif; /* Futuristic font */
            overflow: hidden; /* Prevent scrolling during landing screen */
            height: 100vh; /* Full height */
        }

        .signup-page {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%; /* Full height */
            position: relative;
            backdrop-filter: blur(10px); /* Blur effect for the background */
        }

        .form {
            background: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            width: 400px; /* Fixed width */
            text-align: center;
            transition: transform 0.3s; /* Scale effect */
            position: relative;
            overflow: hidden;
        }

        .form::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: transform 0.5s ease;
            transform: translate(-50%, -50%) scale(0);
            z-index: 0; /* Behind content */
        }

        .form:hover::before {
            transform: translate(-50%, -50%) scale(1); /* Expanding circle effect */
        }

        .form h2 {
            margin-bottom: 20px;
            position: relative;
            z-index: 1; /* On top of background */
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white; /* White text in input fields */
            transition: border 0.3s ease, background 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border: 1px solid #5a67d8; /* Highlight on focus */
            outline: none;
            background-color: rgba(255, 255, 255, 0.3); /* Slightly brighter on focus */
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
            position: relative;
            z-index: 1; /* On top of background */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.05); /* Button scaling */
        }

        /* Landing Screen Styles */
        #landing-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            z-index: 999;
            transition: opacity 1s ease;
        }

        #landing-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Background Animation */
        @keyframes backgroundAnimation {
            0% { background-color: #1a1a1a; }
            50% { background-color: #0d0d0d; }
            100% { background-color: #1a1a1a; }
        }

        .animated-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            animation: backgroundAnimation 10s infinite alternate;
            z-index: -1; /* Behind all other elements */
        }
    </style>
</head>
<body>

    <div class="animated-background"></div> <!-- Dynamic Background Animation -->
    <div id="landing-screen">Welcome to the Admin Portal</div> <!-- Landing Screen -->

    <div class="signup-page">
        <div class="form">
            <h2>Admin Sign In</h2>
            <form class="login-form" method="post" role="form" id="signup-form" autocomplete="off">
                <div id="errorDiv"></div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="text" required name="email" placeholder="Enter your email">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" id="btn-signup">SIGN IN</button>
                    </div>
                </div>
                <p class="message">
                    <a href="#">Forgot your password?</a><br>
                </p>
            </form>
        </div>
    </div>

    <script src="assets/js/jquery-1.12.4-jquery.min.js"></script>
    <script>
        // Hide landing screen after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                document.getElementById('landing-screen').classList.add('hidden');
                document.body.style.overflow = 'auto'; // Enable scrolling
            }, 3000);
        });
    </script>

</body>
</html>
