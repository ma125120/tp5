<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class Product extends BaseModel
{
    use SoftDelete;
    protected $hidden=['pivot','create_time','delete_time'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    static function getRecent($num) {
        return self::limit($num)
        				->order('create_time desc')
        				->select();
    }

    static function getAllInCategory($categoryId) {
        return self::where('category_id','=',$categoryId)->select();
    }

    static function getProductDetail($id) {
        $product = self::with('imgs.imgUrl')
                    ->with(['properties'])
                    ->find($id);
        return $product;
    }

    function getMainImgUrlAttr($value,$data) {
    	return $this->prefixImageUrl($value,$data);
    }

    function imgs() {
        return  $this->hasMany('ProductImage','product_id','id')->order('order','asc'); 
    }

    function properties() {
        return  $this->hasMany('ProductProperty','product_id','id'); 
    }

}