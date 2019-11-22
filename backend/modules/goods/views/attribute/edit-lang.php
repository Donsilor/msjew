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
              <li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">zh-TW</a></li>
              <li class="active"><a href="#tab_2" data-toggle="tab" aria-expanded="true">zh-CN</a></li>
              <li><a href="#tab_3" data-toggle="tab">en-US</a></li>              
          </ul>            
          <div class="tab-content">  
              
              <div class="tab-pane" id="tab_1">
                  <input type="text" name="Langs[zh-TW][attr_name]" value="1111TW"/>  
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane active" id="tab_2">
                  <input type="text" name="Langs[zh-CN][attr_name]" value="2222CN"/>  
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                  <input type="text" name="Langs[en-US][attr_name]" value="US42435"/>
                  <input type="text" name="Langs[en-DE][attr_name]" value="DE42435"/>
              </div>
              <!-- /.tab-pane -->
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
