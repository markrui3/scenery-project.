<?php
namespace Home\Controller;
use Think\Controller;
class SceneryPageController extends Controller {
    public function index(){
		$this->display('SceneryPage/show');
	}
}