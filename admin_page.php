<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

$host = 'localhost';
$db = 'skillswap_';
$user = 'root';
$pass = 'prudhvi@30'; // Update with your correct password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM youtube_collaborations";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
</head>
<body>
  <h2>Admin Dashboard</h2>
  <table border="1">
    <thead>
      <tr>
        <th>Channel Name</th>
        <th>Channel Link</th>
        <th>Program</th>
        <th>Certification</th>
        <th>Email</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['channel_name']); ?></td>
          <td><a href="<?php echo htmlspecialchars($row['channel_link']); ?>" target="_blank">Link</a></td>
          <td><?php echo htmlspecialchars($row['program_to_train']); ?></td>
          <td><?php echo $row['provides_certification'] ? 'Yes' : 'No'; ?></td>
          <td><?php echo htmlspecialchars($row['contact_email']); ?></td>
          <td><?php echo $row['created_at']; ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>

<?php $conn->close(); ?>
