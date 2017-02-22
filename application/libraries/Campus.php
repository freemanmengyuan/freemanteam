<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 智慧校园平台接口操作类
 */
class Campus {
	public $schoolid;  //学校id
	public $CI;        //资源调用ci对象
	public $accessToken = 0;  //学校id
	public function __construct($params)
	{
		$this->schoolid = $params['schoolid'];
		//调用其他资源
		$this->CI = &get_instance();
		$this->CI->load->helper('file');
		$this->CI->config->load('wechat.inc');
		$this->CI->load->model('school_model');
	}

	/**
	 * 生成签名
	 * @param array||string $signData 待签名的参数数组||经过urlencode后的参数字符串，可以使用http_build_query($array)函数将数组转成这样的请求字符串，不包含sign参数和key参数
	 * @param string $key
	 * @param int $type
	 * @return string
	 */
	function buildSign($signData, $key) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$arg = '';
		$strSign = 'sign';
		if (empty($signData) || empty($key)) {
			return false;
		}
		if (is_string($signData)) {
			$urlstr = urldecode($signData);
			$urlarr = explode('&', $urlstr);
			foreach($urlarr as $k => $v) {
				if (strpos($v, $strSign) !== false) {
					unset($urlarr[$k]);
				}
			}
		}
		if (is_array($signData)) {
			if (isset($signData[$strSign])) {
				unset($signData[$strSign]);
			}
			$urlstr = urldecode(http_build_query($signData));
			$urlarr = explode('&', $urlstr);
		}
		sort($urlarr, SORT_STRING);
		$arg = implode('&', $urlarr);
		$sgin = strtoupper(md5($arg . '&key=' . $key));//把最终的字符串签名，获得签名结果
		return $sgin;
	}
	
	//向企业号通讯录写入用户信息
	public function setUserInfo($params) {
		$ret = array();
		if($this->accessToken == 0) {
			$this->accessToken = $this->getAccessToken($this->schoolid);
		}
		if(!empty($params)) {
			if(!$params['id']) {
				$update_party_url = $this->CI->config->item('create_user_url');
			} else {
				$update_party_url = $this->CI->config->item('update_user_url');
			}
			$party_url = $update_party_url.'access_token='.$this->accessToken;
			$data = $this->my_json_encode('text', $params);
			$result_json = $this->https_request($party_url, $data);
			//var_dump($result_json);
			//die;
			$result = json_decode($result_json,true);
			$ret['errcode'] = $result['errcode'];
			$ret['errmsg'] = $result['errmsg'];
		} else {
			$ret['errcode'] = -1;
			$ret['errmsg'] = '参数为空';
		}
		
		return $ret;
	}
	
	//获取企业号通讯录部门信息
	public function getPartyInfo($partid = 1) {
		$party_info = array();
		if($this->accessToken == 0){
			$this->accessToken = $this->getAccessToken($this->schoolid);
		}
		if($partid > 0) {
			$get_party_url = $this->CI->config->item('get_partyinfo_url');
			$get_party_url = $get_party_url.'access_token='.$this->accessToken.'&id='.$partid;
			$party_info_json = file_get_contents($get_party_url);
			$party_info = json_decode($party_info_json,true);
		}
		
		return $party_info;
	}
	
	//更新企业号通讯录部门信息
	public function setPartyInfo($params) {
		$ret = array();
		
		if($this->accessToken == 0){
			$this->accessToken = $this->getAccessToken($this->schoolid);
		}
		if(!empty($params)) {
			if(!$params['id']) {
				$update_party_url = $this->CI->config->item('create_party_url');
			} else {
				$update_party_url = $this->CI->config->item('update_party_url');
			}
			$party_url = $update_party_url.'access_token='.$this->accessToken;
			$data = $this->my_json_encode('text', $params);
			$result_json = $this->https_request($party_url, $data);
			var_dump($result_json);
			die;
			$result = json_decode($result_json,true);
			
		} else {
			$ret['errcode'] = -1;
			$ret['errmsg'] = '参数为空';
		}
		
		return $ret;
	}
	
	//模拟post方式提交数据
	function https_request($url, $data=null) {
		$curl = curl_init();
       
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		if(!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		
		return $output;
	}
	
	//数组转json时出现问题的解决方案
	function my_json_encode($type, $p){
		if (PHP_VERSION >= '5.4'){
			$str = json_encode($p, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		}
		else
		{
			switch ($type)
			{
				case 'text':
					isset($p['text']['content']) && ($p['text']['content'] = urlencode($p['text']['content']));
					break;
			}
			$str = urldecode(json_encode($p));
		}
		return $str;
	}
	
}
