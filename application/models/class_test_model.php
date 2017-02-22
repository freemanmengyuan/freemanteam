<?php
class class_test_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
	//把接收信息入表
	public function insertInfo($data){
		$result=$this->db->insert('class_test',$data);
		return $result;
	}
}