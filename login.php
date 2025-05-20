<?php
session_start();
$error = '';
include("db_connect.php");
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $error = "Invalid CSRF token.";
  } else {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email format.";
    } else {
      $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          if (!empty($_POST['remember_me'])) {
            setcookie("user_id", $user['id'], time() + (86400 * 30), "/", "", true, true);
          }
          if ($email === 'admin@gmail.com') { 
            $_SESSION['is_admin'] = true;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: Adminpanel.php");
            exit();
          } else {
            $_SESSION['is_admin'] = false;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: dashboard.php");
            exit();
          }
        } else {
          $error = "Invalid password.";
        }
      } else {
        $error = "No account found with that email.";
      }
      $stmt->close();
    }
  }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fixit Portal - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('/pound.jpg') no-repeat center center/cover;
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
    .navbar {
      margin-bottom: 20px;
    }
    .login-container {
      max-width: 400px;
      margin: 50px auto;
      padding: 30px;
      background-color: rgba(0, 0, 0, 0.8); 
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .login-btn {
      width: 100%;
      background-color: #0056b3;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
      cursor: pointer;
    }
    .login-btn:hover {
      background-color: #003f7d;
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="login-container">
    <h2 class="text-center mb-4">Login</h2>
    <?php if (!empty($error)) { echo '<div class="alert alert-danger text-center">' . $error . '</div>'; } ?>
    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
        <label class="form-check-label" for="remember_me">Remember Me</label>
      </div>
      <button type="submit" class="login-btn">Login</button>
    </form>
    <div class="text-center mt-3">
      <p>Don't have an account? <a href="signup.php">Create an account</a></p>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
