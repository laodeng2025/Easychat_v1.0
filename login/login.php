<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录页面</title>
    <style>
        /* 网页背景 */
        body {
            background-color: #e0f7fa; /* 天蓝色背景 */
            margin: 0; /* 移除默认的边距 */
            padding: 0; /* 移除默认的内边距 */
            display: flex; /* 使用Flexbox布局 */
            justify-content: center; /* 水平居中 */
            align-items: center; /* 垂直居中 */
            height: 100vh; /* 使body高度等于视口高度 */
            font-family: Arial, sans-serif; /* 设置字体 */
        }

        /* 登录容器样式 */
        .login-container {
            background-color: white; /* 白色背景 */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
	    .h2 h2 {
            color: #00bcd4; /* 天蓝色标题 */
            margin-bottom: 20px;
        }

        /* 表单标题样式 */
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* 输入组样式 */
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        /* 标签样式 */
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        /* 输入框样式 */
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        /* 按钮样式 */
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #00bcd4; /* 淡蓝色按钮 */
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
		        /* 链接样式 */
        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #00bcd4; /* 淡蓝色按钮 */
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
		

        .login-container button:hover {
            background-color: #0097a7; /* 深天蓝色悬停效果 */
        }

        /* 第三方登录链接样式 */
        .third-party-login {
            margin-top: 15px;
            font-size: 14px;
        }

        .third-party-login a {
            color: #00bcd4; /* 淡蓝色色链接 */
            text-decoration: none;
        }

        .third-party-login a:hover {
            text-decoration: underline;
        }

        /* 错误消息样式 */
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<?php
// 启动会话
session_start();

// 连接数据库
require_once '../config/db.php';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 处理登录表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查询用户信息
    $sql = "SELECT numbering, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // 登录成功，将用户名和用户 ID 存储到会话中
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id']; // 将用户 ID 存储到会话中

            // 跳转到首页
            header("Location: /index.php");
            exit;
        } else {
            $error_message = "用户名或密码错误！";
        }
    } else {
        $error_message = "用户名或密码错误！";
    }
}
$conn->close();
?>

    <div class="login-container">
        <h2 color="blue">登录</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="input-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">登录</button>
        </form>
        <div class="third-party-login">
            <a href="PH_login.html">使用第三方登录</a>
            <a href="register.php" >注册</a>
        </div>
    </div>
</body>

</html>
