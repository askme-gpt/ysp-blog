<?php

class MenuModel extends BaseModel
{
    public $table = 'menus';

    /**
     * 规则验证
     * https://github.com/inhere/php-validate/wiki/config-rules-like-laravel
     * @return [type] [description]
     */
    public function rules(): array
    {
        return [
            ['name,path,status,index,paret_id', 'required'],
            ['status', 'number', 'in', [10, 20, 30]],
            ['name', 'string', 'min' => 2, 'max' => 16],
            ['paret_id', 'number', 'min' => 0, 'max' => 16777215],
            ['index', 'number', 'min' => 0, 'max' => 16777215],
        ];
    }

    public function index()
    {
        $list = $this->db->select($this->table, [
            $this->table . '.name',
            $this->table . '.path',
        ], [
            $this->table . '.status' => 10,
            'ORDER'                  => [
                $this->table . '.index' => 'DESC',
                $this->table . '.id'    => 'ASC',
            ],
        ]);
        return $list;
    }

}
