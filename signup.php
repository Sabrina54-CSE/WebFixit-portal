<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db_connect.php");
$error = '';
$success = '';
if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  if ($password !== $confirmPassword) {
    $error = "Passwords do not match!";
  } else {
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows > 0) {
      $error = "Email already registered!";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $name, $email, $hashedPassword);

      if ($stmt->execute()) {
        $success = "Account created successfully! You can now login.";
      } else {
        $error = "Error: " . $stmt->error;
      }
      $stmt->close();
    }
    $checkStmt->close();
  }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fixit Portal - Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('pound.jpg') no-repeat center center/cover;
      background-size: cover;
      color: #fff;
      height: 100vh;
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
    .signup-container {
      max-width: 450px;
      margin: 50px auto;
      padding: 30px;
      background-color: rgba(0, 0, 0, 0.8);
      border-radius: 8px;
    }
    .signup-btn {
      width: 100%;
      background-color: #28a745;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
    }
    .signup-btn:hover {
      background-color: #1c7c31;
    }
    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      background-color: #111;
      color: #bbb;
      text-align: center;
      padding: 15px 0;
    }
    a {
      color: #4da3ff;
    }
    a:hover {
      color: #fff;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Fixit Portal</a>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link active" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="signup-container">
    <h2 class="text-center mb-4">Create an Account</h2>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST" action="signup.php">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" required />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required />
      </div>
      <button type="submit" class="signup-btn">Sign Up</button>
    </form>
    <div class="text-center mt-3">
      <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>
  <footer>
    <p>&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
</body>
</html>