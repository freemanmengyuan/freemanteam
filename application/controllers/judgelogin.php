<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class judgelogin extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
	//判断是否登录
	public function index()
	{
		//接收参数openid
		$openid=$this->input->get('openid');
		$arr=$this->user_model->userinfo($openid);
		$schoolid=$arr['schoolid'];
		$wip=$this->school_model->getschoolwip($schoolid);
		if(!empty($arr)){
			$result['status']='yes';
			$result['userid']=$arr['userid'];
			$result['schoolid']=$arr['schoolid'];
			$result['roleid']=$arr['roleid'];
			$result['wip']=$wip['wip'];
			//print_r($result);
			$data=$this->httpCode(200,'您的账号已绑定',$result);
		}else{
			$result['status']='no';
			$data=$this->httpCode(504,'请登录后再访问',$result);
		}
		echo json_encode($data);
	}
}
