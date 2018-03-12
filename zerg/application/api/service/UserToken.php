<?php
namespace app\api\service;

use app\lib\exception\WxException;
use app\lib\exception\TokenException;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use think\Exception;

class UserToken extends Token {

	protected $code;
	protected $appid;
	protected $secret;
	protected $loginUrl;

	function __construct($code) {
		//echo config('wx.secret');
		$this->code=$code;
		$this->appid=config('wx.appid');
		$this->secret=config('wx.secret');
		$this->loginUrl=sprintf(config('wx.login_url'),$this->appid,$this->secret,$code);
	}

	public function get() {
		$result = curl_get($this->loginUrl);
		$wxResult = json_decode($result,true);
		//dump($wxResult);
		if(empty($wxResult)) {
			throw new WxException('获取session_key及openid时异常，微信内部错误');
		} else {
			$loginFail=array_key_exists('errcode', $wxResult);
			if($loginFail) {
				$this->processLoginError($wxResult);
			} else {
				return $this->grantToken($wxResult);
			}
		}
	}

	private function processLoginError($wxResult) {
		throw new WxException([
			'msg'	=>	$wxResult['errmsg'] ,
			'errorCode'	=>	$wxResult['errcode']
		]);
	}

	private function grantToken($wxResult) {
		//拿到openid,
		//数据库里看一下，这个openid是不是已经存在
		//如果存在则不处理，如果不存在那么新增一条记录
		//生成令牌，准备缓存数据，写入缓存
		//返回令牌至客户端
		//key 令牌
		//value wxResult,uid,scope
		
		$openid=$wxResult['openid'];
		$user = UserModel::getByOpenId($openid);
		if($user) {
			$uid=$user->id;
		} else {
			$uid=self::newUser($openid);
		}

		$cacheValue=$this->prepareCacheValue($wxResult,$uid);
		$token = $this->saveToCache($cacheValue);

		return $token;
	}

	private function newUser($openid) {
		$user=UserModel::create([
			'openid'	=>	$openid
		]);
	}

	private function saveToCache($cacheValue) {
		$key=self::generateToken();
		$value = json_encode($cacheValue);
		$expire_in = config('setting.token_expire_in');

		$req = cache($key,$value,$expire_in);
		if(!$req) {
			throw new TokenException([
				'msg'	=>	'服务器缓存异常',
				'errorCode'	=>	'10005'
			]);
		}

		return $key;
	}

	private function prepareCacheValue($wxResult,$uid) {
		$cacheValue=$wxResult;
		$cacheValue['uid']=$uid;
		//scope = 16代表app用户的权限数值
		$cacheValue['scope'] = ScopeEnum::User;
		//scope=32 代表CMS(管理员)用户的权限数值
		//$cacheValue['scope']=32;

		return $cacheValue;
	}
}