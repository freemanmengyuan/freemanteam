<?php
class student_sign_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	//��ѧ��ǩ����Ϣ���
	public function signInto($data){
		$result=$this->db->insert('student_sign',$data);
		return $result;
	}
	//ͨ��ָ��������ѯѧ��ǩ����Ϣ
	public function  signInfo($data){
		$result=$this->db->where($data)->get('student_sign')->row_array();
		return $result;
	}
	//��ѯ��ѧ��ǩ�������
	public function checkSignInfo($data){
		$schoolid=$data['schoolid'];
		$classid=$data['classid'];
		$studentid=$data['studentid'];
		$sql="select * from student_sign where schoolid=$schoolid and classid=$classid and studentid like ".'"%,'.$studentid.',%"';
		return $this->db->query($sql)->row_array();
	}
}