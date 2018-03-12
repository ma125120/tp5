<?php
namespace app\api\service;

use think\Request;
use think\Cache;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;
use app\lib\exception\UserException;
use app\lib\enum\ScopeEnum;
use app\api\model\Product as ProductModel;
use app\lib\exception\OrderException;
use app\api\model\UserAddress;
use app\api\model\OrderProduct;
use think\Db;

class Order {
	//订单的商品列表。客户端传来的products参数
	protected $oProducts;
	//数据库中的products
	protected $products;

	protected $uid;

	public function place($uid,$oProducts) {
		//products 从数据库中查询出来
		$this->oProducts=$oProducts;
		$this->products = $this->getProductsByOrder($oProducts);
		$this->uid=$uid;

		$status = $this->getOrderStatus();
		if(!$status['pass']) {
			$status['order_id'] = -1;
			return $status;
		}
		//开始创建订单
		$orderSnap = $this->snapOrder($status);
		$order = $this->createOrder($orderSnap);
		$status['pass'] = true;
		$status['order_id'] = $order['order_id'];
		return $status;
	}

	private function getProductsByOrder($oProducts) {
		$oPIDs = [];
		foreach ($oProducts as $key => $item) {
			array_push($oPIDs,$item['product_id']);
		}

		$products = ProductModel::all($oPIDs)
								->visible(['id','price','stock','name','main_img_url'])
								->toArray();

		return $products;
	}
	//创建订单
	private function createOrder($snap) {
		Db::startTrans();
		try{
			$orderNo = $this->makeOrderNo();
			$order = new \app\api\model\Order();
			$order->user_id = $this->uid;
			$order->order_no = $orderNo;
			$order->total_price = $snap['orderPrice'];
			$order->total_count = $snap['totalCount'];
			$order->snap_img = $snap['snapImg'];
			$order->snap_name = $snap['snapName'];
			$order->snap_address = $snap['snapAddress'];
			$order->snap_items = json_encode($snap['pStatus']);

			$order->save();

			$orderID = $order->id;
			$create_time = $order->create_time;

			foreach ($this->oProducts as &$p) {
				$p['order_id'] = $orderID;
			}
			$orderProduct = new OrderProduct();
			$orderProduct->saveAll($this->oProducts);
			Db::commit();

			return [
				'order_no'	=>	$orderNo,
				'order_id'	=>	$orderID,
				'create_time'	=>	$create_time
			];
		} catch (Exception $ex) {
			Db::rollback();
      throw $ex;
    }
		
	}
	//制作订单编号
	private function makeOrderNo() {
		$yCode=array('A','B','C','D','E','F','G','H','I','J');
		$orderSn = 
				$yCode[intval(date('Y'))-2017] . strtoupper(dechex(date('m'))) . date('d') . substr(time(),-5) . substr(microtime(),2,5) . sprintf('%02d',rand(0,99));
		return $orderSn;
	}

	//生成订单快照
	private function snapOrder($status) {
		$snap = [
			'orderPrice'	=>	0,
			'totalCount'	=>	0,
			'pStatus'	=>	[],
			'snapAddress'	=>	null,
			'snapName'	=>	'',
			'snapImg'	=>	''
		];

		$snap['orderPrice']	=	$status['orderPrice'];
		$snap['totalCount']	=	$status['totalCount'];
		$snap['pStatus']	=	$status['pStatusArray'];
		$snap['snapAddress']	=	json_encode($this->getUserAddress());
		$snap['snapName']	=	$this->products[0]['name'];
		$snap['snapImg']	=	$this->products[0]['main_img_url'];

		if(count($this->products)>1) {
			$snap['snapName'].='等';
		}

		return $snap;
	}

	private function getUserAddress() {
		$userAddress = UserAddress::where('user_id','=',$this->uid)->find();
		if(!$userAddress) {
			throw new UserException([
				'msg'	=>	'用户收货地址不存在，下单失败',
				'errorCode'	=>	60001
			]);
		}
		return $userAddress->toArray();
	}

	public function checkOrderStock($orderID) {
		$oProducts = OrderProduct::where('order_id','=',$orderID)
									->select();

		$this->oProducts=$oProducts;
		$this->products = $this->getProductsByOrder($oProducts);
		$status = $this->getOrderStatus();

		return $status;
	}

	private function getOrderStatus() {
		$status = [
			'pass'	=>	true,
			'orderPrice'	=> 0 ,
			'totalCount'	=>	0,
			'pStatusArray'	=>	[]
		];

		foreach ($this->oProducts as $key => $oProduct) {
			$pStatus = $this->getProductStatus(
				$oProduct['product_id'],$oProduct['count'],$this->products
			);
			if(!$pStatus['haveStock']) {
				$status['pass']=false;
			}
			$status['orderPrice']+=$pStatus['totalPrice'];
			$status['totalCount']+=$pStatus['count'];
			array_push($status['pStatusArray'], $pStatus);
		}

		return $status;
	}

	private function getProductStatus($oPIDs, $oCount ,$products) {

		$pIndex = 0;

		$pStatus = [
			'id'	=>	null,
			'haveStock'	=>	false,
			'count'	=>	0,
			'name'	=> '',
			'totalPrice'	=>	0,
			'main_img_url'	=>	'',
			'counts'	=>	0,
			'price'	=>	0,
		];

		for($i=0,$len=count($products);$i<$len;$i++) {
			if($oPIDs == $products[$i]['id']) {
				$pIndex = $i;
			}
		}

		if($pIndex == -1) {
			throw new OrderException([ 'msg' => 'id为'.$oPIDs.'的商品不存在，创建订单失败']);
		} 

		$product = $products[$pIndex];
		$pStatus['id'] = $product['id'];
		$pStatus['counts'] = $oCount;
		$pStatus['count'] = $oCount;
		$pStatus['name'] = $product['name'];
		$pStatus['main_img_url'] = $product['main_img_url'];
		$pStatus['totalPrice'] = $product['price'] * $oCount;
		$pStatus['price'] = $product['price'] * $oCount;

		if($product['stock'] - $oCount >= 0 ) {
			$pStatus['haveStock'] = true;
		} else {
			throw new OrderException([ 'msg' => 'id为'.$oPIDs.'的商品库存不足，仅剩余'.$product['stock'].'，创建订单失败']);
		}

		return $pStatus;
	}


}