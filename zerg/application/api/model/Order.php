<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class Order extends BaseModel
{
    use SoftDelete;
    protected $hidden=['user_id','delete_time','update_time'];

    public static function getSummaryByUser($uid,$page=1,$size=15) {
    	$pagingData = self::where('user_id','=',$uid)
    			->order('create_time desc')
    			->paginate($size,true,['page'=>$page]);
    	return $pagingData;
    }

    public function getSnapItemsAttr($value) {
    	if(empty($value)) {
    		return null;
    	}
    	return json_decode($value);
    }

    public function getSnapAddressAttr($value) {
    	if(empty($value)) {
    		return null;
    	}
    	return json_decode($value);
    }
}