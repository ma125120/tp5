<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class User extends BaseModel
{
    use SoftDelete;
    //protected $visible=['url'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    public function address() {
    	return $this->hasOne('UserAddress','user_id','id');
    }

    public static function getByOpenId($openid) {
    	$user=self::where('openid','=',$openid)->find();
    	return $user;

    }


}