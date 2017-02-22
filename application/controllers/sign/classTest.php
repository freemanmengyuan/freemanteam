<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class classTest extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('class_test_model');
		$this->load->model('class_new_model');
	}
	
	/*随堂测验--老师创建测试题目
	*传参方式：POST
	*传递参数：(必选)key (必选)schoolid   (必选)teacherid	(必选)题目类型 type      题干stem 选项options  答案 answer 
	*返回数据：Josn格式
	*/
	public function createTest(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				//电脑端登录（传值）
				if($_POST['teacherid']){
					$teacherid=$this->input->post('teacherid');
					$schoolid=$this->input->post('schoolid');
					$options=$this->input->post('options');
				}
				//手机端登录（从session中获取）
				if($_SESSION['userid']){
					$teacherid=$_SESSION['userid'];
					$schoolid=$_SESSION['schoolid'];
					$options=$this->input->post('options');
					$options=serialize($options);
				}
				$type=$this->input->post('type');
				$stem=$this->input->post('stem');
				$answer=$this->input->post('answer');
				if(empty($schoolid) || empty($teacherid)){
					$data=$this->httpCode(602,'参数为空','');
				}else{
					//获取classid
					$data_a=array(
						'schoolid'=>$schoolid,
						'teacherid'=>$teacherid,
						'status'=>1,
					);
					$re=$this->class_new_model->get_classid($data_a);
					if(empty($re)){
						$data=$this->httpCode(601,'没有正在进行的相关课程','');
						echo json_encode($data);
						exit();
					}
					$classid=$re['classid'];
					//把创建的话题信息入表class_test
					$data_p=array(
						'schoolid'=>$schoolid,
						'classid'=>$classid,
						'teacherid'=>$teacherid,
						'type'=>$type,
						'stem'=>$stem,
						'options'=>$options,		
						'answer'=>$answer,
						'addtime'=>date('Y-m-d H:i:s',time()),
					);
					$result=$this->class_test_model->insertInfo($data_p);
					$insert_id=$this->db->insert_id();
					if($insert_id){
						$data=$this->httpCode(200,'创建测试题成功','');
					}else{
						$data=$this->httpCode(204,'创建测试题失败','');
					}
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*随堂测验--老师获取试题列表
	*传参方式：POST
	*传递参数：(必选)key   schoolid    teacherid 
	*返回数据：Josn格式
	*/
	public function getTestList(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
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
					echo  json_encode($data);
					exit();
				}
				$data_p=array(
						'schoolid'=>$schoolid,
						'teacherid'=>$teacherid,
						'status'=>1,
				);
				$result=$this->class_new_model->get_classid($data_p);
				if(empty($result)){
					$data=$this->httpCode(204,'没有正在进行中的相关课程','');
					echo json_encode($data);
					exit();
				}
				//获取测试题列表
				$classid=$result['classid'];
				$get_list=$this->db->select('id,stem,options,answer,addtime')->where("classid=$classid")->get('class_test')->result_array();
				foreach($get_list as $k=>$v){
					$get_list[$k]['options']=unserialize($v['options']);
				}
				$data=$this->httpCode(200,'获取成功',$get_list);
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*随堂测验--老师获取答题信息
	*传参方式：POST
	*传递参数：(必选)key   questionid 
	*返回数据：Josn格式
	*/
	public function testAnsInfo(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$questionid=$this->input->post('questionid');
				if(empty($questionid)){
					$data=$this->httpCode(602,'参数为空','');
					echo json_encode($data);
					exit();
				}
				$result_a=$this->db->select('studentid,studentAnswer,addtime')->where("questionid=$questionid")->get('test_result')->result_array();
				if(empty($result_a)){
					$data=$this->httpCode(504,'该题暂没有答题信息','');
				}else{
					foreach($result_a as $k=>$v){
						$answererid=$v['studentid'];
						$re=$this->db->select('truename')->where("userid=$answererid")->get('user')->row_array();
						$truename=$re['truename'];
						$result_a[$k]['truename']=$truename;
					}
					$data=$this->httpCode(200,'获取成功',$result_a);
				}
			}else{
				$data=$this->httpCode(603,'key验证失败','');
			}
		}
		echo json_encode($data);
	}
	/*随堂测验--获取试题(详情)
	*传参方式：POST
	*传递参数：(必选)key  questionid         classid(SESSION中取值)
	*返回数据：Josn格式
	*/
	public function testInfo(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$classid=$_SESSION['classid'];
				$questionid=$this->input->post('questionid');
				if(empty(questionid)){
					$data=$this->httpCode(602,'参数为空','');
					echo  json_encode($data);
					exit();
				}
				if(empty($classid)){
					$data=$this->httpCode(204,'您尚未开设新的课程或课程已结束','');
					echo json_encode($data);
					exit();
				}
				$result=$this->db->select('id,stem,type,options,answer,addtime')->where("id=$questionid")->get('class_test')->result_array();
				foreach($result as $k=>$v){
					$result[$k]['options']=unserialize($v['options']);
				}
				$data=$this->httpCode(200,'获取成功',$result);
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo json_encode($data);
	}
}