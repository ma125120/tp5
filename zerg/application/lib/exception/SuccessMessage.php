<?php 
	namespace app\lib\exception;

	class SuccessMessage extends BaseException 
	{
		//HTTP状态码
		public $code = 201;

		//错误具体信息
		public $msg = 'ok';

		//自定义错误码
		public $errorCode = 0;
	}
?>