<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $advert['name'];
$this->params['breadcrumbs'][] = $this->title;

$script = <<<SCRIPT
$(".gridviewdelete").on("click", function () {
if(confirm('您确定要删除吗？')){
    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: 'batchdelete',
            data: {ids:keys},
            type: 'post',
            success: function (t) {
                t = JSON.parse(t);
                if (t.status == 1) {
                    window.location.href= window.location.href;
                }
            },
            error: function () {
                alert("删除失败！")
            }
     
        })
    }
});
SCRIPT;
$this->registerJs($script);
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
        'options' =>['id'=>'grid'],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name'=>'id',
            ],//复选框列
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
    <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-default btn-xs btn-delete gridviewdelete']) ?>
            </div>
        </div>
    </div>
</div>
