<?php
use common\helpers\Url;

use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
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
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['detail?member_id='.$member_id]),
                        'method' => 'post',
                    ]); ?>
                    <div class="col-sm-4">
                        <div class="input-group drp-container">
                            <?= DateRangePicker::widget([
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
                    <div class="col-sm-4">
                        <div class="input-group m-b">
                            <input type="text" class="form-control" name="title" placeholder="标题或内容" value="<?= $title ?>"/>
                            <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <div class="col-md-12 changelog-info">
                <ul class="time-line">
                    <li id="more">
                        <h5><a  class="openContab blue" data-title="" id="more">更多</a></h5>
                    </li>
                </ul>
                <!-- /.widget-user -->
            </div>

        </div>

    </div>
</div>
<script>
    $(function () {
        //初始化加载数据
        ajaxSend(0);

        var page = 1;
        $("#more a").click(function () {
            ajaxSend(page);
            page = page + 1;
        });
    });
    //ajax交互函数
    function ajaxSend(pageIndex) {
        $.ajax({
            type: "POST",
            url: "<?= Url::to(['ajax-detail']); ?>",
            data: {pageIndex: pageIndex, member_id:<?php echo $member_id ?>,title:"<?php echo $title;?>",start_time:"<?php echo $start_time;?>",end_time:"<?php echo $end_time;?>"}, //传递参数，作为后台返回页码的依据
            dataType: "json",   //返回的数据为json
            beforeSend: function () {
                $("#more a").text("正在加载中...");
            },
            //成功获取数据后，返回的是json二位数组
            success: function (data) {
                $("#more a").text("更多");
                //获取返回的总页数，进行到达最大页判断
                var totalPage = data.data.totalPage;
                var list = data.data.list;   //json中的list是一个数组
                var tpl = "";
                //遍历list数组，index是下标0,1..，array是这个下标对应的键值
                var status=<?php echo json_encode(common\enums\MemberEnum::getBookStatus());?>;
                var status_arr=eval(status);
                $.each(list, function (index, content) {

                    tpl += '<li>'
                        + '<time>' + content['created_at'] + '</time>'
                        + '<div class="title">' + content['email'] + '<span style="margin-left: 10px;">在线留言：</span>' + content['title'] +'</div>'
                        + '<div class="detial">' + content['content'] + '</div>'
                        + '<div class="status">' + status_arr[content['status']] + '</div>'
                        + '<div class="remark">' + content['remark'] + '</div>'
                        +"</li>";

                });
                console.log(list);
                $("#more").before(tpl);
                //判断是否到达最要页数

                if (pageIndex >= totalPage-1) {
                    $("#more a").text("没有了").unbind("click");//取消绑定click事件
                }
            },
            error: function () {
                alert("加载错误");
            }
        });
    }

</script>