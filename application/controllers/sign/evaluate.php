<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class evaluate extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('evaluate_model');
		$this->load->model('class_new_model');
	}
	
	/*添加课堂效果评价（学生对老师的评价）
	*传参方式：POST
	*传递参数：(必选)key , star , content    classid(SESSION获取)
	*返回数据：Josn
	*/
	
	public function addEvaContent(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$star=$this->input->post('star');
				$content=$this->input->post('content');
				$creatid=$_SESSION['userid'];
				//$creatid=2185;		//测试数据
				$classid=$_SESSION['classid'];
				//$classid=77;			//测试数据
				if(empty($classid)){
					$data=$this->httpCode(304,'您未签到或课程已经结束','');
					echo  json_encode($data);
					exit();
				}
				//判断是否已经签到（每节课每人只能评论一次）
				$result_n=$this->db->select('id')->where("classid=$classid  and  creatid=$creatid")->get('class_evaluate')->row_array();
				if(!empty($result_n)){
					$data=$this->httpCode(305,'您已评价,勿重复提交','');
					echo  json_encode($data);
					exit();
				}
				$ree=$this->db->select('schoolid,teacherid')->where("classid=$classid")->get("class_new")->row_array();
				$schoolid=$ree['schoolid'];
				$teacherid=$ree['teacherid'];
				//课堂评论 默认5星，很好
				if(empty($star)){
					$star=5;
				}
				if(empty($content)){
					$content='很好';
				}
				$data_p=array(
					'schoolid'=>$schoolid,
					'classid'=>$classid,
					'teacherid'=>$teacherid,
					'star'=>$star,
					'content'=>$content,
					'creatid'=>$creatid,
					'addtime'=>date('Y-m-d H:i:s',time()),
				);
				$result=$this->evaluate_model->insertInfo($data_p);
				$insert_id=$this->db->insert_id();
				if($insert_id){
					$data=$this->httpCode(200,'评价成功','');
				}else{
					$data=$this->httpCode(204,'评价失败','');
				}
			}else{
				$data=$this->httpCode(603,'key校验失败','');
			}
		}
		echo json_encode($data);
	}
}