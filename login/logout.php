<?php
// 启动会话
session_start();

// 销毁会话
session_destroy();

// 跳转到登录页面
header("Location: login.php");
exit;
?>