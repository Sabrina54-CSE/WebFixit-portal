<?php
<?php
include('db_connect.php');
$result = $conn->query("SELECT sender, message, created_at FROM chat_messages ORDER BY id ASC");
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
header('Content-Type: application/json');
echo json_encode($messages);
?>