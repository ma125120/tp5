<?php
namespace app\api\controller\v1;

use app\api\validate\OrderPlace;
use app\api\validate\PageParameter;
use app\api\validate\IDMustBePostiveInt;
use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
class Order extends BaseController {

  //用户在选择商品后，向API提交他所选择商品的相关信息
  //API在接收到信息后，需要检查库存
  //有库存，存入数据库，下单成功后，告知客户端可以进行支付了
  //调用我们的支付接口，进行支付
  //还需要再次进行库存量检测
  //服务器这边就可以调用微信的支付接口进行支付
  //小程序根据服务器返回的结果拉起微信支付
  //微信会返回给我们一个支付的结果（异步），根据结果
  //成功：也需要进行库存量的检测，进行库存量的扣除
  //失败：返回一个支付失败的结果
  
  protected $beforeActionList = [
    'checkExclusiveScope' =>  ['only'=>'placeOrder'],
    'checkPrimaryScope' =>  ['only'=>'getDetail,getSummaryByUser']
  ];

  public function getSummaryByUser($page=1,$size=15) {
    (new PageParameter())->goCheck();

    $uid = TokenService::getCurrentUid();
    $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
    if($pagingOrders->isEmpty()) {
      return [
        'data'  =>  [],
        'current_page'  =>  $pagingOrders->getCurrentPage()
      ];
    }

    $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
    return [
      'data'  =>  $data,
      'current_page'  =>  $pagingOrders->getCurrentPage()
    ];
  }

  public function getDetail($id) {
    (new IDMustBePostiveInt())->goCheck();
    $orderDetail = OrderModel::get($id);
    if(!$orderDetail) {
      throw new OrderException();
    }
    return $orderDetail->hidden(['prepay_id']);
  }

  public function placeOrder() {
    (new OrderPlace())->goCheck();
    $products = input('post.products/a');
    $uid = TokenService::getCurrentUid();

    $order = new OrderService();
    $status = $order->place($uid, $products);
    return $status;
  }
}

?>