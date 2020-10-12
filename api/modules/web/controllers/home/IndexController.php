<?php

namespace api\modules\web\controllers\home;

use api\controllers\OnAuthController;
use common\helpers\ImageHelper;
use common\models\goods\Style;
use services\market\CouponService;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 */
class IndexController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Style::class;
    protected $authOptional = ['web-site'];

    //商品推荐
    public function actionWebSite(){
        $type_id = 2;
        $limit = 6;
        $language = $this->language;
        $order = 'sale_volume desc';
        $fields = ['m.id', 'm.goods_images', 'm.style_sn','lang.style_name','IFNULL(markup.sale_price,m.sale_price) as sale_price'];
        $style_list = \Yii::$app->services->goodsStyle->getStyleList($type_id,$limit,$order, $fields ,$language);
        $webSite = array();
        $webSite['moduleTitle'] = \Yii::t('common','最畅销订婚戒指');
        foreach ($style_list as $val){
            $moduleGoods = array();
            $moduleGoods['id'] = $val['id'];
            $moduleGoods['categoryId'] = $type_id;
            $moduleGoods['coinType'] = $this->currency;
            $moduleGoods['goodsCode'] = $val['style_sn'];
            $moduleGoods['goodsImages'] = ImageHelper::goodsThumbs($val['goods_images'],'big');
            $moduleGoods['goodsName'] = $val['style_name'];
            $moduleGoods['salePrice'] = $this->exchangeAmount($val['sale_price'],0);

            $moduleGoods['coupon'] = [
                'type_id' => $type_id,//产品线ID
                'style_id' => $val['id'],//款式ID
                'price' => $moduleGoods['salePrice'],//价格
                'num' =>1,//数量
            ];

            $webSite['moduleGoods'][] = $moduleGoods;
        }

        CouponService::getCouponByList($this->getAreaId(), $webSite['moduleGoods']);

        $advert_list = \Yii::$app->services->advert->getTypeAdvertImage(0,1, $language);
        $advert = array();
        foreach ($advert_list as $val){
            $advertImgModelList = array();
            $advertImgModelList['addres'] = $val['adv_url'];
            $advertImgModelList['image'] = $val['adv_image'];
            $advertImgModelList['title'] = $val['title'];
            $advert[] = $advertImgModelList;
        }


        $result = array();
        $result['webSite'] = $webSite;
        $result['advert'] = $advert;
        return $result;

    }

    
    
}