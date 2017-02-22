<?php
class User_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	//（登录页面）判断是否已经绑定登录
	public function judgelogin($data)
	{
		$result=$this->db->select('id')->where($data)->get('user')->row_array();
		if($result){
			return 'yes'; //该用户已绑定登录
		}else{
			return 'no';
		}
	}
	
	//用户登录成功后，把信息写入user表
	public  function insertuserinfo($data)
	{
		 $data=$this->db->insert('user',$data);
		 return  $data;
	}
	
	//通过openid查询user表中相关记录信息（判定是否绑定登录）
	public  function  userinfo($openid)
	{
		$result=$this->db->get_where('user',array('openid'=>$openid))->row_array();
		return $result;
	}
	//通过微信openid删除该用户在user表中的记录
	public function delUserByOpenid($openid){
		$result=$this->db->delete('user',array('openid'=>$openid));
		return $result;
	}

	/**根据userid schoolid查询用户是否存在
	 * @author mengyuan 2017-01-01
	 */
	public function existsUserinfoByUserid($userid, $schoolid)
	{
		$result=$this->db->select('id')->where(array('schoolid'=>$schoolid, 'userid'=>$userid))->get('user')->row_array();
		if($result){
			return true; //该用户已绑定微信
		}else{
			return 'no';
		}
	}
}