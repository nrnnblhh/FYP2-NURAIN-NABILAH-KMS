<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fypkms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the parent is logged in
if (!isset($_SESSION['pId'])) {
    header("Location: studentlogin.php");
    exit();
}

$pId = $_SESSION['pId'];

// Fetch children details
$stmt = $conn->prepare("SELECT * FROM student WHERE pId = ?");
$stmt->bind_param("i", $pId);
$stmt->execute();
$children = $stmt->get_result();
$stmt->close();

// Fetch announcements
$noticeQuery = "SELECT * FROM announcement ORDER BY createdat DESC";
$notices = $conn->query($noticeQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
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
        margin-left: 220px; /* Matches the sidebar width */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        padding: 20px;
        min-height: 100vh;
    }

    /* Header Section */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 800px;
        margin-bottom: 20px;
    }

    /* Children Cards Container */
    .info-cards {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    /* Children Card */
    .card {
        background: white;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 250px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .card h3 {
        margin-bottom: 10px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .card p {
        font-size: 1em;
        margin: 5px 0;
        color: #666;
    }

    /* Notice Board */
    .notice-container {
        margin-top: 30px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 800px;
    }

    .notice-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 10px;
    }

    .notice-box {
        background-color: #87A7B3;
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .notice-header {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .notice-body {
        font-size: 16px;
    }

    .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card {
            width: 100%;
        }

        .main-content {
            margin-left: 0;
        }

        .sidebar {
            position: static;
            width: 100%;
            height: auto;
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
            <a href="studenthome.php" class="active">Home</a>
            <a href="studprofile.php">Profile</a>
            <a href="studattendance.php">Attendance</a>
            <a href="studresult.php">Result</a>
            <a href="studfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <header>
            <h1>Parent Dashboard</h1>
            <a href="home.php" class="logout-btn">Logout</a>
        </header>

        <h3>Your Children:</h3>
        <div class="info-cards">
            <?php while ($child = $children->fetch_assoc()): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($child['sName']); ?></h3>
                    <p>Class: <?php echo htmlspecialchars($child['sClass']); ?></p>
                    <p>Age: <?php echo htmlspecialchars($child['sAge']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Notice Board Section -->
        <div class="notice-container">
            <div class="notice-title">Notice Board</div>
            <?php if ($notices->num_rows > 0): ?>
                <?php while ($notice = $notices->fetch_assoc()): ?>
                    <div class="notice-box">
                        <div class="notice-header">
                            <span>Posted on: <?php echo date("M. d, Y", strtotime($notice['createdat'])); ?></span>
                            <span>By: <?php echo htmlspecialchars($notice['author']); ?></span>
                        </div>
                        <div class="notice-body">
                            <p><?php echo htmlspecialchars($notice['message']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>