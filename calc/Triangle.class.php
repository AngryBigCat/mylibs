<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 17:07
 */
class Triangle extends Shape
{
    private $_side1;
    private $_side2;
    private $_side3;


    public function __construct()
    {
        $this->shapeName = '三角形';
        if ($this->validate($_POST['side1'], '第一条边') && $this->validate($_POST['side2'], '第二条边') && $this->validate($_POST['side3'], '第三条边')) {
            if ($this->validateSum($_POST['side1'], $_POST['side2'], $_POST['side3'])) {
                $this->_side1 = $_POST['side1'];
                $this->_side2 = $_POST['side2'];
                $this->_side3 = $_POST['side3'];
            } else {
                echo '<font color="red">三角形任意两边长度之和需大于第三边</font><br>';
            }
        }
    }

    public function area()
    {
        $s = ($this->_side1 + $this->_side2 + $this->_side3) / 2;
        $result = sqrt($s * ($s - $this->_side1) * ($s - $this->_side2) * ($s - $this->_side3));
        return $result;
    }

    public function perimeter()
    {
        $result = $this->_side1 + $this->_side2 + $this->_side3;
        return $result;
    }

    private function validateSum($s1, $s2, $s3)
    {
        if (($s1 + $s2) > $s3 && ($s1 + $s3) > $s2 && ($s2 + $s3) > $s1) {
            return true;
        } else {
            return false;
        }
    }


}