<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  myhomeworkfinish extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	
	//获取‘我的作业’已经完成的
	public function listfinish()
	{
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'参数为空','');
			echo  json_encode($data);
		}else{
			if($this->apikey($key)){
				//接收值
				$schoolid=$_SESSION['schoolid'];
				$userid=$_SESSION['userid'];
				if(empty($userid) || empty($schoolid)){
					$data=$this->httpCode(611,'参数为空','');
					echo  json_encode($data);					
				}else{
					//$wip=$this->school_model->getschoolwip($schoolid);
					//$url=$wip['wip'].'/api/myhomework/finish';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/myhomework/finish';
					$datastring['userid']=$userid;
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					echo $data;
				}
			}else{
				$data=$this->httpCode(611,'key校验失败','');
				echo  json_encode($data);
			}
		}
	}
}
	