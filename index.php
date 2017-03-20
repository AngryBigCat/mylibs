<?php
/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 22:05
 */

function __autoload($className)
{
    if (file_exists($className.'.class.php')) {
        require $className.'.class.php';
    } else {
        echo $className.'.class.php文件不存在';
    }
}

$img = new Image('images');
$cut = $img->cut('xampp-cloud@2x.png', 10, 10, 500, 500);
$water = $img->watermark($cut, 'windows-logo.png', 9);
echo $img->thumb($water, 100, 100);


?>

<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="" enctype="multipart/form-data" method="post">
    <input type="file" name="userfile">
    <input type="submit" value="提交">
</form>
</body>
</html>
