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

// Fetch results
$classId = $_GET['classId'] ?? null;
$students = [];
if ($classId) {
    // Query to fetch ratings and student data
    $stmt = $conn->prepare("
        SELECT 
            student.sId, 
            student.sName, 
            student.sAge, 
            student.sClass, 
            ratings.monthyear, 
            ratings.rating, 
            ratings.comments
        FROM student
        LEFT JOIN ratings ON student.sId = ratings.sId
        WHERE student.sClass = ?
        ORDER BY ratings.monthyear DESC
    ");
    $stmt->bind_param("s", $classId);
    $stmt->execute();
    $students = $stmt->get_result();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students Ratings</title>
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
                padding: 40px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
                color: #333;
            }

            /* Table Styles */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
                background: #fff;
            }

            th, td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #f4f4f4;
            }

            th {
                background-color: #6c757d;
                color: white;
                text-align: center;
            }

            td {
                color: #333;
                text-align: center;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f1f1f1;
            }

            /* Dropdown */
            select {
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 5px;
                margin-bottom: 20px;
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h2>Students Ratings</h2>
            <form method="GET" action="">
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
            </form>

            <?php if ($classId && $students->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Class</th>
                            <th>Month</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['sId']); ?></td>
                                <td><?php echo htmlspecialchars($row['sName']); ?></td>
                                <td><?php echo htmlspecialchars($row['sAge']); ?></td>
                                <td><?php echo htmlspecialchars($row['sClass']); ?></td>
                                <td><?php echo htmlspecialchars(date("F Y", strtotime($row['monthyear']))); ?></td>
                                <td><?php echo htmlspecialchars($row['rating'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['comments'] ?? 'No Comments'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($classId): ?>
                <p>No ratings found for this class.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
