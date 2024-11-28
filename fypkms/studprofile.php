<?php
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

// Start session to track logged-in parent
session_start();

// Check if parent is logged in
if (!isset($_SESSION['pId'])) {
    header("Location: studentlogin.php"); // Redirect to login if not logged in
    exit;
}

$pId = $_SESSION['pId'];

// Fetch student data for the logged-in parent
$query = "SELECT student.*, parent.pName AS fatherName 
          FROM student 
          INNER JOIN parent ON student.pId = parent.pId 
          WHERE parent.pId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    $student = null;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
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
            height: 100vh;
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

        /* Main Content Wrapper */
        .content-wrapper {
            margin-left: 240px; /* Offset for the sidebar */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: calc(100% - 240px); /* Ensure the content is within the remaining space */
        }

        .container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
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
            <a href="studprofile.php" class="active">Profile</a>
            <a href="studattendance.php">Attendance</a>
            <a href="studresult.php">Result</a>
            <a href="studfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="content-wrapper">
        <div class="container">
            <h2>Student Profile</h2>
            <?php if ($student): ?>
            <table>
                <tr>
                    <th>Student Name</th>
                    <td><?php echo $student['sName']; ?></td>
                </tr>
                <tr>
                    <th>Age</th>
                    <td><?php echo $student['sAge']; ?></td>
                </tr>
                <tr>
                    <th>Class</th>
                    <td><?php echo $student['sClass']; ?></td>
                </tr>
                <tr>
                    <th>Father's Name</th>
                    <td><?php echo $student['fatherName']; ?></td>
                </tr>
                <tr>
                    <th>Father's Contact</th>
                    <td><?php echo $student['fatherContact']; ?></td>
                </tr>
                <tr>
                    <th>Mother's Name</th>
                    <td><?php echo $student['motherName']; ?></td>
                </tr>
                <tr>
                    <th>Mother's Contact</th>
                    <td><?php echo $student['motherContact']; ?></td>
                </tr>
                <tr>
                    <th>Home Address</th>
                    <td><?php echo $student['homeAddress']; ?></td>
                </tr>
            </table>
            <?php else: ?>
            <p>No student details found for your account.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>