<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class discussion extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('discussion_model');
		$this->load->model('student_sign_model');
		$this->load->model('class_new_model');
	}
	/*随堂讨论--老师创建话题
	*传参方式：POST
	*传递参数：(必选)key (必选)schoolid   (必选)teacherid	(必选)讨论话题 topic
	*返回数据：Josn
	*/
	public function create_topic(){
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
				$topic=$this->input->post('topic');
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
						'fid'=>0,						//老师创建话题时，fid为0
						'content'=>$topic,
						'creatid'=>$teacherid,
						'replyid'=>'',
						'addtime'=>date('Y-m-d H:i:s',time()),
					);
					$result=$this->discussion_model->insertInfo($data_p);
					$insert_id=$this->db->insert_id();
					if($insert_id){
						$data=$this->httpCode(200,'创建谈论主题成功','');
					}else{
						$data=$this->httpCode(204,'创建谈论主题失败','');
					}
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	}
	/*获取讨论主题列表
	*传参方式：post
	*传递参数：（必选）key  schoolid  (必选) teacherid  
	*返回格式：Json格式
	*/
	public function getDisInfo(){
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
				//获取讨论话题列表
				$classid=$result['classid'];
				$get_topic=$this->db->select('id,content,addtime')->where("classid=$classid  and fid=0")->get('discussion')->result_array();
				$data=$this->httpCode(200,'获取成功',$get_topic);
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo json_encode($data);
	}
	/*获取讨论内容
	*传参方式：post
	*传递参数: （必选）key   （必选）话题id  topicid
	*返回格式：Json格式
	*/
	public function  getDisContent(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'key为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$topicid=$this->input->post('topicid');
				$dis_content=$this->db->select('replyid,content,addtime')->where("fid=$topicid")->order_by("id", "desc")->get('discussion')->result_array();
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
				$data=$this->httpCode(603,'KEY检验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*添加讨论内容
	*传参方式：post
	*传递参数: （必选）key   （必选） topicid  (必选)  replyid  (必选)  content
	*返回格式：Json格式
	*/
	
	public function  addDisContent(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
			echo json_encode($data);
		}else{
			if($this->apikey($key)){
				//接收参数
				$topicid=$this->input->post('topicid');
				$replyid=$this->input->post('replyid');
				$content=$this->input->post('content');
				if(empty($topicid) || empty($replyid) || empty($content)){
					$data=$this->httpCode(602,'参数为空','');
					echo json_encode($data);
					exit();
				}
				//通过话题id获取字段信息
				$re=$this->db->select('schoolid,classid')->where("id=$topicid")->get('discussion')->row_array();
				$schoolid=$re['schoolid'];
				$classid=$re['classid'];
				
				//把讨论信息入表
				$data_p=array(
					'schoolid'=>$schoolid,
					'classid'=>$classid,
					'fid'=>$topicid,
					'content'=>$content,
					'creatid'=>'',
					'replyid'=>$replyid,
					'addtime'=>date('Y-m-d H:i:s',time()),
				);
				$insert_re=$this->db->insert('discussion',$data_p);
				$insert_id=$this->db->insert_id();
				if(empty($insert_id)){
					$data=$this->httpCode(204,'发布谈论失败','');
				}else{
					$data=$this->httpCode(200,'发布讨论成功','');
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	} 
} 