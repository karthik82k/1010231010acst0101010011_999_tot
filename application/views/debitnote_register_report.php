<?php $this->load->view('include/header');

  $role_id =  $this->session->userdata('user_role');
  $user_id =  $this->session->userdata('user_id');
  $name = $this->session->userdata('name');
  $comp_name = $this->session->userdata('company_name');  
  $financial_year_selected = $this->session->userdata('financial_year');
  $financial_year_id = $this->session->userdata('financial_id');
  $financial_year_from = $this->session->userdata('financial_from');
  $financial_year_to = $this->session->userdata('financial_to');
 ?>
<div class="empty-spacer">
<div class="container"></div>
</div>
<!-- ddf white space code ends here -->    
<style type="text/css">
#profilestatus {font-size:14px;font-weight:bold;}   
.textborder {border: 1px solid #555555;}
.form-control {width:90% !important;}
.ui-multiselect{width:50% !important;margin-bottom: 1%;}

    .well {
        padding: 5px;
    }
</style>
<div class="container category" style="min-height: 560px;">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">DEBIT NOTE REPORT</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>        
    </ol> 
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_salesorder" id="frm_salesorder" action="<?php echo site_url('entry/add_salesorder');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">
              <div class="col-md-12 col-sm-12 col-xs-12">            
               
                <label for="emirate" class="col-md-3 control-label" style="margin-left:0px; margin-bottom:0px; margin-top:0px; margin-right:0px; text-align:right; ">Debit Note for the Month <span class="textspandata">*</span></label>
                    <div class="col-md-5 col-sm-9 col-xs-9">                   
                         <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Month</option>
                        <?php foreach ($month_list as $row) {
                            if($month == $row['Name']){
                              echo "<option value='".$row['Name']."' selected>".$row['Name']."</option>"; 
                            }else{
                              echo "<option value='".$row['Name']."'>".$row['Name']."</option>"; 
                            }
                            
                          }?>
                         </select>
                    </div>
                
                </div>

               
            </div>
            
            </fieldset>
            </div>
            </form>
            </div>
        <div class="col-md-12 col-sm-12 col-xs-12" style="height: 20px;"> </div>
<table  class="table table-striped table-bordered" cellspacing="0" width="100%" style="width: 1800px;">        
        <?php       
           if(!empty($purchase_register_report)){
            ?>
            <thead>
            <tr>
                <th class="no-sort"></th>
                <th class="no-sort"></th>
                <th class="no-sort">DEBIT NOTE</th>
                <th class="no-sort">ORIGINAL</th>
                <th class="no-sort">ORIGINAL</th>
                <th class="no-sort"></th>
                 <th class="no-sort"></th>
                <th class="no-sort">INVOICE</th>
                <th class="no-sort">TAX </th>
                <th class="no-sort" colspan="2" style="text-align: center;">TAXABLE</th>
                <th class="no-sort" colspan="3" style="text-align: center;">INPUT TAX</th>               
                <?php if($export_col == 'y' || $sez_col  == 'y' || $zero_col  == 'y'){
                  ?>
                  <th class="no-sort"  colspan="3" style="text-align: center;">NON TAXABLE</th>
                  <?php
                }
                ?>
            </tr>
             <tr>
                <th class="no-sort">DATE</th>
                <th class="no-sort">Sl. NO</th>
                <th class="no-sort">NUMBER</th>
                <th class="no-sort">INVOICE NO</th>
                <th class="no-sort">INVOICE DATE</th>
                <th class="no-sort">GSTN</th>
                <th class="no-sort">NAME OF THE PARTY</th>
                <th class="no-sort">TOTAL VALUE</th>
                <th class="no-sort">RATE</th>
                <th class="no-sort">DOMESTIC</th>
                <th class="no-sort">INTERSTATE</th>
                <th class="no-sort">SGST</th>
                <th class="no-sort">CGST</th>                
                <th class="no-sort">IGST</th><?php if($export_col == 'y' ){
                  ?>                
                <th class="no-sort">EXPORT </th>
                <?php }
                  if($sez_col  == 'y'){
                  ?>
                <th class="no-sort">SEZ</th>
                <?php } 
                  if($zero_col  == 'y'){
                  ?>
                <th class="no-sort">ZERO RATE</th>
                <?php
                }
              ?>
            </tr>
        </thead>
       <tbody>
        <?php
        

             $domestic_total = 0;
             $interstate_total = 0;
             $export_total = 0;
             $total_amount = 0;
             $total_sgst = 0;
             $total_cgst = 0;
             $total_igst = 0;
             $sez_total = 0;
             $zero_total = 0;

             

             $domestic_cn_total = 0;
             $interstate_cn_total = 0;
             $export_cn_total = 0;
             $total_amount_cn = 0;
             $total_sgst_cn = 0;
             $total_cgst_cn = 0;
             $total_igst_cn = 0;
             $sez_total_cn = 0;
             $zero_total_cn = 0; 

             

             $domestic_dn_total = 0;
             $interstate_dn_total = 0;
             $export_dn_total = 0;
             $total_amount_dn = 0; 
             $total_sgst_dn = 0;
             $total_cgst_dn = 0;
             $total_igst_dn = 0; 
              $sez_total_dn = 0;
             $zero_total_dn = 0; 
           $total_amount = 0;
            foreach ($purchase_master as $key => $val) { 
             // $sal_reg = array();
              foreach ($val as $key1 => $value) {
                  $total = 0;
                  $total_gst = 0;
                 $sal_reg = $purchase_register_report_det[$value['DATE']][$value['PREFIX']][$value['REFNUM']][$value['VOUCHER_ID']][$value['TINNO']][$value['ACCOUNT']];
                $total_sale = $purchase_total[$value['PREFIX']][$value['VOUCHER_ID']];
                foreach ($total_sale as $key_s => $value_s) {
                    $total_gst =   $value_s['CGSTAMOUNT'] + $value_s['SGSTAMOUNT'] + $value_s['IGSTAMOUNT'] + $value_s['AMOUNT']- $value_s['DISCOUNT'] ;
                 $total = $total_gst + $total;
                }

                $total_amount = $total + $total_amount;
                ?>
                <tr>
                <td style="width: 88px;"><?php echo date("d-m-Y",strtotime($value['DATE']));?></td>
                <td style="width: 98px;"><?php echo $value['PREFIX'].'0000'.$value['VOUCHER_ID'];?></td>
                <td style="width: 114px;"><?php echo $value['BILLNO'];?></td>
              <td><?php echo $value['REFNUM'];?></td>
              <td><?php echo date("d-m-Y",strtotime($value['BILLDATE']));?></td>
                <td style="width: 100px;"><?php echo $value['TINNO'];?></td>
                <td><?php echo $value['ACCOUNT'];?></td>
                <td><?php echo number_format($total,2);?></td>
                <?php
                $i = 1;
                 foreach ($sal_reg as $key_a => $value_a) {
                   foreach ($value_a as $key_b => $value_b) {
                    $domestic = 0;
                    $interstate = 0;
                    $export = 0;
                    $sez = 0;
                    $zero = 0;

                  
                     $type = $value_b['LEDGER'];
                    if(stristr($type, "DOMESTIC") != '' ){
                        $domestic = $value_b['AMOUNT'];
                      }

                      if(stristr($type, "INTERSTATE") != '' ){
                        $interstate = $value_b['AMOUNT'];
                      }

                      if(stristr($type, "IMPORT") != '' || stristr($type, "EXPORT") != '' ){
                        $export = $value_b['AMOUNT'];
                      }

                      if(stristr($type, "SEZ") != '' ){
                        $sez = $value_b['AMOUNT'];
                      }

                      if(stristr($type, "ZERO") != '' ){
                        $zero = $value_b['AMOUNT'];
                      }
                       $domestic_total = $domestic + $domestic_total;
             $interstate_total = $interstate +  $interstate_total;
             $export_total = $export + $export_total;
              $sez_total = $sez + $sez_total;
             $zero_total = $zero + $zero_total;
             $total_sgst = $value_b['SGSTAMOUNT'] + $total_sgst;
             $total_cgst = $value_b['CGSTAMOUNT'] + $total_cgst;
             $total_igst = $value_b['IGSTAMOUNT'] + $total_igst;
                    if($i == 1){
                    ?>
                  <td><?php echo $value_b['GSTPERCENT'].' %';?></td>
                <td style="width: 95px;"><?php echo number_format($domestic,2);?></td>
                <td style="width: 95px;"><?php echo number_format($interstate,2);?></td>
                 <td style="width: 85px;"><?php echo number_format($value_b['SGSTAMOUNT'],2);?></td>
                <td style="width: 85px;"><?php echo number_format($value_b['CGSTAMOUNT'],2);?></td>
                <td style="width: 85px;"><?php echo number_format($value_b['IGSTAMOUNT'],2);?></td>
                 <?php if($export_col == 'y' ){
                  ?>
                <td><?php echo number_format($export,2);?></td>
                <?php }
                if($sez_col  == 'y' ){
                  ?>
                <td><?php echo number_format($sez,2);?></td>
                <?php }
                if($zero_col  == 'y'){
                  ?>
                <td><?php echo number_format($zero,2);?></td>
                <?php
                }
              ?>
                </tr>
                <?php
              }else{
                 ?>
                 <tr>
                 <td colspan="6">
                 <td><?php echo $value_b['GSTPERCENT'].' %';?></td>
                  <td style="width: 95px;"><?php echo number_format($domestic,2);?></td>
                <td style="width: 95px;"><?php echo number_format($interstate,2);?></td>
                 <td style="width: 85px;"><?php echo number_format($value_b['SGSTAMOUNT'],2);?></td>
                <td style="width: 85px;"><?php echo number_format($value_b['CGSTAMOUNT'],2);?></td>
                <td style="width: 85px;"><?php echo number_format($value_b['IGSTAMOUNT'],2);?></td>
                 <?php if($export_col == 'y' ){
                  ?>
                <td><?php echo number_format($export,2);?></td>
                <?php }
                if($sez_col  == 'y' ){
                  ?>
                <td><?php echo number_format($sez,2);?></td>
                <?php }
                if($zero_col  == 'y'){
                  ?>
                <td><?php echo number_format($zero,2);?></td>
                <?php
                }
              ?>
                </tr>
                <?php

              }
                $i = $i + 1;
                }
                 }
                
                ?>
                 

          <?php
      
          } } ?>
           <tr>
                <td colspan="7"><b>TOTAL</b></td>
                <td><?php echo  number_format($total_amount,2);?></td>
                <td></td>
                <td><?php echo number_format($domestic_total,2);?></td>
                <td><?php echo number_format($interstate_total,2);?></td>
                 <td><?php echo number_format($total_sgst,2);?></td>
                <td><?php echo number_format($total_cgst,2);?></td>               
                <td><?php echo number_format($total_igst,2);?></td>
                <?php if($export_col == 'y' ){
                  ?>
                <td><?php echo number_format($export_total,2);?></td>
                <?php } if( $sez_col  == 'y' ){
                  ?>
                <td><?php echo number_format($sez_total,2);?></td>
                <?php }
                if($zero_col  == 'y'){
                  ?>
                <td><?php echo number_format($zero_total,2);?></td>
                <?php
                }
              ?>
            </tr>
                    
             
                <?php
               }else if($month !='' && empty($purchase_register_report) ){
        ?>
        <tr><td><b>No Record</b></td></tr>
        <?php
      }
            ?>
            </tr>
        
        </tbody>
    </table>
</div>
 </div>
 </div>

<div style="height:5px;"></div>                
                    <!--  footer code starts here -->
<div class="panel-footer footer navbar-fixed-bottom">  
  <div class="container" style="width: 100%; float: left;">   
    <div class="text-right" style="width: 50%; float: left;"> 
      Copyrights &copy; 2017 Total Accounting 
    </div><div  class="text-right" style="width: 50%; float: right;"> 
      Powered by Salvo Systems Pvt Ltd 
    </div>
  </div>
</div> 

</body>
<script>
 $("#cmb_account_group").change(function(){ 
  var ledger = $('#cmb_account_group').val();
    window.location='<?php echo site_url(); ?>mis_report/debitnote_report/' + ledger;
  });
$(document).ready(function(){
    $('#myTable').dataTable({
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ]
    });
});
</script>
</html>