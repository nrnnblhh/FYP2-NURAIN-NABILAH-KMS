<?php
// Your PHP logic goes here (if any)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiny Todds (KMS)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Tahoma, Geneva, sans-serif;
        }

        body {
            background-color: #F3EEEA;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        /* Header */
        .header {
            background-color: #CBD2A4;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 2.2em;
            font-weight: bold;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .header nav a:hover {
            color: #697565;
        }

        /* Main Content */
        .container {
            text-align: center;
            padding: 40px 20px;
            background-color: #FFF;
            margin: 50px auto;
            width: 80%;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            color: #555;
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .container p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        /* Button Style */
        .btn {
            background-color: #9DB2BF;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s, transform 0.3s;
            text-transform: uppercase;
            font-weight: 600;
        }

        .btn:hover {
            background-color: #BFCCB5;
            transform: scale(1.05);
        }

        /* Cute Illustration Section */
        .illustration {
            margin-top: 30px;
        }

        .illustration img {
            width: 60%;
            max-width: 300px;
            margin-top: 30px;
            animation: bounce 2s infinite;
        }

        /* Animation for bounce */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-8px);
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <h1>Tiny Todds</h1>
        <nav>
            <a href="home.php">Home</a>
            <a href="teacher.html">Teacher</a>
            <a href="student.html">Parent</a>
        </nav>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <h1>Welcome to Tiny Todds 🧸</h1><br><br>
        <p>Manage and monitor your kindergarten activities with ease.<br>A place for children to grow, learn, and play!</p>

        <!-- Adding a GIF under the button -->
    <div class="illustration">
        <img src="../fypkms/src/kids1.gif" alt="Cute animated GIF"><br><br>
    </div>
    </div>
</body>
</html>