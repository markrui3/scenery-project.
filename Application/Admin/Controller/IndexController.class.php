<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        
        $this->display('list');
    }

    public function getScenery(){
        $Dao = M('scenery');
        $result = $Dao->select();
        foreach ($result as &$r) {
            $r['sub'] = '<a href='.U('/Admin/Index/sublist/scenery_id/'.$r['scenery_id']).'>查看</a><a href='.U('/Admin/Index/editSub/').'>添加</a>';
            $r['operation'] = '<a href="{:U(\'/Admin/Index/edit/\')}">编辑</a><a href="#" onclick="del(\'$r[\'scenery_id\']\')">删除</a>';
        }
        echo json_encode($result);
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

    public function subEdit(){
        $this->display('subEdit');
    }
}