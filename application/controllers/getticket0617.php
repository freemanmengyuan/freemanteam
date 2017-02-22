<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class  getticket extends base{
	public function __construct()
	{
		parent::__construct();
	}
	//获取签名
	public function index(){
		
		//获取动态地址
		$sdkUrl=$this->input->post('sdkUrl');
		//$sdkUrl='http://hdketang.ejianwei.com.cn/wap/htmlv/index.html';
		//print_r($sdkUrl);
		//2016-5-19 11:05:04  获取签名
		$appid='wx8b2659e6c0bd4c21';
		$secret='ebfc00bda99990381e1d86d07066d002';
		$another_token_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
		$another_token_Json=file_get_contents($another_token_url);
		$another_tokenArr=json_decode($another_token_Json,true);
		$another_token=$another_tokenArr['access_token'];
		$getticketUrl="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$another_token&type=jsapi";
		$getticket=file_get_contents($getticketUrl);
		$getticket_arr=json_decode($getticket,true);
		$getticket=$getticket_arr['ticket'];
		//print_r($getticket);
		//拼接signature
		$timestamp=time();
		$strings='ajidjifheiomxnuwoxajisaqpasfd132eerquwer89dsaf4afd5f6aaw0saaaf2mkkihj';
		$start=mt_rand(0,53);
		$noncestr=substr($strings,$start,16);
		$string="jsapi_ticket=$getticket&noncestr=$noncestr&timestamp=$timestamp&url=$sdkUrl";
		//print_r($string);
		//echo '<hr/>';
		$signature=sha1($string);
		if($signature){
			$result['signature']=$signature;
			$result['noncestr']=$noncestr;
			$result['timestamp']=$timestamp;
			$result['url']=$sdkUrl;
			$data=$this->httpCode(200,'获取签名成功',$result);
		}else{
			$data=$this->httpCode(204,'获取失败','');
		}
		echo json_encode($data);

	}
}
