<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  myhomeworkcontent extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//做作业（未开始的）
	public function  contentlist()
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
				$workid=$this->input->get('workid');
				if(empty($userid) || empty($schoolid) || empty($workid)){
					$data=$this->httpCode(611,'参数为空','');
					echo  json_encode($data);					
				}else{
					//$wip=$this->school_model->getschoolwip($schoolid);
					//$url=$wip['wip'].'/api/myhomecontent/index';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/myhomecontent/index';
					$datastring['userid']=$userid;
					$datastring['workid']=$workid;
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