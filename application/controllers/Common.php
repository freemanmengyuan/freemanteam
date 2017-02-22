<?php
require('Controller.php');
class Common extends Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('school_model');
		$this->load->helper('file');
		$this->config->load('wechat.inc');
	}
	/**
	主函数 微信统一接入接口 author:mengyuan 2016-12-30
	 */
	public function index($corpid, $redirecturl) {
		if(empty($corpid) && empty($redirecturl)) {
			exit('code error');
		}
		//如果session不存在 登录的企业号不同 则重新进行oAuth授权
		if(!$_SESSION['corpid'] || $corpid != $_SESSION['corpid'] || !$_SESSION['userid']) {
			$author_url = $this->config->item('author_url');
			$redirect_url = $this->config->item('redirect_url');
			$redirect_url = urlencode($redirect_url);
			$get_userinfo_url = $author_url.'appid='.$corpid.'&redirect_uri='.$redirect_url.'&response_type=code&scope=SCOPE&state='.$corpid.'-'.$redirecturl.'#wechat_redirect';
			//$wx_userinfo = file_get_contents($get_userinfo_url);
			//echo $get_userinfo_url;
		    //die;
			header("location:".$get_userinfo_url);
			exit;
		}
		//教师身份 暂定两种角色身份
		if($_SESSION['roleid'] == 3) {
			switch($redirecturl) {
				case 'mycourse':      //我的个人中心
					$url='/wap/htmlv/teacher/index.html?fun=teacherinfo';
					break;
				case 'myhomework':    //批改作业
					$url='/wap/htmlv/teacher/index.html?fun=teacherhome';
					break;
				case 'myexam':    	 //批改考试
					$url='/wap/htmlv/teacher/index.html?fun=teacherexam';
					break;
				case 'mykthd':		 //课堂互动
					$url='/wap/htmlv/teacher/index.html?fun=teachefeatrue';
					break;
				default:			 //个人中心
					$url='/wap/htmlv/teacher/index.html?fun=teacherinfo';
			}
		//学生身份
		} elseif($_SESSION['roleid'] == 4) {
			switch($redirecturl) {
				case 'mycourse':        //我的课程
					$url='/wap/htmlv/index.html?fun=mycourse';
					break;
				case 'userinfo':      //个人中心
					$url='/wap/htmlv/index.html?fun=userinfo';
					break;
				case 'myhomework':    //我的作业
					$url='/wap/htmlv/index.html?fun=myhomework';
					break;
				case 'myexam':    	//我的考试
					$url='/wap/htmlv/index.html?fun=myexam';
					break;
				case 'mykthd':		//课堂互动
					$url='/wap/htmlv/index.html?fun=mykthd';
					break;
				default:
					$url='/wap/htmlv/index.html?fun=myindex';
			}
		}
		//直接跳转到相应页面
		header("location:".$url);
	}

	/**
	 微信企业号回调身份验证接口 author:mengyuan
	 */
	public function appRedirectUrl() {
		$data = array();
		if(!$_GET['code']) {
			exit('code error');
		}
		if(!$_GET['state']) {
			exit('state error');
		}
		$state_info = explode('-', $_GET['state']);
		$corpid = $state_info[0];
		$school_info = $this->school_model->getSchoolinfoByCorpid($corpid );
		if(empty($school_info)) {
			exit('schoolinfo error');
		}
		$corpid = $school_info['corpid'];
		$accessToken = get_access_token($corpid);
		if($accessToken == 0) {
			$secret = $school_info['secret'];
			$token_url = $this->config->item('token_url');
			$getTokenUrl = $token_url.'corpid='.$corpid.'&corpsecret='.$secret;
			$accessTokenJson = file_get_contents($getTokenUrl);
			$accessTokenArr = json_decode($accessTokenJson,true);
			set_access_token($corpid, $accessTokenArr['access_token']);
			$accessToken = $accessTokenArr['access_token'];
		}
		if(!$accessToken) {
			exit('accessToken error');
		}
		$userid_url = $this->config->item('author_userid_url');
		$userid_json = file_get_contents($userid_url.'access_token='.$accessToken.'&code='.$_GET['code']);
		$userid_ary = json_decode($userid_json, true);
		if(!$userid_ary['UserId']) {
			exit('UserId error');
		}
		$userinfo_url = $this->config->item('userinfo_url');
		$userinfo_json = file_get_contents($userinfo_url.'access_token='.$accessToken.'&userid='.$userid_ary['UserId']);
		$login_info = json_decode($userinfo_json, true);
		if($login_info['errcode'] != 0) {
			exit('logininfo error');
		}
		$_SESSION['openid'] = $login_info['userid'];
		$_SESSION['corpid'] = $school_info['corpid'];
		$_SESSION['name'] = $login_info['name'];
		$_SESSION['avatar'] = $login_info['avatar'];
		$_SESSION['schoolid'] = $school_info['schoolid'];
		$_SESSION['wip'] = $school_info['wip'];
		$_SESSION['wip'] = $school_info['wip'];
		$openid = $_SESSION['openid'];
		if(!$openid) {
			exit('openid error');
		}
		//把openid写入SESSION
		$arr=$this->user_model->userinfo($openid);
		//var_dump($arr);
		//die;
		if(!empty($arr)) {
			//2016-6-15 10:30:55  如果没有该校的外网地址，则提示
			if(empty($_SESSION['wip'])) {
				$data=$this->httpCode(404,'没有该校的外网信息,暂无法提供服务','');
				echo json_encode($data);
				exit();
			}
			//把常用值写入SESSION
			$_SESSION['userid'] = $arr['userid'];
			$_SESSION['roleid'] = $arr['roleid'];
			//如果学生意外推出微信，再次登陆依然将尚未结束的课程classid写入SESSION中2016-5-26 11:49:50
			if($arr['roleid']==4 || $arr['roleid']==6) {
				$studentid=$arr['userid'];
				$schoolid=$arr['schoolid'];
				$sql="select classid from student_sign where schoolid=$schoolid and class_status=1 and studentid like  '%,".$studentid.",%'  order by id desc";
				$ree=$this->db->query($sql)->row_array();
				if($ree) {
					$_SESSION['classid']=$ree['classid'];
				}
			}
			//var_dump($arr);
			//die;
			//更新用户信息
			$data_p=array(
			    //'id'=>$arr['id'],
				//'truename'=>$_SESSION['name'],
				//'facepic'=>$_SESSION['avatar'],
				'lasttime'=>date('Y-m-d H:i:s',time()),
				'schoolid'=>$_SESSION['schoolid'],
				'openid' => $_SESSION['openid'],
			);
			if(empty($arr['facepic'])) {
				$data_p['facepic'] = $_SESSION['avatar'];
			}
			//$result = $this->db->insert('user',$data_p);
			$where = array('id' => $arr['id']);
		    $result = $this->db->update('user', $data_p, $where);
			//$result = $this->db->update('user',$data_p); 
			//var_dump($result);
		    //die;
			if(empty($result)) {
				exit('写入失败');
			}
		} else {
			echo '非法操作';
			exit;
		}
		header("location:/index.php/Common/index/".$corpid.'/'.$state_info[1].'/');
	}
}
