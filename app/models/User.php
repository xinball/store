<?php
namespace app\models;

use xbphp\base\Model;
use xbphp\db\Db;
/**
 * 用户Model
 */
class User extends Model{
    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 user 表
     * @var string
     */
    protected $table = 'user';
    // 数据库主键
    protected $primary = 'uid';


    public function checkUser($uname,$password,&$uid): bool
    {
        $sql="select uid,password from `$this->table` where `uname`=:uname";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [':uname' => "$uname"]);
        $sth->execute();
        $user=$sth->fetch();
        $uid=$user['uid'];
        if(md5($password)==$user['password']){
            return true;
        }
        return false;
    }

    public function deleteUser(){

    }


    public function getUserList($pager){
        $arrSql="select * from `user`";
        $countSql="select count(uid) `count` from `user`";
        $this->execute_page($pager,$arrSql,$countSql);
    }

    public function getProductLikedUser($pager){
        $arrSql="select * from `getProductLikedUser`";
        $countSql="select count(1) `count` from `getProductLikedUser`";
        $this->execute_page($pager,$arrSql,$countSql);
    }
    public function getOrder($pager){
        $arrSql="select * from `getOrder`";
        $countSql="select count(1) `count` from `getOrder`";
        $this->execute_page($pager,$arrSql,$countSql);
    }

    public function getUserCount(){
        $sql="select count(uid) value from `user` where level!='-'";
        $sth = Db::pdo()->prepare($sql);
        $sth->execute();
        return $sth->fetch();
    }

    public function addUidPid($uid,$pid): int
    {
        $sql = "insert into `user_product`(uid,pid) values (?,?)";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$uid,$pid]);
        $sth->execute();
        return $sth->rowCount();
    }
    public function delUidPid($uid,$pid): int
    {
        $sql = "delete from `user_product` where uid = ? and pid = ?";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$uid,$pid]);
        $sth->execute();
        return $sth->rowCount();
    }
    public function getUidPid($uid,$pid): int
    {
        $sql = "select count(1) value from `user_product` where uid = ? and pid = ?";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$uid,$pid]);
        $sth->execute();
        return $sth->fetch()['value'];
    }
}
