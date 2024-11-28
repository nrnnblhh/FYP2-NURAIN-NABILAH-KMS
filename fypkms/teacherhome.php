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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>

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
.main-content {
    margin-left: 240px;
    padding: 20px;
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    font-size: 1.8em;
}

.logout-btn {
    background-color: #e74c3c;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

/* Info Cards */
.info-cards {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.card {
    flex: 1;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.card h3 {
    margin-bottom: 10px;
    color: #333;
}

/* Notice Board */
.notice-board {
    margin-top: 30px;
}

.notice-board h2 {
    font-size: 1.5em;
    margin-bottom: 10px;
}

.notice {
    background-color: #ff6b6b;
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.notice p {
    margin: 5px 0;
}

.notice .notice-date,
.notice .notice-author {
    font-weight: bold;
    font-size: 0.9em;
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
            <a href="teacherhome.php" class="active">Home</a>
            <a href="teacherattendance.php">Attendance</a>
            <a href="teacherstud.php">Student</a>
            <a href="teacherresult.php">Result</a>
            <a href="teacher_notice.php">Notice</a>
            <a href="teacherfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <header>
            <h1>Teacher</h1>
            <a href="home.php" class="logout-btn">Logout</a>
        </header>
        
        <div class="info-cards">
            <div class="card">
                <h3>Name</h3>
                <p><?php echo htmlspecialchars($teacher['tName'] ?? ''); ?></p>
            </div>
            <div class="card">
                <h3>Contact</h3>
                <p><?php echo htmlspecialchars($teacher['tContact'] ?? ''); ?></p>
            </div>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>