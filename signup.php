<?php
require 'config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $msg = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Invalid email address.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare('INSERT INTO users (username, fullname, email, password_hash) VALUES (?,?,?,?)');
            $stmt->bind_param('ssss', $username, $fullname, $email, $hash);
            $stmt->execute();
            $msg = 'Account created! <a href="login.php">Log in here</a>';
        } catch (mysqli_sql_exception $e) {
            $msg = 'That username or email already exists.';
        }
    }
}
?>
<!DOCTYPE html>
<html><head><title>Sign Up</title>
<style>body{font-family:Arial;max-width:380px;margin:60px auto}
input{width:100%;padding:9px;margin:6px 0;box-sizing:border-box}
button{width:100%;padding:10px;background:#2c5282;color:#fff;border:0;border-radius:6px}</style>
</head><body>
<h2>Sign Up</h2>
<p><?php echo $msg; ?></p>
<form method="POST" action="signup.php">
  <input name="username" placeholder="Username" required>
  <input name="fullname" placeholder="Full name">
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <button type="submit">Create account</button>
</form>
<p>Already registered? <a href="login.php">Log in</a></p>
</body></html>