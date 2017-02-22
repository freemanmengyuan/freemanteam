<?php
class class_new_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
	//将开课信息如表
	public function insertClassNew($data){
		$result=$this->db->insert('class_new',$data);
		return $result;
	}
	//查询最新开课信息
	//$sql="select classid  from class_new where schoolid=$schoolid  and  teacherid=$teacherid order by created_time  desc";
    public function getClassInfo($data){
		$result=$this->db->select('classid')->where($data)->order_by('created_time','desc')->limit(1)->get('class_new')->row_array();
		return $result;
	}
	//通过指定条件，查找该课堂信息
	public function classInfo($data){
		$result=$this->db->where($data)->get('class_new')->row_array();
		return  $result;
	}
	//通过schoolid和teacherid 获取正在开课的classid
	public function get_classid($data){
		$result=$this->db->select('classid')->where($data)->get('class_new')->row_array();
		return $result;
	}
}