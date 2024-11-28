<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher FAQ</title>
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
            margin-left: 260px;
            padding: 40px 20px;
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-container h1 {
            text-align: center;
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .faq-item {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .faq-item h3 {
            font-size: 20px;
            color: #34495e;
            margin-bottom: 10px;
        }

        .faq-item p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                box-shadow: none;
            }

            .faq-container {
                padding: 20px;
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
            <a href="teacherstud.php">Student</a>
            <a href="teacherresult.php">Result</a>
            <a href="teacher_notice.php">Notice</a>
            <a href="teacherfaq.php" class="active">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <div class="faq-container">
            <h1>Teacher FAQ</h1>

            <div class="faq-item">
                <h3>1. How do I mark attendance?</h3>
                <p>Go to the "Attendance" section in the sidebar. Select the appropriate class and date, then mark each student's attendance as "Present" or "Absent". Click "Submit" to save the records.</p>
            </div>

            <div class="faq-item">
                <h3>2. How do I add a new student to my class?</h3>
                <p>Navigate to the "Students" section. Click on "Add Student", fill in the required details, and submit the form. The student will be added to the database.</p>
            </div>

            <div class="faq-item">
                <h3>3. How do I rate a student's performance?</h3>
                <p>Go to the "Results" section and select "Rate Student". Choose the student, month, and enter the rating and comments. Click "Submit" to save the performance review. Note that ratings cannot be edited once submitted, so please ensure all details are accurate.</p>
            </div>

            <div class="faq-item">
                <h3>4. Can I edit or update a student's rating?</h3>
                <p>No, student ratings cannot be edited after they are submitted. If you need to make changes, contact the school administrator to assist with the process.</p>
            </div>

            <div class="faq-item">
                <h3>5. What should I do if I encounter technical issues?</h3>
                <p>If you face any issues, please contact the school administrator or technical support team. Provide detailed information about the issue for quicker resolution.</p>
            </div>
        </div>
    </div>

</body>
</html>
