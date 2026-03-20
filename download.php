<?php
$host = "localhost";
$db = "your_database";
$user = "your_username";
$pass = "your_password";
$conn = new mysqli($host, $user, $pass, $db);

if (!isset($_GET['token'])) {
    die("No token provided.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM uploads WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (strtotime($row['expires_at']) < time()) {
        unlink($row['filepath']); // delete file
        $conn->query("DELETE FROM uploads WHERE id = " . $row['id']); // remove from DB
        die("Link has expired.");
    }

    // Serve the file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"" . basename($row['filename']) . "\"");
    header('Content-Length: ' . filesize($row['filepath']));
    readfile($row['filepath']);
    exit;
} else {
    die("Invalid link.");
}
?>
