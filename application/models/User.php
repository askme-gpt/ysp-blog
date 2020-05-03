<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author admin
 */
class UserModel extends Model
{
    public static function index()
    {
        $db    = self::$db;
        $users = $db->select('user', ['id', 'name']);
        return $users;
    }
}
