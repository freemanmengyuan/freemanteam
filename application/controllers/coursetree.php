<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  coursetree extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//获取课程结构信息
	public function listinfo()
	{   
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'key为空','');
			echo json_encode($data);
		}else{
			if($this->apikey($key)){
				//接收参数
				$schoolid=$_SESSION['schoolid'];
				$userid=$_SESSION['userid'];
				$courseid=$this->input->get('courseid');
				if(empty($schoolid) || empty($userid) || empty($courseid)){
					$data=$this->httpCode(611,'参数为空','');
					echo json_encode($data);
				}else{
					// $wip=$this->school_model->getschoolwip($schoolid);
					// $url=$wip['wip'].'/api/course/tree';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/course/tree';
					$data_arr['courseid']=$courseid;
					$datastring=http_build_query($data_arr);
					$data=file_get_contents($url.'?'.$datastring);
					echo $data;
				}
			}else{
				$data=$this->httpCode(501,'参数错误','');
				echo json_encode($data);
			}
		}
	}
	
}