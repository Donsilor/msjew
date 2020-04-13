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
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 购物卡详情：</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td width="20%" align="center">卡号：</td>
                        <td><?= $cardModel->sn; ?></td>
                        <td width="20%" align="center">购物卡总金额：</td>
                        <td><?= $cardModel->amount; ?></td>
                    </tr>
                    <tr>
                        <td align="center">发卡时间：</td>
                        <td><?= \Yii::$app->formatter->asDatetime($cardModel->created_at); ?></td>
                        <td align="center">购物卡剩余金额：</td>
                        <td><?= $cardModel->balance; ?></td>
                    </tr>
                    <tr>
                        <td align="center">发卡人：</td>
                        <td><?= $cardModel->user->username; ?></td>
                        <td align="center">购物卡状态：</td>
                        <td><?= '?'; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <div class="box-body table-responsive">
                <?php echo Html::batchButtons(false) ?>
                <?= GridView::widget([
                    'layout' => "{items}",
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'showFooter' => false,//显示footer行
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
                            'label' => '订单号',
                            'value' => function($model) {
                                if(!empty($model->order)) {
                                    return $model->order->order_sn;
                                }
                                return '---';
                            }
                        ],
                        [
                            'label' => '订单总金额',
//                            'filter' => false,
//                            'attribute' => 'use_amount_cny',
                            'value' => function($model) {
                                return $model->order->account->currency . ' ' . $model->order->account->order_amount;
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
                            'label' => '剩余金额',
                            'attribute' => 'balance',
                        ],
                        [
                            'label' => '购物卡状态',
                            'filter' => false,
                            'attribute' => 'status',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model) {
                                return \common\enums\FrameEnum::getValue($model->status);
                            },
                        ],
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
