<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fixit Portal - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('pound.jpg') no-repeat center center/cover;
      background-size: cover;
      color: #fff;
      min-height: 100vh;
      position: relative;
    }
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.6); 
      z-index: -1;
    }
    .hero-section {
      height: 90vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
    }
    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      margin: 0 auto;
    }
    .hero-content h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .hero-content p {
      font-size: 1.3rem;
      margin-top: 15px;
    }
    .navbar {
      margin-bottom: 20px;
    }
    footer {
      background-color: #111;
      color: #bbb;
      text-align: center;
      padding: 15px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Fixit Portal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="Emergency alertpage.php">Emergency Alert</a></li>
          <li class="nav-item"><a class="nav-link" href="live chart.php">Live Chat</a></li>
          <li class="nav-item"><a class="nav-link" href="votting page.php">Vote</a></li>
          <li class="nav-item"><a class="nav-link" href="locationtracking.php">Location</a></li>
          <li class="nav-item"><a class="nav-link" href="report.php">Monthly Report</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="Signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <section class="hero-section">
    <div class="hero-content">
      <h1>Welcome to Fixit Portal</h1>
      <p>
        Fixit Portal is your trusted platform to report and resolve campus and hall-related issues efficiently.
        Whether it's an administrative concern, maintenance problem or any other campus-related issue,
        we're here to ensure swift action. With real-time notifications and direct communication tools,
        we make it easier for you to get things done. Join us and help improve the campus community for everyone.
      </p>
    </div>
  </section>
  <footer>
    <p>&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>