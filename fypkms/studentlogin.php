<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fypkms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // For storing login errors

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pEmail = $_POST['pEmail'];
    $pPass = $_POST['pPass'];

    // Fetch parent details
    $stmt = $conn->prepare("SELECT * FROM parent WHERE pEmail = ? LIMIT 1");
    $stmt->bind_param("s", $pEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $parent = $result->fetch_assoc();
    $stmt->close();

    // Verify password
    if ($parent && password_verify($pPass, $parent['pPass'])) {
        $_SESSION['pId'] = $parent['pId']; // Store parent ID in session
        $_SESSION['pName'] = $parent['pName']; // Store parent name in session
        header("Location: studenthome.php"); // Redirect to parent dashboard
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Login</title>
    <style>
        * {
            font-family: Tahoma, Geneva, sans-serif;
        }
        
        /* General styles for centering and layout */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #F3EEEA;
        }

        .form-container {
            background-color: #ffffff;
            padding: 50px;
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

        /* Input field styles */
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #5a9;
            outline: none;
        }

        /* Button styles */
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

        /* Error message style */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Parent Login</h2><br>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="pEmail" placeholder="Email" required><br>
            <input type="password" name="pPass" placeholder="Password" required><br>
            <p><a href="forgotpassparent.php">Forgot Password?</a></p>
            <button type="submit" class="btn submit-btn">Login</button>
        </form>
    </div>
</body>
</html>