<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $advert['name'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit-lang','adv_id' => $adv_id], '上传图片', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </div>
            </div>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'layout'=> '{items}',

        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

            'id',
            'lang.title',
            [
                'label' => '图片',
                "format"=>'raw',
                'value' => function($model) {
                    return Html::img($model->adv_image,["width"=>"100",]);
                 },
             ],

           // 'adv_url:url',
            'start_time:date',
            'end_time:date',
            'updated_at:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit}  {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['ajax-edit-lang', 'id' => $model->id , 'adv_id' => $model->adv_id],'编辑',[
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                        ]);
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
