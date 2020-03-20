<?php

use common\helpers\Url;
use common\enums\PayEnum;
use common\helpers\Html;
use common\enums\StatusEnum;
use common\helpers\AmountHelper;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-hover text-center">
        <tbody>
        <tr>
            <td>支付编号</td>
            <td style="text-align:left"><?= Html::encode($model->out_trade_no) ?></td>
        </tr>
        <tr>
            <td>支付金额</td>
            <td style="text-align:left">
                应付金额：<?= AmountHelper::outputAmount($model->total_fee,2,$model->currency) ?><br>
                实际支付：<?= AmountHelper::outputAmount($model->pay_fee,2,$model->currency)?>
            </td>
        </tr>
        <tr>
            <td>支付来源</td>
            <td style="text-align:left">
                订单编号：<?= Html::encode($model->order_sn); ?><br>
                订单类型：<?= Html::encode($model->order_group); ?>
            </td>
        </tr>
        <tr>
            <td>支付类型</td>
            <td style="text-align:left"><?= PayEnum::$payTypeExplain[$model['pay_type']]; ?></td>
        </tr>
        <tr>
            <td>商户号</td>
            <td style="text-align:left"><?= Html::encode($model['mch_id']) ?></td>
        </tr>
        <tr>
            <td>
                回执订单号
            </td>
            <td style="text-align:left"><?= Html::encode($model['transaction_id']) ?></td>
        </tr>
        <tr>
            <td>交易类型</td>
            <td style="text-align:left"><?= Html::encode($model['trade_type']) ?></td>
        </tr>
        <tr>
            <td>状态</td>
            <td style="text-align:left">
                <?php if ($model['pay_status'] == StatusEnum::ENABLED) { ?>
                    <span class="label label-primary">支付成功</span>
                <?php } else { ?>
                    <span class="label label-danger">未支付</span>
                <?php } ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>