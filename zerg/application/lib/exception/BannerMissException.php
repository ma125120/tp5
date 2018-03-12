<?php 
	namespace app\lib\exception;
	use app\lib\exception\BaseException;

	class BannerMissException extends BaseException 
	{
		//HTTP状态码
		public $code = 404;

		//错误具体信息
		public $msg = '请求的Banner不存在';

		//自定义错误码
		public $errorCode = 10000;
	}
?>