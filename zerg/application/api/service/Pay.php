<?php 

namespace app\api\service;

use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use think\Exception;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\lib\enum\OrderStatusEnum;
use think\Log;

\think\Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay {
	private $orderID;
	private $orderNO;

	public function __construct($orderID) {
		if(!$orderID) {
			throw new Exception('订单号不允许为空');
		}
		$this->orderID = $orderID;
	}

	public function pay() {
		//订单号根本就不存在
		//订单号确实存在，但是订单和当前用户不匹配
		//订单有可能已经被支付
		//进行库存量检测
		$this->checkOrderVaild();
		$order = new OrderService();
		$status = $order->checkOrderStock($this->orderID);
		if(!$status['pass']) {
			return $status;
		}
		return $this->makeWxPreOrder($status['orderPrice']);
	}
	//构建微信支付订单信息
	private function makeWxPreOrder($totalPrice) {
		//订单号,openid,
		$openid = Token::getCurrentTokenVar('openid');
		if(!$openid) {
			throw new TokenException();
		}

		$wxOrderData = new \WxPayUnifiedOrder();
		$wxOrderData->SetOut_trade_no($this->orderNO);
		$wxOrderData->SetTrade_type('JSPAI');
		$wxOrderData->SetTotal_fee($totalPrice*100);
		$wxOrderData->SetBody('零食商贩');
		$wxOrderData->SetOpenid($openid);
		$wxOrderData->SetNotify_url(config('wx.notify_url'));

		return $this->getPaySignature($wxOrderData);
	}

	private function getPaySignature($wxOrderData) {
		//获取预支付id prepay_id
		$wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
		//dump($wxOrder);
		if($wxOrder['return_code'] != 'SUCCESS' || 
			$wxOrder['result_code'] != 'SUCCESS') {
			Log::record($wxOrder,'error');
			Log::record('获取预支付订单失败','error');
			throw new OrderException(['msg'=>'获取预支付订单失败']);
		}

		$this->recordPreOrder($wxOrder);
    $signature = $this->sign($wxOrder);
    return $signature;
	}

	private function sign($wxOrder) {
		// $wxOrder['prepay_id']=123321;
		$jsApiPayData = new \WxPayJsApiPay();
		$jsApiPayData->SetAppid(config('wx.appid'));
    $jsApiPayData->SetTimeStamp((string)time());
    $rand = md5(time() . mt_rand(0, 1000));
    $jsApiPayData->SetNonceStr($rand);
    $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
    $jsApiPayData->SetSignType('md5');

    $sign = $jsApiPayData->MakeSign();
    $rawValues = $jsApiPayData->GetValues();
    $rawValues['paySign'] = $sign;
    unset($rawValues['appId']);

    return $rawValues;

	}

	private function recordPreOrder($wxOrder){
		// $wxOrder['prepay_id']=123321;
    // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的
    OrderModel::where('id', '=', $this->orderID)
         			->update(['prepay_id' => $wxOrder['prepay_id']]);
  }

	private function checkOrderVaild() {
		$order = OrderModel::where('id','=',$this->orderID)
							->find();
		if(!$order) {
			throw new OrderException();
		}
		if(!Token::isVaildOperate($order->user_id)) {
			throw new TokenException([
				'msg'	=>	'订单与用户不匹配',
				'errorCode'	=>	10003
			]);
		}
		if($order->status != OrderStatusEnum::UNPAID) {
			throw new OrderException([
				'msg'	=>	'订单已经支付过啦',
				'code'	=>	400,
				'errorCode'=>	80003
			]);
		}
		$this->orderNO = $order->order_no;
		return $order->order_no;
		
	}
}