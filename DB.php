<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 0:49
 */

//单例模式   一个类只能有一个实例对象存在 比如建立目录、数据库连接都有可能会用到这样的技术
class DB
{
    private static $obj = null;

    private $_sql = array(
        'field' => '',
        'where' => '',
        'order' => '',
        'limit' => '',
        'group' => '',
        'having' => ''
    );

    private function __construct()
    {
        echo '数据库连接成功<br>';
    }

    public static function getInstance()
    {
        if (is_null(self::$obj)) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function __call($funcname, $args)
    {
        $funcname = strtolower($funcname);

        if (array_key_exists($funcname, $this->_sql)) {
            $this->_sql[$funcname] = $args[0];
        } else {
            echo '调用类'.get_class($this).'中的方法'.$funcname.'()不存在';
        }

        return $this;
    }

    public function __get($name)
    {
        return $this->_sql[$name];
    }

    public function query($sql)
    {
        return  $sql;
    }

    public function select()
    {
        echo "SELECT FROM {$this->_sql['field']} user {$this->_sql['where']} {$this->_sql['order']} {$this->_sql['limit']} {$this->_sql['group']} {$this->_sql['having']}";
    }
}

$db = DB::getInstance();

//$db -> field('username,email')
//    -> where('WHERE sex in ("男", "女")')
//    -> group('GROUP by sex')
//    -> having('HAVING avg(age) > 25')
//    -> select();


$seriadb = serialize($db);

echo $seriadb.'<br>';

$unseriadb = unserialize($seriadb);

var_dump($unseriadb);


