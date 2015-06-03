<?php
namespace Home\Controller;
use Think\Controller;
use Com\TPWechat;
use Com\Wechat;
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

		// // Log::Write('type:'.$type, 'INFO');
		// $news = array(
		// 	0 => array(
		// 			'Title'=>'欢迎关注天津大学北洋教育发展基金会',
		// 	  		'Description'=>'',
  // 					'PicUrl'=>'http://www.peiyangedf.com/Public/pic1.jpg',
  // 					'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAxMjM2Mjg3Mg==&mid=204105713&idx=1&sn=99f1ce8e320440ec3961a9657d26f656#rd'
		// 		),
		// 	1 => array(
		// 			'Title'=>'求实会堂 - 捐赠',
		// 	  		'Description'=>'',
  // 					'PicUrl'=>'http://mmbiz.qpic.cn/mmbiz/xVq6XYRutPGn8BPeeuRWQ9ZIcFEb1bjrdShDbkyGFdTI1ib0ZoJibMkP8PpmceQJvCQ2BAwdibiaYbZORANIHqnSxQ/640?tp=webp&wxfrom=5',
  // 					'Url'=>'http://www.peiyangedf.com'
		// 		)
		// 	);

		$eventtype = $this->weObj->getRev()->getRevEvent();
		switch ($eventtype['event']) {
			case Wechat::EVENT_SUBSCRIBE: //关注事件
				// $weObj->text("欢迎关注天津大学北洋教育发展基金会，目前正在进行的捐助项目（点击查看）\n1.<a href='http://mp.weixin.qq.com/s?__biz=MzAxMjM2Mjg3Mg==&mid=203697380&idx=1&sn=e9325105205d74e4e03349c48a84cb1b#rd'>求实会堂</a>")->reply();
				$this->weObj->text('欢迎关注景点介绍微信平台,<a href="http://peiyangedf.com/Public/sc/">点击进入网站</a>')->reply();
				break;
		}

		$type = $this->weObj->getRev()->getRevType();
		switch($type) {
		    case Wechat::MSGTYPE_TEXT:
	            // $this->weObj->text("欢迎关注景点微信账号")->reply();
		    	$receive = $this->weObj->getRev()->getRevContent();
		    	if(is_numeric($receive)){
		    		$Dao = M('Sub_scenery');
					$r = $Dao->where('sub_scenery_id='.$receive)->find();

					if($r){
		    			$this->weObj->music( $r['sub_scenery_name'], '', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$r['audio_url'] )->reply();
					} else {
	            		$this->weObj->text("请检查您输入的编号是否正确")->reply();
					}
		    	} else {
					$this->weObj->text('欢迎关注景点介绍微信平台,<a href="http://peiyangedf.com/Public/sc/">点击进入网站</a>')->reply();
		    	}
	            exit();
	            break;
		    case Wechat::MSGTYPE_EVENT:
		            break;
		    case Wechat::MSGTYPE_IMAGE:
		            break;
		    default:
	            $this->weObj->text("help info")->reply();
		}

		//获取菜单操作:
		$menu = $this->weObj->getMenu();
		//设置菜单
		$newmenu = array(
			"button" => array(
				array("type" =>"view", 'name' => '景点介绍', 'url'=>'http://peiyangedf.com/Public/sc/')
			)
		);

		$result = $this->weObj->createMenu($newmenu);
	}

	public function oauth(){
		$this->weObj->oauth();
	}

	public function sendVideo($id){
		// {"touser":"OPENID","msgtype":"news","news":{...}}
		$openid = session('openid');
		if($openid == ''){
			$result['code'] = 1002;
			$result['msg'] = '请在微信中打开';

			echo json_encode($result);
			exit();
		}
		$id = I('param.id');

		$Dao = M('Sub_scenery');
		$r = $Dao->where('sub_scenery_id='.$id)->find();
		// var_dump($Dao);
		if(!$r){
			$result['code'] = 1003;
			$result['msg'] = '不存在';

			echo json_encode($result);
			exit();
		}

		$data = array(
			'touser' => $openid,
			'msgtype' => 'music',
			'music' => array(
				'title' => 'test', 
				'description' => '1111',
				'musicurl' => $r['audio_url'],
				'hqmusicurl' => $r['audio_url']
			)
		);

		var_dump($data);
		$r1 = $this->weObj->sendCustomMessage($data);

		// var_dump($token);

		if($r1){
			$result['code'] = 1001;
			$result['msg'] = '发送成功';
		} else {
			$result['code'] = 1005;
			$result['msg'] = '发送失败，请在微信回复1后，再到该网页进行操作';
		}

		echo json_encode($result);
	}
}