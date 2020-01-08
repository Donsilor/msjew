<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\helpers\Url;

use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

$this->title = '客户信息';
$this->params['breadcrumbs'][] = ['label' => $this->title];
$start_time = Yii::$app->request->post('start_time', date('Y-m-d', strtotime("-60 day")));
$end_time = Yii::$app->request->post('end_time', date('Y-m-d', strtotime("+1 day")));
$title = Yii::$app->request->post('title');
$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <div class="box-tools">
                        <a href="<?= Url::to(['export?title='.$title.'&=start_time'.$start_time.'&end_time='.$end_time])?>" class="blue">导出Excel</a>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['/member/member']),
                        'method' => 'post',
                    ]); ?>
                    <div class="col-sm-4">
                        <div class="input-group drp-container">
                            <?= DateRangePicker::widget([
                                'id'=>'datepicker',
                                'name' => 'queryDate',
                                'value' => $start_time . '-' . $end_time,
                                'readonly' => 'readonly',
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'startAttribute' => 'start_time',
                                'endAttribute' => 'end_time',
                                'startInputOptions' => ['value' => $start_time],
                                'endInputOptions' => ['value' => $end_time],
                                'pluginOptions' => [
                                    'locale' => ['format' => 'Y-m-d'],
                                ]
                            ]) . $addon;?>
                        </div>
                    </div>
                    <div class="form-group field-attributespec-type_id">

                        <div class="col-sm-1">
                            <label class="control-label text-right" for="attributespec-type_id">首页登陆</label>
                            <select id="searchmodel-use_type" class="form-control" name="visit_count">
                                <option value="">全部</option>
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="input-group m-b">
                            <input type="text" class="form-control" name="title" placeholder="账号" value="<?= $title ?>"/>
                            <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],

                        [
                            'attribute' => 'username',
                            'value'=>'email',
                            'filter' => Html::activeTextInput($searchModel, 'email', [
                                'class' => 'form-control',
                                'style' =>'width:200px'
                            ]),
                        ],

                        [
                            'label' => '登录信息',
                            'filter' => DateRangePicker::widget([    // 日期组件
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'value' => $searchModel->created_at,
                                'options' => ['readonly' => true],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'locale' => [
                                        'separator' => '/',
                                    ],
                                    'endDate' => date('Y-m-d',time()),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'todayBtn' => 'linked',
                                    'clearBtn' => true,

                                ],
                            ]),
                            'value' => function ($model) {
                                return "最后访问IP：" . $model->last_ip . '<br>' .
                                    "最后访问：" . Yii::$app->formatter->asDatetime($model->last_time) . '<br>' .
                                    "登录次数：" . $model->visit_count . '<br>' .
                                    "注册时间：" . Yii::$app->formatter->asDatetime($model->created_at) . '<br>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label'=>'首次登陆',
                            'value'=>function($model){
                                return $model->visit_count == 1 ? "是":"否";
                             },
                             'filter' => Html::activeDropDownList($searchModel, 'visit_count',['1'=>'是','2'=>'否'], [
                                 'prompt' => '全部',
                                 'class' => 'form-control',
                             ]),
                        ],
                        [
                            'label'=>'是否留言',
                            'value'=>function($model){
                                $count = \common\models\member\Book::find()->where(['member_id'=>$model->id])->count();
                                return $count > 0 ? "是":"否";
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'is_book',['1'=>'是','2'=>'否'], [
                                'prompt' => '全部',
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} ',
                            'buttons' => [
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::linkButton(['ajax-edit', 'id' => $model->id], '账号密码', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },

                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                                'view'=> function($url, $model, $key){
                                    return Html::a('详情', ['book/detail','member_id'=>$model->id],['class'=>'btn btn-info btn-sm']);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
