<?php 

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Db;

\think\Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify {

	public function NotifyProcess($data, &$msg) {
		if($data['result_code'] == 'SUCCESS') {
			$orderNO = $data['out_trade_no'];
			Db::startTrans();
			try {
				$order = OrderModel::where('order_no','=',$orderNO)->find();
				if($order->status == OrderStatusEnum::UNPAID) {
					$service = new OrderService();
					$stockStatus = $service->checkOrderStock($order->id);
					if($stockStatus->pass) {
						$this->updateOrderStatus($orderID,true);
						$this->reduceStock($stockStatus);
					} else {
						$this->updateOrderStatus($orderID,false);
					}
				}
				Db::commit();
				return true;
			} catch(Exception $e) {
				Db::rollback();
				\think\Log::error($e,'error');
				return false;
			}
		}
	}

	private function reduceStock($stockStatus) {
		foreach ($stockStatus['pStatusArray'] as $pStatus) {
			Product::where('id','=',$pStatus['id'])
						->setDec('stock',$pStatus['count']);
		}
	}	

	private function updateOrderStatus($orderID,$success) {
		$status = $success?(OrderStatusEnum::PAID):(OrderStatusEnum::PAID_BUT_OUT_OF);

		OrderModel::where('id','=',$orderID)
							->update(['status'=>$status]); 
	}
}
