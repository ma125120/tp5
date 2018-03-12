<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class ProductProperty extends BaseModel
{
    use SoftDelete;
    protected $hidden=['id','product_id','delete_time'];
    //protected $visible=['url'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    
}