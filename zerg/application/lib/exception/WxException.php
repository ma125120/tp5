<?php 
	namespace app\lib\exception;

	class WxException extends BaseException 
	{
		//HTTP状态码
		public $code = 404;

		//错误具体信息
		public $msg = 'wx获取失败';

		//自定义错误码
		public $errorCode = 30000;
	}
?>