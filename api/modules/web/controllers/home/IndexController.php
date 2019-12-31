<?php

namespace api\modules\web\controllers\home;

use Yii;
use api\controllers\OnAuthController;
use common\models\goods\Style;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;
use yii\db\Expression;
use common\models\goods\AttributeIndex;

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
        $type_id = 12;
        $limit = 6;
        $language = $this->language;
        $fields = ['m.id', 'm.goods_images', 'm.style_sn','lang.style_name','m.sale_price'];
        $style_list = \Yii::$app->services->goodsStyle->getStyleList($type_id,$limit, $fields ,$language);
        $webSite = array();
        $webSite['moduleTitle'] = '最暢銷訂婚戒指';
        foreach ($style_list as $val){
            $moduleGoods = array();
            $moduleGoods['id'] = $val['id'];
            $moduleGoods['categoryId'] = $type_id;
            $moduleGoods['coinType'] = $this->currency;
            $moduleGoods['goodsCode'] = $val['style_sn'];
            $moduleGoods['goodsImages'] = $val['goods_images'];
            $moduleGoods['goodsName'] = $val['style_name'];
            $moduleGoods['salePrice'] = $val['sale_price'];
            $webSite['moduleGoods'][] = $moduleGoods;
        }

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