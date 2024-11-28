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

$popupMessage = ""; // For passing success/error to JavaScript

// Retrieve student data for editing
if (isset($_GET['id'])) {
    $sId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM student WHERE sId = ?");
    $stmt->bind_param("i", $sId);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission to update the student
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sId = intval($_POST['sId']);
    $sName = $_POST['sName'];
    $sAge = $_POST['sAge'];
    $sClass = $_POST['sClass'];
    $fatherName = $_POST['fatherName'];
    $fatherContact = $_POST['fatherContact'];
    $motherName = $_POST['motherName'];
    $motherContact = $_POST['motherContact'];
    $homeAddress = $_POST['homeAddress'];

    // Validate input (basic validation)
    if (empty($sName) || empty($sAge) || empty($sClass) || empty($fatherName) || empty($fatherContact) || empty($motherName) || empty($motherContact) || empty($homeAddress)) {
        $popupMessage = "All fields are required.";
    } else {
        // Update student details in the database
        $stmt = $conn->prepare("UPDATE student SET sName = ?, sAge = ?, sClass = ?, fatherName = ?, fatherContact = ?, motherName = ?, motherContact = ?, homeAddress = ? WHERE sId = ?");
        $stmt->bind_param("sissssssi", $sName, $sAge, $sClass, $fatherName, $fatherContact, $motherName, $motherContact, $homeAddress, $sId);

        if ($stmt->execute()) {
            $popupMessage = "Student successfully updated!";
        } else {
            $popupMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    
    <style>
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
            justify-content: center;
            align-items: center;
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

        .form-container h2 {
            text-align: center;
            background-color: #007bff; /* Changed header color to blue for edit */
            color: white;
            padding: 15px;
            margin: -40px -40px 20px -40px;
            border-radius: 8px 8px 0 0;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-btn {
            text-align: center;
        }

        button.submit-btn {
            width: 200px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            background-color: #007bff; /* Changed button color to blue for edit */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.submit-btn:hover {
            background-color: #0056b3;
        }

        /* Error and Success Messages */
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
</style>

    <script>
        // JavaScript function to show a pop-up message and redirect
        function showPopup(message) {
            if (message) {
                alert(message);
                setTimeout(function() {
                    window.location.href = "teacherviewstud.php"; // Redirect after showing the alert
                }, 100); // Small delay to ensure the alert appears
            }
        }
    </script>
</head>
<body onload="showPopup('<?php echo $popupMessage; ?>')">

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
        <div class="form-container">
            <h2>Edit Student</h2>

            <!-- Form -->
            <form method="POST" action="">
                <input type="hidden" name="sId" value="<?php echo htmlspecialchars($student['sId'] ?? ''); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="sName">Full Name</label>
                        <input type="text" id="sName" name="sName" value="<?php echo htmlspecialchars($student['sName'] ?? ''); ?>" placeholder="Enter student's full name" required>
                    </div>
                    <div class="form-group">
                        <label for="sAge">Age</label>
                        <select id="sAge" name="sAge" required>
                            <option value="" disabled>Select Age</option>
                            <option value="5" <?php echo ($student['sAge'] ?? '') == '5' ? 'selected' : ''; ?>>5</option>
                            <option value="6" <?php echo ($student['sAge'] ?? '') == '6' ? 'selected' : ''; ?>>6</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="sClass">Class</label>
                        <select id="sClass" name="sClass" required>
                            <option value="" disabled>Select Class</option>
                            <option value="Abu Bakar" <?php echo ($student['sClass'] ?? '') == 'Abu Bakar' ? 'selected' : ''; ?>>Abu Bakar</option>
                            <option value="Omar" <?php echo ($student['sClass'] ?? '') == 'Omar' ? 'selected' : ''; ?>>Omar</option>
                            <option value="Othman" <?php echo ($student['sClass'] ?? '') == 'Othman' ? 'selected' : ''; ?>>Othman</option>
                            <option value="Ali" <?php echo ($student['sClass'] ?? '') == 'Ali' ? 'selected' : ''; ?>>Ali</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fatherName">Father's Name</label>
                        <input type="text" id="fatherName" name="fatherName" value="<?php echo htmlspecialchars($student['fatherName'] ?? ''); ?>" placeholder="Enter father's name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="fatherContact">Father's Phone Number</label>
                        <input type="text" id="fatherContact" name="fatherContact" value="<?php echo htmlspecialchars($student['fatherContact'] ?? ''); ?>" placeholder="Enter father's phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="motherName">Mother's Name</label>
                        <input type="text" id="motherName" name="motherName" value="<?php echo htmlspecialchars($student['motherName'] ?? ''); ?>" placeholder="Enter mother's name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="motherContact">Mother's Phone Number</label>
                        <input type="text" id="motherContact" name="motherContact" value="<?php echo htmlspecialchars($student['motherContact'] ?? ''); ?>" placeholder="Enter mother's phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="homeAddress">Home Address</label>
                        <input id="homeAddress" name="homeAddress" value="<?php echo htmlspecialchars($student['homeAddress'] ?? ''); ?>" rows="3" placeholder="Enter home address" required>
                    </div>
                </div>
                <div class="form-btn">
                    <button type="submit" class="submit-btn">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
