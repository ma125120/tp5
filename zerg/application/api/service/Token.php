<?php
namespace app\api\service;

use think\Request;
use think\Cache;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;
use app\lib\enum\ScopeEnum;
use think\Exception;

class Token {

	public static function generateToken() {
		//32个字符组成的随机字符串
		$randChars = getRandChar(32);
		$timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
		//salt盐
		$salt = config('secure.token_salt');

		return md5($randChars.$timestamp.$salt);
	}
	
	public static function getCurrentUid() {
		$uid = self::getCurrentTokenVar('uid');
		return $uid;
	}

	public static function getCurrentTokenVar($key) {
		$token = Request::instance()
						->header('token');

		$vars = Cache::get($token);

		if(!$vars) {
			throw new TokenException();
		} else {
			if(!is_array($vars)) {
				$vars = json_decode($vars,true);
			}
			if(array_key_exists($key, $vars)) {
				return $vars[$key];
			} else {
				throw new Exception('尝试获取的token并不存在');
			}
		}
		return $vars;
	}

	public static function needPrimaryScope() {
    $scope = self::getCurrentTokenVar('scope');
    if($scope) {
      if($scope >= ScopeEnum::User) {
        return true;
      } else {
        throw new ForbiddenException();
      }
    } else {
      throw new TokenException();
    }
  }

  public static function needExclusiveScope() {
    $scope = self::getCurrentTokenVar('scope');
    if($scope) {
      if($scope == ScopeEnum::User) {
        return true;
      } else {
        throw new ForbiddenException();
      }
    } else {
      throw new TokenException();
    }
  }

  public static function isVaildOperate($checkUID) {
  	if(!$checkUID) {
  		throw new Exception('检查UID时,UID不能为空');
  	}
  	$uid = self::getCurrentUid();
  	if($checkUID==$uid) {
  		return true;
  	} else {
  		return false;
  	}
  }

  public static function verifyToken($token) {
    $exist = Cache::get($token);
    if($exist){
      return true;
    } else {
      return false;
    }
  }
}