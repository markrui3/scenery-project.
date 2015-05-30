<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    private $wechatController;

    private function checkWechat(){
        if(!C('TEST')){
            $this->wechatController = new WechatController();
            $this->wechatController->oauth();

            $openid = session('openid');
            if($openid){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function index(){
        if(!$this->checkWechat()){
            die("请在微信中打开");
        }
        $this->display('index');
    }

    public function detail(){
        if(!$this->checkWechat()){
            die("请在微信中打开");
        }

        $sub_scenery_id = I('get.sub_scenery_id');
        $Sub_scenery = M('sub_scenery');
        $sub_scenery_data= $Sub_scenery->where("sub_scenery_id=\"$sub_scenery_id\"")->select();
        $sub_scenery_detail = $sub_scenery_data[0];
        $this->assign('sub_scenery_detail',$sub_scenery_detail);
    	$this->display('detail');
        // echo $sub_scenery_detail[0]['sub_scenery_id'];
    }

    public function loclist(){
        if(!$this->checkWechat()){
            die("请在微信中打开");
        }

        $scenery_id = I('get.scenery_id');

        $Dao = M('Scenery');
        $scenery = $Dao->where('scenery_id='.$scenery_id)->find();

        
        $Sub_scenery = M('sub_scenery');
        $sub_scenery_data = $Sub_scenery->where("scenery_id=\"$scenery_id\"")->select();
        $this->assign('sub_scenery_data',$sub_scenery_data);
        $this->assign('scenery', $scenery);
    	$this->display('list');
    }

    public function download(){
        $openid = session('openid');
        if($openid){
            $this->display('download');
            exit;
        }
        $key = I('param.key');

        $Dao = M('Sub_scenery');
        $data['md5'] = $key;
        $r = $Dao->where($data)->find();
        // var_dump($Dao);
        if(!$r){
            die('wrong key');
        }

        // if(!file_exists($r['http://mcontent.10086.cn/upload/sst/20130115/600547019983600902000006889063/000019_qUi16C6HnEFe6IW0.mp3']))   {   //检查文件是否存在  
        //     echo   "文件找不到";  
        //     exit;    
        // }
        //redirect($r['audio_url']);
        // $r['sub_scenery_name'] = $r['sub_scenery_name'] . substr($r['audio_url'], stripos($r['audio_url'], '.'));
        // $this->assign('scenery', $r);
        // $this->display('file');

        // We'll be outputting a file
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

    public function searchSceneryByPlace(){
    	$province = I('get.province');
    	$city = I('get.city');
    	$Scenery = M('scenery');
        if ($city == ""){
            echo json_encode($Scenery->where("province=\"$province\"")->select());
        } else {
    	    echo json_encode($Scenery->where("province=\"$province\" AND city=\"$city\"")->select());
        };
    }

    public function searchSceneryByName(){
        $scenery_name = I('get.scenery_name');
        $Scenery = M('scenery');
        if($scenery_name != ''){
            $sql = "select * from scenery where scenery_name like '%$scenery_name%'";
            echo json_encode($Scenery->query($sql));
        }
        
    }

    public function logout(){
        session(null);
    }
}