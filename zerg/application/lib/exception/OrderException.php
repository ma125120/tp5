<?php 
	namespace app\lib\exception;

	class OrderException extends BaseException 
	{
		//HTTP状态码
		public $code = 404;

		//错误具体信息
		public $msg = '订单不存在，请检查id';

		//自定义错误码
		public $errorCode = 80000;
	}
?>