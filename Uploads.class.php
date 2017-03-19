<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/19
 * Time: 13:23
 */
class Uploads
{
    //上传文件保存的路径
    private $path = './Uploads';
    //指定上传文件被允许的大小
    private $size = 1000000;
    //设置上传文件被允许的类型
    private $allowtype = array('jpeg', 'jpg', 'gif', 'png');
    //设施上传文件是否使用随机名
    private $israndname = true;

    private $errorNum;
    private $errorMesg;
    private $originName;
    private $tmpFileName;
    private $newFileName;
    private $fileSize;
    private $fileType;

    /**
     * 设置成员属性
     * @param string $key   成员属性的名称
     * @param mixed  $value 属性对应的值
     * return object        返回自己$this
     */
    public function set($key, $value)
    {
        if (array_key_exists($key, get_class_vars(get_class($this)))) {
            $this->setOptions($key, $value);
        }
        return $this;
    }

    /**
     * 处理文件上传
     * @param string $field 上传文件的表单名称
     * return bool          如果上传成功则返回true
     */
    public function upload($field)
    {
        if (!$this->checkFilePath()) {
            $this->errorMesg = $this->getError();
            return false;
        }

        $name = $_FILES[$field]['name'];
        $tmp_name = $_FILES[$field]['tmp_name'];
        $error = $_FILES[$field]['error'];
        $size = $_FILES[$field]['size'];

        $return = true;
        if (is_array($name)) {
            $errors = array();
            //上传多个文件处理方法
            for ($i = 0; $i < count($name); $i++) {
                if ($this->setFile($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
                    if (!$this->checkFileSize() || !$this->checkFileType()) {
                        $errors[] = $this->getError();
                        $return  = false;
                    }
                } else {
                    $errors[] = $this->getError();
                    $return = false;
                }
            }
            if ($return) {
                $fileNames = array();
                for ($i = 0; $i < count($name); $i++) {
                    if ($this->setFile($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
                        $this->setNewFileName();
                        if (!$this->copyFile()) {
                            $errors[] = $this->getError();
                            $return = false;
                        }
                        $fileNames[] = $this->newFileName;
                    }
                }
                $this->newFileName = $fileNames;
            }
            $this->errorMesg = $errors;
            return $return;
        } else {
            //上传单个文件处理方法
            if ($this->setFile($name, $tmp_name, $size, $error)) {
                if ($this->checkFileSize() && $this->checkFileType()) {
                    $this->setNewFileName();
                    if ($this->copyFile()) {
                        return true;
                    } else {
                        $return = false;
                    }
                } else {
                    $return = false;
                }
            } else {
                $return = false;
            }
            if (!$return)
                $this->errorMesg = $this->getError();
            return $return;
        }
    }

    public function getFileName()
    {
        return $this->newFileName;
    }

    public function getErrorMsag()
    {
        return $this->errorMesg;
    }

    private function copyFile()
    {
        if (!$this->errorNum) {
            $path  = rtrim($this->path, '/').'/';
            $path .= $this->newFileName;
            if (!@move_uploaded_file($this->tmpFileName, $path)) {
                $this->setOptions('errorNum', -3);
                return false;
            }
            return true;
        }
        return false;
    }

    private function setNewFileName()
    {
        if ($this->israndname) {
            $this->setOptions('newFileName', $this->proRandName());
        } else {
            $this->setOptions('newFileName', $this->originName);
        }
    }

    private function proRandName()
    {
        return date('YmdHis').'_'.mt_rand(100, 999).'.'.$this->fileType;
    }

    private function checkFileType()
    {
        if (!in_array(strtolower($this->fileType), $this->allowtype)) {
            $this->setOptions('errorNum', -1);
            return false;
        }
        return true;
    }

    private function checkFileSize()
    {
        if ($this->fileSize > $this->size) {
            $this->setOptions('errorNum',-2);
            return false;
        }
        return true;
    }

    private function setFile($name='', $tmp_name='', $size=0, $error=0)
    {
        $this->setOptions('errorNum', $error);
        if ($error)
            return false;
        $this->setOptions('originName', $name);
        $this->setOptions('tmpFileName', $tmp_name);
        $this->setOptions('fileType', $this->getFileType($name));
        $this->setOptions('fileSize', $size);
        return true;
    }

    public function getFileType($name)
    {
        return array_pop(explode('.', $name));
    }

    private function checkFilePath()
    {
        if (empty($this->path)) {
            $this->setOptions('errorNum', -5);
            return false;
        }
        if (!file_exists($this->path) || !is_writable($this->path)) {
            if (!@mkdir($this->path, 0755)) {
                $this->setOptions('errorNum', -4);
                return false;
            }
        }
        return true;
    }

    private function getError()
    {
        $str = '<font color="red">上传文件'.$this->originName.'时出错：</font>';
        switch ($this->errorNum) {
            case 4: $str .= '没有文件被上传';break;
            case 3: $str .= '文件只有部分被上传';break;
            case 2: $str .= '上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值';break;
            case 1: $str .= '上传文件的大小超过了php.ini中upload_max_filesize选项限定的值';break;
            case -1: $str .= '未允许的类型';break;
            case -2: $str .= '上传文件大小不能对象中的属性限定值';break;
            case -3: $str .= '上传失败';break;
            case -4: $str .= '建立存放上传文件目录失败，请重新指定上传目录';break;
            case -5: $str .= '必须指定上传文件的路径';break;
            default: $str .= '未知错误';break;
        }
        return $str;
    }

    private function setOptions($key, $value)
    {
        $this->$key = $value;
    }
}


