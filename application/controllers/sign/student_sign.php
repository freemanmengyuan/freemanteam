<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class student_sign extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('student_sign_model');
		$this->load->model('class_new_model');
	}
	//学生签到
	public function index(){
		//获取学生信息，并写入签到表
		$schoolid=$_SESSION['schoolid'];
		$studentid=$_SESSION['userid'];
		$classid=$_GET['classid'];
		$get_schoolid=$_GET['schoolid'];
		//对classid进行判断
		$result_class=$this->db->where("classid=$classid")->get('class_new')->result_array();
		
		if(empty($result_class)){
			echo '没有相关课程信息';
			exit();
		}
		if(count($result_class)>1){
			echo '你有尚未结束的课程，不能在新课程签到';
			exit();
		}
		if(empty($classid) || empty($get_schoolid)){
			echo '参数为空';
			exit();
		}else{
			if($schoolid != $get_schoolid){
				//exit('非法访问，已阻止');
				header('location:/wap/htmlv/index.html?fun=mysign&type=2&title=签到失败&msg=数据丢失,请返回微信菜单首页重新操作');
			}else{
				$data_arr=array(
					'schoolid'=>$schoolid,
					'classid'=>$classid,
					'class_status'=>1,
				);
				$re=$this->student_sign_model->signInfo($data_arr);
				if(empty($re)){
					if($re['class_status']===0){
						echo '课程已经结束不能签到';
					}else{
						//入表信息
						$data_arr=array(
							'schoolid'=>$get_schoolid,
							'classid'=>$classid,
							'studentid'=>','.$studentid.',',
							'signtime'=>date('Y-m-d H:i:s',time()),
							'class_status'=>1,			//课程进行中
						);
						$result=$this->student_sign_model->signInto($data_arr);
						if($result){
						//扫描签到成功，把正在进行的课程id写入SESSION
						$_SESSION['classid']=$classid;
							//echo '签到成功 NO.1';
							header('location:/wap/htmlv/index.html?fun=mysign&type=1&title=签到成功&msg=');
						}else{
							//echo '签到失败，请重试';
							header('location:/wap/htmlv/index.html?fun=mysign&type=0&title=签到失败&msg=信息丢失,请返回重试');
						}
					}
				}else{
					//屏蔽重复签到的情况
					$data_arr=array(
						'schoolid'=>$schoolid,
						'classid'=>$classid,
						'studentid'=>$studentid,
					);
					$data_a=$this->student_sign_model->checkSignInfo($data_arr);
					if($data_a){
						//echo '您已签到';
						//exit();
						header('location:/wap/htmlv/index.html?fun=mysign&type=2&title=您已签到&msg=');
					}else{
						$data=array(
						'studentid'=>','.$studentid.$re['studentid'],		//签到学生id插入顺序调整（累次从前面添加）
						);
						$result_a=$this->db->where("schoolid=$schoolid  and  classid=$classid")->update('student_sign',$data);
						if($result_a){
						//扫描签到成功，把正在进行的课程id写入SESSION
							$_SESSION['classid']=$classid;
							//echo '签到成功';
							header('location:/wap/htmlv/index.html?fun=mysign&type=1&title=签到成功&msg=');
						}else{
							//echo '签到失败，请重试';
							header('location:/wap/htmlv/index.html?fun=mysign&type=0&title=签到失败&msg=信息丢失,请返回重试');
						}
					}
				}
			}
		}
	}
}