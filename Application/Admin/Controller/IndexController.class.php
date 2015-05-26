<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('index');
    }

    public function login(){
        $this->display('login');
    }

    public function add(){
        $this->display('add');
    }

    public function edit(){
        $this->display('edit');
    }

    public function sublist(){
        $this->display('sublist');
    }
}