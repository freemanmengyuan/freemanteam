<?php
require('Controller.php');
class Department extends  Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
   //添加更新组织架构信息
	public function departmentUpdateApi() { 
	    /*if(empty($data['key']) || !$this->apikey($data['key'])) {
			$this->httpCode(-1,'key校验失败','');
		}
		if(empty($data['userid']) || empty($data['schoolid']) || empty($data['specialname'])) {
			$this->httpCode(-1,'参数缺失','');
		}*/
		$depart_info = $_POST;
		$depart_info = array(
		   'name'=>'智慧课堂',
		   'parentid'=>1,
		);
        
	    $this->load->library('qywechat', array('schoolid'=>69));
		$result = $this->qywechat->setPartyInfo($depart_info); 
		var_dump($result);
		die;
	    //接受提交数据
		$data=$this->input->post('data');
		if(empty($data)) {
		  $this->httpCode(-1,'传递参数为空','');	
		}
		//从企业号获取用户信息  openid   wxuserid
		//把用户信息写入user表	
		$arr=array(
			'username'=>$username,
			'userid'=>$userid,
			'schoolid'=>$schoolid,
			'openid'=>$openid,	
			'roleid'=>$roleid,
			'truename'=>$truename,
			'sex'=>$sex,
			'facepic'=>$facepic,
			'birthday'=>$birthday,
			'specialname'=>$specialname,
			'lasttime'=>date('Y-m-d H:i:s'),
		 );
		 $insert_result=$this->user_model->insertuserinfo($arr);
		 if($insert_result){
			 $this->httpCode(0,'操作成功','');
		 } else {
			 $this->httpCode(-1,'操作失败','');
		 }
	}
	//删除组织架构信息
	public function departmentDelApi() { 
	   $userinfo = $_POST;
	   var_dump($userinfo);
	   die;
	}
}
