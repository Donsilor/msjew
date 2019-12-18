<?php

namespace api\modules\v1\controllers\goods;

use Yii;
use api\controllers\OnAuthController;
use common\models\goods\Style;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;
use common\enums\StatusEnum;

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
    protected $authOptional = ['search','detail','guess-list'];
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
    /**
     * 款式商品搜索
     * @return array
     */
    public function actionSearch()
    {
        $sort_map = [
            "1_0"=>'s.sale_price asc',//销售价
            "1_1"=>'s.sale_price desc',
            "2_0"=>'s.goods_clicks asc',//浏览量
            "2_1"=>'s.goods_clicks desc',
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
        
        $order = $sort_map[$sort] ?? '';
        
        $fields = ['s.id','s.style_sn','lang.style_name','s.style_image','s.sale_price','s.goods_clicks'];
        $query = Style::find()->alias('s')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"s.id=lang.master_id and lang.language='".\Yii::$app->language."'")
            ->orderby($order);
        
        if($type_id) {
            $query->andWhere(['=','s.type_id',$type_id]);
        }
        if($keyword) {
            $query->andWhere(['or',['like','lang.style_name',$keyword],['=','s.style_sn',$keyword]]);
        }        
        $result = $this->pagination($query,$page,$page_size);
        
        foreach($result['data'] as & $val) {
            $val['currency'] = '$'; 
            $val['style_image'] = ImageHelper::thumb($val['style_image']);
        } 
        
        return $result;
        
    }
    
    public function actionDetail()
    {
        $id = \Yii::$app->request->get("id");
        if(empty($id)) {
            return ResultHelper::api(422,"id不能为空");
        }
        $model = Style::find()->where(['id'=>$id])->one();
        if(empty($model)) {
            return ResultHelper::api(422,"商品信息不存在");
        }
        $style_attrs = \Yii::$app->services->goods->formatStyleAttrs($model);
        $attr_list = [];
        foreach ($style_attrs['style_attr'] as $attr){
            
            if(is_array($attr['value'])){
                $attr_value = implode('/',$attr['value']);
            }else{
                $attr_value = $attr['value'];
            }
            $attr_list[] = [
                    'attr_name'=>$attr['attr_name'],
                    'attr_value'=>$attr_value                    
            ];
        }
        if($model->goods_images) {
            $goods_images = explode(",",$model->goods_images);
            $goods_images = [
                    'big'=>ImageHelper::thumbs($goods_images),
                    'thumb'=>ImageHelper::thumbs($goods_images),
            ];
        }else{
            $goods_images = [];
        }
        $info = [
                'id' =>$model->id,
                'type_id'=>$model->type_id,
                'style_name'=>$model->lang->style_name,
                'style_moq'=>1,
                'sale_price'=>$model->sale_price,
                'currency'=>'$',
                'goods_images'=>$goods_images,
                'goods_3ds'=>$model->style_3ds,
                'attr_list' =>$attr_list,                
        ];
        return $info;
    }
    /**
     * 猜你喜欢推荐列表
     */
    public function actionGuessList()
    {
        $id = \Yii::$app->request->get("id");
        if(empty($id)) {
            return ResultHelper::api(422,"id不能为空");
        }
        $model = Style::find()->where(['id'=>$id])->one();
        if(empty($model)) {
            return [];
        }
        $type_id = $model->type_id;
        $fields = ['s.id','s.style_sn','lang.style_name','s.style_image','s.sale_price','s.goods_clicks'];
        $query = Style::find()->alias('s')->select($fields)
                    ->leftJoin(StyleLang::tableName().' lang',"s.id=lang.master_id and lang.language='".\Yii::$app->language."'")
                    ->andWhere(['s.type_id'=>$type_id])
                    ->andWhere(['<>','s.id',$id])
                    ->orderby("s.goods_clicks desc");
        $models = $query->limit(10)->asArray()->all();
        foreach ($models as &$model){
            $model['style_image'] = ImageHelper::thumb($model['style_image']);
            $model['currency'] = '$';
        }
        return $models;        
    }
    
    
    
}