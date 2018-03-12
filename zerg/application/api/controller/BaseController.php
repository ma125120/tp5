<?php

namespace app\api\controller;
use app\api\service\Token as TokenService;

class BaseController extends \think\Controller {
	//用户和CMS都可以访问
	protected function checkPrimaryScope() {
    TokenService::needPrimaryScope();
  }
  //用户可以访问
  protected function checkExclusiveScope() {
    TokenService::needExclusiveScope();
  }

}