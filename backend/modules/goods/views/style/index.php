<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods', $typeModel['type_name'].'管理');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit-lang','type_id'=>Yii::$app->request->get('type_id',0)]) ?>
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
                'footer'=> Html::batchButtons(false),//['search_export','status_disabled']
                'footerOptions' => ['colspan' => 4],  //设置删除按钮垮列显示
            ],
            [
                'attribute' => 'id',
                'filter' => true,
                'format' => 'raw',
                'headerOptions' => ['width'=>'50'],
            
            ],
            [
                'attribute' => 'lang.language',
                 'value' => function ($model) {
                    return \common\enums\LanguageEnum::getValue($model->lang->language);
                 },
                 'filter' => Html::activeDropDownList($searchModel, 'language',\common\enums\LanguageEnum::getMap(), [
                        'prompt' => '默认',
                        'class' => 'form-control',
                ]),
                'headerOptions' => ['width'=>'110'],
            ], 
            [
                'attribute' => 'style_image',
                'value' => function ($model) {
                    return ImageHelper::fancyBox($model->style_image);
                },
                'filter' => false,
                'format' => 'raw',
                'headerOptions' => ['width'=>'80'],
                
            ],
                
            [
                'headerOptions' => ['width'=>'200'],
                'attribute' => 'lang.style_name',
                'value' => 'lang.style_name',
                'filter' => Html::activeTextInput($searchModel, 'style_name', [
                        'class' => 'form-control',
                ]),
                'format' => 'raw',                
            ],
            [
                'attribute' => 'style_sn',
                'filter' => true,
                'format' => 'raw',
                'headerOptions' => ['width'=>'120'],
            ],
            
            [
                    'attribute' => 'type_id',
                    'value' => "type.type_name",
                    'filter' => false,
                    'format' => 'raw',
            ],
            /* [
                'attribute' => 'cat_id',
                'value' => "cate.cat_name",
                'filter' => false,
                'format' => 'raw',
            ],    */         
            [
                'attribute' => 'sale_price',
                'value' => "sale_price",
                'filter' => true,
                'format' => 'raw',
                'headerOptions' => ['width'=>'100'],
            ],
            [
                'attribute' => 'sale_volume',
                'value' => "sale_volume",
                'filter' => true,
                'format' => 'raw',
                'headerOptions' => ['width'=>'80'],
            ],
            [
                'attribute' => 'goods_storage',
                'value' => "goods_storage",
                'filter' => true,
                'format' => 'raw',
                'headerOptions' => ['width'=>'80'],
            ],
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
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                    return Html::edit(['edit-lang','id' => $model->id,'type_id'=>Yii::$app->request->get('type_id')]);
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
