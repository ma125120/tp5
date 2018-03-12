<?php
namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\lib\exception\ProductException;
use app\api\validate\IDMustBePostiveInt;
class Product {

	/*
	*@url themes?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getRecent($count=3) {
      (new Count())->goCheck();

      $result = ProductModel::getRecent($count);
      //$collection = collection($result)->hidden(['price']);
      if(!$result) {
          throw new ProductException();
      }
      return $result;   
   }

   /*
	*@url themes?ids=id1,id2,id3...
	*@return 一组theme模型
	 */
	public function getAllInCategory($id=0) {
      (new IDMustBePostiveInt())->goCheck();

      $result = ProductModel::getAllInCategory($id);

      if(!$result) {
          throw new ProductException();
      }
      return $result;   
   }

   public function getOne($id) {
      (new IDMustBePostiveInt())->goCheck();

      $result = ProductModel::getProductDetail($id);

      if(!$result) {
          throw new ProductException();
      }
      return $result;

   }
}

?>