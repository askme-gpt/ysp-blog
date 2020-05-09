<?php

class BaseModel
{
    use \Inhere\Validate\ValidationTrait;
    public $db;

    protected $data = [];

    public function __construct($table = '')
    {
        $this->db = Yaf\Registry::get('db');
    }

    public function load(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function create()
    {
        if ($this->validate()->isFail()) {
            return false;
        }
        $this->db->insert($this->table, $this->getSafeData());
        return $this->db->id();
    }

}
