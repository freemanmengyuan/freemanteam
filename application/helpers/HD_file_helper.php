<?php
/**
打印log日志
 */
function logger($log_content, $type=0, $file='/log.xml') {
	if ($type == 0) {
		$max_size = 100000;
		$log_filename = $file;
		if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size))
			unlink($log_filename);
		file_put_contents($log_filename, date('y-m-d H:m:s').' QyWechat：'.$log_content."\n", FILE_APPEND);
	}
}

/**
读取access_token 存储时间为2小时
 */
function get_access_token($corpid='') {
	$result = 0;
	$max_time = 7200;
	$filename = './access_token/'.$corpid.'.xml';
    if(file_exists($filename)) {
		if(!empty($corpid)) {
		$time = time()-filemtime($filename);
		if($time < $max_time)
			$result = file_get_contents($filename);
		}
	}
	return $result;
}

/**
写入access_token 存储时间为2小时
 */
function set_access_token($corpid='', $token='') {
	if (!empty($corpid) && !empty($token)) {
		$filename = './access_token/'.$corpid.'.xml';
		file_put_contents($filename,$token);
	}
}

function test() {
	echo 'hello';
}