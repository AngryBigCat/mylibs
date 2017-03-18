<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 17:07
 */
class Rect extends Shape
{
    private $_width;
    private $_height;

    public function __construct()
    {
        $this->shapeName = '矩形';
        if ($this->validate($_POST['width'],'宽度') && $this->validate($_POST['height'], '高度')) {
            $this->_width = $_POST['width'];
            $this->_height = $_POST['height'];
        }
    }

    public function area()
    {
        $result = $this->_width * $this->_height;
        return $result;
    }

    public function perimeter()
    {
        $result = 2 * ($this->_width + $this->_height);
        return $result;
    }


}