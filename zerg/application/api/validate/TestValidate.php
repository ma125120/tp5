<?php
/**
 * Created by 七月.
 * Author: 七月
 * Date: 2017/4/17
 * Time: 5:58
 */

namespace app\api\validate;

use app\api\validate\BaseValidate;

use think\Validate;

class TestValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'email'
    ];
}