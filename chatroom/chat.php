<?php
// 启动会话
session_start();

// 检查用户是否已登录
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // 未登录则跳转到登录页面
    exit;
}

// 获取 URL 中的聊天室 ID
if (isset($_GET['id'])) {
    $chatroom_id = $_GET['id'];
} else {
    die("未指定聊天室 ID。");
}

// 连接数据库
require_once '../config/db.php';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 检查用户是否在聊天室中（users 表或 op 表）
$username = $_SESSION['username'];
$sql = "SELECT id, users, op FROM chatroom WHERE id = $chatroom_id AND (FIND_IN_SET('$username', users) OR FIND_IN_SET('$username', op))";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("您无权访问此聊天室。");
}

// 获取聊天室信息
$chatroom = $result->fetch_assoc();
$users = explode(',', $chatroom['users']);
$op = explode(',', $chatroom['op']);

// 检查当前用户是否是 OP
$is_op = in_array($username, $op);

$conn->close();

// 获取当前日期
$current_date = date('Y-m-d');

// 消息存储路径
$message_dir = __DIR__ . "/chatroom/$chatroom_id/message/";
$message_file = $message_dir . $current_date . '.html';

// 如果消息目录不存在，则创建
if (!is_dir($message_dir)) {
    mkdir($message_dir, 0777, true);
}

// 处理消息提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $timestamp = date('H:i:s');
    $message_data = "<div class='message'><strong>[$timestamp] $username:</strong> $message</div>\n";

    // 将消息追加到文件中
    file_put_contents($message_file, $message_data, FILE_APPEND);
}

// 加载当天的消息
$messages = '';
if (file_exists($message_file)) {
    $messages = file_get_contents($message_file);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聊天室 <?php echo $chatroom_id; ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* 二级导航栏样式 */
        .secondary-nav {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .secondary-nav h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .secondary-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .secondary-nav ul li {
            margin-bottom: 5px;
        }

        .secondary-nav button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 5px;
        }

        .secondary-nav button:hover {
            background-color: #0056b3;
        }

        .secondary-nav button.delete-button {
            background-color: #dc3545;
        }

        .secondary-nav button.delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- 二级导航栏 -->
        <div class="secondary-nav">
            <h3>聊天室成员</h3>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <?php echo htmlspecialchars($user); ?>
                        <?php if ($is_op && $user !== $username): ?>
                            <button class="delete-button" onclick="deleteMember('<?php echo $user; ?>')">删除</button>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if ($is_op): ?>
                <div>
                    <input type="text" id="new-member" placeholder="新成员用户名">
                    <button onclick="addMember()">添加成员</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- 聊天室名称 -->
        <h1>聊天室 <?php echo $chatroom_id; ?></h1>

        <!-- 使用 <a> 标签制作按钮 -->
    <a href="javascript:void(0);" class="refresh-button" onclick="refreshPage()">刷新页面</a>

    <script>
        // 刷新页面的函数
        function refreshPage() {
            location.reload(true);
        }
    </script>
        <!-- 消息显示区域 -->
        <div id="message-container" class="message-container">
            <?php echo $messages; ?>
        </div>

        <!-- 消息输入框 -->
        <form id="message-form" method="post" class="message-form">
            <input type="text" name="message" placeholder="输入消息..." required>
            <button type="submit">发送</button>
        </form>
    </div>

    <script>
        // 自动滚动到底部
        function scrollToBottom() {
            const container = document.getElementById('message-container');
            container.scrollTop = container.scrollHeight;
        }

        // 页面加载后滚动到底部
        scrollToBottom();

        // 处理消息提交
        $('#message-form').on('submit', function (e) {
            e.preventDefault();
            const message = $('input[name="message"]').val();

            $.post('chat.php?id=<?php echo $chatroom_id; ?>', { message: message }, function () {
                // 清空输入框
                $('input[name="message"]').val('');

                // 重新加载消息
                loadMessages();
            });
        });

        // 加载消息
        function loadMessages(date = '<?php echo $current_date; ?>') {
            $.get('load_messages.php', { id: <?php echo $chatroom_id; ?>, date: date }, function (data) {
                $('#message-container').prepend(data);
            });
        }

        // 滚动到顶部时加载前一天的消息
        $('#message-container').on('scroll', function () {
            const container = $(this);
            if (container.scrollTop() === 0) {
                const firstMessage = container.find('.message').first();
                const messageDate = firstMessage.data('date');

                if (messageDate) {
                    const prevDate = new Date(messageDate);
                    prevDate.setDate(prevDate.getDate() - 1);
                    const prevDateStr = prevDate.toISOString().split('T')[0];

                    loadMessages(prevDateStr);
                }
            }
        });

function addMember() {
    const newMember = $('#new-member').val().trim();
    if (!newMember) {
        alert('请输入成员用户名');
        return;
    }

    $.post('add_member.php', { id: <?php echo $chatroom_id; ?>, member: newMember }, function (response) {
        if (response.success) {
            location.reload(); // 刷新页面
        } else {
            alert(response.message || '因技术过垃圾，请刷新！');
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('AJAX 请求失败:', textStatus, errorThrown);
        alert('请求失败，请检查网络连接');
    });
}

function deleteMember(member) {
    if (!confirm(`确定要删除成员 ${member} 吗？`)) {
        return;
    }

    $.post('delete_member.php', { id: <?php echo $chatroom_id; ?>, member: member }, function (response) {
        if (response.success) {
            location.reload(); // 刷新页面
        } else {
            alert(response.message || '因技术过垃圾，请刷新！');
        }
    });
}
    </script>
</body>

</html>