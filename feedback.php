<?php
session_start();
require 'config.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name !== '' && $message !== '') {
        $stmt = $conn->prepare('INSERT INTO feedback (name, message) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $message);
        $msg = $stmt->execute() ? 'Thank you, your feedback was saved.' : 'Error: ' . $conn->error;
    } else {
        $msg = 'Please fill in both fields.';
    }
}
$rows = $conn->query('SELECT id, name, message, submitted_at FROM feedback ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Feedback</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f2f4f8; padding: 40px; }
    .box { background: #fff; padding: 24px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    input, textarea { width: 100%; padding: 10px; margin: 6px 0; box-sizing: border-box; }
    button { padding: 10px 20px; background: #2b6cb0; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Feedback</h2>
    <form method="POST">
      <input name="name" placeholder="Your name" required>
      <textarea name="message" placeholder="Your message" rows="4" required></textarea>
      <button type="submit">Send</button>
    </form>
    <p><strong><?= htmlspecialchars($msg) ?></strong></p>
    <h3>Submitted feedback</h3>
    <table>
      <tr><th>ID</th><th>Name</th><th>Message</th><th>Date</th></tr>
      <?php while ($r = $rows->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['message']) ?></td>
          <td><?= htmlspecialchars($r['submitted_at']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
    <p><a href="dashboard.php">Back to dashboard</a></p>
  </div>
</body>
</html>