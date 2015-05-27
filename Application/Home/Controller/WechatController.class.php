<?php
namespace Home\Controller;
use Think\Controller;
use Com\TPWechat;
class WechatController extends Controller {
	private $appid = "wxc835a06d89071c38";
	private $appsecret = "7c1bed222ef426ae5a2a1ef114254c95";
	private $token = "tjuweixin";
	private $encodingaeskey = "";
	private $weObj;

	public function __construct(){
		$options = array(
			'appid' => $this->appid,
			'appsecret' => $this->appsecret,
	        'token'=> $this->token, //填写你设定的key
	        'encodingaeskey'=> $this->encodingaeskey //填写加密用的EncodingAESKey，如接口为明文模式可忽略
	    );
		$this->weObj = new TPWechat($options);
	}

	public function index(){
		
		$this->weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
		$type = $this->$weObj->getRev()->getRevType();
		switch($type) {
		    case Wechat::MSGTYPE_TEXT:
	            $this->$weObj->text("欢迎关注景点微信账号")->reply();
	            exit;
	            break;
		    case Wechat::MSGTYPE_EVENT:
		            break;
		    case Wechat::MSGTYPE_IMAGE:
		            break;
		    default:
	            $this->$weObj->text("help info")->reply();
		}
	}

	public function oauth(){
		$token = $this->weObj->oauth();
		var_dump($token);
	}
}