<?php 
	namespace app\lib\exception;

	class ThemeException extends BaseException 
	{
		//HTTP状态码
		public $code = 404;

		//错误具体信息
		public $msg = '指定主题不存在，请检查ID是否正确';

		//自定义错误码
		public $errorCode = 30000;
	}
?>