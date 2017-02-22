<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		echo $_GET['corpid'];
		$this->load->model('base_model');
	}
	//api key校验
	public function apikey($key=null)
	{
		return $key=='imkey'?1:0;
	}
	//http code封装
	public function httpCode($code=null,$message=null,$result=null)
	{
		return array('httpcode'=>$code,'message'=>$message,'result'=>$result);
	}
	//curl封装
	function curl($url, $datastring)
	{
		$ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $datastring);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $data = curl_exec($ch);
		// curl_close($ch);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$datastring);
		$data=curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	//登录
	function iflogin()
	{
		return empty($_SESSION['userid'])?0:1;
	}
	
}
