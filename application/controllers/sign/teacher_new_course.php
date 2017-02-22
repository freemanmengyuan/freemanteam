<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  teacher_new_course  extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('school_model');
		$this->load->model('user_model');
		$this->load->model('class_new_model');
	}
	//把开课信息入表
	public function  index(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收数据
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
				if(empty($teacherid) || empty($schoolid)){
					$data=$this->httpCode(602,'参数为空','');
				}else{
					//判断该老师是否有尚未结束的课程（还在开课中...）
					$result=$this->db->select('classid,created_time')->where("schoolid=$schoolid  and  teacherid = $teacherid and status=1")->order_by('classid','desc')->get('class_new')->row_array();
					if(empty($result)){
						//准备写入class_new表中的数据
						$status=1;//开课成功（开课中，并未结束）
						$created_time=date('Y-m-d H:i:s',time());
						$end_time='';//暂无结课时间数据 date()
						$data_arr=array(
							'teacherid'=>$teacherid,
							'schoolid'=>$schoolid,
							'status'=>$status,
							'end_time'=>$end_time,
							'created_time'=>$created_time,
						);
						$result_a=$this->class_new_model->insertClassNew($data_arr);  
						$insert_id=$this->db->insert_id();
						if($insert_id){
							$arr_p['schoolid']=$schoolid;
							$arr_p['classid']=$insert_id;
							$arr_p['created_time']=$created_time;
							$data=$this->httpCode(200,'开课成功',$arr_p);
						}else{
							$data=$this->httpCOde(204,'开课失败','');
						}
					}else{
						$arr_p['schoolid']=$schoolid;
						$arr_p['classid']=$result['classid'];
						$arr_p['created_time']=$result['created_time'];
						$data=$this->httpCode(200,'您有正在进行中的课程，暂不能开课',$arr_p);	
					}
				}
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo json_encode($data);
	}
}