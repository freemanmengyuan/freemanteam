<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class course extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//获取课程列表
	public function listinfo()
	{
		//判断登陆
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'参数为空','');
			echo json_encode($data);
		}else{
			if($this->apikey($key)){
				//接收参数
				$schoolid=$_SESSION['schoolid'];
				$userid=$_SESSION['userid'];
				if(empty($schoolid) || empty($userid)){	
					$data=$this->httpCode(611,'参数为空','');
					echo json_encode($data);
				}else{
					//$wip=$this->school_model->getschoolwip($schoolid);
					// $url=$wip['wip'].'/api/courselearner/index';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/courselearner/index';
					$datastring['userid']=$userid;
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					//var_dump($data);
					//die;
					echo $data;
				}
			}else{
				$data=$this->httpCode(501,'参数错误','');
				echo json_encode($data);
			}
		}
		
	}
}