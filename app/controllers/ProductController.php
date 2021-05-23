<?php


namespace app\controllers;

use app\models\Key;
use app\models\Order;
use app\models\User;
use library\Pager;
use library\RedisConnect;
use xbphp\base\Controller;
use app\models\Product;
use xbphp\base\Model;


class ProductController extends Controller
{
    public function index(){
        $this->navView();
        $pageNow=$_GET['pageNow']??1;
        $pageNow=is_numeric($pageNow)?$pageNow:1;
        $pid = empty($_GET['pid']) ?  '':$_GET['pid'];
        $user=$this->navView("user");
        $like=0;
        if($user)
            $like = (new User())->getUidPid($user['uid'],$pid);
        $product =(new Product())->where(['pid=?'],[$pid])->fetch();
        if($product['active']=="0"){
            exit();
        }
        $evaluateConfig=RedisConnect::getHash('evaluate');
        $pager=new Pager($pageNow,$evaluateConfig['listnum']);
        $param="";
        $params=$_GET;
        $where=array();
        $value=array();
        $i=0;
        foreach ($params as $key=>$v){
            if($key=="pageNow"||trim($v)=="")
                continue;
            if($key=="tip"){
                $where[$i]="$key like ?";
                $value[$i++]="%".$v."%";
            }else if($key=="time1") {
                $where[$i]="time >= ?";
                $value[$i++]=$v;
            }else if($key=="time2"){
                $where[$i]="time <= ?";
                $value[$i++]=$v;
            }
            $this->assign($key,$v);
            $param.=$key."=".$v."&";
        }
        $where[$i]="pid=?";
        $value[$i++]=$pid;
        $where[$i]="type=?";
        $value[$i++]="e";
        $where[$i]="rtype=?";
        $value[$i++]="e";
        (new Order())->where($where,$value)->getCommentList($pager);
        $comments=$pager->arr;
        $this->assign('product', $product);
        $this->assign('keys', $this->jsonkids($pid));
        $this->assign('like', $like);
        $this->assign('comments', $comments);
        $this->assign('message', $pager->echoMessage("这款商品还没有任何评论鸭！<br>评论可以获得积分哟((^∀^*)) ",0));
        $this->assign('TITLE', ($product['pname']?$product['pname']."-":"")."商品详情");
        $this->assign('navigation', $pager->echoNavigation("/product?".$param."pageNow="));
        $this->render();
        exit();
    }


    public function list(){//
        $this->navView();
        $pageNow=$_GET['pageNow']??1;
        $pageNow=is_numeric($pageNow)?$pageNow:1;
        $productConfig=RedisConnect::getHash('product');
        $keys=(new Key())->fetchAll();
        $pager=new Pager($pageNow,$productConfig['listnum'],$productConfig['pagenum']);
        $param="";
        $params=$_GET;
        $where=array();
        $value=array();
        $i=0;
        $where[$i]="active=?";
        $value[$i++]="1";
        foreach ($params as $key=>$v){
            if($key=="pageNow"||$key=="order"||trim($v)=="")
                continue;
            if($key=="pname"||$key=="describe"){
                $where[$i]="`$key` like ?";
                $value[$i++]="%".$v."%";
            }elseif($key=="keys"){
                $kids=array_unique(json_decode($v,true),SORT_NUMERIC);
                foreach ($kids as $kid){
                    $where[$i]="(select count(1) from `product_key` where kid = ? and `product_key`.pid = `product`.pid) > 0";
                    $value[$i++]=$kid;
                }
                $v=json_encode($kids,JSON_UNESCAPED_UNICODE);
            }/*else{
                $where[$i]="$key=?";
                $value[$i++]=$v;
            }*/
            $this->assign($key,$v);
            $param.=$key."=".$v."&";
        }
        $orderPara = $params['order']??null;
        if($orderPara=="like"){
            $order=['likednumber DESC','pid ASC'];
            $param.="order=like&";
        }elseif($orderPara=="prices"){
            $order=['price ASC','pid ASC'];
            $param.="order=prices&";
        }elseif($orderPara=="pricej"){
            $order=['price DESC','pid ASC'];
            $param.="order=pricej&";
        }else{
            $orderPara="sell";
            $order=['sellnumber DESC','pid ASC'];
            $param.="order=sell&";
        }
        $this->assign("order",$orderPara);
        (new Product())->where($where,$value)->order($order)->getProductList($pager);
        $products=$pager->arr;
        $kidslist=array();
        foreach ($products as $i=>$product){
            $kidslist[$i]=$this->jsonkids($product['pid']);
        }
        $this->assign('TITLE', '商品查询-虚拟商品购物系统');
        $this->assign('products', $products);
        $this->assign('kidslist', $kidslist);
        $this->assign('keylist', $keys);
        $this->assign('navigation', $pager->echoNavigation("/product/list?".$param."pageNow="));
        $this->assign('message', $pager->echoMessage("<strong>抱歉！</strong> 未查询到符合条件的商品o(╥﹏╥)o",1,1));
        $this->assign('productConfig', $productConfig);
        $this->render();
        exit();
    }

    public function jsondpCount(){
        echo json_encode((new Product())->getDynastyPoemCount(),JSON_UNESCAPED_UNICODE);
    }
    public function jsonpoemCount(){
        echo json_encode((new Product())->getPoemCount(),JSON_UNESCAPED_UNICODE);
    }
}