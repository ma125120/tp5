<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class OrderProduct extends BaseModel
{
    use SoftDelete;
    //protected $visible=['url'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    function getUrlAttr($value,$data) {
        return $this->prefixImageUrl($value,$data);
    }
}