<?php
// 启动会话
session_start();

// 检查会话中是否存在 'id'
//if (isset($_SESSION['username'])) {
    // 如果存在 'id'，跳转到指定页面
 //   header("Location: page/chatlist.php"); // 替换为你的目标页面
 //   exit; // 确保脚本终止执行
//}

// 如果不存在 'id'，保持当前页面不变
// 这里可以继续执行其他代码
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聊天网站</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="desktop.css" media="(min-width: 769px)">
    <link rel="stylesheet" href="mobile.css" media="(max-width: 768px)">
	<style>


/* 登录按钮容器 */
.login-button-container {
    text-align: center;
}

/* 登录按钮样式 */
.login-button {
    display: inline-block;
    padding: 15px 30px;
    background-color: #00bcd4; /* 天蓝色按钮 */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 18px;
    transition: background-color 0.3s;
}

.login-button:hover {
    background-color: #0097a7; /* 深天蓝色悬停效果 */
}
</style>
</head>
<body>
    <div class="container">
        <!-- 电脑端导航栏 -->
        <nav class="desktop-nav">
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="/page/chatlist.php">聊天室</a></li>
                <li><a href="/login/login.php">设置</a></li>
                <li><a href="/page/help.html">帮助</a></li>
            </ul>
        </nav>

        <!-- 主要内容区域 -->
        <main class="content">
            <h1>欢迎来到聊天网站</h1>
            <p>这是一个操作简单易上手的聊天网站</p>
        </main>
        <!-- 主要内容 -->
        <main class="main-content">
        <div class="container">
            <!-- 登录按钮 -->
    <?php if (!isset($_SESSION['username'])): ?>
        <div class="login-button-container">
            <a href="./login/login.php" class="login-button">登录</a>
        </div>
    <?php endif; ?>
        </div>
            <section class="tutorial">
                <h2>网站使用教程</h2>
                <div class="tutorial-step">
                    <h3>1. 注册账号</h3>
                    <p>点击右上角的“注册”按钮，填写相关信息完成注册。</p>
                </div>
                <div class="tutorial-step">
                    <h3>2. 登录账号</h3>
                    <p>使用注册的用户名和密码登录，进入聊天室。</p>
                </div>
                <div class="tutorial-step">
                    <h3>3. 开始聊天</h3>
                    <p>选择聊天室，与其他用户实时聊天。</p>
                </div>
                <div class="tutorial-step">
                    <h3>4. 管理设置</h3>
                    <p>在个人中心修改个人信息或更改密码。</p>
                </div>
            </section>
        </main>

        <!-- 手机端导航栏 -->
        <nav class="mobile-nav">
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="/page/chatlist.php">聊天室</a></li>
                <li><a href="/login/login.php">设置</a></li>
                <li><a href="/page/help.html">帮助</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>