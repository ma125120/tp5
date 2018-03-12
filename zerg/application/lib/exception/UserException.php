<?php 
	namespace app\lib\exception;
	use app\lib\exception\BaseException;

	class UserException extends BaseException 
	{
		//HTTP状态码
		public $code = 404;

		//错误具体信息
		public $msg = '当前用户不存在';

		//自定义错误码
		public $errorCode = 60000;
	}
?>