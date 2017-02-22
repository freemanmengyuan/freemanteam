<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class getEvaContent  extends  base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('evaluate_model');
		$this->load->model('class_new_model');
	}
	/*获取课堂评论内容
	*传参方式：post
	*传递参数：（必选）key  schoolid  teacherid	
	*返回数据:Json格式
	*/
	
	public function index(){
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
				}
				//获取classid
					$data_a=array(
						'schoolid'=>$schoolid,
						'teacherid'=>$teacherid,
						'status'=>1,
					);
					$re=$this->class_new_model->get_classid($data_a);
					if(empty($re)){
						$data=$this->httpCode(604,'您未签到或课程已结束','');
						echo json_encode($data);
						exit();
					}
					$classid=$re['classid'];
					$eva_info=$this->db->select('star,content,creatid,addtime')->where("classid=$classid")->order_by("id", "desc")->get('class_evaluate')->result_array();
					if($eva_info){
						foreach($eva_info as $k=>$v){
							$creatid=$v['creatid'];
							$re=$this->db->select('truename')->where("userid=$creatid")->get('user')->row_array();
							$truename=$re['truename'];
							$eva_info[$k]['truename']=$truename;
						}
						$data=$this->httpCode(200,'获取成功',$eva_info);
					}else{
						$data=$this->httpCode(204,'暂没有相关评论','');
					}
			}else{
				$data=$this->httpCode(603,'KEY验证失败','');
			}
		}
		echo  json_encode($data);
	}
}