<?php
session_start();
require_once '../conn.php';
$class = "signin";

$cur_page = 'signup';
include 'includes/inc-header.php';
include 'includes/inc-nav.php';

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
        $password = md5($password);
        $check = $conn->prepare("SELECT * FROM passenger WHERE email = ? AND password = ?");
        $check->bind_param("ss", $email, $password);
        if (!$check->execute()) die("Form Filled With Error");
        $res = $check->get_result();
        $no_rows = $res->num_rows;
        if ($no_rows ==  1) {
            $row = $res->fetch_assoc();
            $id = $row['id'];
            $status = $row['status'];
            if ($status != 1) {
?>
<script>
alert("Account Deactivated!\nContact The System Administrator!");
window.location = "signin.php";
</script>
<?php
                exit;
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
?>
<!-- Loader for Access Granted -->
<div id="loader" class="loader-overlay">
    <div class="loader"></div>
</div>

<script>
document.getElementById('loader').style.display = 'flex';
setTimeout(function() {
    window.location = "individual.php";
}, 500); // Redirect after 5 seconds
</script>

<?php
            exit;
        } else { ?>
<!-- Error Message -->
<div id="errorMessage" class="overlay-message error">
    Access Denied. Please check your email and password.
</div>
<script>
document.getElementById('errorMessage').style.display = 'block';
setTimeout(function() {
    document.getElementById('errorMessage').style.display = 'none';
}, 2000);
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
    <title>Sign In</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Roboto&display=swap');

        /* Full-page background with animated gradient */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928DAB);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite; /* Animated gradient */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            overflow: hidden;
            position: relative;
            animation: fadeIn 1s ease-in-out; /* Fade-in effect */
        }

        /* Gradient animation */
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Overlay to make the background darker */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Adjusted for better visibility */
            z-index: 0;
        }

        .signup-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            background: rgba(30, 30, 30, 0.85);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
            text-align: center;
            animation: slideIn 0.5s ease; /* Slide-in effect */
        }

        /* Slide-in animation */
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 20px;
            font-size: 26px;
            color: #f5f5f5;
            letter-spacing: 2px;
            animation: popIn 0.5s ease; /* Pop-in effect */
        }

        /* Pop-in animation */
        @keyframes popIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(76, 139, 245, 0.2);
            animation: pulse 1.5s infinite; /* Pulsing effect */
        }

        /* Pulsing animation */
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 10px rgba(76, 139, 245, 0.2);
            }
            50% {
                box-shadow: 0 0 20px rgba(76, 139, 245, 0.5);
            }
        }

        label {
            position: absolute;
            top: 12px;
            left: 12px;
            color: #aaa;
            font-size: 14px;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        input:focus + label, input:not(:placeholder-shown) + label {
            top: -10px;
            font-size: 12px;
            color: #4c8bf5;
        }

        input:focus {
            border-bottom: 2px solid #4c8bf5;
            box-shadow: 0 0 15px rgba(76, 139, 245, 0.5);
        }

        /* Smooth glowing button with hover effects */
        button {
            background-color: #4c8bf5;
            color: white;
            padding: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.4s ease, box-shadow 0.4s ease, transform 0.3s ease;
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 2px;
            box-shadow: 0 0 20px rgba(76, 139, 245, 0.4);
            position: relative; /* Position for pseudo-element */
            overflow: hidden;
        }

        /* Pseudo-element for gradient button hover effect */
        button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: linear-gradient(135deg, #ff4081, #ff3d00);
            border-radius: 50%;
            z-index: 0;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease;
        }

        button:hover::after {
            transform: translate(-50%, -50%) scale(1);
        }

        button:hover {
            background-color: #4c8bf5; /* Keep the button color */
            color: #fff;
            transform: translateY(-4px) scale(1.05); /* Lift and scale effect */
            box-shadow: 0 10px 30px rgba(76, 139, 245, 0.7);
        }

        /* Add active styles to prevent color change on click */
        button:active {
            transform: translateY(2px); /* Pressed effect */
            box-shadow: 0 5px 20px rgba(76, 139, 245, 0.6);
            background-color: #4c8bf5; /* Keep the original color */
        }

        .message {
            margin-top: 20px;
            color: #ccc;
            font-size: 14px;
        }

        .message a {
            color: #4c8bf5;
            text-decoration: none;
        }

        /* Loader styles */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loader {
            border: 8px solid rgba(76, 139, 245, 0.2);
            border-left-color: #4c8bf5;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .overlay-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 0, 0, 0.8);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            z-index: 9999;
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .overlay-message.error {
            background: rgba(255, 0, 0, 0.9);
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Customer Panel</h2>
        <form class="login-form" method="post" role="form" id="signup-form" autocomplete="off">
            <div id="errorDiv"></div>
            
            <!-- Email Input -->
            <div class="input-container">
                <input type="email" required name="email" placeholder=" " id="email">
                <label for="email">Email Address</label>
            </div>

            <!-- Password Input -->
            <div class="input-container">
                <input type="password" required name="password" placeholder=" " id="password">
                <label for="password">Password</label>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" id="btn-signup">
                    SIGN IN
                </button>
            </div>

            <!-- Forgot Password Link -->
            <p class="message">
                <a href="forgot_password.php">Forgot Password?</a><br>
            </p>
        </form>
    </div>

    <!-- Loader Overlay -->
    <div id="loader" class="loader-overlay" style="display: none;">
        <div class="loader"></div>
    </div>

    <script src="assets/js/jquery-1.12.4-jquery.min.js"></script>
    <script src="assets/js/sweetalert2.js"></script>

    <script>
        // This script will show the loader when logging in
        document.getElementById('signup-form').onsubmit = function() {
            document.getElementById('loader').style.display = 'flex';
        };
    </script>
</body>
</html>
