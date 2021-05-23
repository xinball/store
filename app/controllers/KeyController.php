<?php
namespace app\controllers;

use xbphp\base\Controller;
use app\models\Key;

class KeyController extends Controller
{
    public function index()
    {
        $this->authAdminView();
        $keyword = empty($_GET['keyword']) ?  '':$_GET['keyword'];
        if ($keyword) {
            $items = (new Key())->search($keyword);
        } else {
            $items = (new Key)->where()->fetchAll();
        }

        $this->assign('TITLE', '全部关键词');
        $this->assign('keyword', $keyword);
        $this->assign('keys', $items);
        $this->render();
    }

    // 查看单条记录详情
    public function detail($kid)
    {
        $this->authAdminView();
        // 通过?占位符传入$id参数
        $item = (new Key())->where(["kid = ?"], [$kid])->fetch();

        $this->assign('TITLE', '关键词详情');
        $this->assign('key', $item);
        $this->render();
    }

    // 添加记录，测试框架DB记录创建（Create）
    public function add()
    {
        $this->authAdminView();
        $data['kname'] = $_POST['kname'];
        $count = (new Key())->add($data);

        $this->assign('TITLE', '添加关键词成功');
        $this->assign('count', $count);
        $this->render();
    }

    public function manage($kid = 0)
    {
        $this->authAdminView();
        $item = array();
        if ($kid) {
            // 通过名称占位符传入参数
            $item = (new Key())->where(["kid = :kid"], [':kid' => $kid])->fetch();
        }

        $this->assign('TITLE', '管理关键词');
        $this->assign('key', $item);
        $this->render();
    }

    public function update()
    {
        $this->authAdminView();
        $data = array('kid' => $_POST['kid'], 'kname' => $_POST['kname']);
        $count = (new Key())->where(['kid = :kid'], [':kid' => $data['kid']])->update($data);

        $this->assign('TITLE', '修改成功');
        $this->assign('count', $count);
        $this->render();
    }

    public function delete($kid = null)
    {
        $this->authAdminView();
        $count = (new Key())->where(['kid = :kid'], [':kid' => $kid])->update(['active'=>'0']);
        $this->assign('TITLE', '删除成功');
        $this->assign('count', $count);
        $this->render();
    }
    public function recover($kid = null)
    {
        $this->authAdminView();
        $count = (new Key())->where(['kid = :kid'], [':kid' => $kid])->update(['active'=>'1']);
        $this->assign('TITLE', '恢复成功');
        $this->assign('count', $count);
        $this->render();
    }
}