<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fypkms', 3306);

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tEmail = $_POST['tEmail'];
    $tPass = $_POST['tPass'];

    // Check user
    $sql = "SELECT * FROM teacher WHERE tEmail='$tEmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($tPass, $user['tPass'])) {
            // Store both email and tId in session
            $_SESSION['teacher_email'] = $user['tEmail'];
            $_SESSION['tId'] = $user['tId'];  // Store tId for the logged-in teacher
            header('Location: teacherhome.php');
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>

    <style>
        *{
            font-family: Tahoma, Geneva, sans-serif;
        }
        
        /*general styles for centering and layout*/
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
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Teacher Login</h2><br>
        <form method="POST">
            <input type="email" name="tEmail" placeholder="Email" required><br>
            <input type="password" name="tPass" placeholder="Password" required><br>
            <p><a href="forgotpassword.php">Forgot Password?</a></p>
            <button type="submit" class="btn submit-btn">Login</button>
        </form>
    </div>
</body>
</html>
