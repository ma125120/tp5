<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class ProductImage extends BaseModel
{
    use SoftDelete;
    protected $hidden=['img_id','product_id','delete_time'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    public function imgUrl() {
    	return $this->belongsTo('Image','img_id','id')->bind(['img_src'=>'url']);
    }
}