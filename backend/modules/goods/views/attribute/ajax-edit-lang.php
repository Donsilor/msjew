<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\helpers\Html;
use common\enums\AttrTypeEnum;
use common\enums\InputTypeEnum;
use common\enums\ConfirmEnum;
use common\widgets\langbox\LangBox;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
</div>
    <div class="modal-body">
            <?php 
            echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab',
                    'fields'=>
                    [
                        'attr_name'=>['type'=>'textInput'],
                        'remark'=>['type'=>'textArea','options'=>[]]                            
                    ]]);
    	    ?>
            <!-- /.tab-content -->
            <?= $form->field($model, 'type_id')->widget(kartik\select2\Select2::class, [
                    'data' => Yii::$app->services->goodsType->getDropDown(),
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]);?>
            <?= $form->field($model, 'attr_type')->widget(kartik\select2\Select2::class, [
			        'data' => common\enums\AttrTypeEnum::getMap(),
                    'options' => [],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
            ]);?>             
            <?= $form->field($model, 'input_type')->radioList(common\enums\InputTypeEnum::getMap()) ?>
            <?= $form->field($model, 'is_require')->radioList(common\enums\ConfirmEnum::getMap())?>
            <?= $form->field($model, 'status')->radioList(StatusEnum::getMap())?>
            <?= $form->field($model, 'sort')->textInput() ?> 
                   
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>