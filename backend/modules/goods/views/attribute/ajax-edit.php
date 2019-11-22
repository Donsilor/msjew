<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use unclead\multipleinput\MultipleInput;

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
              <li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">zh-TW</a></li>
              <li class="active"><a href="#tab_2" data-toggle="tab" aria-expanded="true">zh-CN</a></li>
              <li><a href="#tab_3" data-toggle="tab">en-US</a></li>              
          </ul>            
          <div class="tab-content">      
              <div class="tab-pane" id="tab_1">
                    <?= $form->field($model, 'input_type')->textInput() ?>
              </div>
              <!-- /.tab-pane -->
			<div class="tab-pane active" id="tab_2">
                   <?= $form->field($model, 'input_type')->textInput() ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
               	   <?= $form->field($model, 'input_type')->textInput() ?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
            <?= $form->field($model, 'attr_type')->textInput() ?>
              <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
              <?= $form->field($model, 'input_type')->textInput() ?>
              <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
              <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
              <?= $form->field($model, 'sort')->textInput() ?>
              <?= $form->field($model, 'attr_type')->textInput() ?>
              <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
              <?= $form->field($model, 'input_type')->textInput() ?>
              <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
              <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
              <?= $form->field($model, 'sort')->textInput() ?>
              <?= $form->field($model, 'attr_type')->textInput() ?>
              <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
              <?= $form->field($model, 'input_type')->textInput() ?>
              <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
              <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
              <?= $form->field($model, 'sort')->textInput() ?>
              <?= $form->field($model, 'attr_type')->textInput() ?>
              <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
              <?= $form->field($model, 'input_type')->textInput() ?>
              <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
              <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
              <?= $form->field($model, 'sort')->textInput() ?>
              <?= $form->field($model, 'attr_type')->textInput() ?>
              <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
              <?= $form->field($model, 'input_type')->textInput() ?>
              <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
                    <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
                    <?= $form->field($model, 'sort')->textInput() ?>
                    <?= $form->field($model, 'attr_type')->textInput() ?>
                    <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
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