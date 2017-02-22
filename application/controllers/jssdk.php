<?php
/**
 @modefy mengyuan 2016-12-21
*/
defined('BASEPATH') OR exit('No direct script access allowed');
require('base.php');

class jssdk extends base{
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
	parent::__construct();
	$this->load->model('school_model');
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  }

  public function getSignPackage($url) {
    $jsapiTicket = $this->getJsApiTicket();
    $timestamp = time();
    $nonceStr = $this->createNonceStr();
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    $signature = sha1($string);
	$signPackage = array(
      "appId"     => $this->appId,
      "noncestr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
    );
    return $signPackage; 
  }

  private   function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private   function getJsApiTicket() {
	//提取access令牌(sql)
	$sql='select * from jsapiticket';
	$getaccessfile=$this->db->query($sql)->row_array();
	if(empty($getaccessfile)){
		$accessToken = $this->getAccessToken();
		//$getTokenUrl="https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
		$getTokenUrl = 'https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token='.$accessToken;
		$accessTokenJson=file_get_contents($getTokenUrl);
		$accessTokenArr=json_decode($accessTokenJson,true);
		$jsapiticket=$accessTokenArr['ticket'];//token有效时间2小时 7200秒。
		//sql缓存 access和时效存入sql
		$unsettime=time()+3600;//预留1小时
		//$sql="insert into jsapiticket(jsapiticket,unsettime) value('$jsapiticket','$unsettime')";
		//$sql_class->i($sql);
		$data=array('jsapiticket'=>$jsapiticket,'unsettime'=>$unsettime);
		$this->db->insert('jsapiticket', $data);
	}else{	
		if(time()>$getaccessfile['unsettime'] || empty($getaccessfile['jsapiticket'])){
			$accessToken = $this->getAccessToken();
			//$getTokenUrl="https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$getTokenUrl = 'https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token='.$accessToken;
			$accessTokenJson=file_get_contents($getTokenUrl);
			$accessTokenArr=json_decode($accessTokenJson,true);
			$jsapiticket=$accessTokenArr['ticket'];//token有效时间2小时 7200秒。暂时未做缓存处理。
			//sql缓存 access和时效存入sql
			$unsettime=time()+3600;//预留1小时
			//$sql="update jsapiticket set jsapiticket='$jsapiticket',unsettime='$unsettime'";
			//$sql_class->ud($sql);	
			$data=array('jsapiticket'=>$jsapiticket,'unsettime'=>$unsettime);
			$this->db->where('id',1);
			$this->db->update('jsapiticket',$data); 
		}
		else{
			$jsapiticket=$getaccessfile['jsapiticket'];
		}
	}
    return $jsapiticket;
  }

 private   function getAccessToken() {
	//提取access令牌(sql)
	$sql='select * from accesstoken';
	$getaccessfile=$this->db->query($sql)->row_array();
	if(empty($getaccessfile)){
		//$getTokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
		$getTokenUrl = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$this->appId.'&corpsecret='.$this->appSecret;
		$accessTokenJson=file_get_contents($getTokenUrl);
		$accessTokenArr=json_decode($accessTokenJson,true);
		$accessToken=$accessTokenArr['access_token'];//token有效时间2小时 7200秒。
		//sql缓存 access和时效存入sql
		$unsettime=time()+3600;//预留1小时
		//$sql="insert into accesstoken(accesstoken,unsettime) value('$accessToken','$unsettime')";
		//$sql_class->i($sql);
		$data=array('accesstoken'=>$accessToken,'unsettime'=>$unsettime);
		$this->db->insert('accesstoken', $data);
	}else{	
		if(time()>$getaccessfile['unsettime'] || empty($getaccessfile['accesstoken'])){
			//$getTokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$getTokenUrl = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$this->appId.'&corpsecret='.$this->appSecret;
			$accessTokenJson=file_get_contents($getTokenUrl);
			$accessTokenArr=json_decode($accessTokenJson,true);
			$accessToken=$accessTokenArr['access_token'];//token有效时间2小时 7200秒。暂时未做缓存处理
			//sql缓存 access和时效存入sql
			$unsettime=time()+3600;//预留1小时
			//$sql="update accesstoken set accesstoken='$accessToken',unsettime='$unsettime'";
			//$sql_class->ud($sql);	
			$data=array('accesstoken'=>$accessToken,'unsettime'=>$unsettime);
			$this->db->where('id',1);
			$this->db->update('accesstoken',$data); 
		}
		else{
			$accessToken=$getaccessfile['accesstoken'];
		}
	}
   return $accessToken;
  }
}

