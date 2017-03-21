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

$tpl = new Tpl;

$tpl->assign('var', 'xxxxx');
$tpl->assign('asd', false);
$tpl->assign('arrs', ['num1', 'num2', 'num3', 'num4']);

$tpl->display('index.html');

?>

