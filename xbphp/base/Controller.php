<?php
namespace xbphp\base;

use app\models\Product;
use app\models\User;
use library\MessageBox;
use library\RedisConnect;

/**
 * 控制器基类
 */
class Controller
{
    protected $_controller;
    protected $_action;
    protected $_view;

    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }
    public function authUser(): bool
    {
        $uid= $_COOKIE['uid'] ?? '';
        $key='token_'.$uid;
        $token=$_COOKIE[$key]??null;
        return $token&&$token===RedisConnect::getKey($key);
    }
    public function authAdmin(): bool
    {
        $uid= $_COOKIE['auid'] ?? '';
        $key='atoken_'.$uid;
        $token=$_COOKIE[$key]??null;
        return $token&&$token===RedisConnect::getKey($key);
    }
    public function navView($str="admin"){
        $user=null;
        $admin=null;
        if($this->authAdmin()){
            $admin=(new User())->where(['uid=:uid'],['uid'=>($_COOKIE['auid'] ?? null)])->fetch();
            $this->assign("admin",$admin);
        }
        if($this->authUser()){
            $user=(new User())->where(['uid=:uid'],['uid'=>($_COOKIE['uid'] ?? null)])->fetch();
            $this->assign("user",$user);
        }
        if($str=="user"){
            return $user;
        }
        elseif($str=="admin"){
            return $admin;
        }
        return null;
    }
    public function authUserView()
    {
        $uid= $_COOKIE['uid'] ?? '';
        $key='token_'.$uid;
        $token=$_COOKIE[$key]??null;
        if($token&&$token===RedisConnect::getKey($key)){
            $token=md5($uid.rand());
            $expire=RedisConnect::ttl($key)>3600?time()+RedisConnect::ttl($key):time()+3600;
            setcookie("uid",$uid,$expire,"/");
            setcookie("token_".$uid,$token,$expire,"/");
            RedisConnect::setKey("token_".$uid,$token,$expire);
        }else{
            $this->assign('TITLE', '诗词会-登录');
            $this->assign('errMsg', MessageBox::echoDanger("您还没有登录，请重新登录！"));
            $this->assign("view","/user/login");
            $this->assign("prePage", $_SERVER['REQUEST_URI']);
            $this->render();
            exit();
        }
    }

    public function authAdminView()
    {
        $uid= $_COOKIE['auid'] ?? '';
        $key='atoken_'.$uid;
        $token=$_COOKIE[$key]??null;
        if($token&&$token===RedisConnect::getKey($key)){
            $token=md5($uid.rand());
            $expire=RedisConnect::ttl($key)>3600?time()+RedisConnect::ttl($key):time()+3600;
            setcookie("auid",$uid,$expire,"/");
            setcookie("atoken_".$uid,$token,$expire,"/");
            RedisConnect::setKey("atoken_".$uid,$token,$expire);
        }else{
            $this->assign('TITLE', '诗词会-管理员后台登录');
            $this->assign('errMsg', MessageBox::echoDanger("您还没有登录，请重新登录！"));
            $this->assign("view","/admin/login");
            $this->assign("prePage", $_SERVER['REQUEST_URI']);
            $this->render();
            exit();
        }
    }

    protected function jsonkids($pidIn){
        $pid= $_GET['pid'] ?? $pidIn;
        if($pid!=null){
            return json_encode((new Product())->getProductKeys($pid),JSON_UNESCAPED_UNICODE);
        }
        return "[]";
    }
    // 渲染视图
    public function render()
    {
        $this->_view->render();
    }
}