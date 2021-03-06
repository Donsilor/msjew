<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;
use common\enums\StatusEnum;

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
            <?php echo Html::langTab("tab_value")?>           
		 	<div class="tab-content">     
            <?= common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab_value',
                            'fields'=>[
                                'attr_value_name'=>['type'=>'textInput'],
                                'remark'=>['type'=>'textArea','options'=>[]]                            
                            ]]);
            ?>
            </div>
            <!-- /.tab-content -->
             <?= $form->field($model, 'attr_id')->textInput()->hiddenInput(['value'=>$model->attr_id])->label(false) ?>
             <?= $form->field($model, 'image')->widget(common\widgets\webuploader\Files::class, [
                    'config' => [
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
             <?php if(Yii::$app->user->identity->username=='admin') {?>
            	<?= $form->field($model, 'erp_id')->textInput()?>
             <?php }?>
             <?= $form->field($model, 'sort')->textInput() ?>
             <?= $form->field($model, 'status')->radioList(common\enums\StatusEnum::getMap())?>
            
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>