<?php
require('Controller.php');
class User extends  Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
   //添加用户信息
	public function userUpdateApi() { 
		//接受提交数据
		$data=$this->input->post('data');
		if(empty($data)) {
		  $this->httpCode(-1,'传递参数为空','');	
		}
		if(empty($data['key']) || !$this->apikey($data['key'])) {
			$this->httpCode(-1,'key校验失败','');
		}
		if(empty($data['userid']) || empty($data['schoolid']) || empty($data['specialname'])) {
			$this->httpCode(-1,'参数缺失','');
		}
		//$userdate = $_POST;
		$userinfo = array(
		   //'userid'=>'campus_'.$userinfo['userid'],
		   'id'=>0,
		   'userid'=>'campus_hd_'.$data['userid'],
		   'name'=>$data['username'],
		   'department'=>12, //暂时写死 智慧课堂的id
		   'mobile'=>$data['mobile'],
		);
	    $this->load->library('qywechat', array('schoolid'=>69)); //学校id暂时写死 
		$result = $this->qywechat->setUserInfo($userinfo);
		if($result['errcode'] != 0) {
			$this->httpCode($result['errcode'],$result['errmsg'],'');
		}
		//写入本地user用户信息表
		$arr=array(
			'username'=>$data['username'],
			'userid'=>$data['userid'],
			'schoolid'=>69,
			'openid'=>$userinfo['userid'],	
			'roleid'=>$data['roleid'],
			'sex'=>$data['sex'],
			'facepic'=>$data['facepic'],
			'birthday'=>$data['birthday'],
			'specialname'=>$data['specialname'],
			'lasttime'=>date('Y-m-d H:i:s'),
		 );
		 $insert_result=$this->user_model->insertuserinfo($arr);
		 if($insert_result){
			$this->httpCode(0,'操作成功','');
		 } else {
			$this->httpCode(-1,'操作失败','');
		 }
	}
	
	public function userDelApi() { 
	   $userinfo = $_POST;
	   var_dump($userinfo);
	   die;
	}
}
