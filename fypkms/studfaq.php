<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
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
            <a href="studenthome.php">Home</a>
            <a href="studprofile.php">Profile</a>
            <a href="studattendance.php">Attendance</a>
            <a href="studresult.php">Result</a>
            <a href="studfaq.php" class="active">FAQ</a>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <div class="faq-container">
            <h1>Frequently Asked Questions (FAQ)</h1>

            <div class="faq-item">
                <h3>1. How do I log in to the system?</h3>
                <p>Click on the login button on the home page and enter your credentials (username and password). If you are a parent, use the credentials provided by the school administration.</p>
            </div>

            <div class="faq-item">
                <h3>2. I forgot my password. What should I do?</h3>
                <p>If you have forgotten your password, click on the "Forgot Password" link on the login page and follow the instructions to reset your password.</p>
            </div>

            <div class="faq-item">
                <h3>3. How can I view my child's attendance?</h3>
                <p>Navigate to the "Attendance" section in the sidebar. Select your child and the date to view their attendance record for that day.</p>
            </div>

            <div class="faq-item">
                <h3>4. Where can I find my child's academic performance?</h3>
                <p>Go to the "Result" section in the sidebar to view your child's academic performance, including their ratings and comments provided by their teacher.</p>
            </div>

            <div class="faq-item">
                <h3>5. Can I update my profile information?</h3>
                <p>Yes, navigate to the "Profile" section in the sidebar. You can update your contact information, password, and other personal details there.</p>
            </div>

            <div class="faq-item">
                <h3>6. What should I do if I encounter an error?</h3>
                <p>If you encounter any issues, please contact the school administration or technical support team for assistance.</p>
            </div>

            <div class="faq-item">
                <h3>7. How do I log out of the system?</h3>
                <p>Click on the "Logout" button at the top-right corner of the page to securely log out of the system.</p>
            </div>
        </div>
    </div>

</body>
</html>
