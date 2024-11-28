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
    header("Location: parentlogin.php");
    exit();
}

$pId = $_SESSION['pId']; // Use the logged-in parent's ID

// Fetch children linked to the logged-in parent
$children = [];
$stmt = $conn->prepare("SELECT sId, sName FROM student WHERE pId = ?");
$stmt->bind_param("i", $pId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $children[] = $row;
}
$stmt->close();

// Handle form submission to fetch attendance for the chosen child and date
$attendance = null;
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedChild = $_POST['sId'] ?? null;
    $attendDate = $_POST['attendDate'] ?? null;

    if ($selectedChild && !empty($attendDate)) {
        // Prepare statement to fetch attendance data for the specified child and date
        $stmt = $conn->prepare("SELECT * FROM attendance WHERE sId = ? AND attendDate = ?");
        $stmt->bind_param("is", $selectedChild, $attendDate); // 'i' for integer (sId), 's' for string (attendDate)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $attendance = $result->fetch_assoc();
        } else {
            $message = "No attendance records found for this date.";
        }
        $stmt->close();
    } else {
        $message = "Please select a child and a valid date.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
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
    margin-left: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 40px; /* Space from top for the heading */
    height: 100vh;
    position: relative;
}

/* Header */
header h1 {
    font-size: 2em;
    text-align: center;
    margin-bottom: 20px;
}

/* Form Container */
.form-container {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 500px;
    width: 100%;
    margin-top: 20px;
}

.form-container h2 {
    font-size: 1.5em;
    margin-bottom: 20px;
    color: #333;
}

label {
    display: block;
    text-align: left;
    font-weight: bold;
    margin-top: 15px;
    color: #555;
}

input[type="date"],
select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    background-color: #f9f9f9;
}

button.submit-btn {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    margin-top: 20px;
    cursor: pointer;
}

button.submit-btn:hover {
    background-color: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 180px;
    }

    .main-content {
        margin-left: 180px;
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
            <a href="studenthome.php">Home</a>
            <a href="studprofile.php">Profile</a>
            <a href="studattendance.php" class="active">Attendance</a>
            <a href="studresult.php">Result</a>
            <a href="studfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <header>
            <h1>View Attendance</h1>
        </header>

        <div class="form-container">
            <h2>Select a Child and Date to View Attendance</h2>
            <form method="POST">
                <label for="sId">Select Child:</label>
                <select name="sId" id="sId" required>
                    <option value="" disabled selected>Select a child</option>
                    <?php foreach ($children as $child): ?>
                        <option value="<?php echo $child['sId']; ?>">
                            <?php echo htmlspecialchars($child['sName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="attendDate">Select Date:</label>
                <input type="date" name="attendDate" id="attendDate" required>

                <button type="submit" class="submit-btn">View Attendance</button>
            </form>

            <?php if (!empty($message)): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>

            <?php if ($attendance): ?>
                <div class="attendance-record">
                    <h3>Attendance for <?php echo htmlspecialchars($attendance['attendDate']); ?></h3>
                    <p>Status: <?php echo htmlspecialchars($attendance['status']); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>