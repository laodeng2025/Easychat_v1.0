<?php
// 启动会话
session_start();

// 检查用户是否已登录
if (!isset($_SESSION['username'])) {
    die("未登录。");
}

// 获取聊天室 ID 和日期
if (isset($_GET['id']) && isset($_GET['date'])) {
    $chatroom_id = $_GET['id'];
    $date = $_GET['date'];
} else {
    die("参数错误。");
}

// 消息存储路径
$message_file = __DIR__ . "/chatroom/$chatroom_id/message/$date.html";

// 如果文件存在，加载消息
if (file_exists($message_file)) {
    $messages = file_get_contents($message_file);
    echo $messages;
}
?>