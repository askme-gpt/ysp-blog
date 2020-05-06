<?php
use Inhere\Route\Router;

class RouterAdapter extends Yaf\Request_Abstract implements Yaf\Route_Interface
{
    // 参考：https://widuu.com/show/367.html
    public function route($request)
    {
        $requestUri = $request->getRequestUri();
        $baseuri    = $request->getBaseUri();
        if (
            $requestUri != ''
            && $baseuri != ''
            && stripos($requestUri, $baseuri) !== false
        ) {
            $path = substr($requestUri, strlen($baseuri));
        } else {
            $path = $requestUri;
        }
        $path = trim(urldecode($path), Yaf\Router::URI_DELIMITER);

        if (isset($this->_default['module'])) {
            $request->setModuleName($this->_default['module']);
        }
        if (isset($this->_default['controller'])) {
            $request->setControllerName($this->_default['controller']);
        }
        if (isset($this->_default['action'])) {
            $request->setActionName($this->_default['action']);
        }
        $request->setParam($values);
        return true;
    }

    public function assemble(array $info, $query = null)
    {
        return true;
    }
}
