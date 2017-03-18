<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>简单计算器（面向对象）</title>
</head>
<body>
<center>
    <h1>图形（周长&面积）计算器</h1>
    <hr>
    <a href="index.php?action=rect">矩形</a> ||
    <a href="index.php?action=triangle">三角形</a> ||
    <a href="index.php?action=circle">圆形</a>
    <br><br>
    <?php
        error_reporting(E_ALL & ~E_NOTICE);
        function __autoload($className)
        {
            require(strtolower($className).'.class.php');
        }

        echo new Form('index.php');

        //如果提交了数据 输出结果
        if (isset($_POST['calc'])) {
            echo new Result();
        }
    ?>
</center>
</body>
</html>