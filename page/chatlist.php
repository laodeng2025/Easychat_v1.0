<?php
// 启动会话
session_start();

// 检查用户是否已登录
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php"); // 未登录则跳转到登录页面
    exit;
}

// 连接数据库
require_once '../config/db.php';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取当前用户名
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// 查询包含当前用户的聊天室（同时检测 users 和 op 字段）
$sql = "SELECT id, chatname FROM chatroom WHERE FIND_IN_SET('$username', users) OR FIND_IN_SET('$username', op)";
$result = $conn->query($sql);

$chatrooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chatrooms[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聊天列表</title>
    <link rel="stylesheet" href="chatlist.css">
</head>

<body>
    <div class="container">
        <!-- 页头 -->
        <header class="header">
            <h1>聊天列表</h1>
            <p>欢迎回来, <?php echo htmlspecialchars($username); ?>！</p>
			<a href="../login/logout.php">退出登录<a>
            <!-- 创建聊天按钮 -->
            <a href="../chatroom/create_chatroom.php" class="create-chat-button">创建聊天</a>
        </header>

        <!-- 聊天列表 -->
        <main class="chatlist-content">
            <?php if (empty($chatrooms)): ?>
                <p>您尚未加入任何聊天室。</p>
            <?php else: ?>
                <ul class="chatroom-list">
                    <?php foreach ($chatrooms as $chatroom): ?>
                        <li class="chatroom-item">
                            <h2><?php echo htmlspecialchars($chatroom['chatname']); ?></h2>
                            <a href="../chatroom/chat.php?id=<?php echo $chatroom['id']; ?>" class="button">进入聊天室</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </main>

        <!-- 页脚 -->
        <footer class="footer">
            <p>&copy; 2023 聊天网站. 保留所有权利.</p>
        </footer>
    </div>
</body>

</html>