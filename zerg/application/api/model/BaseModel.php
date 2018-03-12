<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    //protected $creteTime = false;//也可以设置成false
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';

    protected $hidden = ['update_time','delete_time'];
    //指定字段名

    function prefixImageUrl($value,$data) {
        if(array_key_exists('from',$data)&&$data['from']==1) {
            return config('setting.img_prefix').$value;
        }
        return $value;
    }
}