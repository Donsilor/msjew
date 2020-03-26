<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>

    <style>
        .rfHeaderFont{
            display: none;
        }
        .content-header{
            padding: 0px;
        }
        .content{
            padding-top: 0px;
        }

        /* -webkit-print-color-adjust: exact; */
        .fl {
            float: left;
        }

        .fr {
            float: right;
        }

        .clf::after {
            display: block;
            content: '.';
            opacity: 0;
            height: 0;
            visibility: hidden;
            clear: both;
        }

        ul,
        li {
            list-style: none;
        }

        img {
            width: 100%;
            height: 100%;
        }

        .scroll {
            overflow-y: scroll;
            box-sizing: border-box;
            background-color: #fff;
            border: 3px solid #333;
            padding: 10px;
        }

        .template {
            width: 100%;
            background-color: #fff;
        }

        .invoice-data {
            width: 300px;
            border: 1px solid #333;
        }

        .invoice-data-topcolor {
            height: 30px;
            background: #333 !important;
            color: #fff !important;
            text-align: center;
            line-height: 30px;
        }

        .invoice-data-l {
            width: 48%;
            padding: 0 3%;
            box-sizing: border-box;
            border-right: 1px solid #333;
        }

        .invoice-data-r {
            width: 52%;
            padding: 0 3%;
            box-sizing: border-box;
        }

        .invoice-data-type {
            font-size: 12px;
            color: #333;
        }

        .invoice-data-val {
            font-size: 16px;
            margin: 10px 0 4px;
            text-align: center;
            font-weight: 600;
        }

        .site-type .list {
            width: 50%;
            margin-top: 20px;
        }

        .site-type .list-tit {
            font-size: 14px;
            color: #333;
            font-weight: 600;
            width: 100px;
        }

        .site-type .child-name{
            font-size: 16px;
            width: 200px;
            word-break: break-all;
            overflow: hidden;
        }
        .site-type .child-addr{
            font-size: 16px;
            width: 200px;
            word-break: break-all;
        }

        .package-information {
            margin-top: 40px;
        }

        .package-tit {
            height: 30px;
            background-color: #333 !important;
            color: #fff !important;
            text-align: center;
            line-height: 30px;
        }

        .package-info {
            border: 1px solid #333;
            box-sizing: border-box;
        }

        .package-child {
            width: 33.333%;
            padding: 0 2%;
            box-sizing: border-box;
        }

        .package-child:nth-child(2) {
            border-left: 1px solid #333;
            border-right: 1px solid #333;
        }

        .package-child-v {
            font-size: 12px;
            line-height: 20px;
            color: #333;
            font-weight: 600;
        }

        .package-child-val {
            font-size: 16px;
            line-height: 32px;
            text-align: center;
        }

        table{
            border: 1px solid #333;
            margin-top: 30px;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th{
            height: 30px;
            background-color: #333 !important;
            color: #fff !important;
            font-weight: normal;
            padding: 0 2%;
            box-sizing: border-box;
        }
        td{
            text-align: center;
            height: 30px;
            padding: 0 2%;
            box-sizing: border-box;
            word-break:break-all;
        }
        th:first-child{
            text-align: left;
        }
        td:first-child{
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        .item-description .table-child {
            text-align: left;
        }

        .table .table-child:not(:first-child) {
            line-height: 22px;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
        }

        .signature-name {
            width: 260px;
            height: 50px;
        }

        .signature-date {
            width: 150px;
            margin-left: 50px;
        }

        .signature-t {
            height: 50px;
            border-bottom: 1px solid #333;
            line-height: 50px;
        }

        .signature .text {
            line-height: 30px;
        }

        .signature-date .signature-t {
            line-height: 70px;
        }

        .signature-name .signature-t {
            font-size: 32px;
        }

        .total {
            margin-top: 80px;
            text-align: right;
            line-height: 30px;
        }

        .total-bg {
            width: 240px;
            height: 30px;
            background-color: #333 !important;
            color: #fff !important;
            padding: 0 10px;
            box-sizing: border-box;
        }

        .total-val {
            width: 180px;
            height: 30px;
            border: 1px solid #333;
            color: #333;
            padding: 0 10px;
            box-sizing: border-box;
        }

    </style>
</head>
<body>

<div class="scroll" id="wdf">
    <!--startprint1-->

    <!--打印内容开始-->

    <div class="template">
        <div class="clf">
            <div class="invoice-data fr">
                <div class="invoice-data-topcolor">發票</div>
                <div class="invoice-data-b clf">
                    <div class="invoice-data-l fl">
                        <div class="invoice-data-type">開票日期</div>
                        <div class="invoice-data-val"><?php echo $result['invoice_date'];?></div>
                    </div>
                    <div class="invoice-data-r fl">
                        <div class="invoice-data-type">頁碼</div>
                        <div class="invoice-data-val">1 of 1</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-type clf">
            <div class="list clf">
                <div class="list-tit fl">托運人:</div>
                <div class="list-details fl">
                    <div class="child-name"><?php echo $result['sender_name']?$result['sender_name']:'BDD Co.';?></div>
                    <div class="child-addr"><?php echo $result['sender_address']?$result['sender_address']: 'Rm4, 23/F,Universal Trade Centre 3 Arbuthnot Road Central';?></div>
                </div>
            </div>
        </div>
        <div class="site-type clf">
            <div class="list fl clf">
                <div class="list-tit fl">進口商:</div>
                <div class="list-details fl">
                    <div class="child-name"><?php echo $result['shipper_name']?$result['shipper_name']: 'BDD Co.';?></div>
                    <div class="child-addr"><?php echo $result['shipper_address']?$result['shipper_address']: 'Rm4, 23/F,Universal Trade Centre 3 Arbuthnot Road Central';?></div>
                </div>
            </div>

            <div class="list fl clf">
                <div class="list-tit fl">收貨人:</div>
                <div class="list-details fl">
                    <div class="child-name"><?php echo $result['realname'];?></div>
                    <div class="child-addr"><?php echo $result['address_details'];?></div>
                </div>
            </div>
        </div>

        <div class="package-information">
            <div class="package-tit">貨單資訊</div>
            <div class="package-info clf">
                <div class="package-child fl">
                    <div class="package-child-v">國際空運貨單</div>
                    <div class="package-child-val"><?php echo $result['express_no'];?></div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">運輸公司</div>
                    <div class="package-child-val"><?php echo $result['express_company_name'];?></div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">出口日期</div>
                    <div class="package-child-val"><?php echo $result['delivery_time'];?></div>
                </div>
            </div>
            <div class="package-info clf">
                <div class="package-child fl">
                    <div class="package-child-v">出口國家</div>
                    <div class="package-child-val">中國</div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">交易幣種</div>
                    <div class="package-child-val"><?php echo $result['currency'];?></div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">目的地國家</div>
                    <div class="package-child-val"><?php echo $result['country'];?></div>
                </div>
            </div>
        </div>

        <table cellspacing="0" cellpadding="0" width="100%" border="1" rules="cols">
            <tr>
                <th width="32%">商品描述</th>
                <th width="16%">國家</th>
                <th width="10%">數量</th>
                <th width="15%">單價</th>
                <th width="27%">總金額</th>
            </tr>
            <?php foreach ($result['order_goods'] as $val){ ?>
            <tr>
                <td><?php echo $val['goods_name'].$val['goods_name'];?></td>
                <td>中國</td>
                <td><?php echo $val['goods_num'];?></td>
                <td><?php echo $val['goods_pay_price']. " ".$val['currency']; ?></td>
                <td><?php echo $val['goods_pay_price']*$val['goods_num'] . " ".$val['currency']; ?></td>
            </tr>
            <?php } ?>

        </table>

        <div class="signature clf">
            <div class="signature-name fl">
                <div class="signature-t"><?php echo $result['sender_name']?$result['sender_name']:'BDD Co.';?></div>
                <div class="text">出口商簽字</div>
            </div>
            <div class="signature-date fl">
                <div class="signature-t"><?php echo $result['invoice_date'];?></div>
                <div class="text">日期</div>
            </div>
        </div>

        <div class="total clf">
            <div class="fr clf">
                <div class="fl total-bg">Total</div>
                <div class="fl total-val"><?php echo $result['currency'] .' '.$result['order_amount']; ?> </div>
            </div>
        </div>
    </div>
    <!--打印内容结束-->

    <!--endprint1-->
</div>



</body>
</html>
