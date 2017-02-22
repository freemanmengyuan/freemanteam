<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class classOver extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('class_new_model');
		$this->load->model('student_sign_model');
	}
	/*课程结束
	* 传参方式:POST
	* 传递参数：(必选)key 	(必选)schoolid 	(必选)teacherid
	* 返回格式：Json
	*/
	public function index(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'key为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				//电脑端登录（传值）
				if($_POST['teacherid']){
					$teacherid=$this->input->post('teacherid');
					$schoolid=$this->input->post('schoolid');
				}
				//手机端登录（从session中获取）
				if($_SESSION['userid']){
					$teacherid=$_SESSION['userid'];
					$schoolid=$_SESSION['schoolid'];
				}
				if(empty($schoolid) || empty($teacherid)){
					$data=$this->httpCode(602,'参数为空','');
				}else{
					$data_p=array(
						'schoolid'=>$schoolid,
						'teacherid'=>$teacherid,
						'status'=>1,
					);
					$result=$this->class_new_model->get_classid($data_p);
					if($result){
						$classid=$result['classid'];
						$end_time=date('Y-m-d H:i:s',time());
						$data_p=array(
							'end_time'=>$end_time,
							'status'=>0,
						);
						$result_a=$this->db->where("schoolid=$schoolid  and classid =$classid")->update('class_new',$data_p);
						$data_pp=array(
							'class_status'=>0,
						);
						$result_b=$this->db->where("classid=$classid")->update('student_sign',$data_pp);
						if($result_a && $result_b){
							$data=$this->httpCode(200,'操作成功','');
						}else{
							$data=$this->httpCode(204,'操作失败','');
						}
					}else{
						$data=$this->httpCode(204,'您没有正在进行的课程','');
					}
				}
			}else{
				$data=$this->httpCode(603,'key验证失败','');
			}
		}
		echo json_encode($data);
	}
}