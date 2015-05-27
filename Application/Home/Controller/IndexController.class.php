<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('index');
    }

    public function detail(){
    	$this->display('detail');
    }

    public function loclist(){
        $scenery_id = I('get.scenery_id');
        $Sub_scenery = M('sub_scenery');
        $sub_scenery_data = $Sub_scenery->where("scenery_id=\"$scenery_id\"")->select();
        $this->assign('sub_scenery_data',$sub_scenery_data);
    	$this->display('list');
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
}