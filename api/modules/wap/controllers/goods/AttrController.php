<?php

namespace api\modules\wap\controllers\goods;

use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\goods\Attribute;
use common\models\goods\AttributeLang;
use api\controllers\OnAuthController;
use common\models\goods\AttributeSpec;
use common\models\goods\AttributeValue;
use common\models\goods\AttributeValueLang;

/**
 * Class ProvincesController
 * @package api\modules\web\controllers\goods
 */
class AttrController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Attribute::class;
    protected $authOptional = ['conditions'];

    /**
     * 属性信息
     * @return array
     */

    public function actionConditions(){
        $attr_ids = \Yii::$app->request->get("attr_ids" );//属性ID
        $type_id = \Yii::$app->request->get("type_id" );//产品线ID
        if(!$attr_ids){
            return ResultHelper::api(422, '属性ID不能为空');
        }
        if(!$type_id){
            return ResultHelper::api(422, '产品线ID不能为空');
        }
        $attr_ids = json_decode($attr_ids);
        $spec_attr_value_list = array();
        foreach ($attr_ids as $attr_id){

            //查询被选中的属性值、属性名和代号
            $attr_values = AttributeSpec::find()->alias('a')
                ->innerJoin(Attribute::tableName()." attr", 'attr.id = a.attr_id')
                ->innerJoin(AttributeLang::tableName()." lang", 'attr.id = lang.master_id')
                ->where(['a.attr_id'=>$attr_id,'a.type_id'=>$type_id])
                ->asArray()
                ->select(['a.attr_values','attr.code','lang.attr_name'])
                ->one();

            if(empty($attr_values)){
                continue;
            }
            $attr_value_ids = explode(',' ,$attr_values['attr_values']);
            $models = AttributeValue::find()->alias("val")
                ->leftJoin(AttributeValueLang::tableName()." lang","val.id=lang.master_id and lang.language='".$this->language."'")
                ->select(['val.id','val.image','lang.attr_value_name','lang.remark'])
                ->where(['and',['in', 'val.id', $attr_value_ids],['val.status'=>StatusEnum::ENABLED]])
                ->asArray()->all();
            $spec_attr_value =array();
            $spec_attr_value['name'] = $attr_values['attr_name'];
            $spec_attr_value['code'] = $attr_values['code'];
            $spec_attr_value['list'] = $models;
            $spec_attr_value_list[$attr_id] = $spec_attr_value;

        }
        return $spec_attr_value_list;

    }



}