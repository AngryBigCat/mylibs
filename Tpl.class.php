<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/21
 * Time: 0:14
 */
class Tpl
{
    //模板文件目录
    public $template_dir    = 'templates';
    //编译文件目录
    public $compile_dir     = 'templates_c';
    //模板中的左定界符
    public $left_delimiter  = '{';
    //模板中的右定界符
    public $right_delimiter = '}';
    //临时数组变量
    private $tpl_vars       = array();

    /**
     * @param  string $tpl_var   注入的变量名
     * @param  mixed  $value     注入的值
     * @return void
     */
    public function assign($tpl_var, $value = null)
    {
        if ($tpl_var != '') {
            $this->tpl_vars[$tpl_var] = $value;
        }
    }

    /**
     * @param  string $fileName 渲染模板文件
     * @return void
     */
    public function display($fileName)
    {
        //定义模板文件路径并判断是否存在，如果不存在就退出程序
        $tplFile = $this->template_dir.'/'.$fileName;
        if (!file_exists($tplFile)) {
            exit($fileName.'模板文件不存在');
        }
        //定义编译模板文件路径
        $comFile = $this->compile_dir.'/com_'.$fileName.'.php';
        //如果模板文件不存在，或者编译模板文件的修改时间比模板文件的修改时间要旧
        if (!file_exists($comFile) || filemtime($comFile) < filemtime($tplFile)) {
            //执行私有方法，将进行编译模板文件
             $repContent = $this->tpl_replace(file_get_contents($tplFile));
             //完成后，将编译好的模板文件写入编译文件中
            file_put_contents($comFile, $repContent);
        }
        include($comFile);
    }

    /**
     * @param $content
     * @return mixed
     */
    public function tpl_replace($content)
    {
        $left = preg_quote($this->left_delimiter, '/');
        $right = preg_quote($this->right_delimiter, '/');
        $pattern = array(
            //{$var}
            '/'.$left.'\$([a-zA-Z_][a-zA-Z0-9_]*)'.$right.'/i',
            //{if $col == "sex"}dasdasdasasd)(**&^^%$#){/if}
            '/'.$left.'\s*if\s*\$(.+?)\s*'.$right.'(.+?)'.$left.'\s*\/if\s*'.$right.'/is',
            //{elseif $col == "sex"}
            '/'.$left.'\s*elseif\s*\$(.+?)\s*'.$right.'/is',
            //{else}
            '/'.$left.'else'.$right.'/is',
            //{loop $arrs as $value}asdasdasd{/loop}
            '/'.$left.'loop\s\$([a-zA-Z_][\w]*)\sas\s\$([a-zA-Z_][\w]*)'.$right.'(.+?)'.$left.'\/loop'.$right.'/is',
            //{loop $aars as $value => $key}asdasdsad{/loop}
            '/'.$left.'loop\s\$([a-zA-Z_][\w]*)\sas\s\$([a-zA-Z_][\w]*)\s\=\>\s\$([a-zA-Z_][\w]+)'.$right.'(.+?)'.$left.'\/loop'.$right.'/is',
            //{include "header.html"}
            '/'.$left.'\s*include\s*[\"\']?(.+?)[\"\']?'.$right.'/i',
        );

        $replacement = array(
            '<?php echo $this->tpl_vars["$1"];?>',
            '<?php if ($this->tpl_vars["$1"]) {?>$2<?php }?>',
            '<?php } elseif ($this->tpl_vars["$1"]) {?>',
            '<?php } else { ?>',
            '<?php foreach ($this->tpl_vars["$1"] as $this->tpl_vars["$2"]) {?>$3<?php }?>',
            '<?php foreach ($this->tpl_vars["$1"] as $this->tpl_vars["$2"] => $this->tpl_vars["$3"]) {?>$4<?php }?>',
            '<?php include "$1";?>'
        );

        $repContent = preg_replace($pattern, $replacement, $content);

        return $repContent;
    }


}