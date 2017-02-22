<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  myhomeworksubmit extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
	}
	//保存、提交作业
	public function homeworksubmit()
	{
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(611,'KEY为空','');
			echo json_encode($data);
		}else{
			if($this->apikey($key)){
				//接收数据
				$schoolid=$_SESSION['schoolid'];
				$userid=$_SESSION['userid'];
				$data_post=$this->input->post('data');
				$data_post=json_decode($data_post,true);
				$arrPostInfo=array(
					//"userid"=>$data_post['userid'],
					"userid"=>$userid,
					"workid"=>$data_post['workid'],
					"isdone"=>$data_post['isdone'],
					"data"=>json_encode($data_post['data']),
				);
				if(empty($schoolid) || empty($data_post)){
					$data=$this->httpCode(611,'参数为空','');
					echo json_encode($data);
				}else{
					// $wip=$this->school_model->getschoolwip($schoolid);
					// $url=$wip['wip'].'/api/myhomepigai/index';
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/myhomepigai/index';
					$data=$this->curl($url,$arrPostInfo);
					echo $data;
				} 
			}else{
				$data=$this->httpCode(601,'参数错误','');
				echo json_encode($data);
			}
		}
		
	}
}