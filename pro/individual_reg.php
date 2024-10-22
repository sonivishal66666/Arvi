<?php
session_start();
require_once '../conn.php';
require_once '../constants.php';
require 'send_otp.php';  // Include the OTP sending script
$class = "reg";
?>

<?php
$cur_page = 'signup';
include 'includes/inc-header.php';
include 'includes/inc-nav.php';

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $cpassword = $_POST['cpassword'];
    $password = $_POST['password'];

    if (empty($name) || empty($address) || empty($phone) || empty($email) || empty($password) || empty($cpassword) || ($password != $cpassword)) { ?>
        <script>
            alert("Ensure you fill the form properly.");
        </script>
    <?php
    } else {
        $check_email = $conn->prepare("SELECT id FROM passenger WHERE email = ? OR phone = ?");
        $check_email->bind_param("ss", $email, $phone);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) { ?>
            <script>
                alert("Email or Phone already exists!");
            </script>
        <?php
        } elseif ($cpassword != $password) { ?>
            <script>
                alert("Passwords do not match.");
            </script>
        <?php
        } else {
            $password = md5($password);
            $loc = uploadFile('file');

            $stmt = $conn->prepare("INSERT INTO passenger (name, email, password, phone, address, loc) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $password, $phone, $address, $loc);
            if ($stmt->execute()) {
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;

                // Fetch and store user data in session
                $user_id = $conn->insert_id;  // Get the last inserted ID
                $_SESSION['user_id'] = $user_id;
                
                sendOTP($email, $otp);

                header("Location: verify_otp.php");
                exit();
            } else { ?>
                <script>
                    alert("We could not register you!");
                </script>
            <?php
            }
        }

        $check_email->free_result();
        $check_email->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup </title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Orbitron&display=swap" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .form-container {
            background: rgba(25, 25, 30, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 800px;
            text-align: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            color: #5a67d8;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(90, 103, 216, 0.8);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
            width: 48%;
        }

        label {
            position: absolute;
            top: 14px;
            left: 12px;
            color: #aaa;
            font-size: 14px;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: #3D3B4D;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: inset 0 0 5px rgba(255, 255, 255, 0.2);
        }

        input:focus,
        select:focus {
            outline: none;
            background-color: #5a67d8;
            box-shadow: 0 0 10px rgba(90, 103, 216, 0.8);
            color: #fff;
        }

        input:focus + label,
        select:focus + label,
        input:not(:placeholder-shown) + label {
            top: -10px;
            left: 12px;
            font-size: 12px;
            color: #5a67d8;
        }

        button {
            background: #5a67d8;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        button:hover {
            background: #434190;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #aaa;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ccc;
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        .g-signin2 {
            margin-top: 10px;
        }

        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('path/to/your/background/image.jpg') center/cover no-repeat;
            filter: blur(8px);
            z-index: -1;
            animation: moveBackground 30s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translateY(0); }
            100% { transform: translateY(-20%); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .input-container {
                width: 100%;
            }
            .form-row {
                flex-direction: column;
            }
            .input-container {
                margin-bottom: 15px;
            }
        }

        /* Neon Effect */
        input:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(90, 103, 216, 0.8), 0 0 20px rgba(90, 103, 216, 0.5);
        }

        button:hover {
            background: #434190;
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(90, 103, 216, 0.5);
        }

    </style>

    <!-- Load the Google API client library -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <div class="form-container">
        <div class="background-animation"></div>
        <h2>Create Account</h2>

        <form class="login-form" method="post" role="form" enctype="multipart/form-data" id="signup-form" autocomplete="off">
            <div class="form-row">
                <div class="input-container">
                    <input type="text" required minlength="10" name="name" placeholder=" " id="name">
                    <label for="name">Full Name</label>
                </div>

                <div class="input-container">
                                        <input type="text" minlength="10" pattern="[0-9]{10}" required name="phone" placeholder=" " id="phone">
                    <label for="phone">Contact Number</label>
                </div>

                <div class="input-container">
                    <input type="email" required name="email" placeholder=" " id="email">
                    <label for="email">Email Address</label>
                </div>

                <div class="input-container">
                    <input type="file" name="file" id="file">
                    <label for="file" style="top: -14px;">Upload File</label>
                </div>

                <div class="input-container">
                    <input type="text" name="address" placeholder=" " required id="address">
                    <label for="address">Address</label>
                </div>

                <div class="input-container" style="width: 48%;">
                    <input type="password" name="password" placeholder=" " id="password" required>
                    <label for="password">Password</label>
                </div>

                <div class="input-container" style="width: 48%;">
                    <input type="password" name="cpassword" placeholder=" " id="cpassword" required>
                    <label for="cpassword">Confirm Password</label>
                </div>

                <div class="input-container" style="width: 48%;">
                    <select name="gender" required id="gender">
                        <option value="" disabled selected></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <label for="gender">Gender</label>
                </div>
            </div>

            <button type="submit" id="btn-signup">CREATE ACCOUNT</button>
        </form>

        <div class="divider"></div>

        <!-- Google Sign-in Button -->
       <!-- <div id="g_id_onload"
            data-client_id="825115534950-qg0lep9l4ciahl3vd5cdli5hclt4ks6a.apps.googleusercontent.com"
            data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" data-type="standard"></div>
    </div> -->

    <script>
        function handleCredentialResponse(response) {
            // Parse the ID token from Google and send it to the backend for validation
            const id_token = response.credential;

            // Post the ID token to the server via AJAX
            const form = new FormData();
            form.append('id_token', id_token);

            fetch('signup.php', {
                method: 'POST',
                body: form
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Invalid Google ID Token')) {
                    alert('Invalid ID Token');
                } else {
                    window.location.href = 'individual.php';
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Load the Google API library
        window.onload = function() {
            google.accounts.id.initialize({
                client_id: '825115534950-qg0lep9l4ciahl3vd5cdli5hclt4ks6a.apps.googleusercontent.com',
                callback: handleCredentialResponse
            });

            google.accounts.id.renderButton(
                document.querySelector('.g_id_signin'),
                { theme: 'outline', size: 'large' }
            );
        };
    </script>
</body>
</html>

