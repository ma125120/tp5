<?php

namespace app\api\validate\v1;
use think\Validate;

class Banner extends Validate
{
	protected $rule = [
		'name' => 'require|max:25',
		'email' => 'email'
	];
}
?>