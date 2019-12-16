<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
$start_time = Yii::$app->request->post('start_time', date('Y-m-d', strtotime("-1 year")));
$end_time = Yii::$app->request->post('end_time', date('Y-m-d', strtotime("+1 day")));
$title = Yii::$app->request->post('title');
$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('member_book', '客户留言');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <a href="<?= Url::to(['export?title='.$title.'&=start_time'.$start_time.'&end_time='.$end_time])?>" class="blue">导出Excel</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['index']),
                        'method' => 'post',
                    ]); ?>
                    <div class="col-sm-4">
                        <div class="input-group drp-container">
                            <?= DateRangePicker::widget([
                                'name' => 'queryDate',
                                'value' => $start_time . '-' . $end_time,
                                'readonly' => 'readonly',
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'startAttribute' => 'start_time',
                                'endAttribute' => 'end_time',
                                'startInputOptions' => ['value' => $start_time],
                                'endInputOptions' => ['value' => $end_time],
                                'pluginOptions' => [
                                    'locale' => ['format' => 'Y-m-d'],
                                ]
                            ]) . $addon;?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group m-b">
                            <input type="text" class="form-control" name="title" placeholder="标题或内容" value="<?= $title ?>"/>
                            <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
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
            'created_at:datetime',

            [
                'attribute'=>'member_id',
                'value'=>function($model){
                    $memeber = Yii::$app->services->member->get($model->member_id);
                    return $memeber['email'];
                },
            ],
            //'language',
            'title',

            //'first_name',
            //'last_name',
            //'telphone',
            //'type_id',

            [
               'attribute'=>'content',
                'value'=>function($model){
                    return "<div style=\"width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis\">".$model->content."</div>";
                    },
                'format' => 'raw',

            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return common\enums\MemberEnum::getBookStatus()[$model->status];
                },
            ],


            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit}  {view}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                        ]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
                'view'=> function($url, $model, $key){
                    return Html::a('详情', ['detail','member_id'=>$model->member_id],['class'=>'btn btn-info btn-sm']);
                },

                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
