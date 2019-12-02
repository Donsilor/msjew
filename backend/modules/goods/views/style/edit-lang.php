<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;
use common\widgets\skutable\SkuTable;

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
			<?= $form->field($model, 'type_id')->dropDownList([])->label("产品线") ?>
            <?= $form->field($model, 'cat_id')->dropDownList([])->label("款式分类") ?>   	
            
			<div class="nav-tabs-custom">
		        <?php echo Html::langTab("tab1")?>    			      
    			<div class="tab-content"> 
    				<?php 
            			echo LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab1','fields'=>[
            			    'style_name'=>['type'=>'textInput','options'=>['maxlength' => true],'label'=>"商品名称"],
            			    'style_desc'=>['type'=>'textArea','options'=>['maxlength' => true],'label'=>"商品描述"]
            			]]);
        			?>
    			</div>
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
    			echo SkuTable::widget(['form'=>$form,'model'=>$model]);
    			?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
