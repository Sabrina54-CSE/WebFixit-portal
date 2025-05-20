<?php
include('db_connect.php');
$success = '';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue = trim($_POST['emergency-issue']);
    $description = trim($_POST['emergency-description']);
    $priority = trim($_POST['priority']);

    if ($issue && $description && $priority) {
        $stmt = $conn->prepare("INSERT INTO emergency_complaints (issue, description, priority) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $issue, $description, $priority);
        if ($stmt->execute()) {
            $success = "Emergency complaint submitted successfully!";
        } else {
            $error = "Failed to submit complaint.";
        }
        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Emergency Alert - Fixit Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      padding-top: 70px; 
    }
    .form-container {
      max-width: 600px;
      margin: 40px auto;
      background-color: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    h1 {
      font-size: 26px;
      font-weight: 600;
      color: #dc3545;
      text-align: center;
      margin-bottom: 30px;
    }
    label {
      font-weight: 500;
      margin-bottom: 6px;
    }
    input[type="text"],
    textarea,
    select {
      border-radius: 8px !important;
    }
    .btn-submit {
      width: 100%;
      background-color: #dc3545;
      border: none;
      padding: 12px;
      font-weight: bold;
      border-radius: 8px;
      color: #fff;
      margin-top: 20px;
    }
    .btn-submit:hover {
      background-color: #c82333;
    }
    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #343a40;
      color: white;
      text-align: center;
      padding: 10px 0;
      z-index: 1000;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="Homepage.php">Fixit Portal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="live chart.php">Live Chat</a></li>
          <li class="nav-item"><a class="nav-link" href="votting page.php">Vote on Issues</a></li>
          <li class="nav-item"><a class="nav-link" href="report.php">Monthly Report</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="form-container">
    <h1>Submit an Emergency Complaint</h1>
    <?php if ($success): ?>
      <div class="alert alert-success text-center"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
      <div class="mb-3">
        <label for="emergency-issue" class="form-label">Issue</label>
        <input type="text" class="form-control" id="emergency-issue" name="emergency-issue" required />
      </div>
      <div class="mb-3">
        <label for="emergency-description" class="form-label">Description</label>
        <textarea class="form-control" id="emergency-description" name="emergency-description" rows="4" required></textarea>
      </div>
      <div class="mb-3">
        <label for="emergency-priority" class="form-label">Priority</label>
        <select class="form-select" id="emergency-priority" name="priority" required>
          <option value="">Select Priority</option>
          <option value="high">High</option>
          <option value="urgent">Urgent</option>
        </select>
      </div>
      <button type="submit" class="btn btn-submit">Submit Emergency Complaint</button>
    </form>
  </div>
  <footer>
    <p class="mb-0">&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
