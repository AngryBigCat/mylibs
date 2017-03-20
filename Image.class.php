<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/20
 * Time: 9:03
 */
class Image
{
    //指定的开始文件夹
    private $path;

    //传入制定的图片路径名
    public function __construct($path = './')
    {
        $this->path = rtrim($path, '/').'/';
    }

    /**
     * 为图像等比例缩放
     * @param   String  $imgName 需要处理的图片名称
     * @param   int     $nWidth  需要缩放的宽度
     * @param   int     $nHeight 需要缩放的高度
     * @param   String  $qz      文件名生成时的前缀
     * @return  mixed            缩放后成如果成功返回文件名否则为false
     */
    public function thumb($imgName, $nWidth, $nHeight, $qz = 'th_')
    {
        $oInfo = $this->getInfo($imgName);
        $oImg  = $this->getImg($imgName, $oInfo);
        $nInfo    = $this->getNewSize($nWidth, $nHeight, $oInfo);
        $newImg  = $this->getNewImg($oImg, $nInfo,$oInfo);
        return $this->createNewImage($newImg, $oInfo, $qz, $imgName);
    }

    /**
     * 为图片添加指定水印
     * @param   String  $groundName 背景图片文件名
     * @param   String  $waterName  水印图片文件名
     * @param   int     $waterPos   水印的位置1-9
     * @param   String  $qz         文件名生成时的前缀
     * @return  mixed               图片水印后如果成功返回文件名否则为false
     */
    public function watermark($groundName, $waterName, $waterPos = 0, $qz = 'wa_')
    {
        if (file_exists($this->path.$groundName) && file_exists($this->path.$waterName)) {
            $groundInfo = $this->getInfo($groundName);
            $waterInfo  = $this->getInfo($waterName);
            if (!$pos = $this->position($groundInfo, $waterInfo, $waterPos)) {
                echo '水印图片不能大于背景图片';
                return false;
            }
            $groundImg = $this->getImg($groundName, $groundInfo);
            $waterImg  = $this->getImg($waterName, $waterInfo);
            $groundImg = $this->copyImg($groundImg, $waterImg, $pos, $waterInfo);
            return $this->createNewImage($groundImg, $groundInfo, $qz, $groundName);
        } else {
            echo '图片或水印文件不存在';
            return false;
        }
    }

    /**
     * 图片剪切
     * @param   String  $name   需要被裁剪的图片文件名
     * @param   int     $x      裁剪图片左边的开始位置
     * @param   int     $y      裁剪图片上边开始位置
     * @param   int     $width  图片裁剪的宽度
     * @param   int     $height 图片裁剪的高度
     * @param   String  $qz     文件名生成时的前缀
     * @return  mixed           图片裁剪后如果成功返回文件名否则为false
     */
    public function cut($name, $x, $y, $width, $height, $qz = 'cu_')
    {
        $imgInfo = $this->getInfo($name);
        if ($x + $width > $imgInfo['width'] || $y + $height > $imgInfo['height']) {
            echo '剪切的位置超过了背景图片的范围';
            return false;
        }
        $back   = $this->getImg($name, $imgInfo);
        $cutImg = imagecreatetruecolor($width, $height);
        imagecopyresampled($cutImg, $back, 0, 0, $x, $y, $width, $height, $width, $height);
        imagedestroy($back);
        return $this->createNewImage($cutImg, $imgInfo, $qz, $name);
    }

    private function copyImg($groundImg, $waterImg, $pos, $waterInfo)
    {
        imagecopy($groundImg, $waterImg, $pos['posX'], $pos['posY'], 0, 0, $waterInfo['width'], $waterInfo['height']);
        imagedestroy($waterImg);
        return $groundImg;
    }

    private function position($groundInfo, $waterInfo, $waterPos)
    {
        if ($groundInfo['width'] < $waterInfo['width'] || $groundInfo['height'] < $waterInfo['height'] ) {
            return false;
        }
        switch ($waterPos) {
            case 1:
                $posX = 0;
                $posY = 0;
                break;
            case 2:
                $posX = ($groundInfo['width'] - $waterInfo['width']) / 2;
                $posY = 0;
                break;
            case 3:
                $posX = $groundInfo['width'] - $waterInfo['width'];
                $posY = 0;
                break;
            case 4:
                $posX = 0;
                $posY = ($groundInfo['height'] - $waterInfo['height']) / 2;
                break;
            case 5:
                $posX = ($groundInfo['width'] - $waterInfo['width']) / 2;
                $posY = ($groundInfo['height'] - $waterInfo['height']) / 2;
                break;
            case 6:
                $posX = $groundInfo['width'] - $waterInfo['width'];
                $posY = ($groundInfo['height'] - $waterInfo['height']) / 2;
                break;
            case 7:
                $posX = 0;
                $posY = $groundInfo['height'] - $waterInfo['height'];
                break;
            case 8:
                $posX = ($groundInfo['width'] - $waterInfo['width']) / 2;
                $posY = $groundInfo['height'] - $waterInfo['height'];
                break;
            case 9:
                $posX = $groundInfo['width'] - $waterInfo['width'];
                $posY = $groundInfo['height'] - $waterInfo['height'];
                break;
            case 0:
            default:
                $posX = mt_rand(0, $groundInfo['width'] - $waterInfo['width']);
                $posY = mt_rand(0, $groundInfo['height'] - $waterInfo['height']);
        }
        return array('posX' => $posX, 'posY' => $posY);
    }

    private function createNewImage($nImg, $oInfo, $qz, $imgName)
    {
        if (empty(ltrim($this->path,'./'))) {
            $pathinfo = pathinfo($imgName);
            $newName = $pathinfo['dirname'].'/'.$qz.$pathinfo['filename'].'.'.$oInfo['type'];
        } else {
            $newName = $qz.$imgName;
        }
        switch ($oInfo['type']) {
            case 'jpg':
                $result = imagejpeg($nImg, $this->path.$newName);
                break;
            case 'gif':
                $result = imagegif($nImg, $this->path.$newName);
                break;
            case 'png':
                $result = imagepng($nImg, $this->path.$newName);
                break;
        }
        imagedestroy($nImg);
        return $newName;
    }

    private function getNewImg($oImg, $nInfo,$oInfo)
    {
        $nImg = imagecreatetruecolor($nInfo['width'], $nInfo['height']);
        imagecopyresampled($nImg, $oImg, 0, 0, 0, 0, $nInfo['width'], $nInfo['height'], $oInfo['width'], $oInfo['height']);
        imagedestroy($oImg);
        return $nImg;
    }

    private function getNewSize($nWidth, $nHeight, $imgInfo)
    {
        $oWidth = $imgInfo['width'];
        $oHeight = $imgInfo['height'];
        if ($oWidth * $nWidth > $oHeight * $nHeight) {
            $nHeight = round($oHeight * $nWidth / $oWidth);
        } else {
            $nWidth = round($oWidth * $nHeight / $oHeight);
        }
        return array('width' => $nWidth, 'height' => $nHeight);
    }

    private function getImg($imgName, $imgInfo)
    {
        switch ($imgInfo['type']) {
            case 'gif':
                return imagecreatefromgif($this->path.$imgName);
            case 'jpg':
                return imagecreatefromjpeg($this->path.$imgName);
            case 'png':
                return imagecreatefrompng($this->path.$imgName);
        }
        return false;
    }

    private function getInfo($imgName)
    {
        $info = array();
        $info['type'] = @array_pop(explode('.', $imgName));
        list($info['width'], $info['height']) = getimagesize($this->path.$imgName);
        return $info;
    }
}