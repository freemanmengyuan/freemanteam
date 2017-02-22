<?php
class student_sign_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	//把学生签到信息入表
	public function signInto($data){
		$result=$this->db->insert('student_sign',$data);
		return $result;
	}
	//通过指定条件查询学生签到信息
	public function  signInfo($data){
		$result=$this->db->where($data)->get('student_sign')->row_array();
		return $result;
	}
	//查询该学生签到的情况
	public function checkSignInfo($data){
		$schoolid=$data['schoolid'];
		$classid=$data['classid'];
		$studentid=$data['studentid'];
		$sql="select * from student_sign where schoolid=$schoolid and classid=$classid and studentid like ".'"%,'.$studentid.',%"';
		return $this->db->query($sql)->row_array();
	}
}