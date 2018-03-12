<?php

namespace app\api\model;
use think\Model;
use think\Exception;
use traits\model\SoftDelete;
use app\lib\exception\BannerMissException;

class Banner extends BaseModel
{
    use SoftDelete;

    protected $autoWriteTimestamp = true;
    // protected $hidden = ['update_time','delete_time'];
    //指定字段名
    /*
    protected $creteTime = 'create_time';//也可以设置成false
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';*/
    // protected $auto = [
    //  'time'
    // ];
    //protected $table='banner';
    // protected $insert = [
    //  'time_insert'
    // ];

    // protected $update = [
    //  'time_update'
    // ];
    
    //protected $pk = 'id';
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     *
     */
    public static function getBannerById($id=0)
    {  
        try {
            $banner=self::with(['items','items.img'])->find($id);//->items;
        } catch(Exception $e) {
            throw $e;
        }
        return $banner;
    }


    public function items()
    {
        return $this->hasMany('banner_item','banner_id','id');
    }
}