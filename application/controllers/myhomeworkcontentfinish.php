<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  myhomeworkcontentfinish  extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//做作业（已经完成的）
	public function  contentfinish()
	{
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'参数为空','');
			echo  json_encode($data);
		}else{
			if($this->apikey($key)){
				//接收get传值
				$userid=$this->input->get('userid');
				$schoolid=$this->input->get('schoolid');
				$examid=$this->input->get('examid');
				if(empty($userid) || empty($schoolid) || empty($examid)){
					$data=$this->httpCode(611,'参数为空','');
					echo  json_encode($data);					
				}else{
					//$wip=$this->school_model->getschoolwip($schoolid);
					//$url=$wip['wip'].'/api/myhomecontent/finish';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/myhomecontent/finish';
					$datastring['userid']=$userid;
					$datastring['examid']=$examid;
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