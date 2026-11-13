<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    }
    $msg = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f2f4f8; display: flex; justify-content: center; padding-top: 80px; }
    .box { background: #fff; padding: 30px; border-radius: 8px; width: 320px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    input { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; }
    button { width: 100%; padding: 10px; background: #2b6cb0; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
    .err { color: #c53030; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>
    <form method="POST">
      <input name="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p class="err"><?= htmlspecialchars($msg) ?></p>
    <p>No account? <a href="signup.php">Sign up</a></p>
  </div>
</body>
</html>