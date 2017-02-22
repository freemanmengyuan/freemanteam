<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class exitLogin extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
	//解绑微信（openid）
	//执行原理：删除userid表中该用户的信息
	public function exitWeixin(){
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'KEY值为空','');
		}else{
			if($this->apikey($key)){
				//获取参数
				$openid=$_SESSION['openid'];
				if(empty($openid)){
					$data=$this->httpCode(611,'参数为空','');
				}else{
					$result=$this->user_model->delUserByOpenid($openid);
					//清除SESSION(只留openid)2016-5-26 11:10:50
					unset($_SESSION['userid']);
					unset($_SESSION['schoolid']);
					unset($_SESSION['wip']);
					unset($_SESSION['classid']);
					$data=$this->httpCode(200,'已解除绑定','');
				}
			}else{
				$data=$this->httpCode(601,'key校验失败','');
			}
		}
		echo json_encode($data);
	}
}