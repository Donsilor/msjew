<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use unclead\multipleinput\MultipleInput;
use common\helpers\Html;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-1 text-right'>{label}</div><div class='col-sm-11'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">

        <?php echo Html::langTab('tab')?>
        <br/>
        <?= $form->field($model, 'pid')->dropDownList($menuDropDownList) ?>
        <div class="tab-content">
            <?php
            echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab',
                'fields'=>
                    [
                        'title'=>['type'=>'textInput'],

                    ]]);
            ?>
        </div>

        <?= $form->field($model, 'url')->textInput()->hint("例如：/index/index，要绝对路由哦") ?>
        <?= $form->field($model, 'params')->widget(MultipleInput::class, [
            'max' => 1,
            'columns' => [
                [
                    'name'  => 'key',
                    'title' => '参数名',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ],
                [
                    'name'  => 'value',
                    'title' => '参数值',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ],
            ]
        ])->label(false);
        ?>
        <?= $form->field($model, 'icon')->textInput()->hint('详情请参考：<a href="http://fontawesome.dashgame.com" target="_blank">http://fontawesome.dashgame.com</a>')?>
        <?= $form->field($model, 'sort')->textInput() ?>
        <?= $form->field($model, 'dev')->radioList(WhetherEnum::getMap())->hint('去 网站设置->系统设置 里面开启或关闭开发模式,开启后才可显示该菜单') ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>