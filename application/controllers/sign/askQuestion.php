<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class askQuestion extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ask_question_model');
		$this->load->model('class_new_model');
	}
	
	/*随机提问--老师创建问题
	*传参方式：POST
	*传递参数：(必选)key (必选)schoolid   (必选)teacherid	(必选)问题  question
	*返回数据：Josn
	*/
	
	public function createQuestion(){
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
				$question=$this->input->post('question');
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
					//把创建的话题信息入表discussion
					$data_p=array(
						'schoolid'=>$schoolid,
						'classid'=>$classid,
						'fid'=>0,						//老师创建问题时，fid为0
						'content'=>$question,
						'creatid'=>$teacherid,
						'replyid'=>'',
						'addtime'=>date('Y-m-d H:i:s',time()),
					);
					$result=$this->ask_question_model->insertInfo($data_p);
					$insert_id=$this->db->insert_id();
					if($insert_id){
						$data_arr['questionid']=$insert_id;
						$data=$this->httpCode(200,'创建问题成功',$data_arr);
					}else{
						$data=$this->httpCode(204,'创建问题失败','');
					}
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*获取问题列表
	*传参方式：post
	*传递参数：（必选）key   schoolid   teacherid
	*返回格式：Json格式
	*/
	
	public function getAskList(){
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
				}else{
					$data_a=array(
						'schoolid'=>$schoolid,
						'teacherid'=>$teacherid,
						'status'=>1,
					);
					$re=$this->class_new_model->get_classid($data_a);
					if(empty($re)){
						$data=$this->httpCode(601,'您尚未签到或课程已结束','');
						echo json_encode($data);
						exit();
					}
					$classid=$re['classid'];
					$ask_list=$this->db->select('id,content,addtime,answererid')->where("classid=$classid and fid=0")->order_by('id','desc')->get('ask_question')->result_array();
					foreach($ask_list as $k=>$v){
						$answererid=$v['answererid'];
						$re=$this->db->select('truename,sex,specialname')->where("userid=$answererid")->get('user')->row_array();
						$ask_list[$k]['truename']=$re['truename'];
						$ask_list[$k]['sex']=$re['sex'];
						$ask_list[$k]['specialname']=$re['specialname'];
					}
					$data=$this->httpCode(200,'获取列表成功',$ask_list);
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*随机提问
	*传参方式：post
	*传递参数：（必选）key   schoolid  teacherid  questionid
	*返回格式：Json格式
	*/
	
	public function randomAsk(){
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
				$questionid=$this->input->post('questionid');
				if(empty($teacherid) || empty($schoolid) || empty($questionid)){
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
						$result_a=$this->db->select('studentid')->where("schoolid=$schoolid and classid=$classid")->get('student_sign')->row_array();
						if(empty($result_a)){
							$data=$this->httpCode(304,'暂没有签到信息','');
							echo json_encode($data);
							exit();
						}
						$studentid_a=$result_a['studentid'];
						$studentid=trim($studentid_a,',');
						$studentid_arr=explode(',',$studentid);
						//$sql="select truename,sex,specialname from user where userid in( $studentid )";
						//$result_b=$this->db->query($sql)->result_array();
						//获取数组中的随机键名
						$num=array_rand($studentid_arr,1);	
						$asked_id=$studentid_arr[$num];
						$student_info=$this->db->select('truename,sex,specialname')->where("userid=$asked_id")->get('user')->row_array();
						if(empty($student_info)){
							$data=$this->httpCode(304,'暂没有该学生信息','');
							echo json_encode($data);
							exit();
						}
						//把被提问学生的id更新到ask_question表中的answererid字段
						$data_a=array(
							'answererid'=>$asked_id,
						);
						$insert_re=$this->db->where("id=$questionid")->update('ask_question',$data_a);
						$rows=$this->db->affected_rows();
						$data=$this->httpCode(200,'发起提问成功',$student_info);
					}else{
						$data=$this->httpCode(204,'您没有正在进行的课程','');
					}
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
				$questionid=$this->input->post('questionid');
				$dis_content=$this->db->select('replyid,content,addtime')->where("fid=$questionid")->order_by("id", "desc")->get('ask_question')->result_array();
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