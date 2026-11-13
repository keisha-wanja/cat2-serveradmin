<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare('SELECT username, fullname, email, created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f2f4f8; display: flex; justify-content: center; padding-top: 80px; }
    .box { background: #fff; padding: 30px; border-radius: 8px; width: 380px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    .ok { color: #2f855a; }
    a { color: #2b6cb0; }
  </style>
</head>
<body>
  <div class="box">
    <h2 class="ok">Welcome, <?= htmlspecialchars($user['fullname']) ?>!</h2>
    <p>You are logged in as <strong><?= htmlspecialchars($user['username']) ?></strong>.</p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Member since: <?= htmlspecialchars($user['created_at']) ?></p>
    <p><a href="feedback.php">Feedback form</a> | <a href="logout.php">Log out</a></p>
  </div>
</body>
</html>