<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\goods\Style;
use common\components\Curd;
use common\models\base\SearchModel;

use backend\controllers\BaseController;
use yii\base\Exception;


/**
* Style
*
* Class StyleController
* @package backend\modules\goods\controllers
*/
class StyleController extends BaseController
{
    use Curd;

    /**
    * @var Style
    */
    public $modelClass = Style::class;


    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
        $type_id = Yii::$app->request->get('type_id',0);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);
        $typeModel = Yii::$app->services->goodsType->getAllTypesById($type_id);
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['style_name','language']);
        //切换默认语言
        $this->setLocalLanguage($searchModel->language);
        if($typeModel){
            $dataProvider->query->andFilterWhere(['in', 'type_id',$typeModel['ids']]);
        }
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.style_name',$searchModel->style_name]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,  
            'typeModel'  =>$typeModel,  
        ]);
    }
    
    /**
     * 编辑/创建 多语言
     *
     * @return mixed
     */
    public function actionEditLang()
    {
        $id = Yii::$app->request->get('id', null);       
        $type_id = Yii::$app->request->get('type_id', 0);
        
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $trans = Yii::$app->db->beginTransaction();
            try{
                
                if(false === $model->save()){
                    throw new Exception(current($model->getFirstErrors()));
                }
                $this->editLang($model);
                
                $trans->commit();
                return $this->message("保存成功", $this->redirect(['index','type_id'=>$type_id]), 'success');
            }catch (Exception $e){
                $trans->rollBack();
                return $this->message("保存失败:".$e->getMessage(), $this->redirect([$this->action->id]), 'error');
            }
        }
        return $this->render($this->action->id, [
                'model' => $model,
        ]);
    }
    
    public function editGoods($model) 
    {
        //基础属性
        $style_attr = json_decode($model->style_attr,true);
        //销售属性
        $style_spec = json_decode($model->style_spec,true);
    }
    
    /**
     * [Style] => Array
        (
            [type_id] => 1
            [cat_id] => 15
            [style_sn] => AAAA
            [market_price] => 123123
            [sale_price] => 3232
            [attr_require] => Array
                (
                    [5] => 0.5
                    [6] => 17
                    [4] => 14
                    [7] => 19
                )

            [goods_storage] => 32
            [goods_images] => Array
                (
                    [0] => http://www.bddmall.com/attachment/images/2019/12/03/image_157533495710056579.jpg
                    [1] => http://www.bddmall.com/attachment/images/2019/12/03/image_157533495699981019.jpg
                    [2] => http://pic.lrz0829.com/images/2019/12/03/image_157535278448101515.jpg
                    [3] => http://www.bddmall.com/attachment/images/2019/12/03/image_157533495699575055.jpg
                    [4] => http://pic.lrz0829.com/images/2019/12/03/image_157534097951985597.jpg
                )

        )

    [StyleLang] => Array
        (
            [zh-CN] => Array
                (
                    [style_name] => 3123123
                    [style_desc] => 3123123
                    [meta_title] => 1111111111
                    [meta_word] => 111
                    [meta_desc] => 111
                )

            [zh-TW] => Array
                (
                    [style_name] => 123123
                    [style_desc] => 312312
                    [meta_title] => 2222
                    [meta_word] => 222
                    [meta_desc] => 222
                )

        )

    [Spec] => Array
        (
            [1,7,9] => Array
                (
                    [goods_sn] => 31232
                    [market_price] => 3123213
                    [sale_price] => 31232
                    [goods_storage] => 32
                    [status] => 1
                )

        )
     */
    public function buildData(){
        $post = \Yii::$app->request->post();
        //款式
        //商品
        
        $style_attr = [];
    }
}
