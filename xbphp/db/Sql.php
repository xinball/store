<?php
namespace xbphp\db;

use PDOStatement;

class Sql
{
    // 数据库表名
    protected $table;

    // 数据库主键
    protected $primary;

    // WHERE和ORDER拼装后的条件
    private $filter = '';

    // Pdo bindParam()绑定的参数集合
    private $param = array();

    /**
     * 查询条件拼接，使用方式：
     *
     * $this->where(['id = 1','and title="Web"', ...])->fetch();
     * 为防止注入，建议通过$param方式传入参数：
     * $this->where(['id = :id'], [':id' => $id])->fetch();
     *
     * @param array $where 条件
     * @param array $param 参数
     * @return $this 当前对象
     */
    public function where($where = array(), $param = array()): Sql
    {
        if ($where) {
            $this->filter .= ' WHERE ';
            $this->filter .= implode(' and ', $where);

            $this->param = $param;
        }
        return $this;
    }

    /**
     * 拼装排序条件，使用方式：
     *
     * $this->order(['id DESC', 'title ASC', ...])->fetch();
     *
     * @param array $order 排序条件
     * @return $this
     */
    public function order($order = array()): Sql
    {
        if($order) {
            $this->filter .= ' ORDER BY ';
            $this->filter .= implode(',', $order);
        }

        return $this;
    }

    // 查询所有
    public function fetchAll(): array
    {
        $sql = sprintf("select * from `%s` %s", $this->table, $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();
        return $sth->fetchAll();
    }

    // 查询一条
    public function fetch()
    {
        $sql = sprintf("select * from `%s` %s", $this->table, $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();
        return $sth->fetch();
    }

    // 根据条件 (id) 删除
    public function delete($id): int
    {
        $sql = sprintf("delete from `%s` where `%s` = :%s", $this->table, $this->primary, $this->primary);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$this->primary => $id]);
        $sth->execute();
        return $sth->rowCount();
    }

    // 新增数据
    public function add($data)
    {
        $sql = sprintf("insert into `%s` %s", $this->table, $this->formatInsert($data));
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();
        return Db::pdo()->lastInsertId();
    }

    // 修改数据
    public function update($data): int
    {
        $sql = sprintf("update `%s` set %s %s", $this->table, $this->formatUpdate($data), $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();
        return $sth->rowCount();
    }

    // 修改数据
    public function execute_page($pager,$arrSql,$countSql,$data=null)
    {
        $limit=" limit ".($pager->pageNow-1)*$pager->pageSize.",".$pager->pageSize;
        if($data){
            $sthArr = Db::pdo()->prepare($arrSql.$limit);
            $sthArr = $this->formatParam($sthArr,$data);
            $sthArr->execute();
            $pager->arr=$sthArr->fetchAll();

            $sthCount = Db::pdo()->prepare($countSql);
            $sthCount = $this->formatParam($sthCount,$data);
        }else{
            $sthArr = Db::pdo()->prepare($arrSql.$this->filter.$limit);
            $sthArr = $this->formatParam($sthArr,$this->param);
            $sthArr->execute();
            $pager->arr=$sthArr->fetchAll();
            //echo $arrSql.$this->filter.$limit;
            //print_r($this->param);
            $sthCount = Db::pdo()->prepare($countSql.$this->filter);
            $sthCount = $this->formatParam($sthCount,$this->param);
        }
        $sthCount->execute();
        $pager->rowCount=$sthCount->fetch()['count'];
        $pager->pageCount=ceil($pager->rowCount/$pager->pageSize);
    }

    /**
     * 占位符绑定具体的变量值
     * @param PDOStatement $sth 要绑定的PDOStatement对象
     * @param array $params 参数，有三种类型：
     * 1）如果SQL语句用问号?占位符，那么$params应该为
     *    [$a, $b, $c]
     * 2）如果SQL语句用冒号:占位符，那么$params应该为
     *    ['a' => $a, 'b' => $b, 'c' => $c]
     *    或者
     *    [':a' => $a, ':b' => $b, ':c' => $c]
     *
     * @return PDOStatement
     */
    public function formatParam(PDOStatement $sth, $params = array()): PDOStatement
    {
        foreach ($params as $param => &$value) {
            $param = is_int($param) ? $param + 1 : ':' . ltrim($param, ':');
            $sth->bindParam($param, $value);
        }

        return $sth;
    }

    // 将数组转换成插入格式的sql语句
    private function formatInsert($data): string
    {
        $fields = array();
        $names = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $names[] = sprintf(":%s", $key);
        }

        $field = implode(',', $fields);
        $name = implode(',', $names);

        return sprintf("(%s) values (%s)", $field, $name);
    }

    // 将数组转换成更新格式的sql语句
    private function formatUpdate($data): string
    {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = :%s", $key, $key);
        }

        return implode(',', $fields);
    }
}