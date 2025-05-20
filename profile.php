<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include('db_connect.php');
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, phone, hall, department, profile_picture, account_type, student_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $hall, $department, $profile_picture, $account_type, $student_id);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Profile - Fixit Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to bottom right, #1c1f2b, #2e3650);
      color: #fff;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }
    .profile-card {
      background: rgba(0,0,0,0.85);
      border-radius: 16px;
      max-width: 400px;
      margin: 80px auto 0 auto;
      padding: 32px 24px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      text-align: center;
    }
    .profile-img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #fff;
      margin-bottom: 16px;
    }
    .info-label {
      font-weight: 600;
      color: #bbb;
      margin-top: 12px;
      margin-bottom: 2px;
    }
    .info-value {
      font-size: 1.1rem;
      margin-bottom: 8px;
    }
    .edit-btn {
      margin-top: 18px;
      width: 100%;
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="Homepage.php">Fixit Portal</a>
    </div>
  </nav>
  <div class="profile-card mt-5">
    <img src="<?php echo $profile_picture ? htmlspecialchars($profile_picture) : 'profile.png'; ?>" alt="Profile Picture" class="profile-img mb-3" />
    <h2 class="mb-1"><?php echo htmlspecialchars($name); ?></h2>
    <div class="info-label">Student ID</div>
    <div class="info-value"><?php echo htmlspecialchars($student_id); ?></div>
    <div class="info-label">Email</div>
    <div class="info-value"><?php echo htmlspecialchars($email); ?></div>
    <div class="info-label">Phone</div>
    <div class="info-value"><?php echo htmlspecialchars($phone); ?></div>
    <div class="info-label">Hall</div>
    <div class="info-value"><?php echo htmlspecialchars($hall); ?></div>
    <div class="info-label">Department</div>
    <div class="info-value"><?php echo htmlspecialchars($department); ?></div>
    <div class="info-label">Account Type</div>
    <div class="info-value"><?php echo htmlspecialchars($account_type); ?></div>
    <a href="edit-profile.php" class="btn btn-warning edit-btn">Edit Profile</a>
  </div>
  <footer>
    <p>&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
</body>
</html>
