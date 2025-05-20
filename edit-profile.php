<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include('db_connect.php');
$user_id=$_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $phone=$_POST['phone'];
    $hall=$_POST['hall'];
    $department=$_POST['department'];
    $account_type=$_POST['account_type'];
    $student_id=$_POST['student_id'];
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir="uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;
        $stmt = $conn->prepare("UPDATE users SET phone=?,hall=?,department=?,account_type=?,student_id=?,profile_picture=? WHERE id=?");
        $stmt->bind_param("ssssssi",$phone,$hall,$department,$account_type,$student_id,$profile_picture,$user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET phone=?,hall=?,department=?,account_type=?,student_id=? WHERE id=?");
        $stmt->bind_param("sssssi", $phone,$hall,$department,$account_type,$student_id, $user_id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location:profile.php");
    exit;
}
$stmt = $conn->prepare("SELECT phone,hall,department, ccount_type,student_id,profile_picture FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($phone, $hall, $department, $account_type, $student_id, $profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
  <div class="container mt-5">
    <h2>Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
      </div>
      <div class="mb-3">
        <label>Hall</label>
        <input type="text" name="hall" class="form-control" value="<?php echo htmlspecialchars($hall); ?>">
      </div>
      <div class="mb-3">
        <label>Department</label>
        <input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($department); ?>">
      </div>
      <div class="mb-3">
        <label>Account Type</label>
        <select name="account_type" class="form-control">
          <option value="student" <?php if($account_type=='student') echo 'selected'; ?>>Student</option>
          <option value="teacher" <?php if($account_type=='teacher') echo 'selected'; ?>>Teacher</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Student ID</label>
        <input type="text" name="student_id" class="form-control" value="<?php echo htmlspecialchars($student_id); ?>">
      </div>
      <div class="mb-3">
        <label>Profile Picture</label>
        <input type="file" name="profile_picture" class="form-control">
        <?php if ($profile_picture): ?>
          <img src="<?php echo htmlspecialchars($profile_picture); ?>" width="80" class="mt-2" />
        <?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>