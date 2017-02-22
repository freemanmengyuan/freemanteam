<?php
require('base.php');

class Weixin extends base {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
	}
	public function index() {
		echo 'hello';
		die;
		header("location:/wap/htmlv/user/guide.html");
		$code=$_GET['code'];
		if(empty($code)){
			exit('code error');
		}
		$appid='wx8b2659e6c0bd4c21';
		$secret='ebfc00bda99990381e1d86d07066d002';
		$getTokenUrl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$accessTokenJson=file_get_contents($getTokenUrl);

		$accessTokenArr=json_decode($accessTokenJson,true);
		$accessToken=$accessTokenArr['access_token'];
		$openid=$accessTokenArr['openid'];
		if(!$openid){
			exit('openid error');
		}
		//echo $openid;exit;	
		//把openid写入SESSION
		$openid = 'oP58NvxT8TvEWpGu1eTQSScojfEw';
		$_SESSION['openid']=$openid;              
		$arr=$this->user_model->userinfo($openid);
		if(!empty($arr)) {
			$schoolid=$arr['schoolid'];
			$wip=$this->school_model->getschoolwip($schoolid);
			//2016-6-15 10:30:55  如果没有该校的外网地址，则提示
			$school_wip=$wip['wip'];
			if(empty($school_wip)){
				$data=$this->httpCode(404,'没有该校的外网信息,暂无法提供服务','');
				echo json_encode($data);
				exit();	
			}
			//把常用值写入SESSION
			$_SESSION['userid']=$arr['userid'];
			$_SESSION['schoolid']=$arr['schoolid'];
			$_SESSION['wip']=$wip['wip'];
			//如果学生意外推出微信，再次登陆依然将尚未结束的课程classid写入SESSION中2016-5-26 11:49:50
			if($arr['roleid']==4 || $arr['roleid']==6){
				$studentid=$arr['userid'];
				$schoolid=$arr['schoolid'];
				$sql="select classid from student_sign  where  schoolid=$schoolid and class_status=1 and studentid like  '%,".$studentid.",%'  order by id desc";
				$ree=$this->db->query($sql)->row_array();
				if($ree){
					$_SESSION['classid']=$ree['classid'];
				}
			}
			//更新用户登录数据(从学校接口读取数据)2016-5-26 11:03:43
			$url=$wip['wip'].'/api/user/index';
			$datastring['userid']=$arr['userid'];
			$datastring=http_build_query($datastring);
			$user_info=file_get_contents($url.'?'.$datastring);
			$data_arr=json_decode($user_info,true);
			$data_arr['data']['realname'];
			$truename = $data_arr['data']['realname'];
			$sex=$data_arr['data']['male'];
			$facepic=$data_arr['data']['logo'];
			if(empty($data_arr['data']['logo'])){
				$facepic='';
			}else{
				$facepic=$wip['wip'].$data_arr['data']['logo'];
			}
			$birthday=$data_arr['data']['birthday'];
			$specialname=$data_arr['data']['specialname'];
			$lasttime=date('Y-m-d H:i:s',time());
			$data_p=array(
				'truename'=>$truename,
				'sex'=>$sex,
				'facepic'=>$facepic,
				'birthday'=>$birthday,
				'specialname'=>$specialname,
				'lasttime'=>$lasttime,
			);
			$result=$this->db->where("openid='".$openid."'")->update('user',$data_p);
			if(empty($result)){
				exit('用户信息更新失败');
			}
			//进入不同菜单页面2016-5-10 18:28:03
			switch($_GET['state']){
				case 'calldo':        //我的课程
					$url='/wap/htmlv/index.html?fun=mycourse';
					break;
				case 'userinfo':      //个人中心
					$url='/wap/htmlv/index.html?fun=userinfo';
					break;
				case 'homework':    //我的作业
					$url='/wap/htmlv/index.html?fun=myhomework';
					break;
				case 'exam':    	//我的考试
					$url='/wap/htmlv/index.html?fun=myexam';
					break;
				case 'kthd':		//课堂互动
					$url='/wap/htmlv/index.html?fun=mykthd';
					break;
				default:
					$url='/wap/htmlv/index.html?fun=myindex';
			}
			//直接跳转到相应页面
			header("location:".$url);
		}else{
			//跳转至登录准备页面
			header("location:/wap/htmlv/user/guide.html");
		}
	}
}





	
	

