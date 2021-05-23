<?php


namespace app\controllers;

use xbphp\base\Controller;
use app\models\Order;


class OrderController extends Controller
{
    public function index(){
        $cid = empty($_GET['cid']) ?  '':$_GET['cid'];
        $comment =(new Order())->where(['cid=?'],[$cid])->fetch();
        $this->assign('comment', $comment);
        $this->assign('TITLE', ($comment['title']?$comment['title']."-":"").($comment['content']?$comment['content']."-":"")."诗词会");
        $this->render();
    }

    public function add(){

    }
    public function like(){

    }
}