<?php
include_once "qywechatapi/WXBizMsgCrypt.php";

// 假设企业号在公众平台上设置的参数如下
$token = "O8JCmGpxyM4";
$encodingAesKey = "XNmgg8blCwkQUQQEIEOyy6h7Nsy3VphAo6DXIKuhzKT";
$corpId = "wxc1779e7769be2fad";
/*
接收到该请求时，企业应
1.解析出Get请求的参数，包括消息体签名(msg_signature)，时间戳(timestamp)，随机数字串(nonce)以及公众平台推送过来的随机加密字符串(echostr),
这一步注意作URL解码。
2.验证消息体签名的正确性 
3. 解密出echostr原文，将原文当作Get请求的response，返回给公众平台
第2，3步可以用公众平台提供的库函数VerifyURL来实现。
*/
$sVerifyMsgSig = $_GET["msg_signature"];
$sVerifyTimeStamp = $_GET["timestamp"];
$sVerifyNonce = $_GET["nonce"];
$sVerifyEchoStr = $_GET["echostr"];
// 需要返回的明文
$sEchoStr = "";
$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
logger('repechostr:'.$sVerifyEchoStr.';errcode:'.$errCode.';resechostr:'.$sEchoStr, 0);
if ($errCode == 0) {
	// 验证URL成功，将sEchoStr返回
    print_r($sEchoStr);
} else {
	print("ERR: " . $errCode . "\n\n");
}

function logger($log_content, $type=0, $file='log.xml') {
	if ($type == 0) {
		$max_size = 100000;
		$log_filename = $file;
		if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size))
			unlink($log_filename);
		file_put_contents($log_filename, date('y-m-d H:m:s').' QyWechat：'.$log_content."\n", FILE_APPEND);
	}
}