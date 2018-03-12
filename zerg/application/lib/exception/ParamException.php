<?php 
	namespace app\lib\exception;
	use app\lib\exception\BaseException;

	class ParamException extends BaseException 
	{
		//HTTP状态码
		public $code = 400;

		//错误具体信息
		public $msg = '参数错误';

		//自定义错误码
		public $errorCode = 10000;
	}
?>