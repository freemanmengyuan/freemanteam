<?php
class ask_question_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
	//把接收信息入表
	public function insertInfo($data){
		$result=$this->db->insert('ask_question',$data);
		return $result;
	}
}