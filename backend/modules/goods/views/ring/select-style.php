<?php

use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods', 'Styles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">

            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
//                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'showFooter' => true,//显示footer行
                    'id'=>'grid',
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false,
                        ],
                        [
                            'class'=>'yii\grid\RadioButtonColumn',
                            'name'=>'style_id',  //设置每行数据的复选框属性
                            'headerOptions' => ['width'=>'30'],

                        ],
                        [
                            'attribute' => 'lang.style_name',
                            'value' => 'lang.style_name',
                            'filter' => Html::activeTextInput($searchModel, 'style_name', [
                                'class' => 'form-control',
                                'style' =>'width:100px'
                            ]),
                            'format' => 'raw',

                        ],
                        [
                            'attribute' => 'style_sn',
                            'filter' => true,
                            'format' => 'raw',
                        ],

                        [
                            'attribute' => 'type_id',
                            'value' => "type.type_name",
                            'filter' => true,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'cat_id',
                            'value' => "cate.cat_name",
                            'filter' => true,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'sale_price',
                            'value' => "sale_price",
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'goods_storage',
                            'value' => "goods_storage",
                            'filter' => false,
                            'format' => 'raw',
                        ],


                    ]
                ]); ?>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>


</script>
