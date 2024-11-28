<?php 
session_start();

//database connection (optional if you're fetching or adding students directly here)
$conn = new mysqli('localhost', 'root', '', 'fypkms');

//check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result</title>
    
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

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: rgb(82, 79, 79);
    padding: 15px 20px;
    border-radius: 5px;
    margin-bottom: 20px;
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

/* Card Container */
.card-container {
    display: flex;
    gap: 20px;
}

/* Cards */
.card {
    flex: 1;
    min-width: 200px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    color: white;
    font-weight: bold;
    text-decoration: none;
}

.card h3 {
    margin-bottom: 15px;
}

.teacherratestud {
    background-color: #9fada1;
}

.teacherviewresult {
    background-color: #9fada1;
}

.icon {
    font-size: 2em;
    margin-top: 10px;
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
            <a href="teacherresult.php" class="active">Result</a>
            <a href="teacher_notice.php">Notice</a>
            <a href="teacherfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <header>
            <h1>Student Result</h1>
            <a href="home.php" class="logout-btn">Logout</a>
        </header>

        <div class="card-container">
            <a href="teacherratestud.php" class="card teacherratestud">
                <h3>Rate Student</h3>
            </a>
            <a href="teacherviewresult.php" class="card teacherviewresult">
                <h3>View Result</h3>
            </a>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
