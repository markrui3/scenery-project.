<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class LoginController extends Controller {

    public function checklogin(){
        $data['username'] = I('param.username');
        $data['password'] = I('param.password');

        if(!$data['username'] || !$data['password']){
            $result['code'] = 1001;
            $result['msg'] = '信息不全';

            echo json_encode($result);
            exit;
        }

        $Dao = M('admin');
        $r = $Dao->where($data)->find();

        // var_dump($Dao);

        if($r){
            session('admin', $r);

            $result['code'] = 1000;
            $result['msg'] = '登录成功';
        } else {
            $result['code'] = 1002;
            $result['msg'] = '登录失败';
        }

        echo json_encode($result);
    }

    public function changePassword(){
        $data['password'] = I('param.new_password1');
        $admin = session('admin');
        $Dao = M('admin');
        $admin['password'] = $data['password'];
        $Dao->save($admin);
        session('admin',$admin);
        $result['msg'] = '修改成功';

        echo json_encode($result);
    }

    public function checkOldPassword(){
        $old_password = I('param.old_password');
        $admin = session('admin');
        if ($admin['password'] == $old_password){
            $result['msg'] = 'correct';
            echo json_encode($result);
        } else {
            $result['msg'] = 'wrong';
            echo json_encode($result);
        }
    }

}