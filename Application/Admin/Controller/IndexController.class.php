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
            $r['sub'] = '<a href='.U('/Admin/Index/sublist/scenery_id/'.$r['scenery_id']).'>查看</a>&nbsp;&nbsp;<a href='.U('/Admin/Index/subEdit/scenery_id/'.$r['scenery_id']).'>添加</a>';
            $r['operation'] = '<a href='.U('/Admin/Index/edit/scenery_id/'.$r['scenery_id']).'>编辑</a>&nbsp;&nbsp;<a href="#" onclick="del(\''.$r['scenery_id'].'\')">删除</a>';
        }
        echo json_encode($result);
    }

    public function getSubscenery(){
        $param['scenery_id'] = I('param.scenery_id');
        $Dao = M('sub_scenery');
        $result = $Dao->where($param)->select();
        foreach ($result as &$r) {
            $r['img'] = '<img style="width:200px" src='.$r['img_url'].' />';
            $r['download'] = '<a href='.$r['audio_url'].'>下载</a>';
            $r['operation'] = '<a href='.U('/Admin/Index/subedit/sub_scenery_id/'.$r['sub_scenery_id']).'>编辑</a>&nbsp;&nbsp;<a href="#" onclick="del(\''.$r['sub_scenery_id'].'\')">删除</a>';
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
        $param['scenery_id'] = I('param.scenery_id');
        $Dao = M('scenery');
        $r = $Dao->where($param)->find();

        $this->assign('scenery', $r);
        $this->display('sublist');
    }

    public function subEdit(){
        $param['scenery_id'] = I('param.scenery_id');
        $Dao = M('scenery');
        $r = $Dao->where($param)->find();

        $this->assign('scenery', $r);
        $this->display('subEdit');
    }

    //文件上传
    public function uploadImg(){
        $upload = new \Think\Upload();// 实例化上传类
        //$upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     'Public/upload/image/'; // 设置附件上传根目录
        $upload->savePath  =     I('post.savePath'); // 设置附件上传（子）目录
        $upload->replace   =     true; 

        $upload->saveName = I('post.saveName');
        // if($sub_scenery_id != null){
        //     $upload->saveName = $sub_scenery_id;
        // }else{
        //     $upload->saveName = ''.time();
        // }
        
        $upload->autoSub  =      false; 
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            echo $upload->getError();
        }else{// 上传成功
            echo json_encode($info['Filedata']['savename']);
        }
    }

    //文件上传
    public function uploadAudio(){
        $upload = new \Think\Upload();// 实例化上传类
        //$upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     'Public/upload/audio/'; // 设置附件上传根目录
        $upload->savePath  =     I('post.savePath'); // 设置附件上传（子）目录
        $upload->replace   =     true; 

        $goodsid = I('post.goodsid');
        if($goodsid != null){
            $upload->saveName = $goodsid;
        }else{
            $upload->saveName = ''.time();
        }
        
        $upload->autoSub  =      false; 
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            echo $upload->getError();
        }else{// 上传成功
            echo json_encode($info['Filedata']['savename']);
        }
    }
}