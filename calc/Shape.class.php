<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 15:30
 */
abstract class Shape
{
    public $shapeName;
    abstract protected function area();
    abstract protected function perimeter();

    protected function validate($value, $message = '输入的值')
    {
        if ($value == '' || !is_numeric($value) || $value < 0) {
            $message = $this->shapeName.$message;
            echo '<font color="red">'.$message.'必须为非负值，并且不能为空</font><br>';
            return false;
        } else {
            return true;
        }
    }
}