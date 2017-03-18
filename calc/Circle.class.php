<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 17:08
 */
class Circle extends Shape
{
    private $_radius;

    public function __construct()
    {
        $this->shapeName = '圆形';
        if ($this->validate($_POST['radius'], '半径')) {
            $this->_radius = $_POST['radius'];
        }
    }

    public function area()
    {
        $result = pi() * $this->_radius * $this->_radius;
        return $result;
    }

    public function perimeter()
    {
        $result = pi() * $this->_radius * 2;
        return $result;
    }

}