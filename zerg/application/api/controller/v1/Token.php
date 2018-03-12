<?php
namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\validate\TokenGet;
use app\api\exception\TokenException;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;

class Token {

	/*
	*@url token?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getToken($code='') {
    
      (new TokenGet())->goCheck();
      // echo $code;
      $ut = new UserToken($code);
      $token=$ut->get();

      if(!$token) {
          throw new TokenException();
      }
      return ['token'=>$token];   
   }

   public function verifyToken($token=''){
      if(!$token){
          throw new ParameterException([
            'token不允许为空'
          ]);
      }
      $valid = TokenService::verifyToken($token);
      return [
        'isValid' => $valid
      ];
    }

}

?>