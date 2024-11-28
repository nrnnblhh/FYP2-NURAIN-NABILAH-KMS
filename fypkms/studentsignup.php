<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fypkms');

$signupSuccessful = false; // Flag to control form visibility

// Google reCAPTCHA secret key
$secretKey = '6Lf17IMqAAAAAD9cd44VOd0ymWymp8QIah1ZdKe4';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pName = $_POST['pName'];
    $pEmail = $_POST['pEmail'];
    $pPass = password_hash($_POST['pPass'], PASSWORD_DEFAULT);

    // Verify reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseData = json_decode($verifyResponse);

    if ($responseData->success) {
        // Insert user into database
        $sql = "INSERT INTO parent (pName, pEmail, pPass) VALUES ('$pName', '$pEmail', '$pPass')";
        if ($conn->query($sql) === TRUE) {
            $signupSuccessful = true;
        } else {
            $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $errorMessage = "reCAPTCHA verification failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Signup</title>
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
            <a href="studentlogin.php" class="login-link">Click here to login</a>
        </div>
    <?php else: ?>
        <!-- Signup Form -->
        <div class="form-container">
            <h2>Parent Signup</h2><br>
            <?php if (isset($errorMessage)): ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="pName" placeholder="Full Name" required><br>
                <input type="email" name="pEmail" placeholder="Email" required><br>
                <input type="password" name="pPass" placeholder="Password" required><br><br>
                <!-- Google reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6Lf17IMqAAAAAANA33GxxcgtVAgfi419lXSwfsZg"></div>
                <br><br>
                <button type="submit" class="btn submit-btn">Sign Up</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>