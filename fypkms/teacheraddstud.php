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

$popupMessage = ""; // For passing success/error messages to JavaScript

// Fetch all parents for the dropdown
$parents = $conn->query("SELECT pId, pName, pEmail FROM parent");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $sName = $_POST['sName'];
    $sAge = $_POST['sAge'];
    $sClass = $_POST['sClass'];
    $pId = $_POST['pId']; // Parent ID from dropdown
    $homeAddress = $_POST['homeAddress'];

    // Validate input
    if (empty($sName) || empty($sAge) || empty($sClass) || empty($pId) || empty($homeAddress)) {
        $popupMessage = "All fields are required.";
    } else {
        // Insert into student table
        $stmt = $conn->prepare("INSERT INTO student (sName, sAge, sClass, pId, homeAddress) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $sName, $sAge, $sClass, $pId, $homeAddress);

        if ($stmt->execute()) {
            $popupMessage = "Student added successfully!";
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
    <title>Add Student</title>
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
            justify-content: flex-start;
            align-items: center;
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
        .form-container {
            margin-left: 240px; /* Sidebar width */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            margin: auto;
            text-align: center;
        }

        .form-container h2 {
            text-align: center;
            color: black;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            text-align: left;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                margin-left: 20px; /* Adjust margin for smaller screens */
                margin-right: 20px;
            }

            .sidebar {
                width: 180px; /* Adjust sidebar width for smaller screens */
            }
        }
    </style>
    <script>
        // JavaScript function to show a pop-up message
        function showPopup(message) {
            if (message) {
                alert(message);
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

    <!-- Form Container -->
    <div class="form-container">
        <h2>Add Student</h2>
        <form method="POST" action="">
            <label for="sName">Full Name:</label>
            <input type="text" name="sName" id="sName" required>

            <label for="sAge">Age:</label>
            <input type="number" name="sAge" id="sAge" required>

            <label for="sClass">Class:</label>
            <input type="text" name="sClass" id="sClass" required>

            <label for="pId">Select Parent:</label>
            <select name="pId" id="pId" required>
                <option value="" disabled selected>Select Parent</option>
                <?php while ($parent = $parents->fetch_assoc()): ?>
                    <option value="<?php echo $parent['pId']; ?>">
                        <?php echo $parent['pName'] . " (" . $parent['pEmail'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="homeAddress">Home Address:</label>
            <textarea name="homeAddress" id="homeAddress" rows="3" required></textarea>

            <button type="submit">Add Student</button>
        </form>
    </div>
</body>
</html>