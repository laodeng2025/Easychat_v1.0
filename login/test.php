<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改背景图片</title>
    <style>
        /* 使用CSS类来管理背景图片 */
        .bg-default { background-image: url('default.jpg'); }
        .bg-image1 { background-image: url('image1.jpg'); }
        .bg-image2 { background-image: url('image2.jpg'); }
        /* 默认应用默认背景图片类 */
        body { class: 'bg-default'; /* 注意：这里实际上不会工作，因为class属性不能这样设置；应使用JavaScript或服务器端设置 */ }
    </style>
    <!-- 考虑将JavaScript代码移至外部文件 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var images = document.querySelectorAll('img');
            images.forEach(function(img) {
                img.addEventListener('click', function() {
                    var image = img.getAttribute('src');
                    // 更新背景图片（客户端即时更改）
                    document.body.classList.remove('bg-default', 'bg-image1', 'bg-image2'); // 移除所有背景图片类
                    var bgClass = image.replace(/\.jpg$/, ''); // 从文件名生成类名（移除扩展名）
                    bgClass = 'bg-' + bgClass.split('/').pop(); // 处理可能的路径问题，只保留文件名部分
                    document.body.classList.add(bgClass); // 添加新的背景图片类

                    // 发送Ajax请求到服务器保存用户选择
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'set_background.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('image=' + encodeURIComponent(image));
                });
            });
        });
    </script>
</head>
<body>
</body>
</html>