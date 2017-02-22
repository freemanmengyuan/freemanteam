<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class personal extends base{
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
		}else{
			if($this->apikey($key)){
				// $userid=$this->input->get('userid');
				// $schoolid=$this->input->get('schoolid');
				$schoolid=$_SESSION['schoolid'];
				$userid=$_SESSION['userid'];
				if(empty($userid) || empty($schoolid)){
					$data=$this->httpCode(601,'参数为空','');
					echo json_encode($data);
				}else{
					$wip=$this->school_model->getschoolwip($schoolid);
					//获取学校名称
					$schoolname=$wip['schoolname'];
					$url=$wip['wip'].'/api/user/index';
					$datastring['userid']=$userid;
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					$data_arr=json_decode($data,true);
					$data_arr['data']['schoolname']=$schoolname;
					echo json_encode($data_arr);
				}
			}else{
				$data=$this->httpCode(611,'参数错误','');
				echo json_encode($data);
			}
		}
		
	}
}