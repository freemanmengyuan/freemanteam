<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class studentSignInfo extends base{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('class_new_model');
		$this->load->model('student_sign_model');
	}
	/*老师查看学生签到情况
	* 传参方式：POST
	* 传递参数：（必选）schoolid    (必选)teacherid
	* 返回数据：Json格式
	*/
	public function  index(){
		$key=$this->input->post('key');
		if(empty($key)){
			$data=$this->httpCode(601,'KEY为空','');
		}else{
			if($this->apikey($key)){
				$teacherid=$this->input->post('teacherid');
				$schoolid=$this->input->post('schoolid');
				if(empty($teacherid) || empty($schoolid)){
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
						$sql="select truename,sex,specialname from user where userid in( $studentid ) order by userid";
						$result_b=$this->db->query($sql)->result_array();
						$data=$this->httpCode(200,'获取成功',$result_b);
					}else{
						$data=$this->httpCode(204,'您没有正在进行的课程','');
					}
				}
			}else{
				$data=$this->httpCode(603,'key校验失败','');
			}
		}
		echo json_encode($data);
	}
}