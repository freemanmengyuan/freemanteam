<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class stuAskQuestion extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ask_question_model');
		$this->load->model('class_new_model');
	}
	
	/*获取问题列表
	*传参方式：post
	*传递参数：（必选）key          classid(SESSION中获取)
	*返回格式：Json格式
	*/
	
	public function getAskList(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$classid=$_SESSION['classid'];
				if(empty($classid)){
					$data=$this->httpCode(304,'您尚未签到或课程已结束','');
					echo json_encode($data);
					exit();
				}
				//增加权限问题（非被指定学生获取不到该提问列表）2016-6-4 09:48:52☆
				$studentid=$_SESSION['userid'];
				$ask_list=$this->db->select('id,content,addtime,answererid')->where("classid=$classid and fid=0  and  answererid=$studentid")->order_by('id','desc')->get('ask_question')->row_array();
				if(empty($ask_list)){
					$data=$this->httpCode(203,'您没有被指定您回答问题','');
				}else{
					$data=$this->httpCode(200,'您被选中回答问题',$ask_list);
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*提交答案
	*传参方式：post
	*传递参数：（必选）key   questionid   answer
	*返回格式：Json格式
	*/
	
	public function submitAnswer(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
			//接收参数
				$questionid=$this->input->post('questionid');
				$answer=$this->input->post('answer');
				if(empty($questionid)){
					$data=$this->httpCode(602,'参数为空','');
				}
				$data_info=$this->db->select('schoolid,classid')->where("id=$questionid")->get('ask_question')->row_array();
				$schoolid=$data_info['schoolid'];
				$classid=$data_info['classid'];
				//把答题信息入表
				$data_p=array(
					'schoolid'=>$schoolid,
					'classid'=>$classid,
					'fid'=>$questionid,
					'content'=>$answer,
					'replyid'=>$_SESSION['userid'],
					'addtime'=>date('Y-m-d H:i:s',time()),
				);
				$ree=$this->ask_question_model->insertInfo($data_p);
				$insert_id=$this->db->insert_id();
				if($insert_id){
					$data=$this->httpCode(200,'操作成功','');
				}
			}else{
				$data=$this->httpCode(603,'校验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*获取被提问者的答题信息
	*传参方式：post
	*传递参数：（必选）key  questionid      
	*返回格式：Json格式
	*/
	
	public function getAnswerInfo(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$studentid=$_SESSION['userid'];
				$questionid=$this->input->post('questionid');
				$dis_content=$this->db->select('replyid,content,addtime')->where("fid=$questionid and replyid=$studentid")->order_by('addtime','desc')->get('ask_question')->result_array();
				if(empty($dis_content)){
					$data=$this->httpCode(204,'暂没有讨论信息','');
				}else{
					foreach($dis_content as $k=>$v){
						$replyid=$v['replyid'];
						$re=$this->db->select('truename')->where("userid=$replyid")->get('user')->row_array();
						$truename=$re['truename'];
						$dis_content[$k]['truename']=$truename;
					}
					$data=$this->httpCode(200,'获取成功',$dis_content);
				}
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo json_encode($data);
	}
}