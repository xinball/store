<?php
namespace xbphp\base;

/**
 * 视图基类
 */
class View
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    // 渲染显示
    public function render()
    {
        extract($this->variables);

        $controllerHeader = APP_PATH . 'app/views/' . $this->_controller . '/head.php';
        $controllerFooter = APP_PATH . 'app/views/' . $this->_controller . '/footer.php';
        $controllerLayout = APP_PATH . 'app/views/' . $this->_controller . '/' . $this->_action . '.php';

        // 页头文件
        if (is_file($controllerHeader)) {
            include ($controllerHeader);
        } else {
            include (APP_HEADER);
        }

        //判断视图文件是否存在
        if(!isset($view)||$view==""){
            if(isset($viewpage)&&$viewpage!=""){
                $view = APP_PATH . 'app/views/' . $this->_controller . '/' . $viewpage . '.php';
            }else{
                $view="";
            }
        }else{
            $view = APP_PATH . 'app/views/' . $view . '.php';
        }
        if($view!=""&&is_file($view))
            include ($view);
        else{
            if (is_file($controllerLayout)) {
                include ($controllerLayout);
            } else {
                include ($controllerLayout);
            }
        }

        // 页脚文件
        if (is_file($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include (APP_FOOTER);
        }
        //$view=$viewpage="";
    }
}