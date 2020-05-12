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
            'LIMIT' => [$offset, $limit],
        ]);
        return $list;
    }
    /**
     * 根据ids获取tag名称
     * @param  string $ids [description]
     * @return [type]      [description]
     */
    public function findTagsById($ids)
    {
        if (empty($ids)) {
            return [];
        }
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $data = $this->db->select($this->table, ['id', 'name'], ['id' => $ids]);
        return $data;
    }
}
