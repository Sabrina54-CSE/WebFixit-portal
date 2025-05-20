<?php
<?php
include('db_connect.php');
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        $stmt = $conn->prepare("INSERT INTO comments (comment) VALUES (?)");
        $stmt->bind_param("s", $comment);
        $stmt->execute();
        $stmt->close();
    }
}
?>