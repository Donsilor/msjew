<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    //'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
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
 			<?= $form->field($model, 'cat_id')->widget(kartik\select2\Select2::class, [
 			        'data' => Yii::$app->services->category->getDropDown(0),
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]);?>
            <?= $form->field($model, 'attr_id')->widget(kartik\select2\Select2::class, [
                    'data' => Yii::$app->services->attribute->getDropDown(1),
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]);?>
			<div id="box-categoryspec-attr_values" style="<?php echo empty($attrValues)?'display:none':''?>">
				<?= $form->field($model, 'attr_values')->checkboxList($attrValues,['prompt'=>'请选择','value'=>explode(",",$model->attr_values)]);?>
			</div>
			
			<?= $form->field($model, 'attr_type')->widget(kartik\select2\Select2::class, [
			        'data' => common\enums\AttrTypeEnum::getMap(),
                    'options' => [],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
            ]);?>           
            <?= $form->field($model, 'input_type')->radioList(common\enums\InputTypeEnum::getMap()) ?>
            <?= $form->field($model, 'is_require')->radioList(common\enums\ConfirmEnum::getMap())?>
            <?= $form->field($model, 'status')->radioList(common\enums\StatusEnum::getMap())?>
            <?= $form->field($model, 'sort')->textInput() ?> 
                   
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>

<script>
$("#categoryspec-attr_id").change(function(){

	$("#box-categoryspec-attr_values").hide();

	var attr_id = $(this).val();	
	if(attr_id){
        $.post("<?php echo Url::to(['ajax-attr-values'])?>",{'id':'<?= $model->id ?>','attr_id':attr_id},function(data){        
             $("#categoryspec-attr_values").html(data); 
             $("#box-categoryspec-attr_values").show();
        });
	}
});
</script>