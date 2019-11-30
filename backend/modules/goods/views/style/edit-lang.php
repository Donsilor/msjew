<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\goods\Style */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('goods', 'Style');
$this->params['breadcrumbs'][] = ['label' => Yii::t('goods', 'Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<h2 class="page-header">商品发布</h2>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基础信息</h3>
            </div>
            <div class="box-body">
            <div class="row">
        		<div class="col-lg-4"><?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label("商品名称") ?></div>
    			<div class="col-lg-4">
    			<?php 
    			echo LangBox::widget(['form'=>$form,'model'=>$model,'fields'=>[
    			    'style_name'=>['type'=>'textInput','options'=>['maxlength' => true],'label'=>[false,[]]]
    			]]);
    			?>
    			</div>
    			<div class="col-lg-4"><?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label("商品名称") ?></div>
			</div>		
			<?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label("商品描述") ?>
            

          
          <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li><a href="javascript:void(0)">商品描述</a></li>
                  <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">Tab 1</a></li>
                  <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Tab 2</a></li>
                  <li ><a href="#tab_3-2" data-toggle="tab" aria-expanded="true">Tab 3</a></li>              
                </ul>
                <div class="tab-content">
                  <div class="tab-pane" id="tab_1-1">
                    <?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label(false) ?>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2-2">
                    <?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label(false) ?>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="tab_3-2">
                    <?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label(false) ?>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>

          
          <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li><a href="javascript:void(0)">款式名称</a></li>
                  <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">Tab 1</a></li>
                  <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Tab 2</a></li>
                  <li ><a href="#tab_3-2" data-toggle="tab" aria-expanded="true">Tab 3</a></li>              
                </ul>
                <div class="tab-content">
                  <div class="tab-pane" id="tab_1-1">
                    <?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label(false) ?>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2-2">
                    <?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label(false) ?>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="tab_3-2">
                    <?= $form->field($model, 'style_sn')->textInput(['maxlength' => true])->label(false) ?>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
          </div>
      
      
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">商品属性</h3>
            </div>
            <div class="box-body">
                <?php 
    			echo LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'','fields'=>[
    			    'style_name'=>['type'=>'textInput','options'=>['maxlength' => true],'label'=>[false,[]]]
    			]]);
    			?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
