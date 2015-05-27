<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $Dao = M('scenery');
        $result = $Dao->select();
        foreach ($result as &$r) {
            $r['sub_query'] = '<a href="{:U(\'/Admin/Index/sublist/sub_scenery_id/'.$r['scenery_id'].'\')}">查看</a>';
            $r['sub_add'] = '<a href="{:U(\'/Admin/Index/editSub/\')}">添加</a>';
        }
        $this->display('index');
    }

    public function login(){
        $this->display('login');
    }

    public function edit(){
        $this->display('edit');
    }

    public function sublist(){
        $this->display('sublist');
    }

    public function editSub(){
        $this->display('editSub');
    }
}