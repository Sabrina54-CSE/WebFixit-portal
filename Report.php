<?php
include('db_connect.php');
$month = date('m');
$year = date('Y');
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaints WHERE MONTH(created_at)=? AND YEAR(created_at)=?");
$stmt->bind_param("ss", $month, $year);
$stmt->execute();
$stmt->bind_result($total_complaints);
$stmt->fetch();
$stmt->close();
$sql = "SELECT department, COUNT(*) as count FROM complaints WHERE MONTH(created_at)='$month' AND YEAR(created_at)='$year' GROUP BY department ORDER BY count DESC LIMIT 1";
$result = $conn->query($sql);
$top_department = "N/A";
$top_count = 0;
if ($row = $result->fetch_assoc()) {
    $top_department = $row['department'];
    $top_count = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Monthly Report - Fixit Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .navbar, footer {
      background-color: #1c1f2b;
    }
    .navbar-brand, .nav-link, footer p {
      color: #fff !important;
    }
    .monthly-report {
      max-width: 1100px;
      margin: 60px auto;
      padding: 40px;
      background: linear-gradient(to bottom right, #ffffff, #f9fbff);
      border-radius: 15px;
      box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
    }
    .monthly-report h2 {
      text-align: center;
      font-size: 2.4rem;
      font-weight: 700;
      color: #2e3650;
      margin-bottom: 10px;
    }
    .monthly-report p.description {
      text-align: center;
      font-size: 1.1rem;
      margin-bottom: 30px;
      color: #555;
    }
    .highlight-box {
      background-color: #fff2f2;
      border-left: 6px solid #dc3545;
      padding: 25px 30px;
      border-radius: 12px;
      margin: 30px 0;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    .highlight-box h4 {
      color: #b02a37;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .highlight-box p {
      margin: 0;
      color: #333;
    }
    .note {
      font-size: 0.95rem;
      text-align: center;
      color: #777;
      margin-top: 30px;
    }
    .btn-download {
      display: block;
      margin: 30px auto 0;
      background: #426be8;
      color: #fff;
      padding: 12px 28px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      transition: 0.3s;
    }
    .btn-download:hover {
      background: #2f52b4;
    }
    footer {
      text-align: center;
      padding: 18px 0;
      margin-top: 60px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Fixit Portal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon text-white"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="live chart.php">Live Chat</a></li>
          <li class="nav-item"><a class="nav-link" href="Emergency alertpage.php">Emergency Alert</a></li>
          <li class="nav-item"><a class="nav-link" href="votting page.php">Vote</a></li>
          <li class="nav-item"><a class="nav-link" href="locationtracking.php">Location</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <section class="monthly-report">
    <h2>Monthly Complaint Report</h2>
    <p class="description">An overview of the most reported departments this month and recommended administrative actions.</p>
    <div class="highlight-box">
      <h4>Total Complaints This Month</h4>
      <p><strong><?php echo $total_complaints; ?></strong> complaints submitted in <?php echo date('F Y'); ?>.</p>
    </div>
    <div class="highlight-box">
      <h4>Department with Most Complaints</h4>
      <p>
        <strong><?php echo htmlspecialchars($top_department); ?></strong>
        received the highest number of complaints (<?php echo $top_count; ?>) this month.
      </p>
    </div>
    <p class="note">Regular monitoring and proactive resolution of issues will ensure a better experience for all students.</p>
    <button class="btn-download">⬇️ Download Full Report</button>
  </section>
  <footer>
    <p>&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
