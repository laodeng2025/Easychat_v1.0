<?php
session_start();
if (!isset($_SESSION['username'])) {
    die(json_encode(['success' => false, 'message' => '未登录']));
}

$chatroom_id = $_POST['id'];
$member = $_POST['member'];

// 连接数据库
require_once '../config/db.php';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// 检查当前用户是否是 OP
$username = $_SESSION['username'];
$sql = "SELECT op FROM chatroom WHERE id = $chatroom_id AND FIND_IN_SET('$username', op)";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die(json_encode(['success' => false, 'message' => '无权操作']));
}

// 更新 users 字段
$sql = "UPDATE chatroom SET users = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', users, ','), ',$member,', ',')) WHERE id = $chatroom_id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '删除失败']);
}

$conn->close();
?>