<?php
use Inhere\Route\Router;

class RouterAdapter extends Yaf\Request_Abstract implements Yaf\Route_Interface
{
    public function route($request)
    {
        https: //www.laruence.com/manual/yaf.class.router.addRoute.html
        $router = new Router();
        Yaf\Loader::import(APPLICATION_PATH . '/conf/routes.php');

        $path   = $request->getRequestUri();
        $method = $request->getMethod();
        $info   = $router->match($path, $method);
        if ($info[2] == null) {
            return false;
        }
        $route                 = $info[2];
        $handler               = $route->getHandler();
        [$controller, $action] = explode('@', $handler);
        $params                = $route->getParams();
        $request->setModuleName('Index');
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setParam($params);
        return true;
    }

    public function assemble(array $info, $query = null)
    {
        return true;
    }
}
