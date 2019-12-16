<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods_diamond', '裸钻管理');
$this->params['breadcrumbs'][] = $this->title;
//$cert_type = \common\enums\DiamondEnum::getCertTypeList();
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
                'attribute' => 'goods_image',
                'value' => function ($model) {
                    return ImageHelper::fancyBox($model->goods_image);
                },
                'filter' => false,
                'format' => 'raw',
            ],
            [
                'attribute' => 'lang.goods_name',
                'value' => 'lang.goods_name',
                'filter' => Html::activeTextInput($searchModel, 'goods_name', [
                    'class' => 'form-control',
                    'style' =>'width:200px'
                ]),
                'format' => 'raw',
            ],

            [
                'attribute' => 'goods_sn',
                'value' => 'goods_sn',
                'filter' => Html::activeTextInput($searchModel, 'goods_sn', [
                    'class' => 'form-control',
                    'style' =>'width:100px'
                ]),
                'format' => 'raw',
            ],
            //'goods_image',
            //'goods_num',
//            [
//                'attribute' => 'cert_type',
//                'format' => 'raw',
//                'headerOptions' => ['class' => 'col-md-1'],
//                'value' => function ($model){
//                    return $cert_type[$model->cert_type];
//                },
//                'filter' => Html::activeDropDownList($searchModel, 'cert_type',$cert_type, [
//                    'prompt' => '全部',
//                    'class' => 'form-control',
//                ]),
//            ],
            [
                'attribute' => 'cert_id',
                'value' => 'cert_id',
                'filter' => Html::activeTextInput($searchModel, 'cert_id', [
                    'class' => 'form-control',
                    'style' =>'width:100px'
                ]),
                'format' => 'raw',
            ],

            [
                'attribute' => 'sale_price',
                'filter' => true,
                'format' => 'raw',
            ],

            //'cost_price',
            [
                'attribute' => 'carat',
                'filter' => true,
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
                'template' => '{edit} {status} {view}',
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
