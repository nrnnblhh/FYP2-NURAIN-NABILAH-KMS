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

// Handle deletion
if (isset($_GET['delete'])) {
    $sId = intval($_GET['delete']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete related rows in the result table
        $stmt = $conn->prepare("DELETE FROM student WHERE sId = ?");
        $stmt->bind_param("i", $sId);
        $stmt->execute();
        $stmt->close();

        // Delete related rows in the attendance table
        $stmt = $conn->prepare("DELETE FROM attendance WHERE sId = ?");
        $stmt->bind_param("i", $sId);
        $stmt->execute();
        $stmt->close();

        // Delete the student
        $stmt = $conn->prepare("DELETE FROM student WHERE sId = ?");
        $stmt->bind_param("i", $sId);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Redirect to refresh the page
        header("Location: teacherviewstud.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        die("Error deleting student: " . $e->getMessage());
    }
}

//fetch all students
$result = $conn->query("SELECT * FROM student");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>

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

.main-content {
    margin-left: 250px; /* Adjust based on sidebar width */
    padding: 20px;
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

/* Table Styles */
.table-container {
    width: 100%;
}

table {
    width: 100%; /* Ensures the table fits within the container */
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #f1f1f1;
}

th {
    background-color: #629584;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

td {
    color: #333;
}

/* Button Styles */
.btn {
    display: inline-block;
    padding: 7px 15px;
    text-decoration: none;
    color: #fff;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    cursor: pointer;
}

.btn.edit {
    background-color: #28a745;
}

.btn.delete {
    background-color: #dc3545;
}

.btn:hover {
    opacity: 0.8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 10px;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    table {
        font-size: 12px;
    }

    th, td {
        padding: 10px;
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
            <a href="teacherstud.php" class="active">Student</a>
            <a href="teacherresult.php">Result</a>
            <a href="teacher_notice.php">Notice</a>
            <a href="teacherfaq.php">FAQ</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Students List</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Class</th>
                        <th>Father's Name</th>
                        <th>Father's Phone</th>
                        <th>Mother's Name</th>
                        <th>Mother's Phone</th>
                        <th>Home Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['sId']; ?></td>
                            <td><?php echo htmlspecialchars($row['sName']); ?></td>
                            <td><?php echo htmlspecialchars($row['sAge']); ?></td>
                            <td><?php echo htmlspecialchars($row['sClass']); ?></td>
                            <td><?php echo htmlspecialchars($row['fatherName']); ?></td>
                            <td><?php echo htmlspecialchars($row['fatherContact']); ?></td>
                            <td><?php echo htmlspecialchars($row['motherName']); ?></td>
                            <td><?php echo htmlspecialchars($row['motherContact']); ?></td>
                            <td><?php echo htmlspecialchars($row['homeAddress']); ?></td>
                            <td>
                                <a href="teachereditstud.php?id=<?php echo $row['sId']; ?>" class="btn edit">Edit</a>
                                <a href="?delete=<?php echo $row['sId']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>