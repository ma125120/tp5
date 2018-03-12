<?php

namespace app\api\model;
use think\Model;

use traits\model\SoftDelete;

class Theme extends BaseModel
{
    use SoftDelete;
    protected $hidden=['topic_img_id','head_img_id','update_time','delete_time'];
    protected $autoWriteTimestamp = true;
    //指定字段名

    public static function getThemes($ids='')
    {  
    		//echo $ids;
        try {
            $themes=self::with(['topicImg','headImg'])->select($ids);
										//where('id','in',$ids)->select($ids);//->items;
        } catch(Exception $e) {
            throw $e;
        }
        return $themes;
    }

    public static function getTheme($id='')
    {  
        try {
            $theme=self::with('products,topicImg,headImg')->find($id);
										//where('id','in',$ids)->select($ids);//->items;
        } catch(Exception $e) {
            throw $e;
        }
        return $theme;
    }

    public function topicImg()
    {
        return $this->belongsTo('Image','topic_img_id','id')
        					->bind([
        							'topic_img_url'	=>	'url'
        						]);
    }

    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id')
        						->bind([
        							'head_img_url'	=>	'url'
        						]);
    }

    public function products()
    {
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

}