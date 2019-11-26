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
                    'footer'=>'<span class="btn btn-success btn-sm jsBatchStatus" data-grid="grid" data-url="'.Url::to(['attribute/ajax-batch-update']).'" data-value="1">批量启用</span>  
                               <span class="btn btn-default btn-sm jsBatchStatus" data-grid="grid" data-url="" data-value="0">批量禁用</span> 
                               <span class="btn btn-primary jsPExport" data-grid="grid" data-url="" data-value="0">数据导出</span> 
                               <span class="btn btn-danger jsBatchStatus" data-grid="grid" data-url="" data-value="-1">批量删除</span>',
                    //'footer' => Html::multiDelete(['attribute/ajax-multi-update']).' '.Html::multiDelete(['attribute/ajax-multi-update']),
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
                'label' => '分类',
                'attribute' => 'cate.cat_name',
                'filter' => Html::activeDropDownList($searchModel, 'cat_id', $cateDropDownList, [
                        'prompt' => '全部',
                        'class' => 'form-control'
                ]),
            ],
            [
                'attribute' => 'input_type',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                    'value' => function ($model){
                    return \common\enums\InputTypeEnum::getValue($model->input_type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'input_type',\common\enums\InputTypeEnum::getMap(), [
                        'prompt' => '全部',
                        'class' => 'form-control'
                ]),
            ],
            /* [
                'attribute' => 'is_require',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\ConfirmEnum::getValue($model->is_require);
                }
            ], */
            /* [
                'attribute' => 'is_system',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\ConfirmEnum::getValue($model->is_system);
                }
            ], */
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
            ],            
  
       ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
<!--

//-->
/* $(".js_qiyong").click(function(){

   alert(1);
   var ids = $("#grid").yiiGridView("getSelectedRows");
   alert(ids);
	
}); */
</script>
