<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fypkms', 3306);

// Set PHP timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
// Set MySQL timezone to match PHP
$conn->query("SET time_zone = '+08:00';");

// Check for connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize variables
$error = '';
$success = '';

// Check if the token is present in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM parent WHERE resetToken = ? AND tokenExpiry > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid; get the parent record
        $parent = $result->fetch_assoc();

        // Process password reset if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            // Validate passwords
            if (empty($newPassword) || empty($confirmPassword)) {
                $error = "Both password fields are required.";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Passwords do not match.";
            } elseif (strlen($newPassword) < 6) { // Password length validation
                $error = "Password must be at least 6 characters.";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password in the database and clear the token
                $stmt = $conn->prepare("UPDATE parent SET pPass = ?, resetToken = NULL, tokenExpiry = NULL WHERE resetToken = ?");
                $stmt->bind_param('ss', $hashedPassword, $token);

                if ($stmt->execute()) {
                    $success = "Your password has been reset successfully. You can now <a href='studentlogin.php'>log in</a>.";

                    // Prevent further checks by clearing the token in PHP
                    unset($token);
                } else {
                    $error = "Failed to reset your password. Please try again.";
                }
            }
        }
    } else {
        $error = "Invalid or expired token.";
    }
} else {
    $error = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            font-family: Tahoma, Geneva, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 60%;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .form-container input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
            text-align: center;
        }

        .success {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Reset Password</h2><br>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php else: ?>
            <form method="POST">
                <input type="password" name="newPassword" placeholder="New Password" required>
                <input type="password" name="confirmPassword" placeholder="Confirm Password" required><br><br>
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>