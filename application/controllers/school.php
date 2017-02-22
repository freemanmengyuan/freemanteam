<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class school extends base {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
	}
	public function schoollist()
	{
		//接收参数
		$key=$this->input->get('key');
		
		if(empty($key)){
			$data=$this->httpCode(611,'参数为空','');
		}else{
			if($this->apikey($key)){
				
				$result=$this->school_model->getschoollist();
				if(empty($result)){
					$data=$this->httpCode(404,'数据为空','');
				}else{
					$data=$this->httpCode(200,'成功',$result);	
				}
			}else{
				$data=$this->httpCode(611,'key校验失败','');
			}
		}
		echo json_encode($data);
	}	
}

