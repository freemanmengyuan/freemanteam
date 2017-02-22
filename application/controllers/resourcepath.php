<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  resourcepath extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//获取课程资源路径
	public function  path()
	{   
		//判断登陆
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
				$totalpage=$this->input->get('totalpage');
				//素材ID
				$masterid=$this->input->get('masterid');
				if(empty($schoolid) || empty($userid) || empty($masterid)){
					$data=$this->httpCode(611,'参数为空','');
					echo json_encode($data);
				}else{
					// $wip=$this->school_model->getschoolwip($schoolid);
					// $url=$wip['wip'].'/api/course/courseresourcephoto';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/course/courseresourcephoto';
					$data_arr['id']=$masterid;
					$data_arr['totalpage']=$totalpage;
					$datastring=http_build_query($data_arr);
					$data=file_get_contents($url.'?'.$datastring);
				   echo json_encode($arr);
				}
			}else{
				$data=$this->httpCode(501,'参数错误','');
				echo json_encode($data);
			}
		}
	}
	
}