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
    	$this->display('Index/list');
    }

    public function searchScenery(){
    	$province = I('get.province');
    	$city = I('get.city');
    	$Scenery = M('scenery');
    	echo json_encode($Scenery->where("province=\"$province\" AND city=\"$city\"")->select());
    }
}