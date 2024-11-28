<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fypkms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch announcements
$sql = "SELECT * FROM announcement ORDER BY createdat DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Board</title>
    <link rel="stylesheet" href="teacherannounce.css">
</head>
<body>
    <div class="container">
        <h2>Notice Board</h2>
        <div class="notices">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="notice-card">
                        <div class="notice-header">
                            <span>Posted on: <?php echo date("M. d, Y", strtotime($row['createdat'])); ?></span>
                            <span>By: <?php echo htmlspecialchars($row['author']); ?></span>
                            <button class="delete-btn" onclick="deleteNotice(<?php echo $row['aId']; ?>)">×</button>
                        </div>
                        <div class="notice-body">
                            <p><?php echo htmlspecialchars($row['message']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function deleteNotice(aId) {
            if (confirm("Are you sure you want to delete this notice?")) {
                window.location.href = `deletenotice.php?aId=${aId}`;
            }
        }
    </script>
</body>
</html>
