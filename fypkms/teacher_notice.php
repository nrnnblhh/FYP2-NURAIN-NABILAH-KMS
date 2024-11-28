<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['tId'])) {
    header('Location: teacherlogin.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fypkms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teacher data based on session tId
$tId = $_SESSION['tId']; // Retrieve tId from session
$sql = "SELECT tName, tContact FROM teacher WHERE tId = $tId";
$result = $conn->query($sql);
$teacher = $result->fetch_assoc() ?? [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $announcement = $conn->real_escape_string($_POST['announcement']);
    $sql = "INSERT INTO announcement (message, createdat) VALUES ('$announcement', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Announcement added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Page</title>
    
    <style>
        * {
            font-family: Tahoma, Geneva, sans-serif;
        }
        
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    background-color: #f9f9f9;
}

/* Sidebar */
.sidebar {
    width: 220px;
    background-color: #333;
    color: white;
    position: fixed;
    height: 100%;
    padding-top: 20px;
    box-shadow: 4px 0 8px rgba(0, 0, 0, 0.1);
}

.profile {
    text-align: center;
    padding: 15px;
}

.profile img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid white;
}

.profile h3 {
    color: white;
    margin-top: 10px;
    font-size: 18px;
}

.sidebar nav a {
    display: block;
    padding: 15px 20px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-left: 4px solid transparent;
    transition: all 0.3s ease-in-out;
}

.sidebar nav a:hover {
    background-color: #444;
    border-left: 4px solid #E4E0E1;
}

.sidebar nav a.active {
    background-color: #A9A9A9;
    border-left: 4px solid white;
    color: #fff;
}

/* Main Content */
.container {
    margin-left: 260px;
    padding: 30px;
}

h2 {
    text-align: center;
    color: #2c3e50;
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: 600;
}

form {
    max-width: 600px;
    margin: 0 auto;
    background: #ffffff;
    padding: 20px 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

textarea {
    width: 100%;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none;
}

textarea:focus {
    border-color: #1abc9c;
    outline: none;
    box-shadow: 0 0 5px rgba(26, 188, 156, 0.5);
}

button {
    background-color: #1abc9c;
    color: white;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #16a085;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }

    .container {
        margin-left: 220px;
        padding: 20px;
    }

    textarea {
        font-size: 13px;
    }

    button {
        font-size: 14px;
    }
}
</style>

</head>
<body>
    <!-- Sidebar Section -->
    <div class="sidebar">
        <div class="profile">
            <h3>Tiny Todds</h3>
        </div>
        <nav>
            <a href="teacherhome.php">Home</a>
            <a href="teacherattendance.php">Attendance</a>
            <a href="teacherstud.php">Student</a>
            <a href="teacherresult.php">Result</a>
            <a href="teacher_notice.php" class="active">Notice</a>
            <a href="teacherfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>Announce Something</h2>
        <form method="POST" action="">
            <textarea name="announcement" rows="4" placeholder="Write your message here..." required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
