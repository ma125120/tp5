<?php
namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ThemeException;
use \app\api\validate\IDCollection;

class Theme {

	/*
	*@url themes?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getThemes($ids) {
      (new IDCollection())->goCheck();

      $themes = ThemeModel::getThemes($ids);

      if(!$themes) {
          throw new ThemeException();
      }
      return $themes;   
   }

   /*
	*@url themes?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getTheme($id=0) {
      (new IDMustBePostiveInt())->goCheck();

      $result = ThemeModel::getTheme($id);

      if(!$result) {
          throw new ThemeException();
      }
      return $result;   
   }

}

?>