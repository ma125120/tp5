<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class Category extends BaseModel
{
    use SoftDelete;
    //protected $hidden=['pivot','create_time','delete_time'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    static function getCategorys() {
        //return null;
        return self::with('img')
                        ->select();
    }

    

    public function img() {
        return $this->belongsTo('Image','topic_img_id','id')
                    ->bind([
                      'top_img_url'   =>  'url'
                    ]);;
    }

}