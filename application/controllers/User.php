<?php
require('Controller.php');
class User extends  Controller {
	public $schoolid;
	public function __construct()
	{
		parent::__construct();
		$this->schoolid = 69;
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
   //添加用户信息
	public function userUpdateApi() { 
		//接受提交数据
		//$data=$this->input->post('data');
		$data = $_POST;
		/*$data = array(
		    'id' => 0,
			'key'=>'imkey',
			'userid'=>'10086',
			'username'=>'mengyuan',
			'mobile'=>'15893324158',
			'roleid'=>'4',
			'specialname'=>'软件工程',
			'sex'=>'男',
		);*/
		if(empty($data)) {
		  $this->httpCode(-1,'传递参数为空','');	
		}
		if(empty($data['key']) || !$this->apikey($data['key'])) {
			$this->httpCode(-1,'key校验失败','');
		}
		if(empty($data['userid'])) {
			$this->httpCode(-1,'userid参数缺失','');
		}
		if(empty($data['mobile'])) {
			$this->httpCode(-1,'参数mobile缺失','');
		}
		if(empty($data['realname'])) {
			$this->httpCode(-1,'参数realname缺失','');
		}
		//查询是否已同步过微信
		$ext_userinfo = $this->user_model->existsUserinfoByUserid($data['userid'], $this->schoolid);
		if($ext_userinfo) {
			$data['id'] = $data['userid'];
		} else {
			$data['id'] = 0;
		}
		if($data['roleid'] == 4) { //暂时写死 智慧课堂的id
			$department = 13;
		} elseif($data['roleid'] == 3) {
			$department = 14;
		}
		$userinfo = array(
		   'id'=>$data['id'],
		   'userid'=>'campus_hd_'.$data['userid'],
		   'name'=>$data['realname'],
		   'department'=>$department,
		   'mobile'=>$data['mobile'],
		);
		//同步微信操作
	    $this->load->library('qywechat', array('schoolid'=>69)); //学校id暂时写死
		$result = $this->qywechat->setUserInfo($userinfo);
		if($result['errcode'] != 0) {
			$this->httpCode($result['errcode'],$result['errmsg'],'');
		}
		//更新本地user用户信息表
		$arr=array(
			'username'=>$data['username'],
			'truename'=>$data['realname'],
			'userid'=>$data['userid'],
			'schoolid'=>$this->schoolid,
			'openid'=>$userinfo['userid'],	
			'roleid'=>$data['roleid'],
			'sex'=>$data['sex'],
			'facepic'=>$data['facepic'],
			'birthday'=>$data['birthday'],
			'specialname'=>$data['specialname'],
			'lasttime'=>date('Y-m-d H:i:s'),
		 );
		 if($data['id'] == 0) {
			 $insert_result = $this->user_model->insertuserinfo($arr);
		 } elseif($data['id'] > 0) {
			 $where = array('userid' => $data['id']);
			 $insert_result = $this->db->update('user', $arr, $where);
		 }
		 if($insert_result){
			$this->httpCode(0,'操作成功','');
		 } else {
			$this->httpCode(-1,'操作失败','');
		 }
	}
	//删除用户
	public function userDelApi() { 
	   $userinfo = $_POST;
	   var_dump($userinfo);
	   die;
	}
}
