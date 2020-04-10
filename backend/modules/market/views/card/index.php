<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('card', '购物卡发放列表');
$card_title = Yii::t('card',  '购物卡使用列表');
$this->params['breadcrumbs'][] = $this->title;
$type_id = Yii::$app->request->get('type_id', 0);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?= Url::to(['card/index']) ?>"> <?= Html::encode($this->title) ?></a>
                </li>
                <li>
                    <a href="<?= Url::to(['card-details/index']) ?>"> <?= Html::encode($card_title) ?></a>
                </li>
                <li class="pull-right">
                    <div class="box-header box-tools">
                        <?= Html::create(['edit-lang', 'type_id' => $type_id]) ?>
                    </div>
                </li>
            </ul>
            <div class="box-body table-responsive">
                <?php echo Html::batchButtons(false) ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'showFooter' => true,//显示footer行
                    'id' => 'grid',
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false,
                        ],
//                        [
//                            'class' => 'yii\grid\CheckboxColumn',
//                            'name' => 'id',  //设置每行数据的复选框属性
//                            'headerOptions' => ['width' => '30'],
//                        ],
                        [
                            'label' => '序号',
                            'attribute' => 'id',
                            'filter' => true,
                            'format' => 'raw',
                            //'headerOptions' => ['width' => '80'],
                        ],
                        [
                            'label' => '发卡时间',
                            'value' => function($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            }
                        ],
                        [
                            'label' => '卡号',
                            'filter' => false,
                            'attribute' => 'sn',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a($model->sn, ['view', 'id' => $model->id], ['style'=>"text-decoration:underline;color:#3c8dbc"]);
                            }
                        ],
                        [
                            'label' => '总金额',
                            'attribute' => 'amount',
                        ],
                        [
                            'label' => '余额',
                            'attribute' => 'balance',
                        ],
                        [
                            'label' => '有效时间',
                            'value' => function($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            }
                        ],
                        [
                            'label' => '使用范围',
                        ],
                        [
                            'label' => '操作人',
                        ],
                        [
                            'label' => '购物卡状态',
                            'attribute' => 'status',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model) {
                                return \common\enums\FrameEnum::getValue($model->status);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'status', \common\enums\FrameEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{edit} {status}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit-lang', 'id' => $model->id, 'type_id' => Yii::$app->request->get('type_id'), 'returnUrl' => Url::getReturnUrl()]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model['status']);
                                }
                            ]
                        ]
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
