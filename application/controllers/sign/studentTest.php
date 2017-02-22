<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class studentTest extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('class_test_model');
		$this->load->model('class_new_model');
	}
	
	/*随堂测验--获取试题列表
	*传参方式：POST
	*传递参数：(必选)key  classid(SESSION中取值)
	*返回数据：Josn格式
	*/
	public function testList(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				$classid=$_SESSION['classid'];
				//$classid=40;//测试使用
				if(empty($classid)){
					$data=$this->httpCode(204,'您尚未签到或课程已结束','');
					echo json_encode($data);
					exit();
				}
				//获取已完成的测试题列表
				$studentid=$_SESSION['userid'];
				//$studentid=2185;
				$sql_fin="select id,stem,type,addtime  from class_test  where classid=$classid and id in (select questionid from test_result where studentid=$studentid and classid=$classid)";
				$result_a=$this->db->query($sql_fin)->result_array();
				//获取未完成的测试题列表
				$sql_fin="select id,stem,type,addtime  from class_test  where  classid=$classid  and id  not in(select questionid from test_result where studentid=$studentid and classid=$classid)";
				$result_b=$this->db->query($sql_fin)->result_array();
				if(empty($result_b) && empty($result_a)){
					$result=array();
					$data=$this->httpCode(205,'暂没有测试信息',$result);
					echo json_encode($data);
					exit();
				}
				$result_all['done']=$result_a;
				$result_all['nodone']=$result_b;
				$data=$this->httpCode(200,'获取成功',$result_all);
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
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
				//$classid=40;//测试使用
				$studentid=$_SESSION['userid'];
				//$studentid=2185;//测试使用
				$questionid=$this->input->post('questionid');
				if(empty($questionid)){
					$data=$this->httpCode(602,'参数为空','');
					echo  json_encode($data);
					exit();
				}
				if(empty($classid)){
					$data=$this->httpCode(204,'您尚未签到或课程已结束','');
					echo json_encode($data);
					exit();
				}
				$re=$this->db->select('id')->where("questionid=$questionid and studentid=$studentid")->get('test_result')->result_array();
				$result=$this->db->select('id,stem,type,options,answer,addtime')->where("id=$questionid")->get('class_test')->result_array();
				if(empty($re)){
					//未完成测试题
					foreach($result as $k=>$v){
						$result[$k]['options']=unserialize($v['options']);
					}
					$data=$this->httpCode(200,'获取成功',$result);
				}else{
					//已完成测试题
					foreach($result as $k=>$v){
					$qid=$v['id'];
					$te_info=$this->db->select('studentAnswer,addtime')->where("questionid=$qid and studentid=$studentid")->get('test_result')->row_array();
					$result[$k]['answer_info']=$te_info;
					$result[$k]['options']=unserialize($v['options']);
					}
					$data=$this->httpCode(200,'获取成功',$result);
				}
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*随堂测验--学生提交答案
	*传参方式：POST
	*传递参数：(必选)key  ,  questionid , studentAnswer
	*返回数据：Josn格式
	*/
	public function submitAnswer(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$questionid=$this->input->post('questionid');
				$studentAnswer=$this->input->post('studentAnswer');
				if(empty($questionid) || empty($studentAnswer)){
					$data=$this->httpCode(602,'参数为空','');
					echo json_encode($data);
					exit();
				}
				$studentid=$_SESSION['userid'];
				//$studentid=2185;//测试数据
				//通过话题id获取字段信息
				$re=$this->db->select('schoolid,classid,answer')->where("id=$questionid")->get('class_test')->row_array();
				$schoolid=$re['schoolid'];
				$classid=$re['classid'];
				$rightAnswer=$re['answer'];
				//把讨论信息入表
				$data_p=array(
					'schoolid'=>$schoolid,
					'classid'=>$classid,
					'questionid'=>$questionid,
					'studentid'=>$studentid,	
					'studentAnswer'=>$studentAnswer,
					'addtime'=>date('Y-m-d H:i:s',time()),
				);
				$insert_re=$this->db->insert('test_result',$data_p);
				$insert_id=$this->db->insert_id();
				if(empty($insert_id)){
					$data=$this->httpCode(204,'提交试题失败','');
				}else{
					$data_aa['insert_id']=$insert_id;
					$data=$this->httpCode(200,'提交试题成功',$data_aa);
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	}
}