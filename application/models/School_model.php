<?php
/**
author: mengyuan 2016-12-18
*/
class School_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
	public function getschoollist()
	{	
		$arr=$this->db->select('zimu')->get('school')->result_array();
		foreach ($arr as $v) {
			$arr_01=$this->db->select('schoolid,schoolname')->where('zimu=',$v['zimu'])->get('school')->result_array()	;
			$data[$v['zimu']]=$arr_01;	
		}
		return $data;	
	}
	//通过shoolid 获取wip,nip地址和学校名字
	public function  getschoolwip($schoolid)
	{
		$data=$this->db->select('wip,schoolname,nip')->where('schoolid',$schoolid)->get('school')->row_array();
		return $data;
	}

	//通过corpid获取学校信息
	public function  getSchoolinfoByCorpid($corpid) {
		$data = array();
		if($corpid){
			 $data=$this->db->select('schoolid,corpid,secret,schoolname,wip,nip')->where('corpid=',$corpid)->get('school')->row_array();
		}
		return $data;
	}
	
	//通过shoolid 获取学校信息
	public function  getSchoolinfoBySchoolid($schoolid) {
		$data = array();
		if($schoolid) {
			$data=$this->db->select('schoolid,corpid,secret,schoolname,wip,nip')->where('schoolid',$schoolid)->get('school')->row_array();
		}
		
		return $data;
	}


}