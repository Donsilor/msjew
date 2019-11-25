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
                 
          <ul class="nav nav-tabs">
              <?php foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){?>
              <li class="<?php echo Yii::$app->language==$lang_key?"active":"" ?>">
              		<a href="#tab_value_<?php echo $lang_key?>" data-toggle="tab" aria-expanded="false"><?php echo $lang_name?></a>
              </li>
              <?php }?>           
          </ul>            
          <div class="tab-content">  
             	<?php $newLangModel = $model->langModel();?>
              		<?php 
              		  foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){
              		     $is_new = true;    
              		  ?>                        		
              		    <?php foreach ($model->langs as $langModel) {?>
                            <?php if($lang_key == $langModel->language){?>
                            	<!-- 编辑-->
                                <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_value_<?= $lang_key?>">
                                     <?= $form->field($langModel, 'attr_value_name')->textInput(['name'=>Html::langInputName($langModel,$lang_key,"attr_value_name")]) ?>
                              	     <?= $form->field($langModel, 'remark')->textArea(['name'=>Html::langInputName($langModel,$lang_key,"remark")]) ?>
                              	     
                              	</div>
                              	<!-- /.tab-pane -->
                            	<?php $is_new = false; break;?>
                            <?php }?>
                        <?php }?>
                        <?php if($is_new == true){?>
                        <!-- 新增 -->
                        <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_value_<?= $lang_key?>">
                               <?= $form->field($newLangModel, 'attr_value_name')->textInput(['name'=>Html::langInputName($newLangModel,$lang_key,"attr_value_name")]) ?>
                               <?= $form->field($newLangModel, 'remark')->textArea(['name'=>Html::langInputName($newLangModel,$lang_key,"remark")]) ?>
                               
                        </div>
                        <!-- /.tab-pane -->
                        
                        <?php }?>                         
                    <?php }?>                   
            </div>
            <!-- /.tab-content -->
             <?= $form->field($model, 'attr_id')->textInput()->hiddenInput(['value'=>$model->attr_id])->label(false) ?>
             <?= $form->field($model, 'sort')->textInput() ?>
             <?= $form->field($model, 'status')->radioList(StatusEnum::getMap())?>
            
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>