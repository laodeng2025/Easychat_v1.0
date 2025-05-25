<?php
/**
 * 图标后缀映射配置
 * 数字代码 => 文件后缀
 */
return [
    0 => 'png',   // 默认后缀
    1 => 'jpg',
    2 => 'jpeg',
    3 => 'gif',
    4 => 'webp',
    5 => 'svg',
    6 => 'bmp',
    7 => 'ico'
];

// 注意：此文件应被其他PHP文件通过 require/include 引入
// 使用示例：
// $config = require 'config.php';
// $suffix = $config[2]; // 获取数字2对应的后缀 'jpeg'
?>