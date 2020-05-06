<?php

/**
 * @name ArticleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author admin
 */
class ArticleModel
{
    private $db;
    private $table = 'articles';

    public function __construct()
    {
        $this->db = Yaf\Registry::get('db');
    }

    public function index($search = '', $offset = 0, $limit = 15)
    {
        $list = $this->db->select($this->table, [
            "[>]users"      => ["user_id" => "id"],
            "[>]categories" => ["category_id" => "id"],
        ], [
            $this->table . '.id',
            $this->table . '.title',
            $this->table . '.tags',
            $this->table . '.content',
            $this->table . '.visits',
            $this->table . '.created_at',
            'users.name',
            'categories.name',
        ], [
            $this->table . '.status' => 10,
            'ORDER'                  => [$this->table . '.id' => 'DESC'],
            'LIMIT'                  => [$offset, $limit],
        ]);
        $count = $this->db->count($this->table, ['status' => 10]);
        return [
            'list'  => $list,
            'count' => $count,
        ];
    }

    public function allArticle()
    {
        return $this->db->select($this->table, ['id', 'title', 'content', 'updated_at'], ['status' => 10]);
    }

    public function insertArticle($arrInfo)
    {
        return true;
    }

    public function paginate($value = '')
    {

    }
}
