<?php
namespace app\models;

use xbphp\base\Model;
use xbphp\db\Db;

class Product extends Model
{
/*
    public $pid;//bigint 20
    public $did;//bigint 20
    public $title;//string 255
    public $content;//string
    public $likenumber;//bigint 20 =0
    public $commentnumber;//bigint 20 =0
    public $active;//bool =1
*/

    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 poem 表
     * @var string
     */
    protected $table = 'product';
    // 数据库主键
    protected $primary = 'pid';

    public function getUserLikeProduct($pager){
        $arrSql="select * from `user_product`";
        $countSql="select count(pid) `count` from `user_product`";
        $this->execute_page($pager,$arrSql,$countSql);
    }

    public function getProductList($pager){
        $arrSql="select * from `product`";
        $countSql="select count(pid) `count` from `product`";
        $this->execute_page($pager,$arrSql,$countSql);
    }

    public function getProductCount(){
        $sql="select count(pid) value from `product` where active=1";
        $sth = Db::pdo()->prepare($sql);
        $sth->execute();
        return $sth->fetch();
    }
    public function getProductKeys($pid): array
    {
        $sql="select `key`.kid,kname from `product_key`,`key` where `product_key`.pid = ? and `product_key`.kid = `key`.kid and  `key`.active=1";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$pid]);
        $sth->execute();
        return $sth->fetchAll();
    }
}
