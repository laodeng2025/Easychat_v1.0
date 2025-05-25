<?php
session_start();
if (!isset($_SESSION['username'])) {
    die(json_encode(['success' => false, 'message' => '未登录']));
}

$chatroom_id = $_POST['id'];
$new_member = trim($_POST['member']);

// 验证输入
if (empty($new_member) || strpos($new_member, ',') !== false) {
    die(json_encode(['success' => false, 'message' => '用户名不合法']));
}

// 连接数据库
$conn = new mysqli('localhost', 'root', '123456', 'localhost');
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => '数据库连接失败: ' . $conn->connect_error]));
}

// 检查当前用户是否是 OP
$username = $_SESSION['username'];
$sql = "SELECT op, users FROM chatroom WHERE id = $chatroom_id AND FIND_IN_SET('$username', op)";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die(json_encode(['success' => false, 'message' => '无权操作']));
}

// 检查成员是否已存在
$row = $result->fetch_assoc();
$users = explode(',', $row['users']);
if (in_array($new_member, $users)) {
    die(json_encode(['success' => false, 'message' => '成员已存在']));
}

// 更新 users 字段
$sql = "UPDATE chatroom SET users = IF(users = '', '$new_member', CONCAT(users, ',$new_member')) WHERE id = $chatroom_id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '添加失败: ' . $conn->error]);
}

$conn->close();
?>