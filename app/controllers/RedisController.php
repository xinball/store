<?php


namespace app\controllers;

use library\RedisConnect;
use xbphp\base\Controller;

class RedisController extends Controller
{
    public function index(){
    }

    public function get(){
        $key=empty($_GET['key'])?null:$_GET['key'];
        if($key)
            echo RedisConnect::getKey($key);
    }
    public function lindex(){
        $key=empty($_GET['key'])?null:$_GET['key'];
        $index=empty($_GET['index'])?null:$_GET['index'];
        if($key&&$index)
            echo RedisConnect::getListIndex($key,$index);
    }
    public function hget(){
        $key=empty($_GET['key'])?null:$_GET['key'];
        $hashKey=empty($_GET['hashKey'])?null:$_GET['hashKey'];
        if($key&&$hashKey)
            echo RedisConnect::getHashKey($key,$hashKey);
    }
    public function set(){
        $key=empty($_GET['key'])?null:$_GET['key'];
        $value=empty($_GET['value'])?null:$_GET['value'];
        if($key&&$value)
            RedisConnect::setKey($key,$value);
    }
    public function lset(){
        $key=empty($_GET['key'])?null:$_GET['key'];
        $index=empty($_GET['index'])?null:$_GET['index'];
        $value=empty($_GET['value'])?null:$_GET['value'];
        if($key&&$value&&$index)
            RedisConnect::setListIndex($key,$value,$index);
    }
}