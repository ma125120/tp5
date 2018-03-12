<?php 
	namespace app\lib\exception;

	class ForbiddenException extends BaseException 
	{
		//HTTP状态码
		public $code = 403;

		//错误具体信息
		public $msg = '权限不足';

		//自定义错误码
		public $errorCode = 10001;
	}
?>