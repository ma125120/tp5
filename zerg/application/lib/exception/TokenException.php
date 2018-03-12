<?php 
	namespace app\lib\exception;

	class TokenException extends BaseException 
	{
		//HTTP状态码
		public $code = 401;

		//错误具体信息
		public $msg = 'token已过期或者无效token';

		//自定义错误码
		public $errorCode = 10001;
	}
?>