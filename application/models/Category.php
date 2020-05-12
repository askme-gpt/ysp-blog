<?php

class CategoryModel
{
    private $db;
    private $table = 'categories';

    public function __construct()
    {
        $this->db = Yaf\Registry::get('db');
    }

    public function index($offset = 0, $limit = 15)
    {
        $list = $this->db->select($this->table, [
            $this->table . '.id',
            $this->table . '.name',
        ], [
            $this->table . '.status' => 10,
            'ORDER'                  => [
                $this->table . '.index' => 'DESC',
                $this->table . '.id'    => 'asc',
            ],
            'LIMIT'                  => [$offset, $limit],
        ]);
        return $list;
    }

    public function insertArticle($arrInfo)
    {
        return true;
    }
}
