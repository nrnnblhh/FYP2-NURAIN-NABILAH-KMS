<?php 
//database connection
$conn = new mysqli('localhost', 'root', '', 'fypkms');

$signupSuccessful = false; // Flag to control form visibility

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tName = $_POST['tName'];
    $tEmail = $_POST['tEmail'];
    $tContact = $_POST['tContact'];
    $tPass = password_hash($_POST['tPass'], PASSWORD_DEFAULT);

    // Verify reCAPTCHA
    $secretKey = "6Lf17IMqAAAAAD9cd44VOd0ymWymp8QIah1ZdKe4"; //replace with your reCAPTCHA secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents($verifyURL . "?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        $errorMessage = "Please complete the CAPTCHA verification.";
    } else {
        // Insert user
        $sql = "INSERT INTO teacher (tName, tEmail, tContact, tPass) VALUES ('$tName', '$tEmail', '$tContact', '$tPass')";
        if ($conn->query($sql) === TRUE) {
            $signupSuccessful = true;
        } else {
            $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Signup</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        * {
            font-family: Tahoma, Geneva, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #F3EEEA;
        }

        .form-container, .success-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="contact"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="contact"]:focus,
        input[type="password"]:focus {
            border-color: #5a9;
            outline: none;
        }

        .btn {
            display: inline-block;
            width: 90%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            color: #ffffff;
            background-color: #4caf50;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #43a047;
        }

        .success-message {
            font-size: 1.2em;
            color: #343131;
            margin-bottom: 20px;
        }

        .login-link {
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .error-message {
            font-size: 1em;
            color: #e74c3c;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php if ($signupSuccessful): ?>
        <!-- Success Message -->
        <div class="success-container">
            <p class="success-message">Sign up successful!</p>
            <a href="teacherlogin.php" class="login-link">Click here to login</a>
        </div>
    <?php else: ?>
        <!-- Signup Form -->
        <div class="form-container">
            <h2>Teacher Signup</h2><br>
            <?php if (isset($errorMessage)): ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="tName" placeholder="Full Name" required><br>
                <input type="email" name="tEmail" placeholder="Email" required><br>
                <input type="text" name="tContact" placeholder="Contact" required><br>
                <input type="password" name="tPass" placeholder="Password" required><br><br>
                
                <!-- Google reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6Lf17IMqAAAAAANA33GxxcgtVAgfi419lXSwfsZg"></div>
                <br><br>
                <button type="submit" class="btn submit-btn">Sign Up</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
