<?php

namespace app\api\validate;

class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids'	=>	'require|checkIds'
    ];

    protected $message = [
    	'ids'		=>	'ids必须为正整数的数组'
    ];

    function checkIds($value,$rule='',$data='',$field='') {
    	$values=explode(',',$value);
    	if(empty($values)) {
    		return false;
    	}
    	foreach ($values as $id) {
    		if(!$this->isPositiveInteger($id)) {
    			return false;
    		}
    	}
    	return true;
    }
}