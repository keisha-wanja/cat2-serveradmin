<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name !== '' && $message !== '') {
        $stmt = $conn->prepare('INSERT INTO feedback (name, email, message) VALUES (?,?,?)');
        $stmt->bind_param('sss', $name, $email, $message);
        $stmt->execute();
        $msg = 'Feedback saved ✅';
    } else {
        $msg = 'Name and message are required.';
    }
}
$rows = $conn->query('SELECT id, name, email, message, created_at FROM feedback ORDER BY id DESC');
?>
<!DOCTYPE html>
<html><head><title>Feedback</title>
<style>body{font-family:Arial;max-width:700px;margin:40px auto}
input,textarea{width:100%;padding:9px;margin:6px 0;box-sizing:border-box}
button{padding:10px 20px;background:#2c5282;color:#fff;border:0;border-radius:6px}
table{border-collapse:collapse;width:100%;margin-top:20px}
td,th{border:1px solid #ccc;padding:8px;text-align:left}th{background:#edf2f7}</style>
</head><body>
<h2>Feedback Form</h2>
<p><?php echo $msg; ?></p>
<form method="POST" action="feedback.php">
  <input name="name" placeholder="Your name" required>
  <input name="email" type="email" placeholder="Email">
  <textarea name="message" rows="4" placeholder="Your feedback" required></textarea>
  <button type="submit">Submit</button>
</form>
<h3>Saved feedback (retrieved from the database)</h3>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Time</th></tr>
<?php while ($r = $rows->fetch_assoc()): ?>
<tr>
  <td><?php echo $r['id']; ?></td>
  <td><?php echo htmlspecialchars($r['name']); ?></td>
  <td><?php echo htmlspecialchars($r['email']); ?></td>
  <td><?php echo htmlspecialchars($r['message']); ?></td>
  <td><?php echo $r['created_at']; ?></td>
</tr>
<?php endwhile; ?>
</table>
<p><a href="dashboard.php">Back</a></p>
</body></html>