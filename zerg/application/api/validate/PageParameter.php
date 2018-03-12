<?php

namespace app\api\validate;

class PageParameter extends BaseValidate
{
    protected $rule = [
        'page'	=>	'isPositiveInteger',
        'size'  =>  'isPositiveInteger'
    ];

    protected $message = [
    	'page'	=>	'分页参数page必须是正整数',
        'size'  =>  '分页参数size必须是正整数'
    ];

}