<?php
require('Controller.php');
class User extends  Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
   //用户登录
	public function userlogin(){ 
		//校验key
		$key=$this->input->post('key');
	//print_r($key);exit();
		if(empty($key)){
			$data=$this->httpCode(611,'参数为空','');
		}else{
			if($this->apikey($key)){
				//接收参数
				$username=$this->input->post('username');
				$password=$this->input->post('password');
				$schoolid=$this->input->post('schoolid');
				//$openid  =$this->input->post('openid');
				$openid=$_SESSION['openid'];
				//判断post接收的数据
				if(empty($username) || empty($password) || empty($schoolid) || empty($openid)){
					$data=$this->httpCode(611,'用户名、密码、学校均不能为空','');
				}else{
					//判断是否已经绑定登录
					$string='openid="'.$openid.'" or ( username="'.$username.'" and schoolid='.$schoolid.')';
					$result_a=$this->user_model->judgelogin($string);
					if($result_a=='yes'){
						$data=$this->httpCode(304,'您的账号已绑定其他设备，请解绑后再登录','');
					}else{
						$wip=$this->school_model->getschoolwip($schoolid);
						//2016-6-15 10:30:55  如果没有该校的外网地址，则提示
						$school_wip=$wip['wip'];
						if(empty($school_wip)){
							$data=$this->httpCode(404,'没有该校的外网信息,暂无法提供服务','');
							echo json_encode($data);
							exit();	
						}
						$url=$wip['wip'].'/api/login/index';
						$datastring['username']=$username;                
						$datastring['password']=$password;        
						$datastring=http_build_query($datastring);
						$data_user=$this->curl($url,$datastring);
						$data_user_arr=json_decode($data_user,true);
						//判断数据
						if(!is_array($data_user_arr)){
							$data=$this->httpCode(505,'系统繁忙','');
						}else{
							//判断登录错误信息
							$code=$data_user_arr['code'];
							if($code==300){
								$data=$this->httpCode(300,'用户名不能为空','');
								echo json_encode($data);
								die();
							}
							if($code==400){
								$data=$this->httpCode(400,'密码不能为空','');
								echo json_encode($data);
								die();
							}
							if($code==500){
								$data=$this->httpCode(500,'用户名不存在','');
								echo json_encode($data);
								die();
							}	
							if($code==600){
								$data=$this->httpCode(644,'密码错误','');
								echo json_encode($data);
								die();
							}
							if($code==100){
								$userid=$data_user_arr['data']['id'];
								$roleid=$data_user_arr['data']['roleid'];
								$result['userid']=$userid;
								$result['schoolid']=$schoolid;
								$result['roleid']=$roleid;
								$result['wip']=$wip['wip'];
								$result['nip']=$wip['nip'];
								
								//2016-5-23 16:29:17 通过userid 获取个人详细信息
								//$wip=$this->school_model->getschoolwip($schoolid);
								//获取学校名称
								$schoolname=$wip['schoolname'];
								$url_a=$wip['wip'].'/api/user/index';
								$datastring_a['userid']=$data_user_arr['data']['id'];		//userid
								$datastring_aa=http_build_query($datastring_a);
								$data_info=file_get_contents($url_a.'?'.$datastring_aa);
								$data_arr=json_decode($data_info,true);
								//判断登录者身份2016-6-1 16:39:21
								if($data_arr['data']['roleid'] !=4 && $data_arr['data']['roleid'] !=6 ){
									$data=$this->httpCode(304,'请以学习者账号登录该平台','');
									echo  json_encode($data);
									exit();
								}
								$truename=$data_arr['data']['realname'];
								$sex=$data_arr['data']['male'];
								if(empty($data_arr['data']['logo'])){
									$facepic='';
								}else{
									$facepic=$wip['wip'].$data_arr['data']['logo'];
								}
								$birthday=$data_arr['data']['birthday'];
								$specialname=$data_arr['data']['specialname'];
								//把用户信息写入user表	
								$arr=array(
									'username'=>$username,
									'userid'=>$userid,
									'schoolid'=>$schoolid,
									'openid'=>$openid,	
									'roleid'=>$roleid,
									'truename'=>$truename,
									'sex'=>$sex,
									'facepic'=>$facepic,
									'birthday'=>$birthday,
									'specialname'=>$specialname,
									'lasttime'=>date('Y-m-d H:i:s'),
								 );
								 $insert_result=$this->user_model->insertuserinfo($arr);
								 if($insert_result){
									$result['status']=1;
									$data=$this->httpCode(208,'登录并绑定成功',$result);
									$_SESSION['userid']=$userid;
									$_SESSION['schoolid']=$schoolid;
									$_SESSION['wip']=$wip['wip'];
									//$_SESSION['classid']=40;  //电脑端测试使用
									//如果学生意外推出微信，再次登陆依然将尚未结束的课程classid写入SESSION中2016-5-26 11:49:50
									if($data_arr['data']['roleid']==4 || $data_arr['data']['roleid']==6 ){
										$sql="select classid from student_sign where  schoolid=$schoolid  and class_status=1 and studentid like  '%,".$userid.",%'  order by id desc";
										$ree=$this->db->query($sql)->row_array();
										if($ree){
											$_SESSION['classid']=$ree['classid'];
										}
									}
								 }else{
									$result['status']=0;
									$data=$this->httpCode(504,'用户绑定失败',$result);
								 }
							 }
						}		
					}
				}
			}else{
				$data=$this->httpCode(601,'key校验失败','');
			}
		}
		echo    json_encode($data);	
	}
}
