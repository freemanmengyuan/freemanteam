<?php
require('base.php');
class Personal extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//获取个人信息
	public function personal_info()
	{   
	    //判断登陆
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		//验证key
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'KEY为空','');
			echo json_encode($data);
		} else {
			if($this->apikey($key)){
				$schoolid=$_SESSION['schoolid'];
				$userid=$_SESSION['userid'];
				$openid=$_SESSION['openid'];
				$wip=$_SESSION['wip'];
				if(empty($userid) || empty($schoolid)){
					$data=$this->httpCode(601,'参数为空','');
					echo json_encode($data);
				}else{
					$wip=$this->school_model->getschoolwip($schoolid);
					//获取学校名称
					$schoolname=$wip['schoolname'];
					$result=$this->user_model->userinfo($openid);
					$result['schoolname']=$schoolname;
					$result['wip']=$wip;
					//var_dump($wip);
					//die;
					$data=$this->httpCode(200,'获取成功',$result);
					echo json_encode($data);
				}
			}else{
				$data=$this->httpCode(611,'参数错误','');
				echo json_encode($data);
			}
		}
		
	}
}