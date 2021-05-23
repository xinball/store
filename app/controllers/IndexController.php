<?php


namespace app\controllers;

use library\Func;
use xbphp\base\Controller;


class IndexController extends Controller
{
    public function index(){
        $this->assign("TITLE","首页-虚拟商品购物系统");
        $this->navView();
        $this->render();
    }
}