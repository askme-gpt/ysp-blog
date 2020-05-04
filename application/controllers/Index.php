<?php

use \Yaf\Controller_Abstract as Controller;

/**
 * @name IndexController
 * @author admin
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Controller
{
    /**
     * 默认初始化方法，如果不需要，可以删除掉这个方法
     * 如果这个方法被定义，那么在Controller被构造以后，Yaf会调用这个方法
     */
    public function init()
    {
        if (wantJson()) {
            Yaf\Dispatcher::getInstance()->disableView();
        }
    }

    /**
     * 默认动作
     * Yaf支持直接把Yaf\Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yaf_skeleton/index/index/index/name/admin 的时候, 你就会发现不同
     */
    public function indexAction($name = "Stranger")
    {
        //1. fetch query
        $get = $this->getRequest()->getQuery();
        //2. fetch model
        $user = new UserModel();
        $list = $user->list();
        return view('index.index', compact('list', 'name'));
    }
}
