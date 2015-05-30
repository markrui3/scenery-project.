<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class IndexController extends Controller {
    public function _initialize(){
        $admin = session('admin');
        if(!$admin){
           $this->display('login');
           exit;
        }
    }

    public function index(){
        $this->display('list');
    }

    public function getScenery(){
        $Dao = M('scenery');
        $result = $Dao->select();
        foreach ($result as &$r) {
            $r['sub'] = '<a href='.U('/Admin/Index/sublist/scenery_id/'.$r['scenery_id']).'>查看</a>&nbsp;&nbsp;<a href='.U('/Admin/Index/subedit/scenery_id/'.$r['scenery_id']).'>添加</a>';
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
            $r['download'] = '<a href='.U('/Admin/Index/download/key/'.$r['md5']).'>下载</a>';
            $r['operation'] = '<a href='.U('/Admin/Index/subedit/sub_scenery_id/'.$r['sub_scenery_id']).'>编辑</a>&nbsp;&nbsp;<a href="#" onclick="del(\''.$r['sub_scenery_id'].'\')">删除</a>';
        }
        echo json_encode($result);
    }

    public function login(){
        $this->display('login');
    }

    public function edit(){
        $param['scenery_id'] = I('param.scenery_id');
        $Dao = M('scenery');
        $r = $Dao->where($param)->find();

        $this->assign('scenery', $r);
        $this->display('edit');
    }

    public function sublist(){
        $param['scenery_id'] = I('param.scenery_id');
        $Dao = M('scenery');
        $r = $Dao->where($param)->find();

        $this->assign('scenery', $r);
        $this->display('sublist');
    }

    public function update(){
        $scenery_id = I('param.scenery_id');
        $param['scenery_name'] = I('param.scenery_name');
        $param['province'] = I('param.province');
        $param['city'] = I('param.city');
        $Dao = M('scenery');
        if($scenery_id == ''){
            $r = $Dao->add($param);
        }else{
            $r = $Dao->where('scenery_id=%s', $scenery_id)->save($param);
        }
        echo json_encode($r);
    }

    public function subupdate(){
        $sub_scenery_id = I('param.sub_scenery_id');
        $param['sub_scenery_name'] = I('param.sub_scenery_name');
        $param['img_url'] = I('param.img_url');
        $param['audio_url'] = DOC_ROOT."/Public/upload/audio/".I('param.audio_url');
        $param['article'] = I('param.article');
        $param['md5'] = MD5($param['audio_url']);
        
        $Dao = M('sub_scenery');
        if($sub_scenery_id == ''){
            $param['scenery_id'] = I('param.scenery_id');
            $r = $Dao->add($param);
        }else{
            $r = $Dao->where('sub_scenery_id=%s', $sub_scenery_id)->save($param);
        }
        echo json_encode($r);
    }

    public function del(){
        $param['scenery_id'] = I('param.scenery_id');
        $Dao = M('scenery');
        $r = $Dao->where($param)->delete();
        $Dao = M('sub_scenery');
        $r = $Dao->where($param)->delete();
        echo json_encode($r);
    }

    public function subdel(){
        $param['sub_scenery_id'] = I('param.sub_scenery_id');
        $Dao = M('sub_scenery');
        $r = $Dao->where($param)->delete();
        echo json_encode($r);
    }

    public function subedit(){
        $Dao = new Model();
        $param['sc.sub_scenery_id'] = I('param.sub_scenery_id');
        if($param['sc.sub_scenery_id'] != ''){
            $r = $Dao->table('scenery s, sub_scenery sc')->where($param)->where('sc.scenery_id=s.scenery_id')->find();
        }else{
            $data['scenery_id'] = I('param.scenery_id');
            $r = $Dao->table('scenery')->where($data)->find();
        }

        $this->assign('scenery', $r);
        $this->display('subEdit');
    }

    function download(){
        $key = I('param.key');

        $Dao = M('Sub_scenery');
        $data['md5'] = $key;
        $r = $Dao->where($data)->find();
        // var_dump($Dao);
        if(!$r){
            die('wrong key');
        }
        header('Accept-Ranges: bytes');
        header('Accept-Length: ' . filesize($r['audio_url']));
        header('Content-Transfer-Encoding: binary');
        header('Content-type: application/octet-stream');

        $filename = $r['sub_scenery_name'] . substr($r['audio_url'], stripos($r['audio_url'], '.'));

        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: application/octet-stream; name=' . $filename);
        
        var_dump(is_readable($r['audio_url']));
        if(is_file($r['audio_url']) && is_readable($r['audio_url'])){
            $file = fopen($r['audio_url'], "r");
            echo fread($file, filesize($r['audio_url']));
            fclose($file);
       }
       exit;
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
        $upload->exts      =     array('mp3', 'wma', 'wav', 'ape', 'flac', 'aac');// 设置附件上传类型
        $upload->rootPath  =     'Public/upload/audio/'; // 设置附件上传根目录
        $upload->savePath  =     I('post.savePath'); // 设置附件上传（子）目录
        $upload->replace   =     true; 

        $upload->saveName = I('post.saveName');
        
        $upload->autoSub  =      false; 
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            echo $upload->getError();
        }else{// 上传成功
            echo json_encode($info['Filedata']['savename']);
        }
    }


    public function logout(){
        session(null);
        redirect(U('/Admin/Index/login'));
    }
}