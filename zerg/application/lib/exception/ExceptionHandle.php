<?php 
	namespace app\lib\exception;
	use think\exception\Handle;
	use think\Exception;
	use think\Request;
	use app\lib\exception\BaseException;
	use think\Log;
	use think\Env;
	class ExceptionHandle extends Handle
	{
		//HTTP状态码
		private $code = 400;

		//错误具体信息
		private $msg = '参数错误';

		//自定义错误码
		private $errorCode = 10000;
		public function render(\Exception $e) {
			//var_dump($e);
			if($e instanceof BaseException) {
				//如果是自定义的异常
				$this->code = $e->code;
				$this->msg = $e->msg;
				$this->errorCode = $e->errorCode;
			} else {
				if(Env::get('debug')) {
					return parent::render($e);
				} else {
					$this->code = 500;
					$this->msg = '服务器内部错误';
					$this->errorCode = 999;
					$this->recordErrorLog($e);
				}
			}

			$req=Request::instance();

			$result=[
				'msg'	=>	$this->msg,
				'error_code'	=>	$this->errorCode,
				'request_url'	=>	$req->url()
			];

			return json($result,$this->code);
			//return parent::render($e);
		}

		private function recordErrorLog(Exception $e) {
			Log::init([
				'type'	=>	'File',
				'path'	=>	LOG_PATH,
				'level'	=>	['error']
			]);
			Log::record($e->getMessage(),'error');
		}
	}
?>