<?php
/**
 * Created by 七月.
 * Author: 七月
 * Date: 2017/4/18
 * Time: 5:15
 */

namespace app\api\validate;

use app\lib\exception\ParamException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{

    protected $scene = [
        'edit'  =>  ['id']   
    ];
    
    public function goCheck($scene='')
    {
        // 获取http传入的参数
        // 对这些参数做检验
        $request = Request::instance();
        $params = $request->param();
        //dump($params);
        if($scene) {
            $result = $this->scene($scene)->batch()->check($params); 
        } else {
            $result = $this->check($params);
            //$result = $this->batch()->check($params);
        }
        if(!$result){
            $error = $this->error;
            throw new ParamException([
                    'msg'   =>  $error
                ]);
        }
        else{
            return true;
        }
    }
    /*是否为正整数*/
    public function isPositiveInteger($value, $rule = '',
        $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        else{
            return false;
            //return $field.'必须是正整数';
        }
    }
    /*是否不为空*/
    public function isNotEmpty($value, $rule = '',
        $data = '', $field = '')
    {
        if(!empty($value)) {
            return true;
        }
        else{
            return false;
            //return $field.'必须是正整数';
        }
    }
    /*根据规则获取数据*/
    public function getDataByRule($arrays) {
        if(array_key_exists('user_id', $arrays) | 
           array_key_exists('uid', $arrays)) {
            throw new ParamException(['msg'=>'参数中含有的非法的参数名user_id或者uid']);
        }
        $newArray = [];
        foreach($this->rule as $key=>$value) {
            $newArray[$key]=$arrays[$key];
        }
        return $newArray;
    }

    public function isMobile($value) {
        //$rule = '^1(3|4|5|6|7|8|9)[0-9]\d{8}$^';
        $rule = '/[0-9]{3}\-\d+$/';
        $result = preg_match($rule,$value);
        //$result1 = preg_match($rule1,$value);
        if($result) {
            return true;
        } else {
            return false;
        }
    }  

    
}