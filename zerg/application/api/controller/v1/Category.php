<?php
namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\api\validate\Count;
use app\lib\exception\CategoryException;

class Category {

	/*
	*@url themes?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getCategorys() {
      //(new Count())->goCheck();

      $result = CategoryModel::getCategorys();
      
      if(!$result) {
          throw new CategoryException();
      }
      return $result;   
   }

   /*
	*@url themes?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getTheme($id=0) {
      (new IDMustBePostiveInt())->goCheck();

      $themes = ThemeModel::getTheme($id);

      if(!$themes) {
          throw new BaseException([
            'code'=>2000,
            'msg'=>'no data',
            'errorCode'	=>	30000
          ]);
      }
      return $themes;   
   }

}

?>