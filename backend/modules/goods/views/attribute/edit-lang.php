<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\goods\Attribute */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('attribute', 'Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('attribute', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>                
            <div class="box-body">

                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    ],
                ]); ?>
      
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
                   <br/>   
                    <?= $form->field($model, 'attr_type')->textInput() ?>
                    <?= $form->field($model, 'cat_id')->dropDownList(['1'=>'分类1']) ?>
                    <?= $form->field($model, 'input_type')->textInput() ?>
                    <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
                    <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
                    <?= $form->field($model, 'sort')->textInput() ?>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            </div>
        </div>
    </div>
</div>
