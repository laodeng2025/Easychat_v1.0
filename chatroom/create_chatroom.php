<?php
// 启动会话
session_start();

// 检查用户是否已登录
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // 未登录则跳转到登录页面
    exit;
}

// 连接数据库
require_once '../config/db.php';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 处理表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chatname = $_POST['chatname'];
    $op = $_SESSION['username']; // 管理员为当前登录用户
    $users = $_POST['users'];

    // 生成一个唯一的八位聊天室 ID
    $chat_id = generateUniqueChatId($conn);

    // 插入聊天室信息到数据库
    $sql = "INSERT INTO chatroom (chat_id, chatname, op, users) VALUES ('$chat_id', '$chatname', '$op', '$users')";

    if ($conn->query($sql) === TRUE) {
        echo '<p class="message success">聊天室创建成功！聊天室 ID：' . $chat_id . '</p>';
    } else {
        echo '<p class="message error">聊天室创建失败: ' . $conn->error . '</p>';
    }
}

$conn->close();

/**
 * 生成一个唯一的八位聊天室 ID
 * @param mysqli $conn 数据库连接对象
 * @return string 八位聊天室 ID
 */
function generateUniqueChatId($conn) {
    do {
        // 生成一个八位随机数
        $chat_id = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        // 检查聊天室 ID 是否已存在
        $checkSql = "SELECT * FROM chatroom WHERE chat_id = '$chat_id'";
        $checkResult = $conn->query($checkSql);
    } while ($checkResult->num_rows > 0); // 如果 ID 已存在，重新生成

    return $chat_id;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创建聊天室</title>
    <style>
        /* 通用样式 */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7fa; /* 天蓝色背景 */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            color: #00bcd4; /* 天蓝色标题 */
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #00bcd4; /* 天蓝色按钮 */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px; /* 按钮之间的间距 */
        }

        button:hover {
            background-color: #0097a7; /* 深天蓝色悬停效果 */
        }

        .back-button {
            background-color: #00bcd4; /* 红色按钮 */
        }

        .back-button:hover {
            background-color: #0097a7; /* 深红色悬停效果 */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>创建聊天室</h1>
        <form method="post">
            <label for="chatname">聊天室名称：</label>
            <input type="text" id="chatname" name="chatname" required>

            <label for="users">成员用户名（以逗号分隔）：</label>
            <textarea id="users" name="users" rows="3" required></textarea>

            <button type="submit">创建</button>
        </form>

        <!-- 返回按钮 -->
        <button class="back-button" onclick="window.location.href='../page/chatlist.php'">返回</button>
    </div>
</body>

</html>