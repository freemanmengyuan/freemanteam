<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  zjexamteacher extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
	}
	//获取‘我的作业列表’信息
	public function examlist(){	
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'key为空','');
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
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/teacherexampigai/examlist';
					$datastring['userid']=$userid;
					$datastring['ispublish']=$this->input->get('ispublish');					
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					echo $data;
				}
			}else{
				$data=$this->httpCode(601,'key校验失败','');
				echo  json_encode($data);
			}
		}
	}
	//获取‘作业学生列表’信息
	public function studentlist(){
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'key为空','');
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
					$datastring['examid']=$this->input->get('examid');
					$datastring['isdone']=$this->input->get('isdone');
					if(empty($datastring['examid']) || empty($datastring['isdone'])){
						$data=$this->httpCode(611,'参数为空','');
						echo  json_encode($data);
						exit();
					}
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/teacherexampigai/studentlist';					
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					echo $data;
				}
			}else{
				$data=$this->httpCode(601,'key校验失败','');
				echo  json_encode($data);
			}
		}
	}
	//获取‘作业学生详细信息’信息
	public function studentdetail(){
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'key为空','');
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
					$datastring['myexamid']=$this->input->get('myexamid');					
					if(empty($datastring['myexamid'])){
						$data=$this->httpCode(611,'参数为空','');
						echo  json_encode($data);
						exit();
					}
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/teacherexampigai/studentdetail';
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					echo $data;
				}
			}else{
				$data=$this->httpCode(601,'key校验失败','');
				echo  json_encode($data);
			}
		}
	}
	//老师评语
	public function teacherremark(){
		if(!$this->iflogin()){
			$data=$this->httpCode(600,'未登录','');
			echo json_encode($data);
			exit();
		}
		$key=$this->input->get('key');
		if(empty($key)){
			$data=$this->httpCode(611,'key为空','');
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
					$datastring['myexamid']=$this->input->get('myexamid');
					$datastring['remark']=$this->input->get('remark');
					if(empty($datastring['myexamid'])||empty($datastring['remark'])){
						$data=$this->httpCode(611,'参数为空','');
						echo  json_encode($data);
						exit();
					}
					$wip=$_SESSION['wip'];
					$url=$wip.'/api/teacherexampigai/teacherremark';
					$datastring=http_build_query($datastring);
					$data=file_get_contents($url.'?'.$datastring);
					echo $data;
				}
			}else{
				$data=$this->httpCode(601,'key校验失败','');
				echo  json_encode($data);
			}
		}
	}
}