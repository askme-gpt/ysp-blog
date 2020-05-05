<?php

use Model\UserModel;

/**
 * @name IndexController
 * @author admin
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class UserController extends \Yaf\Controller_Abstract
{

    public function indexAction()
    {
        $data = UserModel::index();
        echo json_encode($data, 320);
    }
}
