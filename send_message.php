<?php
<?php
include('db_connect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'];
    $message = $_POST['message'];
    if ($message != '') {
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $sender, $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>