<?php
namespace app\api\controller\v1;

use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User;
use app\api\model\UserAddress;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;
use app\lib\enum\ScopeEnum;
use app\api\controller\BaseController;

class Address extends BaseController {
  protected $beforeActionList = [
    'checkPrimaryScope' =>  ['only'=>'createOrUpdateAddress,getUserAddress']
  ];

      /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
  public function getUserAddress(){
    $uid = TokenService::getCurrentUid();
    $userAddress = UserAddress::where('user_id', $uid)
        ->find();
    if(!$userAddress){
      throw new UserException([
          'msg' => '用户地址不存在',
          'errorCode' => 60001
      ]);
     }
     return $userAddress;
  }

	/*
	*@url token?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function createOrUpdateAddress() {
      $validate = new AddressNew();
      $validate->goCheck();
      // 根据token获取uid
      // 根据uid获取用户数据  
      // 如果用户不存在，抛出异常
      // 获取用户从客户端提交的地址信息
      // 根据用户地址是否存在，从而判断是添加地址还是更新地址
      $ut = TokenService::getCurrentUid('uid');

      $user = User::get($ut);
      if(!$user) {
        throw new UserException();
      }

      $dataArray = $validate->getDataByRule(input('post.'));
      
      $userAddress = $user->address;

      if(!$userAddress) {
        $user->address()->save($dataArray);//增加
      } else {
        $user->address->save($dataArray);//更新
      }

      return ['status' => 'success'];   
   }

   function test() {
    \think\Log::record('error','error');
   }

   function index() {
    return view('v1/user/index');
   }
}

?>