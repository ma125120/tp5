/**
 * Created by jimmy on 17/2/26.
 */

// var Base = require('../../utils/base.js').base;
import {Base} from '../../utils/base.js';

class Home extends Base{
    constructor(){
        super();
    }

    /*banner图片信息*/
    getBannerData(callback){
        var that=this;
        var param={
            url: 'banner/1',

            sCallback:function(data){
              console.log(123);
              console.log(data);
                data=data.items;
                callback && callback(data);
            }
        };
        this.request(param);
    }
    /*首页主题*/
    getThemeData(callback){
        var param={
            url: 'themes?ids=1,2,3',
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    /*首页部分商品*/
    getProductorData(callback){
        var param={
            url: 'product/recent/3',
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }
};

export {Home};