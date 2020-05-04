<?php
class BladeAdapter implements Yaf\View_Interface
{
    protected $_tpl_vars;
    /**
     * Smarty object
     * @var Smarty
     */
    public $_blade;

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
        $this->_blade = new BladeCache($views, $cache);
        // 不使用缓存
        // $this->_blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
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

    public function assign($name, $value = null)
    {
        return true;
    }

    public function render($view_path, $tpl_vars = null)
    {
        return true;
    }

    public function display($view_path, $tpl_vars = null)
    {
        echo $this->_blade->run($view_path, $tpl_vars);
    }

    public function getView()
    {
        return $this->_blade;
    }
}
