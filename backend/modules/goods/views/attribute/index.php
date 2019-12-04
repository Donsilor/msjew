<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods_attribute', '属性管理');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit-lang', 'cate_id' => 0], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
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
                    'footer'=> Html::batchButtons(),//['search_export','status_disabled']
                    'footerOptions' => ['colspan' => 4],  //设置删除按钮垮列显示                        
            ],
            //'id',
            [
                'attribute'=>'attr_name',
                'value' =>'lang.attr_name',
                'filter' => Html::activeTextInput($searchModel, 'attr_name', [
                        'class' => 'form-control',
                        'style' =>'width:100px'
                ]),
            ],
            [
                 'attribute'=>'lang.attr_values',
            ], 
            [
                'attribute' => 'attr_type',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\AttrTypeEnum::getValue($model->attr_type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'attr_type',\common\enums\AttrTypeEnum::getMap(), [
                        'prompt' => '全部',
                        'class' => 'form-control'
                ]),
            ],
            [
                    //'label' => 'cat_name',
                    'attribute' => 'type.type_name',
                    'filter' => Html::activeDropDownList($searchModel, 'type_id', Yii::$app->services->goodsType->getDropDown(), [
                            'prompt' => '全部',
                            'class' => 'form-control'
                    ]),
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\StatusEnum::getValue($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\StatusEnum::getMap(), [
                        'prompt' => '全部',
                        'class' => 'form-control',
                        
                ]),
            ],
            [
                'attribute' => 'sort',
                'headerOptions' => ['style'=>'width:80px'],
                //'filter' => false,
            ],
            /* [
                'attribute'=>'created_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
                'format' => 'raw',
            ],
            [
                'attribute'=>'updated_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->updated_at);
                },
                'format' => 'raw',
            ], */
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {add} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit-lang', 'id' => $model->id]);
                },
                'add'=>function($url, $model, $key){
                    return Html::edit(['attribute-value/ajax-edit-lang','attr_id' => $model->id], '添加属性值', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                            'class'=>'btn btn-success btn-sm'
                    ]);
                },
                'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
            ],            
  
       ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>