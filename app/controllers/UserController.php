<?php


namespace app\controllers;

use app\models\Order;
use app\models\Product;
use app\models\Record;
use library\Func;
use library\MessageBox;
use library\Pager;
use library\RedisConnect;
use xbphp\base\Controller;
use app\models\User;
use xbphp\base\Model;


class UserController extends Controller
{
    public function index()
    {
        $user=$this->navView("user");
        $this->authUserView();

        $productConfig=RedisConnect::getHash('product');
        $this->assign('TITLE', '虚拟商品购物系统-用户中心');
        $this->assign('productConfig', $productConfig);
        $this->render();
        exit();
    }

    public function user(){
        $user=$this->navView("user");
        $this->authUserView();
    }

    public function jsonproduct(){
        $this->authUserView();
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
        $user=$this->navView("user");
        $this->authUserView();
        if(!$user||$user['level']=='-'){
            echo '{"uid":"","uname":"","email":"","tel":"","regtime":"","likeproductnumber":"0","money":"0","level":"0"}';
            exit();
        }
        echo json_encode($user,JSON_UNESCAPED_UNICODE);
        exit();
    }
    public function login(){
        $user=$this->navView("user");
        //接收用户数据
        $uname= $_POST['uname'] ?? null;
        $password= $_POST['password'] ?? null;
        $prePage= $_POST['prePage'] ?? null;
        $keep= isset($_POST['keep']);
        $this->assign('TITLE', '虚拟商品购物系统-登录');
        if($uname&&$password){//登录
            $this->assign("uname",$uname);
            $flag = (new User())->checkUser($uname,$password,$uid);
            if($flag){
                if(RedisConnect::getKey("left_".$uid)&&RedisConnect::getKey("left_".$uid)<=1) {
                    $this->assign('errMsg', MessageBox::echoDanger("您的用户名已被锁定，请更改密码或等待解锁后重新登录！"));
                }else{
                    $user=(new User())->where(['uid=?'],[$uid])->fetch();
                    if($user['level']==0) {
                        $this->assign('errMsg', MessageBox::echoDanger("用户尚未激活，请激活后登录！"));
                    }elseif($user['level']=='-'){
                        $this->assign('errMsg', MessageBox::echoDanger("用户已被封禁！无法登录！"));
                    }else{
                        $token=md5($uid.rand());
                        if($keep)
                            $expire=time()+3600*24*30;
                        else
                            $expire=time()+3600;
                        setcookie("uid",$uid,$expire,"/");
                        setcookie("token_".$uid,$token,$expire,"/");
                        RedisConnect::setKey("token_".$uid,$token,$expire);
                        RedisConnect::del("left_".$uid);
                        if($prePage)
                            header("location:$prePage");
                        else
                            header("location:index");
                        exit();
                    }
                }
            }else{
                $left=RedisConnect::getKey("left_".$uid)?:6;
                RedisConnect::setKey("left_".$uid,$left-1,time()+3600);
                $this->assign('errMsg', MessageBox::echoDanger("用户名号不存在或密码错误，您还有".($left-1)."次机会！"));
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
    public function register()
    {
        $user=$this->navView("user");
        //接收用户数据
        $uname= $_POST['uname'] ?? null;
        $email= $_POST['email'] ?? null;
        $password= $_POST['password'] ?? null;
        $this->assign('TITLE', '虚拟商品购物系统-注册');
        if($uname&&$password&&$email){//注册
            $this->assign("uname",$uname);
            $this->assign("email",$email);
            if($uname==""||strlen($uname)>20){
                $this->assign('errMsg', MessageBox::echoDanger("用户名格式不合规范！"));
            }elseif($email==""||strlen($email)>255){
                $this->assign('errMsg', MessageBox::echoDanger("邮箱格式不合规范！"));
            }elseif($password==""||strlen($password)>20){
                $this->assign('errMsg', MessageBox::echoDanger("密码格式不合规范！"));
            }elseif((new User())->where(['uname = ?'],[$uname])->fetch()){
                $this->assign('errMsg', MessageBox::echoDanger("用户名 ".$uname." 已经存在！请重新起一个吧"));
            }elseif((new User())->where(['email = ?'],[$email])->fetch()){
                $this->assign('errMsg', MessageBox::echoDanger("该邮箱已注册！"));
            }else{
                $this->assign('TITLE', '虚拟商品购物系统-登录');
                $data['uname']=$uname;
                $data['email']=$email;
                $data['password']=md5($password);
                $count=(new User())->add($data);
                if($count>0){
                    $uid=(new User())->where(['uname=?'],[$uname])->fetch()['uid'];
                    $this->assign("viewpage","login");
                    $this->assign('successMsg', MessageBox::echoSuccess("恭喜您注册成功，请进入您的<strong>邮箱激活账号</strong>吧！<br/>注意！链接将于2日后过期，请及时激活！",15000));
                    $code=rand();
                    $activeExpire=time()+3600*24*2;
                    RedisConnect::setKey("active_".$uid,$code,$activeExpire);
                    Func::sendMail($email,"虚拟商品购物系统-用户激活",
                        "激活链接：<a href='https://store.xinball.top/user/active?uid=$uid&code=$code'>激活</a>"
                        ."<br/>过期时间：".date("Y-m-d H:i:s",$activeExpire));
                }
                else{
                    $this->assign('successMsg', MessageBox::echoDanger("注册用户失败！"));
                }
            }
        }else{//不注册
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

    public function active(){
        $user=$this->navView("user");
        $code= $_GET['code'] ?? null;
        $uid= $_GET['uid'] ?? null;
        $uname= $_GET['uname'] ?? null;
        $this->assign("TITLE","虚拟商品购物系统-用户激活");
        $flag=true;
        if ($uname){
            $user=(new User())->where(['uname=?'],[$uname])->fetch();
            if($user){
                $uid=$user['uid'];
                $this->assign("TITLE", "虚拟商品购物系统-登录");
                if($user['level']>0) {
                    $this->assign('warnMsg', MessageBox::echoWarning($user['uname'] . "，您已成为正式用户，无需激活！"));
                    $this->assign("viewpage", "login");
                }elseif($user['level']=='-') {
                    $this->assign('errMsg', MessageBox::echoDanger($user['uname'] . "，您的账号已被封禁，无法激活！"));
                    $this->assign("viewpage", "login");
                }else{
                    $this->assign('successMsg', MessageBox::echoSuccess("请进入您的邮箱 <strong>".$user['email']."</strong> 激活账号吧！<br/>注意！链接将于2日后过期，请及时激活！",15000));
                    $this->assign("viewpage", "login");
                    $code=rand();
                    $activeExpire=time()+3600*24*2;
                    RedisConnect::setKey("active_".$uid,$code,$activeExpire);
                    Func::sendMail($user['email'],"虚拟商品购物系统-用户激活",
                        "激活链接：<a href='https://store.xinball.top/user/active?uid=$uid&code=$code'>激活</a>"
                        ."<br/>过期时间：".date("Y-m-d H:i:s",$activeExpire));
                }
            }else{
                $this->assign('errMsg', MessageBox::echoDanger("该用户名不存在！"));
            }
        }elseif($uid){
            $user = (new User())->where(['uid=?'],[$uid])->fetch();
            if($user){
                if($user['level']>0) {
                    $this->assign('warnMsg', MessageBox::echoWarning($user['uname'] . "，您已成为正式用户，无需激活！"));
                    $this->assign("viewpage", "login");
                    $this->assign("TITLE", "虚拟商品购物系统-登录");
                }elseif($user['level']=='-'){
                    $this->assign('errMsg', MessageBox::echoDanger($user['uname']."，您的账号已被封禁，无法激活！"));
                    $this->assign("viewpage","login");
                    $this->assign("TITLE","虚拟商品购物系统-登录");
                }elseif(is_numeric($code)&&$user['level']==0){
                    if(empty(RedisConnect::getKey("active_".$uid))){
                        $this->assign('errMsg', MessageBox::echoDanger("抱歉！您的激活链接已过期<br/>请重新申请激活！",15000));
                    }else if($code===RedisConnect::getKey("active_".$uid)){
                        if((new User())->where(['uid=:uid'],['uid'=>$uid])->update(['level'=>'1'])>0){
                            RedisConnect::del("active_".$uid);
                            $this->assign("viewpage","login");
                            $this->assign("TITLE","虚拟商品购物系统-登录");
                            $this->assign('successMsg', MessageBox::echoSuccess("<strong>恭喜您！</strong>您已成为虚拟商品购物系统正式用户！"));
                        }else{
                            $this->assign('errMsg', MessageBox::echoDanger("用户激活失败！"));
                        }
                    }else{
                        $flag=false;
                    }
                }else{
                    $flag=false;
                }
            }else{
                $flag=false;
            }
        }
        if(!$flag)
            $this->assign('errMsg', MessageBox::echoDanger("抱歉！您的激活链接不正确<br/>请重新打开邮件内的链接进行激活！"));
        $this->render();
        exit();
    }

    public function forget(){
        $user=$this->navView("user");
        $forget= $_GET['forget'] ?? null;
        $uid= $_GET['uid'] ?? null;
        $uname= $_GET['uname'] ?? null;
        $password= $_GET['password'] ?? null;
        $this->assign("TITLE","虚拟商品购物系统-找回密码");
        $flag=true;
        if ($uname){
            $user=(new User())->where(['uname=?'],[$uname])->fetch();
            if($user['level']==0) {
                $this->assign('errMsg', MessageBox::echoDanger("用户尚未激活！"));
            }elseif($user['level']=='-'){
                $this->assign('errMsg', MessageBox::echoDanger("用户已被封禁！"));
            }elseif($user){
                $uid=$user['uid'];
                $this->assign('successMsg', MessageBox::echoSuccess("请进入您的邮箱： <strong>".$user['email']."</strong> 重置密码吧！<br/>注意！重置链接将于2日后过期，请及时重置密码！",15000));
                $forget=rand();
                $forgetExpire=time()+3600*24*2;
                RedisConnect::setKey("forget_".$uid,$forget,$forgetExpire);
                Func::sendMail($user['email'],"虚拟商品购物系统-找回令牌",
                    "密码重置链接：<a href='https://store.xinball.top/user/forget?uid=$uid&forget=$forget'>重置密码</a>"
                    ."<br/>过期时间：".date("Y-m-d H:i:s",$forgetExpire));
            }else{
                $flag=false;
            }
        }elseif($uid){
            $this->assign("TITLE","虚拟商品购物系统-令牌重置");
            $user = (new User())->where(['uid=?'],[$uid])->fetch();
            if($user){
                if(is_numeric($forget)){
                    if(empty(RedisConnect::getKey("forget_".$uid))){
                        $this->assign('errMsg', MessageBox::echoDanger("抱歉！您的令牌重置链接已过期<br/>请重新申请重置令牌！",15000));
                    }else if($forget===RedisConnect::getKey("forget_".$uid)){
                        $this->assign("user",$user);
                        $this->assign("forget",$forget);
                        if($password){
                            if(strlen($password)>20){
                                $this->assign('errMsg', MessageBox::echoDanger("令牌格式不合规范！"));
                            }else{
                                if((new User())->where(['uid=:uid'],[':uid'=>$uid])->update(['password'=>md5($password)])>0){
                                    RedisConnect::del("forget_".$uid);
                                    RedisConnect::del("token_".$uid);
                                    RedisConnect::del("atoken_".$uid);
                                    $this->assign("viewpage","login");
                                    $this->assign("TITLE","虚拟商品购物系统-登录");
                                    $this->assign('successMsg', MessageBox::echoSuccess("<strong>恭喜！</strong>您的令牌已重置，请重新登录！"));
                                }else{
                                    $this->assign('errMsg', MessageBox::echoDanger("重置失败！与原令牌相同！"));
                                }
                            }
                        }
                    }else{
                        $flag=false;
                    }
                }else{
                    $flag=false;
                }
            }else{
                $flag=false;
            }
        }
        if(!$flag)
            $this->assign('errMsg', MessageBox::echoDanger("抱歉！您的令牌重置链接不正确<br/>请重新打开邮件内的链接！"));
        $this->render();
        exit();
    }
    public function likepoem($params){
        $user=$this->navView("user");
        $pid = $_GET['pid']??null;
        if ($pid) {
            $pageNow=$_GET['pageNow']??1;
            $pager=new Pager($pageNow,10);

            $param="";
            $i=0;
            $where=array();
            $value=array();
            foreach ($params as $v){
                $arr=explode('=',$v);
                if($arr[0]=="pageNow"||sizeof($arr)!=2||$arr[1]=="")
                    continue;
                $param.=$arr[0]."=".$_GET[$arr[0]]."&";
                $where[$i]="$arr[0]=?";
                $value[$i++]=$_GET[$arr[0]];
            }
            (new User())->where($where,$value)->getUserLikePoem($pager);
            $users=$pager->arr;
            echo json_encode($users,JSON_UNESCAPED_UNICODE);
            $navigation = $pager->echoNavigation("/user/likepoem?".$param."pageNow=");
        } else {
            $users = null;
            $navigation = null;
        }
        $this->assign('TITLE', '用户列表');
    }
    public function logout(){
        $uid= $_COOKIE['uid'] ?? '';
        $key='token_'.$uid;
        $token=$_COOKIE[$key]??null;
        if($token&&$token===RedisConnect::getKey($key)){
            $_COOKIE['uid']="";
            $_COOKIE[$key]="";
            RedisConnect::del($key);
        }
        header("location:".$_SERVER['HTTP_REFERER']);
    }
    public function authDoView(){///status:1 success,2 info,3 warning,4 error,-1 herf
        if(!$this->authUser()){
            echo '{"status":-1,"result":"/user/login?prePage='.$_SERVER['HTTP_REFERER'].'"}';
            exit();
        }
    }
    public function changeuser(){
        $user=$this->navView("user");
        $this->authDoView();
        $tel= $_POST['tel'] ?? null;
        $email= $_POST['email'] ?? null;
        $password= $_POST['password'] ?? null;
        $password1= $_POST['password1'] ?? null;
        $status=4;
        //if($uid!=null&&$uname!=null&&$nickname!=null&&$email!=null&&$tel!=null&&$slogan!=null&&$sex!=null&&$birthday!=null&&$assets!=null&&$exp!=null&&$level!=null){
        if($user!=null&&$email!=null&&$password!=null&&$password!=""){
            if($tel!=$user['tel']||$email!=$user['email']||($password1!="")){
                if($user['level']!="-"&&$user['level']!="0"){
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
    public function addlike(){
        $user=$this->navView("user");
        $this->authDoView();
        $pid= $_GET['pid'] ?? null;
        $status=4;
        if($pid&&$user){
            $product=(new Product())->where(['pid=:pid','active=:active'],[':pid'=>$pid,':active'=>'1'])->fetch();
            if($product){
                if($user['level']!="-"&&$user['level']!="0"){
                    if((new User())->addUidPid($user['uid'],$pid)>0){
                        $status=1;
                        $result="收藏商品成功！";
                    }else{
                        $result="收藏商品失败！";
                    }
                }else{
                    $result="用户权限不足，无法收藏！";
                }
            }else{
                $result="无法找到商品！";
            }
        }else{
            $result="商品编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function dellike(){
        $user=$this->navView("user");
        $this->authDoView();
        $pid= $_GET['pid'] ?? null;
        $status=4;
        if($pid&&$user){
            $product=(new Product())->where(['pid=:pid','active=:active'],[':pid'=>$pid,':active'=>1])->fetch();
            if($product){
                if($user['level']!="-"&&$user['level']!="0"){
                    if((new User())->delUidPid($user['uid'],$pid)>0){
                        $status=1;
                        $result="移出收藏商品成功！";
                    }else{
                        $result="移出收藏商品失败！";
                    }
                }else{
                    $result="用户权限不足，无法移出收藏！";
                }
            }else{
                $result="无法找到商品！";
            }
        }else{
            $result="商品编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function like(){
        $user=$this->navView("user");
        $this->authDoView();
        if ($user) {
            $productConfig=RedisConnect::getHash('product');
            $pageNow=empty($_GET['pageNow'])||!is_numeric($_GET['pageNow'])?1:$_GET['pageNow'];
            $pager=new Pager($pageNow,$productConfig['listnum'],$productConfig['pagenum']);

            $param="";
            $params=$_GET;
            $i=0;
            $where=array();
            $value=array();
            $where[$i]="active=?";
            $value[$i++]="1";
            $where[$i]="uid=?";
            $value[$i++]=$user['uid'];
            foreach ($params as $key=>$v){
                if($key=="pageNow"||trim($v)=="")
                    continue;
                $param.=$key."=".$v."&";
                $where[$i]="$key=?";
                $value[$i++]=$v;
            }
            (new User())->where($where,$value)->getProductLikedUser($pager);
            $products=$pager->arr;
            $navigation = $pager->echoNavPage("/user/like?".$param."pageNow=","like");
            foreach ($products as $i=>$product){
                $products[$i]['keys']=$this->jsonkids($product['pid']);
            }
            $data['messages']=$pager->echoMessage("<strong>抱歉！</strong> 未查询到收藏的商品o(╥﹏╥)o",1,1);
        } else {
            $products = "[]";
            $navigation = "";
            $data['messages']=MessageBox::echoWarning("<strong>抱歉！</strong> 用户登录有误！");
        }
        $data['navPage']=$navigation;
        $data['products']=$products;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        //$this->assign('productConfig', $productConfig);
        //$this->assign('products', $products);
        //$this->assign('navigation', $navigation);
        //$this->render();
    }
    public function order(){
        $user=$this->navView("user");
        $this->authDoView();
        if ($user) {
            $orderConfig=RedisConnect::getHash('order');
            $pageNow=empty($_GET['pageNow'])||!is_numeric($_GET['pageNow'])?1:$_GET['pageNow'];
            $pager=new Pager($pageNow,$orderConfig['listnum'],$orderConfig['pagenum']);

            $param="";
            $params=$_GET;
            $i=0;
            $where=array();
            $value=array();
            $where[$i]="uid=?";
            $value[$i++]=$user['uid'];
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
            $navigation = $pager->echoNavPage("/user/order?".$param."pageNow=","order");
            $data['messages']=$pager->echoMessage("<strong>抱歉！</strong> 未查询到订单o(╥﹏╥)o",1,1);
        } else {
            $orders = "[]";
            $navigation = "";
            $data['messages']=MessageBox::echoWarning("<strong>抱歉！</strong> 用户登录有误！");
        }
        $data['navPage']=$navigation;
        $data['orders']=$orders;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        //$this->assign('productConfig', $productConfig);
        //$this->assign('products', $products);
        //$this->assign('navigation', $navigation);
        //$this->render();
    }
    public function orderdetail(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $data=array();
        $data['status']=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($user['level']!="-"&&$user['level']!="0"){
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
    public function addpay(){
        $user=$this->navView("user");
        $this->authDoView();
        $pid= $_GET['pid'] ?? null;
        $number= $_GET['number'] ?? null;
        $status=4;
        if($pid){
            if(is_numeric($number)&&$number>0){
                $product=(new Product())->where(['pid=:pid','active=:active'],[':pid'=>$pid,':active'=>1])->fetch();
                if($product){
                    if($user['level']!="-"&&$user['level']!="0"){
                        if($number<=$product['number']){
                            $oid = (new Order())->add(['uid'=>$user['uid'],'pid'=>$pid,'number'=>$number,'price'=>$product['price'],
                            'total'=>$number*$product['price'],'type'=>'p']);
                            if($oid>0){
                                (new Record())->add(['oid'=>$oid,'type'=>'p']);
                                $status=1;
                                $result="商品已发起购买申请，请在10分钟内完成付款！";
                            }else{
                                $result="商品加购失败！";
                            }
                        }else{
                            $result="商品数量不足，无法购买！";
                        }
                    }else{
                        $result="用户权限不足，无法加入购物车！";
                    }
                }else{
                    $result="无法找到商品！";
                }
            }else{
                $result="商品数量有误！";
            }
        }else{
            $result="商品编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function delpay(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='p'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'d']);
                        (new Record())->add(['oid'=>$oid,'type'=>'d']);
                        if($oid>0){
                            $status=1;
                            $result="商品取消购买成功！";
                        }else{
                            $result="商品取消购买失败！";
                        }
                    }else{
                        $result="用户权限不足，无法取消购买！";
                    }
                }else{
                    $result="该商品不在购买（待付款商品）列表中，无法移除！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }

    public function del(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='f'||$order['type']=='g'||$order['type']=='j'||$order['type']=='e'||$order['type']=='r'||$order['type']=='n'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'d']);
                        (new Record())->add(['oid'=>$oid,'type'=>'d']);
                        if($oid>0){
                            $status=1;
                            $result="订单删除成功！";
                        }else{
                            $result="订单删除失败！";
                        }
                    }else{
                        $result="用户权限不足，无法删除订单！";
                    }
                }else{
                    $result="该商品订单无法删除！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }

    public function buy(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='p'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        if($user['money']>=$order['total']){
                            $product=(new Product())->where(['pid=:pid'],[':pid'=>$order['pid']])->fetch();
                            if($product&&$product['number']>=$order['number']){
                                (new Product())->where(['pid=:pid'],[':pid'=>$order['pid']])->update(['number'=>($product['number']-$order['number']),'sellnumber'=>($product['sellnumber']+$order['number'])]);
                                (new User())->where(['uid=:uid'],[':uid'=>$user['uid']])->update(['money'=>bcsub($user['money'],$order['total'],2)]);
                                (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'b']);
                                $rid=(new Record())->add(['oid'=>$oid,'type'=>'b']);
                                if($rid>0){
                                    $status=1;
                                    $result="恭喜您，付款成功！";
                                }else{
                                    $result="付款失败！";
                                }
                            }else{
                                $result="商品下架或商品数量不足，购买失败！";
                            }
                        }else{
                            $result="账户余额不足，购买失败！";
                        }
                    }else{
                        $result="用户权限不足，无法付款！";
                    }
                }else{
                    $result="该商品不在购买（待付款商品）列表中，无法付款！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function delbuy(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='b'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        $product=(new Product())->where(['pid=:pid'],[':pid'=>$order['pid']])->fetch();
                        (new Product())->where(['pid=:pid'],[':pid'=>$order['pid']])->update(['number'=>($product['number']+$order['number']),'sellnumber'=>($product['sellnumber']-$order['number'])]);
                        (new User())->where(['uid=:uid'],[':uid'=>$user['uid']])->update(['money'=>bcadd($user['money'],$order['total'],2)]);
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'r']);
                        $rid=(new Record())->add(['oid'=>$oid,'type'=>'r']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，退货成功！";
                        }else{
                            $result="退货失败！";
                        }
                    }else{
                        $result="用户权限不足，无法退货！";
                    }
                }else{
                    $result="该商品不在待发货列表中，无法退货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function jujue(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $tip= $_GET['tip'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='s'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'j']);
                        $rid=(new Record())->add(['oid'=>$oid,'tip'=>$tip,'type'=>'j']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，拒绝收货成功！";
                        }else{
                            $result="拒绝收货失败！";
                        }
                    }else{
                        $result="用户权限不足，无法拒绝收货！";
                    }
                }else{
                    $result="该商品不在待收货列表中，无法拒绝收货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function get(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='s'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'g']);
                        $rid=(new Record())->add(['oid'=>$oid,'type'=>'g']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，收货成功！";
                        }else{
                            $result="收货失败！";
                        }
                    }else{
                        $result="用户权限不足，无法收货！";
                    }
                }else{
                    $result="该商品不在待收货列表中，无法收货！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function toreturn(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $tip= $_GET['tip'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='g'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'t']);
                        $rid=(new Record())->add(['oid'=>$oid,'tip'=>$tip,'type'=>'t']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，发起退货申请成功！";
                        }else{
                            $result="发起退货申请失败！";
                        }
                    }else{
                        $result="用户权限不足，无法发起退货申请！";
                    }
                }else{
                    $result="该商品不在待评论列表中，无法发起退货申请！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
    public function comment(){
        $user=$this->navView("user");
        $this->authDoView();
        $oid= $_GET['oid'] ?? null;
        $tip= $_GET['tip'] ?? null;
        $status=4;
        if($oid){
            $order=(new Order())->where(['oid=:oid'],[':oid'=>$oid])->fetch();
            if($order){
                if($order['type']=='g'){
                    if($user['level']!="-"&&$user['level']!="0"){
                        (new Order())->where(['oid=:oid'],[':oid'=>$oid])->update(['type'=>'e']);
                        $rid=(new Record())->add(['oid'=>$oid,'tip'=>$tip,'type'=>'e']);
                        if($rid>0){
                            $status=1;
                            $result="恭喜您，评论成功！";
                        }else{
                            $result="评论失败！";
                        }
                    }else{
                        $result="用户权限不足，无法评论！";
                    }
                }else{
                    $result="该商品不在待评论列表中，无法评论！";
                }
            }else{
                $result="无法找到订单！";
            }
        }else{
            $result="订单编号错误（数据传输错误）！";
        }
        echo '{"status":'.$status.',"result":"'.$result.'"}';
    }
}
