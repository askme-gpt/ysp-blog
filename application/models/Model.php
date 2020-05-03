<?php

use Medoo\Medoo;
use Yaf\Bootstrap_Abstract as Bootstrap;

class Model extends Bootstrap
{
    public $pdo;

    public function __construct($options = null)
    {
        global $_db; //静态db
        $dbConfig           = \Yaf\Registry::get("config")->database->toarray();
        $dbConfig['option'] = array(PDO::ATTR_CASE => PDO::CASE_NATURAL);
        $options            = $dbConfig;
        //以上是连接数据库的，这就是为什么要继承Bootstrap_Abstract的原因
        //然后把$this->pdo = new PDO($dsn, $this->username, $this->password, $this->option);改成
        if (!$_db) {
            $_db = $this->pdo = new PDO($dsn, $this->username, $this->password, $this->option);
        } else {
            $this->pdo = $_db;
        }

    }
}
