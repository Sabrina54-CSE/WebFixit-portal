<?php
include('db_connect.php');
$res = $conn->query("SELECT comment FROM comments ORDER BY id ASC");
$comments = [];
while ($row = $res->fetch_assoc()) {
    $comments[] = $row['comment'];
}
echo json_encode($comments);
?>