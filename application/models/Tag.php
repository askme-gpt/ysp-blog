<?php

class TagModel
{
    private $db;
    private $table = 'tags';

    public function __construct()
    {
        $this->db = Yaf\Registry::get('db');
    }

    public function index($search = '', $offset = 0, $limit = 15)
    {
        $list = $this->db->select($this->table, [
            $this->table . '.id',
            $this->table . '.name',
        ], [
            'ORDER' => [$this->table . '.id' => 'DESC'],
        ]);
        return $list;
    }
}
