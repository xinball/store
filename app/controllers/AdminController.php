<?php



namespace app\controllers;

use app\models\Key;
use app\models\Order;
use app\models\Product;
use app\models\Record;
use app\models\User;
use library\Func;
use library\MessageBox;
use library\Pager;
use library\RedisConnect;
use xbphp\base\Controller;
use xbphp\base\Model;


class AdminController extends Controller
{
    public function index(){
        $this->navView();
        $this->authAdminView();

        $productConfig=RedisConnect::getHash('product');
        $this->assign('TITLE', '虚拟商品购物系统-管理员中心');
        $this->assign('productConfig', $productConfig);
        $this->render();
        exit();
    }
    public function jsonproduct(){
        $this->authAdminView();
        $pid = empty($_GET['pid']) ?  '':$_GET['pid'];
        $product =(new Product())->where(['pid=?'],[$pid])->fetch();
        if(!$product||$product['active']=="0"){
            echo '{"pid":"","pname":"","price":"","describe":"","img":"","sellnumber":"0","number":"0","likednumber":"0","active":"0","keys":"[]"}';
            exit();
        }
        $product['keys']=$this->jsonkids($pid);
        echo json_encode($product,JSON_UNESCAPED_UNICODE);
        exit();
    }
    public function jsonuser(){
        $this->authAdminView();
        $uid = empty($_GET['uid']) ?  '':$_GET['uid'];
        $user =(new User())->where(['uid=?'],[$uid])->fetch();
        if(!$user||$user['level']=='-'){
            echo '{"uid":"","uname":"","password":"","email":"","tel":"","regtime":"","money":"0","level":"0"}';
            exit();
        }
        echo json_encode($user,JSON_UNESCAPED_UNICODE);
        exit();
    }
    public function jsonadmin(){
        $user=$this->navView();
        $this->authUserView();
        if(!$user||$user['level']=='-'){
            echo '{"uid":"","uname":"","email":"","tel":"","regtime":"","likeproductnumber":"0","money":"0","level":"0"}';
            exit();
        }
        echo json_encode($user,JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function productlist(){
        $this->navView();
        $this->authAdminView();

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
        foreach ($params as $key=>$v){
            if($key=="pageNow"||$key=="order"||trim($v)=="")
                continue;
            if($key=="pname"||$key=="describe"){
                $where[$i]="$key like ?";
                $value[$i++]="%".$v."%";
            }elseif($key=="keys"){
                $kids=array_unique(json_decode($v,true),SORT_NUMERIC);
                foreach ($kids as $kid){
                    $where[$i]="(select count(1) from `product_key` where kid = ? and `product_key`.pid = `product`.pid) > 0";
                    $value[$i++]=$kid;
                }
                $v=json_encode($kids,JSON_UNESCAPED_UNICODE);
            }
            $this->assign($key,$v);
            $param.=$key."=".$v."&";
        }
        $orderPara = $params['order']??null;
        if($orderPara=="like"){
            $order=['likednumber DESC','pid ASC'];
            $param.="order=like&";
        }elseif($orderPara=="num"){
            $order=['number DESC','pid ASC'];
            $param.="order=num&";
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

        $this->assign('TITLE', '商品管理-虚拟商品系统');
        $this->assign('products', $products);
        $this->assign('kidslist', $kidslist);
        $this->assign('keylist', $keys);
        $this->assign('navigation', $pager->echoNavigation("/admin/productlist?".$param."pageNow="));
        $this->assign('message', $pager->echoMessage("<strong>抱歉！</strong> 未查询到符合条件的商品o(╥﹏╥)o",1,1));
        $this->assign('productConfig', $productConfig);
        $this->render();
        exit();
    }

    public function userlist(){
        $this->navView();
        $this->authAdminView();

        $pageNow=$_GET['pageNow']??1;
        $pageNow=is_numeric($pageNow)?$pageNow:1;
        $userConfig=RedisConnect::getHash('user');
        $pager=new Pager($pageNow,$userConfig['listnum']);
        $param="";
        $params=$_GET;
        $where=array();
        $value=array();
        $i=0;
        foreach ($params as $key=>$v){
            if($key=="pageNow"||$key=="order"||trim($v)=="")
                continue;
            if($key=="uname"||$key=="email"){
                $where[$i]="$key like ?";
                $value[$i++]="%".$v."%";
            }else if($key=="regtime1") {
                $where[$i]="regtime >= ?";
                $value[$i++]=$v;
            }else if($key=="regtime2"){
                $where[$i]="regtime <= ?";
                $value[$i++]=$v;
            }else{
                $where[$i]="$key=?";
                $value[$i++]=$v;
            }
            $this->assign($key,$v);
            $param.=$key."=".$v."&";
        }
        $orderPara = $params['order']??null;
        if($orderPara&&($orderPara=="likeproductnumber"||$orderPara=="money")){
            $order=[$orderPara.' DESC','uid ASC'];
            $param.="order=$orderPara&";
        }else{
            $orderPara="likeproductnumber";
            $order=['likeproductnumber DESC','uid ASC'];
            $param.="order=likeproductnumber&";
        }
        $this->assign("order",$orderPara);
        (new User())->where($where,$value)->order($order)->getUserList($pager);
        $users=$pager->arr;

        $this->assign('TITLE', '用户管理-虚拟商品购物系统');
        $this->assign('users', $users);
        $this->assign('navigation', $pager->echoNavigation("/admin/userlist?".$param."pageNow="));
        $this->assign('message', $pager->echoMessage("<strong>抱歉！</strong> 未查询到符合条件的用户o(╥﹏╥)o",1,1));
        $this->assign('userConfig', $userConfig);
        $this->render();
        exit();
    }
    public function login(){
        //接收用户数据
        $this->navView();
        $uname= $_POST['uname'] ?? null;
        $password= $_POST['password'] ?? null;
        $prePage= $_POST['prePage'] ?? null;
        $keep= isset($_POST['keep']);
        $this->assign('TITLE', '虚拟商品购物系统-管理员后台登录');
        if($uname&&$password){//登录
            $this->assign("uname",$uname);
            $flag = (new User())->checkUser($uname,$password,$uid);
            if(RedisConnect::getKey("left_".$uid)&&RedisConnect::getKey("left_".$uid)<=1) {
                $this->assign('errMsg', MessageBox::echoDanger("您的账户已被锁定，请更改密码或等待解锁后重新登录！"));
            }elseif($flag){
                $user=(new User())->where(['uid=?'],[$uid])->fetch();
                if($user['level']<2){
                    $this->assign('errMsg', MessageBox::echoDanger("<strong>权限不足！</strong>您还不是管理员哦！"));
                }else{
                    $token=md5($uid.rand());
                    if($keep)
                        $expire=time()+3600*24*30;
                    else
                        $expire=time()+3600;
                    setcookie("auid",$uid,$expire,"/");
                    setcookie("atoken_".$uid,$token,$expire,"/");
                    RedisConnect::setKey("atoken_".$uid,$token,$expire);
                    RedisConnect::del("left_".$uid);
                    if($prePage)
                        header("location:$prePage");
                    else
                        header("location:index");
                    exit();
                }
            }else{
                $left=RedisConnect::getKey("left_".$uid)?:6;
                RedisConnect::setKey("left_".$uid,$left-1,time()+3600);
                $this->assign('errMsg', MessageBox::echoDanger("用户不存在或密码错误，您还有".($left-1)."次机会！"));
            }
        }else{//不登录
            $errMsg = $_POST['errMsg']??'';
            $successMsg = $_POST['successMsg']??'';
            if($errMsg){
                $this->assign('errMsg', $errMsg);
            }else if($successMsg){
                $this->assign('successMsg', $successMsg);
            }
        }
        $this->render();
        exit();
    }

    public function authDoView(){///status:1 success,2 info,3 warning,4 error,-1 herf
        if(!$this->authAdmin()){
            echo '{"status":-1,"result":"/admin/login?prePage='.$_SERVER['HTTP_REFERER'].'"}';
            exit();
        }
    }
    public function changeproduct(){
        $this->authDoView();
        $pid= $_POST['pid'] ?? null;
        $pname= $_POST['pname'] ?? null;
        $price= $_POST['price'] ?? null;
        $describe= $_POST['describe'] ?? null;
        $number= $_POST['number'] ?? null;
        $keys= $_POST['keys'] ?? null;
        $result="";
        if($pid!=null && $pname!=null && $price!=null && $describe!=null && $number!=null && $keys!=null ){
            $product=(new Product())->where(['pid=?'],[$pid])->fetch();
            if($product){
                $kids_=(new Key())->getKidByPid($pid);
                $kids=array_unique(json_decode($keys,true),SORT_NUMERIC);
                $kids0=array();
                foreach($kids_ as $i=>$kid){
                    $kids0[$i]=$kid['kid'];
                }
                $kids0=array_unique($kids0,SORT_NUMERIC);
                $changeFlag=false;
                if($pname!=$product['pname']||$price!=$product['price']||$describe!=$product['describe']||$number!=$product['number']) {
                    if ((new Product())->where(['pid=:pid'], [':pid' => $pid])->update(['pname' => $pname, 'price' => $price, 'describe' => $describe, 'number' => $number]) > 0) {
                        $status = 1;
                        $result = "修改商品信息成功！";
                    } else {
                        $status = 4;
                        $result = "修改商品信息失败！";
                    }
                    $changeFlag=true;
                }
                if(json_encode($kids0)!=json_encode($kids)) {
                    foreach ($kids0 as $i => $kid) {
                        if (in_array($kid, $kids)) {
                            $kids0[$i] = "";
                            $kids[array_search($kid, $kids)] = "";
                        }
                    }
                    $count=0;
                    foreach ($kids0 as $kid) {
                        if ($kid != "")
                            $count+=(new Key())->delByPidKid($pid, $kid);
                    }
                    foreach ($kids as $i => $kid) {
                        if ($kid != "")
                            $count+=(new Key())->addByPidKid($pid, $kid);
                    }
                    if($count==0){
                        $status = 4;
                        $result .= "修改商品关键词信息失败！";
                    }else{
                        $status = 1;
                        $result = "修改商品关键词信息成功！";
                    }
                    $changeFlag=true;
                }
                if(!$changeFlag){
                    $status=2;
                    $result="信息无修改！";
                }
            }else{
                $status=4;
                $result="无法找到商品！";
            }
        }else{
            $status=4;
            $result="无法找到商品！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function delproduct(){
        $this->authDoView();
        $pid= $_POST['pid'] ?? null;
        if($pid){
            $product=(new Product())->where(['pid=?'],[$pid])->fetch();
            if($product){
                if((new Product())->where(['pid=:pid'],[':pid'=>$pid])->update(['active'=>0])>0){
                    $status=1;
                    $result="商品删除成功！";
                }else{
                    $status=4;
                    $result="删除失败！";
                }
            }else{
                $status=4;
                $result="无法找到商品！";
            }
        }else{
            $status=4;
            $result="无法找到商品！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function recoverproduct(){
        $this->authDoView();
        $pid= $_POST['pid'] ?? null;
        if($pid){
            $product=(new Product())->where(['pid=?'],[$pid])->fetch();
            if($product){
                if((new Product())->where(['pid=:pid'],[':pid'=>$pid])->update(['active'=>1])>0){
                    $status=1;
                    $result="商品恢复成功！";
                }else{
                    $status=4;
                    $result="恢复失败！";
                }
            }else{
                $status=4;
                $result="无法找到商品！";
            }
        }else{
            $status=4;
            $result="无法找到商品！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function adduser(){
        $this->authDoView();
        $uname= $_POST['uname'] ?? null;
        $email= $_POST['email'] ?? null;
        $password= $_POST['password'] ?? null;
        $money= $_POST['money'] ?? null;
        $level= $_POST['level'] ?? null;
        $status=4;
        if($uname!=null && $password!=null && $email!=null && $money!=null && $level!=null){
            $this->assign("uname",$uname);
            $this->assign("email",$email);
            if(!is_float($money+0.0)||!($level=='-'||$level=='0'||$level=='1')){
                $result="数据格式不合规范！";
            }elseif($email==""||strlen($email)>255){
                $result="邮箱格式不合规范！";
            }elseif($password==""||strlen($password)>20){
                $result="密码格式不合规范！";
            }elseif((new User())->where(['uname = ?'],[$uname])->fetch()){
                $result="用户名 ".$uname." 已经存在！请重新起一个吧";
            }else{
                $data['uname']=$uname;
                $data['email']=$email;
                $data['money']=$money;
                $data['level']=$level;
                $data['password']=md5($password);
                $count=(new User())->add($data);
                if($count>0){
                    $status=1;
                    $result="创建用户成功！";
                }else{
                    $result="创建用户失败！";
                }
            }
        }else{
            $result="添加用户失败！";//.$uname." ".$email." ".$money." ".$level." ".$password;
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function addproduct(){
        $this->authDoView();
        $pname= $_POST['pname'] ?? null;
        $price= $_POST['price'] ?? null;
        $describe= $_POST['describe'] ?? null;
        $number= $_POST['number'] ?? null;
        $status=4;
        if($pname!=null && $price!=null && $describe!=null && $number!=null){
            $this->assign("pname",$pname);
            if(!is_float($price+0.0)||!is_numeric($number)){
                $result="数据格式不合规范！";
            }elseif($describe==""||strlen($describe)>255||$pname==""||strlen($pname)>255){
                $result="商品名称或描述格式不合规范！";
            }else{
                $data['pname']=$pname;
                $data['price']=$price;
                $data['describe']=$describe;
                $data['number']=$number;
                $count=(new Product())->add($data);
                if($count>0){
                    $status=1;
                    $result="创建商品成功！";
                }else{
                    $result="创建商品失败！";
                }
            }
        }else{
            $result="添加商品失败！";//.$uname." ".$email." ".$money." ".$level." ".$password;
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function deluser(){
        $this->authDoView();
        $admin=$this->navView();
        $uid= $_POST['uid'] ?? null;
        if($uid){
            $user=(new User())->where(['uid=:uid'],[':uid'=>$uid])->fetch();
            if($user){
                if($user['level']=="-"||$user['level']<=$admin['level']){
                    if((new User())->where(['uid=:uid'],[':uid'=>$uid])->update(['level'=>'-'])>0){
                        $status=1;
                        $result="用户封禁成功！";
                    }else{
                        $status=4;
                        $result="用户封禁失败！";
                    }
                }else{
                    $result="用户权限不足，无法封禁！";
                }
            }else{
                $status=4;
                $result="无法找到用户！";
            }
        }else{
            $status=4;
            $result="无法找到用户！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function recoveruser(){
        $this->authDoView();
        $admin=$this->navView();
        $uid= $_POST['uid'] ?? null;
        if($uid){
            $user=(new User())->where(['uid=:uid'],[':uid'=>$uid])->fetch();
            if($user){
                if($user['level']=="-"||$user['level']<=$admin['level']){
                    if((new User())->where(['uid=:uid'],[':uid'=>$uid])->update(['level'=>'0'])>0){
                        $status=1;
                        $result="用户恢复成功！";
                    }else{
                        $status=4;
                        $result="用户恢复失败！";
                    }
                }else{
                    $result="用户权限不足，无法恢复！";
                }
            }else{
                $status=4;
                $result="无法找到用户！";
            }
        }else{
            $status=4;
            $result="无法找到用户！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }

    public function changeuser(){
        $admin=$this->navView();
        $this->authDoView();
        $uid= $_POST['uid'] ?? null;
        $uname= $_POST['uname'] ?? null;
        $email= $_POST['email'] ?? null;
        $money= $_POST['money'] ?? null;
        $tel= $_POST['tel'] ?? null;
        $level= $_POST['level'] ?? null;
        //if($uid!=null&&$uname!=null&&$nickname!=null&&$email!=null&&$tel!=null&&$slogan!=null&&$sex!=null&&$birthday!=null&&$assets!=null&&$exp!=null&&$level!=null){
        if($uid!=null&&$uname!=null&&$email!=null&&$level!=null&&$money!=null){
            $user=(new User())->where(['uid=:uid'],[':uid'=>$uid])->fetch();
            if($user){
                if($uname!=$user['uname']||$email!=$user['email']||$money!=$user['money']||$level!=$user['level']||$tel!=$user['tel']){
                    if($user['level']=="-"||$user['level']<=$admin['level']){
                        if((new User())->where(['uid=:uid'],[':uid'=>$uid])->update(['uname'=>$uname,'email'=>$email,'money'=>$money,'tel'=>$tel,'level'=>$level])>0){
                            $status=1;
                            $result="修改用户信息成功！";
                        }else{
                            $status=4;
                            $result="修改用户信息失败！";
                        }
                    }else{
                        $status=4;
                        $result="管理员权限不足，无法修改！";
                    }
                }else{
                    $status=2;
                    $result="信息无修改！";
                }
            }else{
                $status=4;
                $result="无法找到用户！";
            }
        }else{
            $status=4;
            $result="用户信息提交失败！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function changeadmin(){
        $user=$this->navView();
        $this->authDoView();
        $tel= $_POST['tel'] ?? null;
        $email= $_POST['email'] ?? null;
        $password= $_POST['password'] ?? null;
        $password1= $_POST['password1'] ?? null;
        $status=4;
        //if($uid!=null&&$uname!=null&&$nickname!=null&&$email!=null&&$tel!=null&&$slogan!=null&&$sex!=null&&$birthday!=null&&$assets!=null&&$exp!=null&&$level!=null){
        if($user!=null&&$email!=null&&$password!=null&&$password!=""){
            if($tel!=$user['tel']||$email!=$user['email']||($password1!="")){
                if($user['level']!="-"&&$user['level']>1){
                    if(md5($password)==$user['password']){
                        $data=['tel'=>$tel,'email'=>$email];
                        if($password1==""||($password1!=""&&$password!=$password1)){
                            if($password1!="")
                                $data['password']=md5($password1);
                            if((new User())->where(['uid=:uid'],[':uid'=>$user['uid']])->update($data)>0){
                                $status=1;
                                $result="用户信息修改成功！";
                            }else{
                                $result="修改用户信息失败！";
                            }
                        }else{
                            $result="修改密码失败，原密码和新密码不能相同！";
                        }
                    }else{
                        $result="原密码错误，用户信息修改失败！";
                    }
                }else{
                    $result="用户权限不足，无法修改！";
                }
            }else{
                $status=2;
                $result="信息无修改！";
            }
        }else{
            $result="用户信息提交失败！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function orderdetail(){
        $user=$this->navView();
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $data=array();
        $data['status']=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($user['level']!="-"&&$user['level']>1){
                    $data['status']=1;
                    $records=(new Order())->getOrder($oid);
                    $data['result']=json_encode($records,JSON_UNESCAPED_UNICODE);
                }else{
                    $data['result']="用户权限不足，无法查询订单！";
                }
            }else{
                $data['result']="无法找到订单！";
            }
        }else{
            $data['result']="订单编号错误（数据传输错误）！";
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    public function order(){
        $admin=$this->navView();
        $this->authDoView();
        if ($admin) {
            $orderConfig=RedisConnect::getHash('order');
            $pageNow=empty($_GET['pageNow'])||!is_numeric($_GET['pageNow'])?1:$_GET['pageNow'];
            $pager=new Pager($pageNow,$orderConfig['listnum'],$orderConfig['pagenum']);

            $param="";
            $params=$_GET;
            $i=0;
            $where=array();
            $value=array();
            $where[$i]="type!=?";
            $value[$i++]="d";
            $where[$i++]="type=rtype";
            foreach ($params as $key=>$v){
                if($key=="pageNow"||trim($v)=="")
                    continue;
                $param.=$key."=".$v."&";
                $where[$i]="$key=?";
                $value[$i++]=$v;
            }
            (new User())->where($where,$value)->getOrder($pager);
            $orders=$pager->arr;
            $navigation = $pager->echoNavPage("/admin/order?".$param."pageNow=","order");
            $data['messages']=$pager->echoMessage("<strong>抱歉！</strong> 未查询到订单o(╥﹏╥)o",1,1);
        } else {
            $orders = "[]";
            $navigation = "";
            $data['messages']=MessageBox::echoWarning("<strong>抱歉！</strong> 管理员登录有误！");
        }
        $data['navPage']=$navigation;
        $data['orders']=$orders;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        //$this->assign('productConfig', $productConfig);
        //$this->assign('products', $products);
        //$this->assign('navigation', $navigation);
        //$this->render();
    }
    public function del(){
        $admin=$this->navView();
        $this->authDoView();
        if ($admin) {
            $orderConfig=RedisConnect::getHash('order');
            $pageNow=empty($_GET['pageNow'])||!is_numeric($_GET['pageNow'])?1:$_GET['pageNow'];
            $pager=new Pager($pageNow,$orderConfig['listnum'],$orderConfig['pagenum']);

            $param="";
            $params=$_GET;
            $i=0;
            $where=array();
            $value=array();
            $where[$i]="type=?";
            $value[$i++]="d";
            $where[$i++]="type=rtype";
            foreach ($params as $key=>$v){
                if($key=="pageNow"||trim($v)=="")
                    continue;
                $param.=$key."=".$v."&";
                $where[$i]="$key=?";
                $value[$i++]=$v;
            }
            (new User())->where($where,$value)->getOrder($pager);
            $orders=$pager->arr;
            $navigation = $pager->echoNavPage("/admin/order?".$param."pageNow=","del");
            $data['messages']=$pager->echoMessage("<strong>抱歉！</strong> 未查询到订单o(╥﹏╥)o",1,1);
        } else {
            $orders = "[]";
            $navigation = "";
            $data['messages']=MessageBox::echoWarning("<strong>抱歉！</strong> 管理员登录有误！");
        }
        $data['navPage']=$navigation;
        $data['orders']=$orders;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        //$this->assign('productConfig', $productConfig);
        //$this->assign('products', $products);
        //$this->assign('navigation', $navigation);
        //$this->render();
    }
    public function send(){
        $admin=$this->navView();
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='b'){
                    if($admin['level']!="-"&&$admin['level']>1){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'s']);
                        $rid=(new Record())->add(['oid'=>$oid,'auid'=>$admin['uid'],'type'=>'s']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，发货成功！";
                        }else{
                            $result="发货失败！";
                        }
                    }else{
                        $result="管理员权限不足，无法发货！";
                    }
                }else{
                    $result="该商品不在待发货列表中，无法发货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function f(){
        $admin=$this->navView();
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $tip= $_GET['tip'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='b'){
                    if($admin['level']!="-"&&$admin['level']>1){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'f']);
                        $rid=(new Record())->add(['oid'=>$oid,'tip'=>$tip,'auid'=>$admin['uid'],'type'=>'f']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，拒绝发货成功！";
                        }else{
                            $result="拒绝发货失败！";
                        }
                    }else{
                        $result="管理员权限不足，无法拒绝发货！";
                    }
                }else{
                    $result="该商品不在待发货列表中，无法拒绝发货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function returned(){
        $admin=$this->navView();
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='t'||$order['type']=='j'){
                    if($admin['level']!="-"&&$admin['level']>1){
                        $product=(new Product())->where(['pid=:pid'],[':pid'=>$order['pid']])->fetch();
                        $user=(new User())->where(['uid=:uid'],[':uid'=>$order['uid']])->fetch();
                        (new Product())->where(['pid=:pid'],[':pid'=>$order['pid']])->update(['number'=>($product['number']+$order['number']),'sellnumber'=>($product['sellnumber']-$order['number'])]);
                        (new User())->where(['uid=:uid'],[':uid'=>$user['uid']])->update(['money'=>bcadd($user['money'],$order['total'],2)]);
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'r']);
                        $rid=(new Record())->add(['oid'=>$oid,'auid'=>$admin['uid'],'type'=>'r']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，退货成功！";
                        }else{
                            $result="退货失败！";
                        }
                    }else{
                        $result="管理员权限不足，无法退货！";
                    }
                }else{
                    $result="该商品不在“已发起退货”的订单列表中，无法退货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function notreturn(){
        $admin=$this->navView();
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $tip= $_GET['tip'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='t'){
                    if($admin['level']!="-"&&$admin['level']>1){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'n']);
                        $rid=(new Record())->add(['oid'=>$oid,'tip'=>$tip,'auid'=>$admin['uid'],'type'=>'n']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，拒绝退货成功！";
                        }else{
                            $result="拒绝退货失败！";
                        }
                    }else{
                        $result="管理员权限不足，无法拒绝退货！";
                    }
                }else{
                    $result="该商品不在“已发起退货”的订单列表中，无法拒绝退货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function logout(){
        $uid= $_COOKIE['auid'] ?? '';
        $key='atoken_'.$uid;
        $token=$_COOKIE[$key]??null;
        if($token&&$token===RedisConnect::getKey($key)){
            $_COOKIE['auid']="";
            $_COOKIE[$key]="";
            RedisConnect::del($key);
        }
        header("location:".$_SERVER['HTTP_REFERER']);
    }
}