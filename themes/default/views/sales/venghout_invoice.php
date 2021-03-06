
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice&nbsp;<?= $invs->reference_no ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
    <style>
        .box{
            border: 1px solid black;
            border-radius: 7px;
            padding: 10px;
        }
        @media print {
            .box{

                padding: 10px!important;
            }
            .b1{
                height: 150px!important;
            }

            .prak_kok{
                background: white!important;
            }
            tr.tb_strip:nth-child(odd){
                background:whitesmoke!important;);
            }
        }

        body{

            font-family: "Khmer OS System","Times New Roman";
            -moz-font-family: "Khmer OS System";
        }
        .header1{
            font-family:"Khmer OS Muol Light";
            -moz-font-family: "Khmer OS System";

        }
        .header2{
            font-family:"Times New Roman";
            -moz-font-family: "Times New Roman";
            font-weight: bolder;

        }
        .tb_cus thead td{
            padding: 3px 0px;
        }
        .tfoot_ch td{
            padding: 3px 5px;
            text-align: right;

        }
        .tfoot td{
            width: 283px;
            font-size: 12px;

        }
        .tb_height td{
            padding-left:5px;
        }
        .tfoot td table{
            border: 1px solid black;
            font-size: 13px;
        }
        .tfoot td p{
            line-height: 0px!important;

        }
        .tb_cus td{
            border-right: 1px solid black;
        }

        tbody tr.tb_strip:nth-child(odd){
            background:whitesmoke;
        }
        .container{

        }
    </style>
</head>
<body>
<div class="container">
    <div class="header" >
        <table width="100%">
            <tr>
                <td style="vertical-align: bottom; ">
                    <h1><?= $biller->company ?></h1>
                    <div class="line" style="border: 1px solid black"></div>
                    <br>
                    <div class="box b1" style="height: 160px" >
                        <p>ឈ្មោះអតិថិជន / Customer Name : <?= $customer->company ?></p>
                        <p>តំណាង / Rep : <?= $invs->saleman_first.' '.$invs->saleman_last ?> </p>
                        <p>បញ្ជូនទៅ / Ship To : <?= $customer->address ?></p>

                    </div>
                </td>
                <td width="3%"></td>
                <?php
                $sqlPayment=$this->db->query('Select erp_payment_term.description from erp_payment_term where id="'.$invs->payment_term.'"')->result();
                foreach ($sqlPayment as $getPMT){
                    $payment_term_r=$getPMT->description;
                }
                ?>
                <td width="40%" style="vertical-align: bottom;">

                    <h3 class="text-center header1" style="font-size: 23px">វិក័យប័ត្រ</h3>
                    <h3 class="text-center header2" style="font-size: 28px"><b>Invoice</b></h3>
                    <div class="box" >
                        <?php
//                         $this->erp->print_arrays($rows);
                        ?>
                        <p>កាលបរិច្ឆេទ / Date : <?=  date("d/m/Y", strtotime($invs->date)); ?></p>
                        <p>ល.រ​ វិក័យប័ត្រ / Inv No : <?= $invs->reference_no ?></p>
                        <p>ល.ខ ទូទាត់ / Terms: <?= $payment_term_r ?></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="body">
        <table  width="100%" class="tb_cus" style="overflow: hidden;border: 1px solid black">
            <thead class="text-center"​ style="border-bottom: 1px solid black">
            <td>ល.រ <br>No.</td>
            <td>បរិយាយមុខទំនិញ<br>​Items Description</td>
            <td>សម្គាល់<br>Remarks</td>
            <td>បរិមាណ<br>QTY</td>
            <td>ឯកតា<br>U/M</td>
            <td>ថ្លៃឯកតា<br>Price</td>
            <td>ថ្លៃទំនិញ<br>Amount</td>
            </thead>
            <tbody class="text-center">
            <?php $r = 1;
            $tax_summary = array();
            foreach ($rows as $row):
                $free = lang('free');
                $product_unit = '';


                if($row->variant){
                    $product_unit = $row->variant;
                }else{
                    $product_unit = $row->uname;
                }

                $product_name_setting;
                if($setting->show_code == 0) {
                    $product_name_setting = $row->product_name;
                }else {
                    if($setting->separate_code == 0) {
                        $product_name_setting = $row->product_name;
                    }else {
                        $product_name_setting = $row->product_name;
                    }
                }


                ?>
                <tr class="tb_strip">
                    <td style="width:40px; "><?= $r; ?></td>

                    <td class="text-left" style="padding-left: 5px">
                        <?= $product_name_setting ?>
                        <?= $row->details ? '<br>' . $row->details : ''; ?>
                        <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                    </td>
                    <td><?= $row->product_noted ?></td>
                    <td><?= $this->erp->formatQuantity($row->quantity); ?></td>
                    <td ><?php echo $product_unit ?></td>

                    <!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatNumber($row->net_unit_price); ?></td> -->
                    <td ><?= $row->subtotal!=0?$this->erp->formatNumber($row->unit_price):$free; ?></td>
                    <td><?= $row->subtotal!=0?$this->erp->formatNumber($row->subtotal):$free; ?></td>
                </tr>
                <?php
                $total += $row->subtotal;
                $r++;
            endforeach;
//            $this->erp->print_arrays($invs);
    $minus_row=12;
            if($invs->order_discount<=0){
                $minus_row+=2;
            }
            if($invs->order_tax<=0){
                $minus_row+=2;
            }
//            if($invs->paid<=0){
//                $minus_row+=4;
//            }
            if($invs->shipping<=0){
                $minus_row+=2;
            }
            if($invs->order_discount<=0 && $invs->order_tax<=0 && $invs->shipping<=0){
                $minus_row+=2;
            }
            for($i=1;$i<($minus_row-$r);$i++){
                ?>
                <tr style="border-bottom: transparent;background: transparent!important">
                    <td></td>
                    <td class="text-left" style="padding-left: 5px">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <?php //$this->erp->print_arrays($invs); ?>
            <tfoot >
            <tr style="border-top: 1px solid black">
                <td rowspan="1" colspan="4"​ style="vertical-align: top; padding: 1px 18px">
                    <p>សម្គាល់ / Message : </p>
                    <div><?= $this->erp->decode_html($customer->invoice_footer); ?></div>
                </td>
                <td colspan="3" style="padding: 0px;overflow: hidden">
                    <table width="100%" style="" class="tfoot_ch">
                        <?php
                        $txt_gr='';
                        $showgr='';
                        if($invs->order_discount>0 || $invs->total_tax>0 || $invs->shipping>0){
                            ?>
                            <tr style="border-bottom: 1px solid black">
                                <td style="border-right: 1px solid black">សរុប<br>Subtotal</td>
                                <td style="border-right: none"><?= $this->erp->formatNumber($total); ?></td>
                            </tr>
                            <?php
                        }
                        else{
                            $txt_gr='Total';
                            $showgr=false;
                        }
                        ?>

                        <?php
                        if($invs->order_discount>0){
                            ?>
                            <tr style="border-bottom: 1px solid black">
                                <td style="border-right: 1px solid black">បញ្ចុះតម្លៃ<br> Discount</td>
                                <td style="border-right: none"><small>(<?= $invs->order_discount_id ?>%)</small><?= $this->erp->formatNumber($invs->order_discount); ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <?php
                        if($invs->shipping>0){
                            ?>
                            <tr style="border-bottom: 1px solid black">
                                <td style="border-right: 1px solid black">ដឹកជញ្ជូន<br>Shipping</td>
                                <td style="border-right: none"><?= $this->erp->formatNumber($invs->shipping); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        if($invs->total_tax>0){
                            ?>
                            <tr style="border-bottom: 1px solid black">
                                <td style="border-right: 1px solid black">ពន្ធអាករ<br><?= $invs->vat?></td>
                                <td style="border-right: none"><?= $this->erp->formatNumber($invs->total_tax); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr style="">
                            <style>
                                .t_cus_line{
                                    border-right: 1px solid black;
                                    position: relative;
                                }
                                .t_cus_line:after{
                                    border-left: 1px solid black;
                                    position: absolute;
                                    content: '';
                                    height: 400%;
                                    top:-30px;
                                    left:100%;
                                    background:black;
                                }
                            </style>
                            <td class="t_cus_line">សរុប<br>Total</td>
                            <td style="border-right: none"><?= $this->erp->formatNumber($invs->grand_total); ?></td>
                        </tr>
                        <?php
                        //if($invs->paid>0){
                            ?>
                            <tr style="border-top: 1px solid black; background: white">
                                <td width="50%" style="border-right: 1px solid black" class="prak_kok">ប្រាក់កក់<br>Deposite</td>
                                <td width="50%" style="border-right: none " class="prak_kok"><?= $this->erp->formatNumber($invs->paid) ?></td>
                            </tr>
                            <tr style="border-top: 1px solid black;">
                                <td style="border-right: 1px solid black">ប្រាក់នៅសល់<br>Balance</td>
                                <td style="border-right: none"><?= $this->erp->formatNumber($invs->grand_total-$invs->paid) ?></td>
                            </tr>
                            <?php
                       // }
                        ?>


                    </table>
                </td>

            </tr>

            </tfoot>
        </table>
    </div>
    <br>
    <?php
    //                $this->erp->print_arrays($invs);
    ?>
    <?php
    $sql=$this->db->query('select erp_users.first_name,erp_users.last_name from erp_users where id="'.$invs->created_by.'"')->result();
    foreach ($sql as $cname){
        $fn=$cname->first_name;
        $ln=$cname->last_name;
    }
    //          $this->erp->print_arrays($sql);
    ?>
    <style>
        .tfoot tr td table tr td{
            word-break: break-all;
        }
        td.tb_height{

            height: 180px;

        }
        .tfoot tr td table{
            height:100%;

        }
        @media print {

        }
    </style>
    <div class="footer">
        <table class="tfoot" width="100%">
            <tr>
                <td class="tb_height" style="padding-right: 2px">
                    <table>
                        <tr  class="text-center">
                            <td style="padding-top: 2px">អតិថិជន <br>Customer</td>
                        </tr>
                        <tr>
                            <td><p>ឈ្មោះ ៖​</p> </td>

                        </tr>
                        <tr><td><p>Name </p></td></tr>
                        <tr>
                            <td><p>ទូរស័ព្ទ​ ៖ </p></td>
                        </tr>
                        <tr><td><p>Phone</p></td></tr>
                        <tr>
                            <td><p>ហត្ថលេខា​ ៖ </p></td>

                        </tr>
                        <tr><td><p>Sign</p></td></tr>
                    </table>
                </td>
                <td  class="tb_height" style="padding-right: 2px">
                    <table>
                        <tr  class="text-center">
                            <td  style="padding-top: 2px">គណនេយ្យករ <br>Accountant Approved</td>
                        </tr>
                        <tr>
                            <td><p>ឈ្មោះ ៖​ </p></td>

                        </tr>
                        <tr><td><p>Name </p></td></tr>
                        <tr>
                            <td><p>ទូរស័ព្ទ​ ៖ </p></td>
                        </tr>
                        <tr><td><p>Phone</p></td></tr>
                        <tr>
                            <td><p>ហត្ថលេខា​ ៖ </p></td>

                        </tr>
                        <tr><td><p>Sign</p></td></tr>
                    </table>
                </td>
                <td  class="tb_height" style="padding-right: 2px">
                    <table>
                        <tr  class="text-center">
                            <td colspan="1" style="padding-top: 2px">អ្នករៀបចំ<br>Prepare by</td>
                        </tr>
                        <tr>
                            <td><p>ឈ្មោះ ៖​ <?= $fn.' '.$ln ?><span></td>
                        </tr>
                        <tr><td><p>Name </p></td></tr>
                        <tr>
                            <td><p>ទូរស័ព្ទ​ ៖ </p></td>
                        </tr>
                        <tr><td><p>Phone</p></td></tr>
                        <tr>
                            <td><p>ហត្ថលេខា​ ៖ </p></td>
                        </tr>
                        <tr><td><p>Sign</p></td></tr>
                    </table>
                </td>
                <td  class="tb_height">
                    <table>
                        <tr  class="text-center">
                            <td  style="padding-top: 2px">តំណាងផ្នែកលក់ <br>Sale Rep</td>
                        </tr>
                        <tr>
                            <td><p>ឈ្មោះ ៖​ <?= $invs->saleman_first.' '.$invs->saleman_last ?><span></td>

                        </tr>
                        <tr><td><p>Name </p></td></tr>
                        <tr>
                            <td><p>ទូរស័ព្ទ​ ៖ </p></td>
                        </tr>
                        <tr><td><p>Phone</p></td></tr>
                        <tr>
                            <td><p>ហត្ថលេខា​ ៖ </p></td>

                        </tr>
                        <tr><td><p>Sign</p></td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>


</div>

</body>
</html>
