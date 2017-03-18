<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 14:34
 */
class Result
{
    private $_shape;

    public function __construct()
    {
        $this->_shape = new $_GET['action'];
    }

    public function __toString()
    {
        $result  = $this->_shape->shapeName.'的面积为：'.round($this->_shape->area(), 2).'<br>';
        $result .= $this->_shape->shapeName.'的周长为：'.round($this->_shape->perimeter(), 2).'<br>';
        return $result;
    }
}