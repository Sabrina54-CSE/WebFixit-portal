<?php
include('db_connect.php');
$likes = 0;
$dislikes = 0;
$res = $conn->query("SELECT vote_type, COUNT(*) as count FROM votes GROUP BY vote_type");
while ($row = $res->fetch_assoc()) {
    if ($row['vote_type'] === 'like') $likes = $row['count'];
    if ($row['vote_type'] === 'dislike') $dislikes = $row['count'];
}
echo json_encode(['likes' => $likes, 'dislikes' => $dislikes]);
?>