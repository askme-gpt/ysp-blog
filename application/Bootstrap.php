<?php

/**
 * @name Bootstrap
 * @author admin
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf\Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{

    public function _initConfig()
    {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $arrConfig);
    }

    public function _initLoad()
    {
        Yaf\Loader::import(APPLICATION_PATH . '/vendor/autoload.php');
    }

    public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        //注册一个插件
        // $objSamplePlugin = new SamplePlugin();
        // $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        $router    = $dispatcher->getRouter();
        $my_router = new RouterAdapter();
        // 要增加的路由协议的名字,要增加的路由协议, Yaf_Route_Interface的一个实例
        $router->addRoute('my_route', $my_router);
    }

    public function _initView(Yaf\Dispatcher $dispatcher)
    {
        $blade = new BladeAdapter();
        $dispatcher::getInstance()->setView($blade);
    }

    public function _initDatabase()
    {
        $arrConfig = Yaf\Registry::get('config');
        $option    = [
            'database_type' => $arrConfig->database->database_type,
            'database_name' => $arrConfig->database->database_name,
            'server'        => $arrConfig->database->server,
            'username'      => $arrConfig->database->username,
            'password'      => $arrConfig->database->password,
            'prefix'        => $arrConfig->database->prefix ?? '',
            'charset'       => $arrConfig->database->charset ?? 'utf8',
            'logging'       => $arrConfig->database->logging,
            'option'        => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
            ],
        ];
        Yaf\Registry::set('db', new \Medoo\Medoo($option));
    }
}
