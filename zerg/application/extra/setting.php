<?php
if(PATH_SEPARATOR == ':') {
		$fix = 'http://120.79.25.182/zerg/public/images/';
} else {
		$fix = 'http://zerg.cn/images/';
}

return [
	'img_prefix'=>	$fix,
	'token_expire_in'	=> 7200
];