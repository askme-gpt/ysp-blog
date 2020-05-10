<?php

use Yaf\Controller_Abstract as Controller;

/**
 * @name IndexController
 * @author admin
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Controller
{

    /**
     * 默认动作
     * Yaf支持直接把Yaf\Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yaf_skeleton/index/index/index/name/admin 的时候, 你就会发现不同
     */
    public function indexAction($name = "Stranger")
    {
        $get  = $this->getRequest()->getQuery();
        $user = new UserModel();
        $list = $user->list();
        // echo json_encode($list, 320);
        // return view('index.index', compact('list', 'name'));
    }

}
