<?php
$host = "localhost";
$db = "ralphmatthewpere_testdb";
$user = "your_username"; // Replace with your DB username
$pass = "your_password"; // Replace with your DB password
$conn = new mysqli($host, $user, $pass, $db);

if ($_FILES['file']['size'] > 100 * 1024 * 1024) {
    die("File too large. Max is 100MB.");
}

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir);
}

$filename = basename($_FILES["file"]["name"]);
$uniqueName = uniqid() . "_" . $filename;
$targetFile = $uploadDir . $uniqueName;

if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour

    $stmt = $conn->prepare("INSERT INTO uploads (filename, filepath, token, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $filename, $targetFile, $token, $expires);
    $stmt->execute();

    echo "Upload successful!<br>";
    echo "Download link (valid
