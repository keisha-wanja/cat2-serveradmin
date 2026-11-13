<?php
require 'config.php';
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
<html><head><title>Log In</title>
<style>body{font-family:Arial;max-width:380px;margin:60px auto}
input{width:100%;padding:9px;margin:6px 0;box-sizing:border-box}
button{width:100%;padding:10px;background:#2c5282;color:#fff;border:0;border-radius:6px}</style>
</head><body>
<h2>Log In</h2>
<p style="color:#c53030"><?php echo $msg; ?></p>
<form method="POST" action="login.php">
  <input name="username" placeholder="Username" required>
  <input name="password" type="password" placeholder="Password" required>
  <button type="submit">Log in</button>
</form>
<p>No account? <a href="signup.php">Sign up</a></p>
</body></html>