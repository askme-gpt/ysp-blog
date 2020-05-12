<?php

use Medoo\Medoo;

// use eftec\bladeone\BladeOne;

class BladeAdapter implements Yaf\View_Interface
{

    public $_blade;
    public $_variables;
    /**
     * Constructor
     *
     * @param string $tmplPath
     * @param array $extraParams
     * @return void
     */
    public function __construct()
    {
        $bladeConfig = Yaf\Registry::get("config")->get("blade")->toarray();
        $views       = $bladeConfig['template_dir'] ?? '';
        $cache       = $bladeConfig['cache_dir'] ?? '';
        // 使用缓存
        $this->_blade     = new BladeCache($views, $cache);
        $this->_variables = [];

        // 不使用缓存
        // $this->_blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $this->_blade->setInjectResolver(function ($className) {
            // Custom logic for resolving
            return new $className();
        });

        $base_url = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        // 全局变量
        $this->_blade->share('base_url', $base_url);

        foreach ($bladeConfig as $key => $value) {
            $this->_blade->$key = $value;
        }
    }

    /**
     * Set the path to the templates
     *
     * @param string $path The directory to set as the path.
     * @return void
     */
    public function setScriptPath($path)
    {
        if (is_readable($path)) {
            $this->_blade->template_dir = $path;
            return;
        }

        throw new Exception('Invalid path provided');
    }
    /**
     * Retrieve the current template directory
     *
     * @return string
     */
    public function getScriptPath($request = null)
    {
        return $this->_blade->template_dir ?? null;
    }

    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_variables = array_merge($this->_variables, $spec);
        } else {
            $this->_variables[$spec] = $value;
        }
    }

    public function clearVars()
    {
        $this->_variables = [];
    }

    public function render($name, $tpl_vars = null)
    {
        // return true;
        if ($tpl_vars && is_array($tpl_vars)) {
            $this->_variables = array_merge($this->_variables, $tpl_vars);
        }
        return $this->_blade->run($name, $this->_variables);
    }

    public function display($name, $tpl_vars = null)
    {
        echo $this->render($name, $tpl_vars);
    }

    public function getView()
    {
        return $this->_blade;
    }

    public function __set($key, $val)
    {
        $this->_variables[$key] = $val;
    }

    public function __isset($key)
    {
        throw new Exception("Not implemented");
        // return (null !== $this->_blade->get_template_vars($key));
    }

    public function __unset($key)
    {
        unset($this->_variables[$key]);
    }
}
