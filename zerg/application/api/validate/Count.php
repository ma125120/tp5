<?php

namespace app\api\validate;

class Count extends BaseValidate
{
    protected $rule = [
        'count'	=>	'isPositiveInteger|between:1,15'
    ];

    protected $message = [
    	'count.isPositiveInteger'		=>	'count必须为正整数'
    ];

}