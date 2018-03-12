<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class UserAddress extends BaseModel
{
    use SoftDelete;
    //protected $visible=['url'];
    protected $createTime=false;

    //指定字段名

    public static function getByOpenId($openid) {
    	$user=self::where('openid','=',$openid)->find();
    	return $user;
    }
}