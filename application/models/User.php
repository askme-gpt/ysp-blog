<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author admin
 */
class UserModel
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = Yaf\Registry::get('db');
    }

    function list() {
        $users = $this->db->select($this->table, ['id', 'name']);
        return $users;
    }
}
