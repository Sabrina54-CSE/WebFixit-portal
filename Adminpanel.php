<?php
session_start();
if(!isset($_SESSION['is_admin'])||$_SESSION['is_admin']!==true) {
    header("Location:login.php");
    exit;
}
include('db_connect.php');
if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['update_status'])) {
    $complaint_id=$_POST['complaint_id'];
    $status=$_POST['status'];
    $feedback=$_POST['feedback'];
    $update_sql="UPDATE complaints SET status=?,feedback=?WHERE id=?";
    $stmt=$conn->prepare($update_sql);
    $stmt->bind_param("ssi",$status,$feedback,$complaint_id);
    $stmt->execute();
    $stmt->close();
}
$sql="SELECT*FROM complaints ORDER BY created_at DESC";
$result=$conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Fixit Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { 
            background:#f4f6fa; 
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }
        main { 
            margin-top: 40px; 
            flex: 1 0 auto;
        }
        .table thead{background:#0a2c73;color:#fff; }
        .status-select{min-width:120px;}
        textarea{resize:vertical; }
        footer {
            background:#222;
            color:#fff;
            text-align: center;
            padding: 18px 0;
            width: 100%;
            margin-top: 40px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Fixit Portal Admin</a>
      </div>
    </nav>
    <main class="container mt-5">
        <h2 class="mb-4">All Complaints</h2>
        <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Student Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th>Submitted On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <form method="POST" class="d-flex align-items-center">
                            <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-select status-select me-2">
                                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="In Progress" <?php if($row['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                                <option value="Resolved" <?php if($row['status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                            </select>
                    </td>
                    <td>
                            <textarea name="feedback" class="form-control" rows="2"><?php echo htmlspecialchars($row['feedback']); ?></textarea>
                    </td>
                    <td><?php echo isset($row['created_at'])?date('F j,Y', strtotime($row['created_at'])):'';?></td>
                    <td>
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center">No complaints found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </main>
    <footer>
        &copy; 2025 Fixit Portal. All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
