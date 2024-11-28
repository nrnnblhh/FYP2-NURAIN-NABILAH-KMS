<?php
// Set the correct timezone for PHP
date_default_timezone_set('Asia/Kuala_Lumpur'); // Replace with your correct timezone

// Include PHPMailer
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fypkms', 3306);

// Check for connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$popupMessage = ''; // Initialize popup message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pEmail = $_POST['pEmail'];

    // Validate email format
    if (!filter_var($pEmail, FILTER_VALIDATE_EMAIL)) {
        $popupMessage = "Invalid email format.";
    } else {
        // Check if email exists in the database
        $sql = "SELECT * FROM parent WHERE pEmail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $pEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate unique token
            $token = bin2hex(random_bytes(50));
            $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes')); // Token expires in 30 minutes

            // Save token and expiry in the database
            $stmt = $conn->prepare("UPDATE parent SET resetToken = ?, tokenExpiry = ? WHERE pEmail = ?");
            $stmt->bind_param('sss', $token, $expiry, $pEmail);
            $stmt->execute();

            // Send reset email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nabilahnurain2301@gmail.com'; // Your email
                $mail->Password = 'kesc adui xwuh rliw';          // Your App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Disable SSL certificate verification (for development purposes only)
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ),
                );

                // Email settings
                $mail->setFrom('nabilahnurain2301@gmail.com', 'Tiny Todds');
                $mail->addAddress($pEmail); // Recipient's email
                $mail->Subject = 'Password Reset Request';

                // Dynamically generate the reset link with the current host and port
                $host = $_SERVER['HTTP_HOST']; // Gets "localhost:3000"
                $resetLink = "http://$host/fypkms/resetpassparent.php?token=$token";
                $mail->Body = "Hi,<br><br>Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";
                $mail->isHTML(true);

                $mail->send();
                $popupMessage = "A password reset link has been sent to your email.";
            } catch (Exception $e) {
                $popupMessage = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $popupMessage = "No user found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script>
        // JavaScript function to display a popup message
        function showPopup(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
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

        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        input[type="email"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="email"]:focus {
            border-color: #5a9;
            outline: none;
        }

        button {
            display: inline-block;
            width: 44%;
            padding: 6px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            color: #ffffff;
            background-color: #4caf50;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #43a047;
        }
    </style>
</head>
<body onload="showPopup('<?php echo $popupMessage; ?>')">
    <div class="form-container">
        <h2>Forgot Password</h2>
        <form method="POST">
            <input type="email" name="pEmail" placeholder="Enter your registered email" required><br><br>
            <button type="submit" class="btn submit-btn">Send Reset Link</button>
        </form>
    </div>
</body>
</html>