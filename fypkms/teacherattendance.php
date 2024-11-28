<?php
// Start session to use session variables
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "fypkms";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch students by class
$classId = $_GET['classId'] ?? null;
$attendDate = $_GET['attendDate'] ?? date("Y-m-d");
$students = [];
if ($classId) {
    $stmt = $conn->prepare("SELECT * FROM student WHERE sClass = ?");
    $stmt->bind_param("s", $classId);
    $stmt->execute();
    $students = $stmt->get_result();
    $stmt->close();
}

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendDate = $_POST['attendDate'];
    $classId = $_POST['classId'];
    $attendance = $_POST['attendance'];

    // Insert attendance into the database
    foreach ($attendance as $sId => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (sId, attendDate, classId, status) VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status = ?");
        $stmt->bind_param("issss", $sId, $attendDate, $classId, $status, $status);
        $stmt->execute();
        $stmt->close();
    }

    // Set session variable for success message
    $_SESSION['success_message'] = "Attendance recorded successfully!";
    header("Location: teacherattendance.php?classId=" . $classId . "&attendDate=" . $attendDate);
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance</title>
    <script>
        // JavaScript function to show a pop-up message
        function showPopup(message) {
            if (message) {
                alert(message);
            }
        }
    </script>

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
    margin-left: 250px;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
}

/* Form Container */
.form-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 30px 40px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 900px;
    width: 100%;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #4CAF50;
    color: white;
}

td input[type="radio"] {
    margin-right: 10px;
}

.submit-btn {
    width: 200px;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: block;
    margin: 20px auto;
}

.submit-btn:hover {
    background-color: #218838;
}
</style>
</head>

<body>
    <script>
        // Show a popup message if the session variable is set
        <?php if (isset($_SESSION['success_message'])): ?>
            alert('<?php echo $_SESSION['success_message']; ?>');
            <?php unset($_SESSION['success_message']); // Clear the message after displaying ?>
        <?php endif; ?>
    </script>

    <!-- Sidebar Section -->
    <div class="sidebar">
        <div class="profile">
            <h3>Tiny Todds</h3>
        </div>
        <nav>
            <a href="teacherhome.php">Home</a>
            <a href="teacherattendance.php" class="active">Attendance</a>
            <a href="teacherstud.php">Student</a>
            <a href="teacherresult.php">Result</a>
            <a href="teacher_notice.php">Notice</a>
            <a href="teacherfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h2>Take Attendance</h2>
            <form method="GET" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="classId">Select Class</label>
                        <select id="classId" name="classId" required onchange="this.form.submit()">
                            <option value="" disabled selected>Select Class</option>
                            <option value="Abu Bakar" <?php echo ($classId == "Abu Bakar") ? "selected" : ""; ?>>Abu Bakar</option>
                            <option value="Omar" <?php echo ($classId == "Omar") ? "selected" : ""; ?>>Omar</option>
                            <option value="Othman" <?php echo ($classId == "Othman") ? "selected" : ""; ?>>Othman</option>
                            <option value="Ali" <?php echo ($classId == "Ali") ? "selected" : ""; ?>>Ali</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attendDate">Select Date</label>
                        <input type="date" id="attendDate" name="attendDate" value="<?php echo htmlspecialchars($attendDate); ?>" onchange="this.form.submit()" required>
                    </div>
                </div>
            </form>

            <?php if ($classId && $students->num_rows > 0): ?>
                <form method="POST" action="">
                    <input type="hidden" name="classId" value="<?php echo htmlspecialchars($classId); ?>">
                    <input type="hidden" name="attendDate" value="<?php echo htmlspecialchars($attendDate); ?>">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Mark Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['sId']; ?></td>
                                    <td><?php echo htmlspecialchars($row['sName']); ?></td>
                                    <td>
                                        <input type="radio" name="attendance[<?php echo $row['sId']; ?>]" value="Present" required> Present
                                        <input type="radio" name="attendance[<?php echo $row['sId']; ?>]" value="Absent"> Absent
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="form-btn">
                        <button type="submit" class="submit-btn">Submit Attendance</button>
                    </div>
                </form>
            <?php elseif ($classId): ?>
                <p>No students found in this class.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
