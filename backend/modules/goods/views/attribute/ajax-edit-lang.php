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
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
</div>
    <div class="modal-body">
                 
          <ul class="nav nav-tabs">
              <?php foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){?>
              <li class="<?php echo Yii::$app->language==$lang_key?"active":"" ?>">
              		<a href="#tab_<?php echo $lang_key?>" data-toggle="tab" aria-expanded="false"><?php echo $lang_name?></a>
              </li>
              <?php }?>           
          </ul>            
          <div class="tab-content">  
             	<?php $langModel = $model->langModel();?>
              	<?php if(empty($model->langs)){?>
                		<?php foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){?>
                			<div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                             <?= $form->field($langModel, 'attr_name')->textInput(['name'=>Html::langInputName($langModel,$lang_key,"attr_name")]) ?>
                      	</div>
                		<?php }?>
                <?php }else{?>
                      <?php foreach ($model->langs as $key=>$langModel) {?>
                      	<div class="tab-pane<?php echo Yii::$app->language==$langModel->language?" active":"" ?>" id="tab_<?= $langModel->language?>">
                             <?= $form->field($langModel, 'attr_name')->textInput(['name'=>Html::langInputName($langModel,$lang_key,"attr_name")]) ?>
                      	</div>
                      	<!-- /.tab-pane -->        
                      <?php }?>
                <?php }?>
            </div>
            <!-- /.tab-content -->
            <?= $form->field($model, 'attr_type')->textInput() ?>
            <?= $form->field($model, 'cat_id')->dropDownList(['1'=>'分类1']) ?>
            <?= $form->field($model, 'input_type')->textInput() ?>
            <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
            <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
            <?= $form->field($model, 'sort')->textInput() ?>              
          </div>
           
                   
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>