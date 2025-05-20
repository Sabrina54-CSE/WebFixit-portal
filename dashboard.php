<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include('db_connect.php');
$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['category'], $_POST['complainType'], $_POST['complaintText'])) {
    $category=$_POST['category'];
    $type=$_POST['complainType'];
    $details=$_POST['complaintText'];
    $status='Pending';
    $stmt=$conn->prepare("INSERT INTO complaints (user_id,category,description,status)VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss",$user_id,$type,$details,$status);
    $stmt->execute();
    $stmt->close();
    header("Location:dashboard.php");
    exit;
}
$query="SELECT name,profile_picture FROM users WHERE id = ?";
$stmt=$conn->prepare($query);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$stmt->bind_result($user_name,$profile_picture);
$stmt->fetch();
$stmt->close();
$complaints_query="SELECT COUNT(*) FROM complaints WHERE user_id = ? AND status = 'Pending'";
$stmt=$conn->prepare($complaints_query);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$stmt->bind_result($pending_complaints);
$stmt->fetch();
$stmt->close();
$resolved_query="SELECT COUNT(*) FROM complaints WHERE user_id = ? AND status = 'Resolved'";
$stmt = $conn->prepare($resolved_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($resolved_complaints);
$stmt->fetch();
$stmt->close();
$total_complaints=$pending_complaints+$resolved_complaints;
$recent_query="SELECT id, category, status, created_at FROM complaints WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($recent_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($cid,$cat,$stat,$created);
$recent_complaints = [];
while ($stmt->fetch()) {
    $recent_complaints[] = [
        'id' => $cid,
        'category' => $cat,
        'status' => $stat,
        'created_at' => $created
    ];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Dashboard - Complaint System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f4f6fa;
      margin: 0;
      padding-bottom: 60px;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      height: 100vh;
      width: 250px;
      background-color: #0a2c73;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 20px;
    }
    .sidebar a {
      display: block;
      padding: 15px 20px;
      color: white;
      text-decoration: none;
      font-weight: 500;
    }
    .sidebar a:hover {
      background-color: #0f3ba4;
    }
    .main-content {
      margin-left: 260px;
      padding: 20px;
    }
    .card {
      border-radius: 10px;
      transition: transform 0.3s ease-in-out;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card:hover {
      transform: translateY(-5px);
    }
    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #0a2c73;
      color: white;
      text-align: center;
      padding: 10px 0;
      z-index: 1000;
    }
    .navbar {
      background-color: #0a2c73;
    }
    .navbar .navbar-brand {
      font-weight: bold;
      font-size: 22px;
    }
    .navbar .navbar-nav {
      margin-left: auto;
    }
    .navbar .navbar-text {
      color: white;
      font-size: 16px;
      margin-right: 10px;
    }
    .profile-img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
    }
    h3 {
      color: #0a2c73;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php">Fixit Portal</a>
      <div class="navbar-nav ms-auto d-flex align-items-center">
        <img src="<?php echo $profile_picture ? htmlspecialchars($profile_picture) :'/profile.png'; ?>" alt="User Profile" class="profile-img" />
        <span class="navbar-text"><strong><?php echo htmlspecialchars($user_name); ?></strong></span>
      </div>
    </div>
  </nav>
  <div class="sidebar">
    <h4 class="text-center mb-4">📊Dashboard</h4>
    <a href="index.php">🏠 Home</a>
    <a href="Emergency_alertpage.php">🚨Emergency Alert</a>
    <a href="profile.php">👤 Profile</a> 
    <a href="live_chart.php">💬 Live Chat</a>
    <a href="logout.php">🔓 Logout</a>
  </div>
  <div class="main-content">
    <div class="container-fluid">
      <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($user_name); ?></h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-white bg-warning mb-3">
            <div class="card-body">
              <h5 class="card-title">Pending Complaints</h5>
              <h2><?php echo $pending_complaints; ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-success mb-3">
            <div class="card-body">
              <h5 class="card-title">Resolved Complaints</h5>
              <h2><?php echo $resolved_complaints; ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Total Complaints</h5>
              <h2><?php echo $total_complaints; ?></h2>
            </div>
          </div>
        </div>
      </div>
      <h3>Recent Complaints</h3>
      <table class="table table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Status</th>
            <th>Submitted On</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($recent_complaints) > 0): ?>
            <?php foreach ($recent_complaints as $rc): ?>
              <tr>
                <td><?php echo htmlspecialchars($rc['id']); ?></td>
                <td><?php echo htmlspecialchars($rc['category']); ?></td>
                <td>
                  <?php if ($rc['status'] == 'Pending'): ?>
                    <span class="badge bg-warning">Pending</span>
                  <?php else: ?>
                    <span class="badge bg-success">Resolved</span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('F j,Y', strtotime($rc['created_at'])); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4"class="text-center">No complaints found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <h3 class="mt-5">Submit a New Complaint</h3>
      <form method="POST">
        <div class="mb-3">
          <label for="category" class="form-label"><b>Category</b></label>
          <select class="form-control" id="category" name="category" onchange="updateComplaintType()" required>
            <option value="">Select Category</option>
            <option value="hall">Bonolota Hall</option>
            <option value="academic">Academic</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="complainType" class="form-label"><b>Complaint Type</b></label>
          <select class="form-control" id="complainType" name="complainType" required>
            <option value="">Select Complaint Type</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="complaintText" class="form-label"><b>Complaint Details</b></label>
          <textarea class="form-control" id="complaintText" name="complaintText" rows="4" placeholder="Write your complaint here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit Complaint</button>
      </form>
    </div>
  </div>
  <footer>
    <p class="mb-0">&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
  <script>
    function updateComplaintType() {
      const category = document.getElementById('category').value;
      const complainTypeSelect = document.getElementById('complainType');
      complainTypeSelect.innerHTML = '<option value="">Select Complaint Type</option>';
      if (category === 'hall') {
        const hallComplaints = ['Hall Maintenance','Food Quality','Room Conditions'];
        hallComplaints.forEach(type => {
          const option=document.createElement('option');
          option.value=type;
          option.textContent = type;
          complainTypeSelect.appendChild(option);
        });
      } else if (category === 'academic') {
        const academicComplaints = ['Exam Issues','Course Materials','Lecturer Behavior','Campus Safety','Facilities','IT Support','Location information', 'Administrative Issues'];
        academicComplaints.forEach(type => {
          const option = document.createElement('option');
          option.value = type;
          option.textContent = type;
          complainTypeSelect.appendChild(option);
        });
      }
    }
  </script>
</body>
</html>
<?php
$conn->close();
?>
