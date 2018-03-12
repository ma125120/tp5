<?php

namespace app\api\model;
use think\Model;
use think\Exception;
use app\lib\exception\BannerMissException;
use traits\model\SoftDelete;

class BannerItem extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['update_time','delete_time','img_id','banner_id'];
    protected $autoWriteTimestamp = true;
    //指定字段名
    
    protected $creteTime = 'create_time';//也可以设置成false
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';
    protected $type = [
        //'test'  =>  'serialize',
        'update_time'   =>  'timestamp'
    ];
    
    protected $auto = [
        'time'
    ];

    public function img()
    {
        return $this->belongsTo('Image','img_id','id')->bind('url');
    }

    //protected $pk = 'id';
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     *
     */
    public static function getBannerItemByid($id=0)
    {  
        try {
            $result=BannerItem::where('banner_id',$id)->select();
        } catch(Exception $e) {
            throw $e;
        }
        return $result;
        //return null;  
    }
}