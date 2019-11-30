<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\enums\SettingEnum;
use yii\grid\GridView;
use common\helpers\Url;
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

                <?php echo Html::langTab('tab')?>
                <div class="tab-content">  
           			<?php $newLangModel = $model->langModel();?>
              		<?php 
              		  foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){
              		     $is_new = true;    
              		  ?>                        		
              		    <?php foreach ($model->langs as $langModel) {?>
                            <?php if($lang_key == $langModel->language){?>
                            	<!-- 编辑-->
                                <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                                    <?= $form->field($langModel, 'adv_name')->textInput(Html::langInputOptions($langModel,$lang_key,"adv_name",['style'=>'width:200px;'])) ?>
                              	</div>
                              	<!-- /.tab-pane -->
                            	<?php $is_new = false; break;?>
                            <?php }?>
                        <?php }?>
                        <?php if($is_new == true){?>
                        <!-- 新增 -->
                        <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                            <?= $form->field($newLangModel, 'adv_name')->textInput(Html::langInputOptions($newLangModel,$lang_key,"adv_name",['style'=>'width:200px;'])) ?>
                        </div>
                        <!-- /.tab-pane -->
                        <?php }?>                         
                    <?php }?>
                    <?= $form->field($model, 'adv_type')->radioList(SettingEnum::$advTypeAction) ?>
                    <?= $form->field($model, 'show_type')->radioList(SettingEnum::$showTypeAction) ?>
                    <?= $form->field($model, 'adv_height')->textInput(['style'=>'width:100px;'])?>
                    <?= $form->field($model, 'adv_width')->textInput(['style'=>'width:100px;']) ?>
                    <?= $form->field($model, 'open_type')->radioList(SettingEnum::$openTypeAction); ?>
                    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
                </div>  
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white"><?= Html::a('返回',['advert/index']); ?></span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">图片列表</h3>
                <div class="box-tools">
                    <?= Html::create(['advert-images/ajax-edit-lang', 'adv_id' => $model->id], '上传图片', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>                
            <div class="box-body">
    			<div class="box-body table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-hover'],
                        'layout'=> '{items}',
                        'showFooter' => true,//显示footer行
                        'id'=>'grid',
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'visible' => false,
                            ],

                            'id',
                            'lang.title',
                            [
                                'label' => '图片',
                                "format"=>'raw',
                                'value' => function($model) {
                                    return Html::img($model->adv_image,["width"=>"100",]);
                                },
                            ],

                            // 'adv_url:url',
                            'start_time:date',
                            'end_time:date',
                            'updated_at:date',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model){
                                    return StatusEnum::getValue($model->status);
                                },
                                'filter' => Html::activeDropDownList($model, 'status',StatusEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',

                            ]),
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{edit} {status} {delete}',
                                'buttons' => [
                                    'edit' => function($url, $model, $key){
                                        return Html::edit(['advert-images/ajax-edit-lang', 'id' => $model->id , 'adv_id' => $model->adv_id],'编辑',[
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'status' => function($url, $model, $key){
                                        return Html::status($model->status,['data-url'=>Url::to(['advert-images/ajax-update'])]);
                                    },

                                    'delete' => function($url, $model, $key){
                                        return Html::delete(['delete', 'id' => $model->id]);
                                    },
                                ]
                            ]
                        ]
                    ]); ?>


            </div> 
               
        </div>
    </div>
</div>
</div>
