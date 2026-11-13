<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html><head><title>Dashboard</title>
<style>body{font-family:Arial;max-width:600px;margin:60px auto;text-align:center}</style>
</head><body>
<h2>Login successful ✅</h2>
<h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
<p><a href="feedback.php">Feedback form</a> | <a href="logout.php">Log out</a></p>
</body></html>
