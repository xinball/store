<?php

namespace app\models;

use xbphp\base\Model;
use xbphp\db\Db;

class Order extends Model
{
/*
    public $cid;//bigint 20
    public $user;//User
    public $poem;//Product
    public $public;//bool =1
    public $title;//string 255
    public $content;//string
    public $sendtime;//DateTime
    public $altertime;//DateTime
*/

    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 order 表
     * @var string
     */
    protected $table = 'order';
    // 数据库主键
    protected $primary = 'oid';

    public function getProductComment($pid){
        $sql="select * from `getOrder` where `pid` = ? and `type` = `e` and `rtype` = `e`";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$pid]);
        $sth->execute();
        return $sth->fetchAll();
    }
    public function getOrder($oid){
        $sql="select * from `getOrder` where `oid` = ?";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$oid]);
        $sth->execute();
        return $sth->fetchAll();
    }
    public function getCommentList($pager){
        $arrSql="select * from `getOrder`";
        $countSql="select count(rid) `count` from `getOrder`";
        $this->execute_page($pager,$arrSql,$countSql);
    }
}