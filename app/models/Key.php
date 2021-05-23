<?php
namespace app\models;

use xbphp\base\Model;
use xbphp\db\Db;

/**
 * 用户Model
 */
class Key extends Model
{
    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    protected $table = 'key';
    protected $primary = 'kid';

    /**
     * 搜索功能，因为Sql父类里面没有现成的like搜索，
     * 所以需要自己写SQL语句，对数据库的操作应该都放
     * 在Model里面，然后提供给Controller直接调用
     * @param $keyword string 查询的关键词
     * @return array 返回的数据
     */
    public function search(string $keyword): array
    {
        $sql = "select * from `$this->table` where `kname` like :kname";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [':kname' => "%$keyword%"]);
        $sth->execute();
        return $sth->fetchAll();
    }
    public function getKidByPid($pid){
        $sql = "select kid from `product_key` where `pid` = ?";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$pid]);
        $sth->execute();
        return $sth->fetchAll();
    }
    public function delByPidKid($pid,$kid): int
    {
        $sql = "delete from `product_key` where `pid` = ? and `kid` = ?";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$pid,$kid]);
        $sth->execute();
        return $sth->rowCount();
    }
    public function addByPidKid($pid,$kid): int
    {
        $sql = "insert into `product_key` (pid,kid) values (?,?)";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$pid,$kid]);
        $sth->execute();
        return $sth->rowCount();
    }
}