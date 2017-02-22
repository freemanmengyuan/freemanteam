<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');
class studentDiscussion extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('discussion_model');
		$this->load->model('student_sign_model');
		$this->load->model('class_new_model');
	}
	
	/*获取讨论主题列表
	*传参方式：post
	*传递参数：（必选）key 
	*返回格式：Json格式
	*/
	public function getDisInfo(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//获取参数
				$classid=$_SESSION['classid'];
				if(empty($classid)){
					$data=$this->httpCode(304,'您尚未签到或课程已结束','');
					echo json_encode($data);
					exit();
				}
				//获取讨论话题列表
				$get_topic=$this->db->select('id,content,addtime')->where("classid=$classid  and fid=0")->get('discussion')->result_array();
				if(empty($get_topic)){
					$data=$this->httpCode(204,'该课程尚未创建讨论话题','');
				}else{
					$data=$this->httpCode(200,'获取成功',$get_topic);		
				}
			
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
			
		}
		echo json_encode($data);
	}
	
	/*获取讨论内容
	*传参方式：post
	*传递参数: （必选）key   （必选）话题id  topicid     (必选) $moreid
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
				$moreid=$this->input->post('moreid');
				if(empty($topicid) || empty($moreid)){
					$data=$this->httpCode(602,'参数为空','');
				}else{
					$offset=20*($moreid-1);
					$nums=20;
					$sql="select  id,replyid,content,addtime  from discussion  where fid=$topicid  order by id  desc limit $offset,$nums";
					$dis_content=$this->db->query($sql)->result_array();
					if(empty($dis_content)){
						$data=$this->httpCode(204,'没有更多评论信息','');
					}else{
						foreach($dis_content as $k=>$v){
							$replyid=$v['replyid'];
							$re=$this->db->select('truename')->where("userid=$replyid")->get('user')->row_array();
							$truename=$re['truename'];
							$dis_content[$k]['truename']=$truename;
						}
						$data_re['userid']=$_SESSION['userid'];
						$data_re['data']=$dis_content;
						$data=$this->httpCode(200,'获取成功',$data_re);
					}
				}
			}else{
				$data=$this->httpCode(603,'KEY检验失败','');
			}
		}
		echo json_encode($data);
	}
	
	/*添加讨论内容
	*传参方式：post
	*传递参数: （必选）key   （必选） topicid   (必选)  content
	*返回格式：Json格式
	*/
	
	public function  addDisContent(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$topicid=$this->input->post('topicid');
				//$replyid=$this->input->post('replyid');
				$content=$this->input->post('content');
				if(empty($topicid) || empty($content)){
					$data=$this->httpCode(602,'参数为空','');
					echo json_encode($data);
					exit();
				}
				$replyid=$_SESSION['userid'];
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
					$data_aa['insert_id']=$insert_id;
					$data=$this->httpCode(200,'发布讨论成功',$data_aa);
				}
			}else{
				$data=$this->httpCode(603,'KEY校验失败','');
			}
		}
		echo json_encode($data);
	} 
}