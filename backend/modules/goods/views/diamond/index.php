<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods_diamond', 'Diamonds');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit-lang']) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover'],
        'showFooter' => true,//显示footer行
        'id'=>'grid',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

            'id',
            [
                'attribute' => 'lang.goods_name',
                'value' => 'lang.goods_name',
                'filter' => Html::activeTextInput($searchModel, 'goods_name', [
                    'class' => 'form-control',
                    'style' =>'width:100px'
                ]),
                'format' => 'raw',
            ],
            [
                'attribute' => 'goods_sn',
                'filter' => true,
                'format' => 'raw',
            ],

            //'goods_image',
            //'goods_num',
            [
                'attribute' => 'cert_type',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\DiamondEnum::$typeOptions[$model->cert_type];
                },
                'filter' => Html::activeDropDownList($searchModel, 'cert_type',\common\enums\DiamondEnum::$typeOptions, [
                    'prompt' => '全部',
                    'class' => 'form-control',
                ]),
            ],

            [
                'attribute' => 'cert_id',
                'filter' => false,
                'format' => 'raw',
            ],
            [
                'attribute' => 'sale_price',
                'filter' => false,
                'format' => 'raw',
            ],

            //'cost_price',
            [
                'attribute' => 'carat',
                'filter' => false,
                'format' => 'raw',
            ],

            //'clarity',
            //'cut',
            //'color',
            //'shape',
            //'depth_lv',
            //'table_lv',
            //'symmetry',
            //'polish',
            //'fluorescence',
            //'source_id',
            //'source_discount',
            //'is_stock',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\FrameEnum::getValue($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\FrameEnum::getMap(), [
                    'prompt' => '全部',
                    'class' => 'form-control',
                ]),
            ],
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit-lang', 'id' => $model->id]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
