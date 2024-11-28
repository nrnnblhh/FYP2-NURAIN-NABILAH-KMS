<?php
$conn = new mysqli("localhost", "root", "", "fypkms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['aId'])) {
    $aId = intval($_GET['aId']);
    $sql = "DELETE FROM announcement WHERE id = $aId";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Notice deleted successfully!'); window.location.href = 'notice_board.php';</script>";
    } else {
        echo "Error deleting notice: " . $conn->error;
    }
}
?>
