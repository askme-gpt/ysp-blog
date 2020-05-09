<?php

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
