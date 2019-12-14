<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods', $typeModel['type_name'].'商品列表');
$style_title = Yii::t('goods', $typeModel['type_name'].'管理');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['style/index?type_id='.Yii::$app->request->get('type_id',0)]) ?>"> <?= Html::encode($style_title) ?></a></li>
                <li class="active"><a href="<?= Url::to(['goods/index?type_id='.Yii::$app->request->get('type_id',0)]) ?>"> <?= Html::encode($this->title) ?></a></li>
                <li class="pull-right">
                    <div class="box-header box-tools"> <?= Html::create(['edit-lang','type_id'=>Yii::$app->request->get('type_id',0)]) ?></div>
                </li>
            </ul>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

            'id',
            //'style_id',
            'goods_sn',
            'type_id',
            //'goods_image',
            //'merchant_id',
            //'cat_id',
            //'cat_id1',
            //'cat_id2',
            'sale_price',
            //'sale_volume',
            'market_price',
            //'promotion_price',
            //'promotion_type',
            //'storage_alarm',
            //'goods_clicks',
            //'goods_collects',
            //'goods_comments',
            //'goods_stars',
            //'goods_storage',
            'is_stock',
            //'goods_id',
            'status',
            //'verify_status',
            //'verify_remark',
            'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {status} {view}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit', 'id' => $model->id]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
                'view'=> function($url, $model, $key){
                    return Html::a('预览', '',['class'=>'btn btn-info btn-sm']);
                },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
