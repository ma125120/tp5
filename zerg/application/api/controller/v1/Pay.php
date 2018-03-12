<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;

class Pay extends BaseController {

  
  protected $beforeActionList = [
    'checkExclusiveScope' =>  ['only'=>'placeOrder']
  ];

  public function getPreOrder($id='') {
    (new IDMustBePostiveInt())->goCheck();

    $pay = new PayService($id);

    return $pay->pay();
  }

  public function receiveNotify() {
    //通知频率为15/15/30/180/1800/1800/1800/1800/3600 ，单位：秒
    //1.检测库存量
    //2.更新该订单的status状态
    //3.减去库存
    //成功：我们返回微信成功处理的信息
    //失败：需要返回没有成功的信息
    //特点：post:xml格式，不会携带参数
    $nofify = new WxNotify();
    $nofify->handle();
  }
}

?>