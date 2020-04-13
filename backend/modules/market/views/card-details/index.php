<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$card_title = Yii::t('card',  '购物卡发放列表');
$this->title = Yii::t('card', '购物卡使用列表');
$this->params['breadcrumbs'][] = $this->title;
$type_id = Yii::$app->request->get('type_id', 0);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?= Url::to(['card/index']) ?>"> <?= Html::encode($card_title) ?></a>
                </li>
                <li class="active">
                    <a href="<?= Url::to(['card-details/index']) ?>"> <?= Html::encode($this->title) ?></a>
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
                        [
                            'label' => '序号',
                            'attribute' => 'id',
                            'filter' => true,
                            'format' => 'raw',
                            'headerOptions' => ['width' => '80'],
                        ],
                        [
                            'label' => '使用时间',
                            'value' => function($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            }
                        ],
                        [
                            'label' => '卡号',
                            'filter' => false,
                            'attribute' => 'card.sn',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a($model->card->sn, ['card/view', 'id' => $model->card->id], ['style'=>"text-decoration:underline;color:#3c8dbc"]);
                            }
                        ],
                        [
                            'label' => '订单号',
                            'value' => function($model) {
                                if(!empty($model->order)) {
                                    return $model->order->order_sn;
                                }
                                return '---';
                            }
                        ],
                        [
                            'label' => '余额变动',
                            'filter' => false,
                            'format' => 'raw',
                            'attribute' => 'use_amount_cny',
                            'value' => function($model) {
                                return $model->currency . ' ' . $model->use_amount . ' <br/> CNY ' . $model->use_amount_cny;
                            }
                        ],
                        [
                            'label' => '购物卡总金额',
                            'attribute' => 'card.amount',
                        ],
                        [
                            'label' => '剩余金额',
                            'attribute' => 'balance',
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
//                        [
//                            'class' => 'yii\grid\ActionColumn',
//                            'header' => '操作',
//                            'template' => '{edit} {status}',
//                            'buttons' => [
//                                'edit' => function ($url, $model, $key) {
//                                    return Html::edit(['edit-lang', 'id' => $model->id, 'type_id' => Yii::$app->request->get('type_id'), 'returnUrl' => Url::getReturnUrl()]);
//                                },
//                                'status' => function ($url, $model, $key) {
//                                    return Html::status($model['status']);
//                                }
//                            ]
//                        ]
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
