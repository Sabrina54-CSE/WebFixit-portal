<?php
include('db_connect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vote_type = $_POST['vote_type'];
    if ($vote_type === 'like' || $vote_type === 'dislike') {
        $stmt = $conn->prepare("INSERT INTO votes (vote_type) VALUES (?)");
        $stmt->bind_param("s", $vote_type);
        $stmt->execute();
        $stmt->close();
    }
}
?>