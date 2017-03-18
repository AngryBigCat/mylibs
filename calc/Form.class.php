<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 14:34
 */

class Form
{
    //需要输出的页面
    private $_action;
    //当前学选择的图形
    private $_shape;

    /**
     *
     */
    public function __construct($action = '')
    {
        $this->_action = $action;
        $this->_shape = isset($_GET['action']) ? $_GET['action'] : 'rect';
    }

    /**
     *
     */
    public function __toString()
    {
        //动态绑定三个类型的方法
        $action = 'get'.ucfirst($this->_shape);

        //生成form表单
        $form  = '<form action="'.$this->_action.'?action='.$this->_shape.'" method="post">';
        $form .= $this->$action();
        $form .= '<br><input type="submit" name="calc" value="计算">';
        $form .= '</form><br>';
        return $form;
    }


    private function getRect()
    {
        $input = '<b>请输入 | 矩形 | 的高度和宽度</b><br><br>';
        $input .= '宽度：<input type="text" name="width" value="'.$_POST['width'].'"><br><br>';
        $input .= '高度：<input type="text" name="height" value="'.$_POST['height'].'"><br>';
        return $input;
    }

    private function getTriangle()
    {
        $input = '<b>请输入 | 三角形 | 的三条边长度</b><br><br>';
        $input .= '第一条边：<input type="text" name="side1" value="'.$_POST['side1'].'"><br><br>';
        $input .= '第二条边：<input type="text" name="side2" value="'.$_POST['side2'].'"><br><br>';
        $input .= '第三条边：<input type="text" name="side3" value="'.$_POST['side3'].'"><br>';
        return $input;
    }

    private function getCircle()
    {
        $input = '<b>请输入 | 圆形 | 的半径</b><br><br>';
        $input .= '半径：<input type="text" name="radius" value="'.$_POST['radius'].'"><br>';
        return $input;
    }


}