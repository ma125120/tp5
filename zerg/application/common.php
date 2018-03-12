<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function curl_get($url, $httpCode=0) {
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	//不做证书检验，部署在linux环境下请改为true,
	if(PATH_SEPARATOR == ':') {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	} else {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$file_contents=curl_exec($ch);
	$httpCode= curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $file_contents;
}

function getRandChar($len) {
	$str=null;
	$strPol='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$max=strlen($strPol)-1;

	for($i=0;
		$i<$len;
		$i++) {
		$str.=$strPol[rand(0,$max)];
	}

	return $str;
}
