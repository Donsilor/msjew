<?php

namespace backend\modules\common\controllers;


use common\models\common\Advert;
use common\models\common\AdvertLang;
use common\models\goods\Type;
use function Complex\negative;
use services\common\AdvertService;
use Yii;
use common\models\common\AdvertImages;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use yii\base\Exception;

/**
* AdvertImages
*
* Class AdvertImagesController
* @package backend\modules\setting\controllers
*/
class AdvertImagesController extends BaseController
{
    use Curd;
    
    /**
    * @var AdvertImages
    */
    public $modelClass = AdvertImages::class;
    public $adv_id;
    public function init()
    {
        $this->adv_id = Yii::$app->request->get('adv_id');        
        parent::init();
    }
    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);
        
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['title','start_end','adv_type','area_ids']);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.title',$searchModel->title]) ;
        $dataProvider->query->andWhere(['>','common_advert_images.status',-1]);

        if($searchModel->area_ids){
            $dataProvider->query->andFilterWhere(['like', 'area_ids',','.$searchModel->area_ids.',']) ;
        }

        $dataProvider->query->joinWith(['cate']);
        $adv_type = $searchModel->adv_type;
        if(!empty($adv_type)){
            $dataProvider->query->andWhere(['=','adv_type',$searchModel->adv_type]);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEditLang()
    {
        $id = Yii::$app->request->get('id');
        
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            try{
                $trans = Yii::$app->db->beginTransaction();
                if(false === $model->save()){
                    throw new Exception($this->getError($model));
                }
                $this->editLang($model);
                
                \Yii::$app->services->advert->createAdverArea($model->id);
                $trans->commit();
                return $this->redirect(['advert-images/index']);
            }catch (Exception $e){
                $trans->rollBack();
                $error = $e->getMessage();
                return $this->message($error, $this->redirect(['advert-images/index']), 'error');
            }
        }
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
    /**
     * 删除
     * @param unknown $id
     * @return mixed|string
     */
    public function actionDelete($id)
    {
        if ($model = $this->findModel($id)) {
            $model->status = -1;
            $model->save();
            return $this->message("删除成功", $this->redirect(['index', 'id' => $model->id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'id' => $model->id]), 'error');
    }
}
