<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods_ring', '对戒');
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
            [
                'class'=>'yii\grid\CheckboxColumn',
                'name'=>'id',  //设置每行数据的复选框属性
                'headerOptions' => ['width'=>'30'],
                'footer'=> Html::batchButtons(['status_enabled','status_disabled','batch_delete']),//['search_export','status_disabled']
                'footerOptions' => ['colspan' => 4],  //设置删除按钮垮列显示
            ],
            [
                'attribute' => 'id',
                'value' => 'id',
                'filter' => Html::activeTextInput($searchModel, 'id', [
                    'class' => 'form-control',
                    'style' =>'width:50px'
                ]),
                'format' => 'raw',
            ],
            [
                'attribute' => 'ring_images',
                'value' => function ($model) {
                    if(!empty($model->ring_images)){
                        $ring_images = explode(',', $model->ring_images);
                        $ring_image = $ring_images[0];
                    }else{
                        $ring_image = '';
                    }
                    return ImageHelper::fancyBox($ring_image);
                },
                'filter' => false,
                'format' => 'raw',
            ],
            [
                'attribute' => 'lang.ring_name',
                'value' => 'lang.ring_name',
                'filter' => Html::activeTextInput($searchModel, 'ring_name', [
                    'class' => 'form-control',
                    'style' =>'width:200px'
                ]),
                'format' => 'raw',
            ],

            [
                'attribute' => 'ring_sn',
                'filter' => Html::activeTextInput($searchModel, 'ring_sn', [
                    'class' => 'form-control',
                    'style' =>'width:100px'
                ]),
                'format' => 'raw',
            ],
            [
                'attribute' => '产品线',
                'value'=>function($model){
                    return '对戒';
                }
            ],
            [
                'attribute' => 'sale_price',
                'filter' => Html::activeTextInput($searchModel, 'sale_price', [
                    'class' => 'form-control',
                    'style' =>'width:100px'
                ]),
                'format' => 'raw',
            ],

            [
                'attribute'  => '库存',
                'value' => function($model){
                    return Yii::$app->services->goodsStyle->getRingStorage($model->id);
                }
            ],


            //'ring_image',
            //'qr_code',
            //'ring_salenum',
            //'ring_style',
            //'sale_price',
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

            [
                'attribute' => 'created_at',
                'filter' => false,
                'value' => function($model){
                    return date('Y-m-d',$model->created_at);
                },
            ],

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
