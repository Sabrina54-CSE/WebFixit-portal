<?php
include('db_connect.php');
$result = $conn->query("SELECT * FROM locations");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Location Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      transition: transform 0.3s ease-in-out;
      height: 200px;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-header {
      background-color: #10318c;
      color: white;
      font-weight: bold;
    }
    .card-body {
      padding: 10px;
    }
    .card-title {
      font-size: 16px;
      margin-bottom: 10px;
    }
    .time-info {
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center mb-4">Location Tracker</h2>
    <div class="row">
      <?php while($row = $result->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header"><?php echo htmlspecialchars($row['department']); ?></div>
          <div class="card-body">
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p><strong>Direction:</strong> <?php echo htmlspecialchars($row['direction']); ?></p>
            <p class="time-info"><strong>Time:</strong> <?php echo htmlspecialchars($row['time']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
