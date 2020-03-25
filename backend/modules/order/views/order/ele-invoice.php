<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>

    <script language="javascript">
        function preview(fang) {
            var popup = document.getElementById('popup');
            popup.style.display = 'block';
            if (fang < 10) {
                bdhtml = window.document.body.innerHTML; //获取当前页的html代码
                sprnstr = "<!--startprint" + fang + "-->"; //设置打印开始区域
                eprnstr = "<!--endprint" + fang + "-->"; //设置打印结束区域
                prnhtml = bdhtml.substring(bdhtml.indexOf(sprnstr) + 18); //从开始代码向后取html
                prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr)); //从结束代码向前取html
                window.document.body.innerHTML = prnhtml;
                // window.print();
                window.document.body.innerHTML = bdhtml;
            } else {
                window.print();
            }
        }
    </script>
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
            background: #000000 !important;
            color: #fff;
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

        .site-type .child {
            font-size: 16px;
        }

        .package-information {
            margin-top: 40px;
        }

        .package-tit {
            height: 30px;
            background-color: #333;
            color: #fff;
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

        .table {
            margin-top: 20px;
            border: 1px solid #333;
        }

        .item-description {
            width: 36%;
        }

        .country {
            width: 12%;
        }

        .qty {
            width: 10%;
        }

        .unit-price {
            width: 15%;
        }

        .total-amt {
            width: 27%;
        }

        .table .table-child {
            padding: 0 3%;
            box-sizing: border-box;
            text-align: center;
            font-size: 12px;
        }

        .table>div {
            padding-bottom: 20px;
        }

        .table>div:not(:last-child) {
            min-height: 240px;
            border-right: 1px solid #333;
            box-sizing: border-box;
        }

        .table .table-child:first-child {
            height: 30px;
            background-color: #333;
            color: #fff;
            line-height: 30px;
            font-weight: 600;
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
            background-color: #333;
            color: #fff;
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
                <div class="invoice-data-topcolor">发票</div>
                <div class="invoice-data-b clf">
                    <div class="invoice-data-l fl">
                        <div class="invoice-data-type">开票日期</div>
                        <div class="invoice-data-val">14-mar-2018</div>
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
                <div class="list-tit fl">托运人：</div>
                <div class="list-details fl">
                    <div class="child">Li Jun Qi</div>
                    <div class="child">15F-4,No.410,Sec.5,</div>
                    <div class="child">Zhongxiao E,Rd.,Xinyi Dist.,</div>
                    <div class="child">TaiPei City 110,TaiWan(R.O.C)</div>
                </div>
            </div>
        </div>
        <div class="site-type clf">
            <div class="list fl clf">
                <div class="list-tit fl">进口商：</div>
                <div class="list-details fl">
                    <div class="child">Li Jun Qi</div>
                    <div class="child">15F-4,No.410,Sec.5,</div>
                    <div class="child">Zhongxiao E,Rd.,Xinyi Dist.,</div>
                    <div class="child">TaiPei City 110,TaiWan(R.O.C)</div>
                </div>
            </div>

            <div class="list fl clf">
                <div class="list-tit fl">收货人：</div>
                <div class="list-details fl">
                    <div class="child">Li Jun Qi</div>
                    <div class="child">15F-4,No.410,Sec.5,</div>
                    <div class="child">Zhongxiao E,Rd.,Xinyi Dist.,</div>
                    <div class="child">TaiPei City 110,TaiWan(R.O.C)</div>
                </div>
            </div>
        </div>

        <div class="package-information">
            <div class="package-tit">货单信息</div>
            <div class="package-info clf">
                <div class="package-child fl">
                    <div class="package-child-v">国际空运货单</div>
                    <div class="package-child-val">7800885823</div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">运输公司</div>
                    <div class="package-child-val">FedEXnniNIOLl</div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">出口日期</div>
                    <div class="package-child-val"></div>
                </div>
            </div>
            <div class="package-info clf">
                <div class="package-child fl">
                    <div class="package-child-v">出口国家</div>
                    <div class="package-child-val">TaiWan</div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">交易币种</div>
                    <div class="package-child-val">Hong Kong</div>
                </div>
                <div class="package-child fl">
                    <div class="package-child-v">目的地国家</div>
                    <div class="package-child-val">Hong Kong</div>
                </div>
            </div>
        </div>

        <div class="table clf">
            <div class="item-description fl">
                <div class="table-child">商品描述</div>
                <div class="table-child">Item Description</div>
                <div class="table-child">Item Description</div>
                <div class="table-child">Item Description</div>
            </div>
            <div class="country fl">
                <div class="table-child">国家</div>
                <div class="table-child">Country</div>
                <div class="table-child">Country</div>
                <div class="table-child">Country</div>
            </div>
            <div class="qty fl">
                <div class="table-child">数量</div>
                <div class="table-child">Qty</div>
                <div class="table-child">Qty</div>
                <div class="table-child">Qty</div>
            </div>
            <div class="unit-price fl">
                <div class="table-child">单价</div>
                <div class="table-child">Unit Price</div>
                <div class="table-child">Unit Price</div>
                <div class="table-child">Unit Price</div>
            </div>
            <div class="total-amt fl">
                <div class="table-child">总金额</div>
                <div class="table-child">Total Amt.</div>
                <div class="table-child">Total Amt.</div>
                <div class="table-child">Total Amt.</div>
            </div>
        </div>

        <div class="signature clf">
            <div class="signature-name fl">
                <div class="signature-t">找钱孙</div>
                <div class="text">出口商签字</div>
            </div>
            <div class="signature-date fl">
                <div class="signature-t">14-MAR-2018</div>
                <div class="text">日期</div>
            </div>
        </div>

        <div class="total clf">
            <div class="fr clf">
                <div class="fl total-bg">总金额</div>
                <div class="fl total-val">币种 19,512,00</div>
            </div>
        </div>
    </div>
    <!--打印内容结束-->

    <!--endprint1-->
</div>



</body>
</html>
