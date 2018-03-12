<?php
namespace app\api\controller\v2;

use think\Exception;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\TestValidate;
use think\Validate;
//use app\lib\exception\BannerMissException;
use app\lib\exception\BannerMissException;

use app\api\model\Banner as BannerModel;
use app\api\model\BannerItem as BannerItemModel;
use think\Env;
class Banner
{
    public function index() {
        return 'dsada';
    }
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     *
     */
    public function getBanner($id=0)
    {
        return json(['id'=>'test']);
        
    }
    /**
     * 获取指定id的bannerItem信息
     * @url /bannerItem/:pid
     * @http GET
     * @id banner的id号
     *
     */
    public function getBannerItem($id=0)
    {
        (new IDMustBePostiveInt())->goCheck('edit');

        $banner = BannerItemModel::getBannerItemById($id);

        if(!$banner) {
            throw new BannerMissException([
                'code'=>2000,
                'msg'=>'no data'
                ]);
        }
        //return $banner;
        return json($banner);
        
    }

}