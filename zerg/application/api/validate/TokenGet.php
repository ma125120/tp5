<?php

namespace app\api\validate;

class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require'
    ];

    protected $message = [
    	'code'	=>	'code不能为空'
    ];
}