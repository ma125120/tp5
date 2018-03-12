<?php

namespace app\api\validate;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products'	=>	'checkProducts'
    ];

    protected $singleRule = [
    	'product_id'	=>	'require|isPositiveInteger',
    	'count'	=>	'require|isPositiveInteger'
    ];

    public function checkProducts($values) {
    	if(empty($values)) {
    		throw new ParamException(['msg'=>'商品列表不能为空']);
    	}
      if(!is_array($values)) {
        throw new ParamException(['msg'=>'商品参数不正确,应该为数组']);
      }
      foreach ($values as $key => $value) {
        $this->checkProduct($value);
      }
      return true;
    }

    public function checkProduct($value) {
    	$validate = new BaseValidate($this->singleRule);
    	$result = $validate->check($value);
    	if(!$result) {
    		throw new ParamException(['msg'=>'商品列表参数错误']);
    	}
    }

}