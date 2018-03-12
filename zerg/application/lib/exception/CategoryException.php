<?php 
	namespace app\lib\exception;

	class CategoryException extends BaseException 
	{
		//HTTP状态码
		public $code = 404;

		//错误具体信息
		public $msg = '请求列表不存在，请检查参数';

		//自定义错误码
		public $errorCode = 20000;
	}
?>