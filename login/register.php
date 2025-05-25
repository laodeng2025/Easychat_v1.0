<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册页面</title>
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #00bcd4; /* 天蓝色标题 */
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"],
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
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #0097a7; /* 深天蓝色悬停效果 */
        }

        .message {
            margin-bottom: 20px;
            color: #333;
            font-size: 14px;
        }

        .message.error {
            color: red;
        }

        .message.success {
            color: green;
        }

        .links {
            margin-top: 20px;
        }

        .links button {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>注册</h2>
<?php
// 连接数据库
require_once '../config/db.php';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 检查用户名是否已存在
    $checkSql = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo '<p class="message error">该用户名已存在，请选择其他用户名。</p>';
    } else {
        // 生成一个唯一的八位编号
        $numbering = generateUniqueNumbering($conn);

        // 对密码进行哈希处理
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 插入用户信息到数据库
        $sql = "INSERT INTO users (username, password, numbering) VALUES ('$username', '$hashedPassword', '$numbering')";

        if ($conn->query($sql) === TRUE) {
            echo '<p class="message success">注册成功！您的专属编号是：' . $numbering . '</p>';
        } else {
            echo '<p class="message error">注册失败: ' . $conn->error . '</p>';
        }
    }
}
$conn->close();

/**
 * 生成一个唯一的八位编号
 * @param mysqli $conn 数据库连接对象
 * @return string 八位编号
 */
function generateUniqueNumbering($conn) {
    do {
        // 生成一个八位随机数
        $numbering = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        // 检查编号是否已存在
        $checkSql = "SELECT * FROM users WHERE numbering = '$numbering'";
        $checkResult = $conn->query($checkSql);
    } while ($checkResult->num_rows > 0); // 如果编号已存在，重新生成

    return $numbering;
}
?>
        <form method="post">
            <label for="username">用户名：</label>
            <input type="text" name="username" required><br>
            <label for="password">密码：</label>
            <input type="password" name="password" required><br>
            <input type="submit" value="注册">
        </form>
        <div class="links">
            <button onclick="window.location.href='login.php'">去登录</button>
            <button onclick="window.location.href='/'">主页</button>
        </div>
    </div>
</body>

</html>