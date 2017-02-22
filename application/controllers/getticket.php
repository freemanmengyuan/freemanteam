<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('jssdk.php');

class  getticket extends base{
	
	public function __construct()
	{
		parent::__construct($appid,$secret);
	}
	//获取签名
	public function index(){
		//获取动态地址
		$url=$this->input->post('sdkUrl');
		// 获取签名
		/*$appid='wx8b2659e6c0bd4c21';
		$secret='ebfc00bda99990381e1d86d07066d002';*/
		$appid='wxc1779e7769be2fad';
		$secret='P9vriyeXTLJ71uOShJJ6y815NmL1ODK_gTtma_ad0Q5P7XwQBaBUHOKGOz1X2H0E';
		$obj=new jssdk($appid,$secret);
		//测试使用
		//$result['sign']=$obj->getSignPackage($url);
		//$result['Ticket']=$obj->getJsApiTicket();
		//$result['accesstoken']=$obj->getAccessToken();
		$result=$obj->getSignPackage($url);
		if($result){
			$data=$this->httpCode(200,'获取签名成功',$result);
		}else{
			$data=$this->httpCode(204,'获取失败','');
		}
		echo json_encode($data);

	}
}
