<?php

namespace api\modules\v1\controllers\goods;

use Yii;
use api\controllers\OnAuthController;
use common\models\goods\Style;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 */
class StyleController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Style::class;
    protected $authOptional = ['search'];
    /**
     * 款式商品列表
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return [];
    }
    
    public function actionSearch()
    {
        $sort_map = [
            "1_0"=>'s.sale_price asc',//销售价
            "1_1"=>'s.sale_price desc',
            "2_0"=>'s.virtual_volume asc',//虚拟销量
            "2_1"=>'s.virtual_volume desc',
            "3_0"=>'s.onsale_time asc',//上架时间
            "3_1"=>'s.onsale_time desc',
            "4_0"=>'s.rank asc',//权重
            "4_1"=>'s.rank desc',
        ];
        
        $type_id = \Yii::$app->request->post("type_id");//产品线
        $keyword = \Yii::$app->request->post("keyword");//产品线
        $sort = \Yii::$app->request->post("sort",'4_1');//排序
        $page = \Yii::$app->request->post("page",1);//页码
        $page_size = \Yii::$app->request->post("page_size",20);//每页大小
        $language = \Yii::$app->language;//查询语言
        
        $order = $sort_map[$sort] ?? '';
        
        $fields = ['s.id','s.style_sn','lang.style_name','s.style_image','s.sale_price','s.virtual_volume as sale_volume'];
        $query = Style::find()->alias('s')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"s.id=lang.master_id and lang.language='".$language."'")
            ->orderby($order);
        
        if($type_id) {
            $query->andWhere(['=','s.type_id',$type_id]);
        }
        if($keyword) {
            $query->andWhere(['or',['like','lang.style_name',$keyword],['=','s.style_sn',$keyword]]);
        }        
        $result = $this->pagination($query,$page,$page_size);
        
        foreach($result['data'] as & $val) {
            $val['sale_price_label'] = Yii::t("common","参考价"). ' ￥'.$val['sale_price']; 
            $val['style_image'] = ImageHelper::thumb($val['style_image']);
        } 
        
        return $result;
        
    }
    
}