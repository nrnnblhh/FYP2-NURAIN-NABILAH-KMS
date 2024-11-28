<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "fypkms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure teacher session exists
$teacher = isset($_SESSION['teacher']) ? $_SESSION['teacher'] : ['tName' => 'Default Name'];

// Fetch classes
$classResult = $conn->query("SELECT DISTINCT sClass FROM student");

// Handle form submission
$message = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class = $_POST['class'] ?? '';
    $monthyear = $_POST['monthyear'] ?? '';
    $ratings = $_POST['ratings'] ?? [];

    // Validate monthyear format and convert to `YYYY-MM-DD`
    if (DateTime::createFromFormat('Y-m', $monthyear) === false) {
        $error = "Invalid date format. Please select a valid month.";
    } else {
        $monthyear = $monthyear . "-01"; // Append day for `DATE` format

        foreach ($ratings as $sId => $data) {
            $rating = $data['rating'] ?? null;
            $comments = $data['comments'] ?? '';

            // Validate inputs
            if ($rating === null || $rating < 1 || $rating > 10) {
                $error = "Invalid rating for student ID $sId. Please enter a value between 1 and 10.";
                break;
            }

            // Prevent duplicate entries
            $stmt = $conn->prepare("SELECT * FROM ratings WHERE sId = ? AND monthyear = ?");
            $stmt->bind_param("is", $sId, $monthyear);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                // Insert rating if no duplicate found
                $stmt = $conn->prepare("INSERT INTO ratings (sId, monthyear, rating, comments) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isis", $sId, $monthyear, $rating, $comments);
                if (!$stmt->execute()) {
                    $error = "Error inserting data for student ID $sId: " . $stmt->error;
                    break;
                }
            } else {
                $error = "Duplicate entry found for student ID $sId in $monthyear.";
                break;
            }
        }

        if (!$error) {
            $message = "Ratings successfully saved!";
        }
    }
}

// Fetch students for the selected class
$student = null;
if (isset($_GET['class'])) {
    $sClass = $_GET['class'];
    $stmt = $conn->prepare("SELECT * FROM student WHERE sClass = ?");
    $stmt->bind_param("s", $sClass);
    $stmt->execute();
    $student = $stmt->get_result();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Students</title>
    
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
    margin-left: 240px; /* Matches the width of the sidebar */
    padding: 40px 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    box-sizing: border-box;
}

/* Form Container */
.form-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 30px 40px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 900px;
    width: 100%;
    text-align: center;
}

.form-container h2 {
    background-color: #6c757d;
    color: white;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

/* Form Elements */
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center; /* Center form items */
}

select, input[type="month"], input[type="number"], textarea {
    width: 90%;
    max-width: 400px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

textarea {
    resize: none;
    height: 80px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

table th {
    background-color: #6c757d;
    color: white;
}

/* Button */
button.submit-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

button.submit-btn:hover {
    background-color: #218838;
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

    <div class="main-content">
        <div class="form-container">
            <h2>Rate Student Performance</h2>

            <!-- Success or Error Messages -->
            <?php if ($message): ?>
                <p class="success"><?php echo $message; ?></p>
            <?php elseif ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Class Selection -->
            <form method="GET" action="teacherratestud.php">
                <label for="class">Select class:</label>
                <select name="class" id="class" required onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    <?php while ($row = $classResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['sClass']; ?>" <?php if (isset($_GET['class']) && $_GET['class'] == $row['sClass']) echo 'selected'; ?>>
                            <?php echo $row['sClass']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <!-- Rating Form -->
            <?php if (isset($_GET['class']) && $student && $student->num_rows > 0): ?>
                <form method="POST" action="teacherratestud.php">
                    <input type="hidden" name="class" value="<?php echo isset($_GET['class']) ? htmlspecialchars($_GET['class']) : ''; ?>">
                    <br><br><label for="monthyear">Select Month:</label>
                    <input type="month" name="monthyear" id="monthyear" required>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Rating (1-10)</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $student->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['sName']); ?></td>
                                    <td>
                                        <input type="number" name="ratings[<?php echo $row['sId']; ?>][rating]" min="1" max="10" required>
                                    </td>
                                    <td>
                                        <textarea name="ratings[<?php echo $row['sId']; ?>][comments]" rows="2"></textarea>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <button type="submit" class="submit-btn">Submit Ratings</button>
                </form>
            <?php else: ?>
                <?php if (isset($_GET['class'])): ?>
                    <p>No students found for the selected class: <?php echo htmlspecialchars($_GET['class']); ?>.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
