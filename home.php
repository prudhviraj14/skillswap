<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";  
$password = "prudhvi@30";  
$conn = new mysqli($servername, $username, $password, "skillswap_");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];
$sql_user = "SELECT Fullname FROM userregister WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $userId);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$username = $result_user->num_rows > 0 ? htmlspecialchars($result_user->fetch_assoc()['Fullname']) : "User";

$sql_pending_requests = "SELECT COUNT(*) AS pending_count FROM connections WHERE receiver_id = ? AND status = 'pending'";
$stmt_pending = $conn->prepare($sql_pending_requests);
$stmt_pending->bind_param("i", $userId);
$stmt_pending->execute();
$pending_count = $stmt_pending->get_result()->fetch_assoc()['pending_count'];

$sql_review_notifications = "SELECT COUNT(*) AS new_reviews FROM reviews WHERE user_id = ?";
$stmt_review_notifications = $conn->prepare($sql_review_notifications);
$stmt_review_notifications->bind_param("i", $userId);
$stmt_review_notifications->execute();
$new_reviews = $stmt_review_notifications->get_result()->fetch_assoc()['new_reviews'];

$stmt_user->close();
$stmt_pending->close();
$stmt_review_notifications->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SkillSwap Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(130deg, #111827, #1f2937, #374151);
      background-size: 400% 400%;
      animation: gradientBG 20s ease infinite;
      color: #f9fafb;
    }

    @keyframes gradientBG {
      0% {background-position: 0% 50%;}
      50% {background-position: 100% 50%;}
      100% {background-position: 0% 50%;}
    }

    header {
      background: rgba(17, 24, 39, 0.9);
      padding: 1.2rem;
      font-size: 2rem;
      font-weight: bold;
      color: #60a5fa;
      text-align: center;
      text-shadow: 1px 1px 4px #0ea5e9;
    }

    nav {
      display: flex;
      justify-content: space-between;
      padding: 1rem 2rem;
      background: rgba(31, 41, 55, 0.95);
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    nav a {
      color: #f3f4f6;
      margin: 0 1rem;
      text-decoration: none;
      position: relative;
      font-weight: 500;
    }

    nav a:hover {
      color: #38bdf8;
      text-shadow: 0 0 8px #38bdf8;
    }

    .notification-badge {
      background: #ef4444;
      color: white;
      font-size: 0.75rem;
      border-radius: 999px;
      padding: 2px 8px;
      position: absolute;
      top: -10px;
      right: -10px;
    }

    .logout-btn {
      background: #ef4444;
      border: none;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      font-weight: 600;
      transition: 0.3s;
    }

    .logout-btn:hover {
      background: #dc2626;
    }

    .container {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 2rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }

    .search-bar {
      margin: 2rem 0;
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    .search-bar input {
      padding: 0.8rem;
      border-radius: 10px;
      border: none;
      width: 60%;
      font-size: 1rem;
    }

    .search-bar button {
      padding: 0.8rem 1.5rem;
      background: #0ea5e9;
      border: none;
      color: white;
      font-weight: 600;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .search-bar button:hover {
      background: #0284c7;
      box-shadow: 0 0 12px #38bdf8;
    }

    .btn {
      background: #22c55e;
      padding: 1rem 2rem;
      color: white;
      border: none;
      border-radius: 12px;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn:hover {
      background: #16a34a;
      box-shadow: 0 0 12px #4ade80;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin: 2rem 0;
    }

    .skill-box {
      background: rgba(255, 255, 255, 0.08);
      padding: 1rem 1.5rem;
      margin: 1rem 0;
      border-radius: 12px;
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255,255,255,0.1);
      transition: transform 0.3s;
    }

    .skill-box:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 14px rgba(255, 255, 255, 0.2);
    }

    footer {
      margin-top: 5rem;
      text-align: center;
      background: rgba(17, 24, 39, 0.8);
      padding: 2rem;
      color: #9ca3af;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

<header>SkillSwap</header>

<nav>
  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="discover.html">Discover Skills</a>
    <a href="connect.html">
      <?php if ($pending_count > 0): ?>
        <span class="notification-badge"><?php echo $pending_count; ?></span>
      <?php endif; ?>
      Swap Requests
    </a>
    <a href="chat.php">Chats</a>
    <a href="resume_screener/index.php">Upskill Ideas</a>
  </div>
  <div>
    <a href="reviews.php" style="position: relative;">
      <i class="fa-solid fa-bell"></i>
      <?php if ($new_reviews > 0): ?>
        <span class="notification-badge"><?php echo $new_reviews; ?></span>
      <?php endif; ?>
    </a>
    <a href="profile.html"><i class="fa-solid fa-user"></i></a>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</nav>

<div class="container">
  <h2 style="text-align:center;">Welcome, <?php echo $username; ?> 👋</h2>
  <p style="text-align:center;">Discover, share, and swap skills with the community around you!</p>

  <div class="search-bar">
    <input type="text" id="search-input" placeholder="Search for a skill..." />
    <button id="search-btn">Search</button>
  </div>

  <div class="buttons">
    <a href="discover.html" class="btn">Explore Skills</a>
    <a href="connect.html" class="btn">My Swaps</a>
  </div>

  <div id="search-results"></div>

  <!-- Latest Skills PHP inclusion -->
  <?php include 'latest_skills.php'; ?>
</div>

<footer>
  <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
    <p>&copy; <?php echo date('Y'); ?> <strong>SkillSwap</strong>. All rights reserved. Designed with ❤️</p>
    <div style="display: flex; gap: 1rem; font-size: 0.9rem;">
      <a href="privacy-policy.php" style="color: #60a5fa; text-decoration: none; transition: 0.3s;">Privacy Policy</a>
      <a href="terms-of-service.php" style="color: #60a5fa; text-decoration: none; transition: 0.3s;">Terms of Service</a>
    </div>
  </div>
</footer>


<script>
document.getElementById('search-btn').addEventListener('click', function () {
  const query = document.getElementById('search-input').value.trim();
  if (query !== '') {
    fetch('searchbar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'query=' + encodeURIComponent(query)
    })
      .then(response => response.json())
      .then(data => {
        const resultsDiv = document.getElementById('search-results');
        resultsDiv.innerHTML = '';
        if (data.length > 0) {
          data.forEach(user => {
            resultsDiv.innerHTML += `<div class="skill-box"><strong>${user.name}</strong> - ${user.skills} <a class="btn" style="margin-left:10px;" href="user_profile.php?id=${user.id}">View Profile</a></div>`;
          });
        } else {
          resultsDiv.innerHTML = '<p>No skills found.</p>';
        }
      })
      .catch(error => console.error('Error:', error));
  }
});
</script>

</body>
</html>
