<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link href="/backend/resources/css/invoice.css" rel="stylesheet">
</head>
<body>

<div class="scroll" id="wdf">
    <!--startprint1-->

    <!--打印内容开始-->

    <div class="template">
        <div class="clf">
            <div class="invoice-data fr">
                <div class="invoice-data-topcolor">发票</div>
                <div class="invoice-data-b clf">
                    <div class="invoice-data-l fl">
                        <div class="invoice-data-type">开票日期</div>
                        <div class="invoice-data-val"><?php echo $result['invoice_date'];?></div>
                    </div>
                    <div class="invoice-data-r fl">
                        <div class="invoice-data-type">页码</div>
                        <div class="invoice-data-val">1 of 1</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-type clf">
            <div class="list clf">
                <div class="list-tit fl">托运人:</div>
                <div class="list-details fl">
                    <div class="child-name"><?php echo $result['sender_name']?$result['sender_name']:'BDD Co.Ltd';?></div>
                    <div class="child-addr"><?php echo $result['sender_address']?$result['sender_address']: '中环亚毕诺道3号环球贸易中心23楼04室';?></div>
                </div>
            </div>
        </div>
        <div class="site-type clf">
            <div class="list fl clf">
                <div class="list-tit fl">进口商:</div>
                <div class="list-details fl">
                    <div class="child-name"><?php echo $result['realname'];?></div>
                    <div class="child-addr"><?php echo $result['address_details'];?></div>
                </div>
            </div>

            <div class="list fl clf">
                <div class="list-tit fl">收货人:</div>
                <div class="list-details fl">
                    <div class="child-name"><?php echo $result['realname'];?></div>
                    <div class="child-addr"><?php echo $result['address_details'];?></div>
                </div>
            </div>
        </div>

        <div class="package-information">
            <div class="package-tit">货单信息</div>
            <div class="package-info clf">
                <div class="package-child fl">
                    <div class="package-child-v">国际空运货单.</div>
                    <div class="package-child-val"><?php echo $result['express_no'];?></div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">运输公司</div>
                    <div class="package-child-val"><?php echo $result['express_company_name'];?></div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">出口日期</div>
                    <div class="package-child-val"><?php echo $result['delivery_time'];?></div>
                </div>
            </div>
            <div class="package-info clf">
                <div class="package-child fl">
                    <div class="package-child-v">出口国家</div>
                    <div class="package-child-val">中国</div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">交易币种</div>
                    <div class="package-child-val"><?php echo $result['currency'];?></div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">目的地国家</div>
                    <div class="package-child-val"><?php echo $result['country'];?></div>
                </div>
            </div>
        </div>

        <table cellspacing="0" cellpadding="0" width="100%" border="1" rules="cols">
            <tr>
                <th width="">商品描述</th>
                <th width="13%">国家</th>
                <th width="8%">数量</th>
                <th width="22%">单价</th>
                <th width="22%">总金额</th>
            </tr>
            <?php foreach ($result['order_goods'] as $val){ ?>
            <tr>
                <td><?php echo $val['goods_name'];?></td>
                <td>中国</td>
                <td><?php echo $val['goods_num'];?></td>
                <td><?php echo $val['goods_pay_price']. " ".$val['currency']; ?></td>
                <td><?php echo $val['goods_pay_price']*$val['goods_num'] . " ".$val['currency']; ?></td>
            </tr>
            <?php } ?>

        </table>

        <div class="signature clf">
            <div class="signature-name fl">
                <div class="signature-t"><?php echo $result['sender_name']?$result['sender_name']:'BDD Co.';?></div>
                <div class="text">出口商签字</div>
            </div>
            <div class="signature-date fl">
                <div class="signature-t"><?php echo $result['invoice_date'];?></div>
                <div class="text">日期</div>
            </div>
        </div>

        <div class="total clf">
            <div class="fr clf">
                <div class="fr total-val"><?php echo $result['currency'] .' '.$result['order_amount']; ?> </div>
                <div class="fr total-bg">Total</div>
            </div>
        </div>
    </div>
    <!--打印内容结束-->

    <!--endprint1-->
</div>



</body>
</html>