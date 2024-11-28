<?php
session_start();

// Database connection
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

// Fetch children's ratings
$stmt = $conn->prepare("
    SELECT 
        student.sId, 
        student.sName, 
        student.sClass, 
        ratings.monthyear, 
        ratings.rating, 
        ratings.comments 
    FROM student 
    LEFT JOIN ratings ON student.sId = ratings.sId 
    WHERE student.pId = ? 
    ORDER BY ratings.monthyear DESC
");
$stmt->bind_param("i", $pId);
$stmt->execute();
$ratings = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Children's Monthly Ratings</title>
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
    display: flex;
    justify-content: center; /* Horizontally centers the main content */
    align-items: center; /* Vertically centers the main content */
    height: 100vh; /* Ensures the content takes full viewport height */
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
    left: 0;
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
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    margin-left: 240px; /* Matches the width of the sidebar */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 800px; /* Limits the width of the content */
    width: 100%;
}

/* Title */
h1 {
    font-size: 1.8em;
    margin: 0; /* Removes extra margin on top */
    padding-bottom: 20px;
    text-align: center;
}

/* Rating Card */
.rating-card {
    background: white; /* Ensures the card is white */
    color: #333;
    padding: 15px;
    border-radius: 8px;
    margin: 20px auto;
    text-align: left;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.rating-card h2 {
    margin: 0 0 10px;
    font-size: 1.5em;
}

.rating-card p {
    margin: 5px 0;
}

/* No Ratings Message */
.no-ratings {
    font-size: 1.2em;
    color: #666;
    text-align: center;
    margin-top: 20px;
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
            <a href="studenthome.php">Home</a>
            <a href="studprofile.php">Profile</a>
            <a href="studattendance.php">Attendance</a>
            <a href="studresult.php" class="active">Result</a>
            <a href="studfaq.php">FAQ</a>
        </nav>
    </div>

    <div class="main-content">
        <header><br><br>
        </header>

        <h1>Children's Monthly Ratings</h1><br><br>

        <?php if ($ratings->num_rows > 0): ?>
            <?php while ($row = $ratings->fetch_assoc()): ?>
                <div class="rating-card">
                    <h2><?php echo htmlspecialchars($row['sName']); ?> (Class: <?php echo htmlspecialchars($row['sClass']); ?>)</h2>
                    <p><strong>Month:</strong> <?php echo htmlspecialchars($row['monthyear']); ?></p>
                    <p><strong>Rating:</strong> <?php echo htmlspecialchars($row['rating'] ?? '-'); ?></p>
                    <p><strong>Comments:</strong> <?php echo htmlspecialchars($row['comments'] ?? 'No Comments'); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-ratings">No ratings available for your children at this time.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
