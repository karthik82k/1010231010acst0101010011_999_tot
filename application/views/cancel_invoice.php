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
        <li style="color: red;">CANCEL INVOICE</li>   
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
       
<table  class="table table-striped table-bordered" cellspacing="0" width="100%">        
        <?php       
           if(!empty($cancel_sales_report)){
          ?>
          <thead>
            <tr>
                <th class="no-sort">Date</th>
                <th class="no-sort">Voucher No.</th>
                <th class="no-sort">Invoice No</th>
                <th class="no-sort">Account</th>
                <th class="no-sort">Ledger</th>
                <th class="no-sort"></th>
            </tr>
        </thead>
        <tbody>
        <?php         
            foreach ($cancel_sales_report as $val) {
               
        ?>
            <tr>
                
                <td><?php echo $val['DATE'];?></td>
                <td><?php echo $val['PREFIX'].$val['V_ID'];?></td>
                <td><?php echo $val['PREFIX'].$val['REFNUM'];?></td>
                <td><?php echo $val['ACCOUNT'];?></td>
                <td><?php echo $val['LEDGER'];?></td>
               <td>  &nbsp;&nbsp;<a href="<?php echo site_url('/mis_report/cancel_sales_report/'.$val['VOUCHER_ID'].'?pre='.urlencode($val['PREFIX']));?>">View Details</a>
               
               </td>
            </tr>
            <?php
        }
           
          }else if(!empty($cancel_sales_report_det)){
           ?>          
           <thead>
           <tr>
            <th colspan="6" style="text-align: center;">SALES VOUCHER</th>
          </tr>
          </thead>
           <tbody>
          <tr>
            <td width="10%"><b>Voucher No</b></td><td><?php echo $prefix.$sn;?></td>
            <td><b>Bill No</b></td><td><?php echo $bill_no;?></td>
            <td width="10%"><b>Date</b></td><td><?php echo $date;?></td>
          </tr>
           <tr>
            <td width="10%"><b>Account Name</b></td><td><?php echo $ACCOUNT;?></td>
            <td width="10%"><b>Ledger Name</b></td><td><?php echo $LEDGER;?></td>
            <td></td><td></td>
          </tr>
           <tr>
            <td colspan="6">
            <table  class="table table-striped table-bordered">
                <thead>
             <tr>
                <th class="no-sort">ITEM NAME</th>
                <th class="no-sort">TAX RATE</th>
                <th class="no-sort">UNIT PRICE</th>
                <th class="no-sort">QUANTITY</th>
                <th class="no-sort">DISCOUNT</th>
                <th class="no-sort">AMOUNT</th>
                <th class="no-sort">TOTAL</th>
            </tr>
            </thead>
            <tbody>
            <?php
           
          $i = 1;
          $total_final =0;
          $final_tax_amount = 0;
          $final_total_with_tax_amount  = 0;
           $sgst_amount_final = 0;
          $cgst_amount_final = 0;
          $igst_amount_final = 0;
          $total_final = 0;
           $total = 0;
          foreach ($cancel_sales_report_det as  $value) {
          $item = $value['ITEM'];
          $unit = $value['UNIT']; 
          $rate = $value['RATE'];
          $qty = round($value['QUANTITY'],2);
          $discount = round($value['DISCOUNT'],2);
          $amount = round($value['AMOUNT'],2);

          $rate = $value['RATE'];
          $qty = round($value['QUANTITY']);
          $discount = round($value['DISCOUNT'],2);
          $amount = $value['AMOUNT'];
          $sgst_per = round($value['SGSTPERCENT'],2);
          $cgst_per = round($value['CGSTPERCENT'],2);          
          $igst_per = round($value['IGSTPERCENT'],2);
          $tax_rate = $sgst_per + $cgst_per + $igst_per;
          $sgst_amount = round($value['SGSTAMOUNT'],2);
          $cgst_amount = round($value['CGSTAMOUNT'],2);
          $igst_amount = round($value['IGSTAMOUNT'],2);
          $amount_with_discount = $amount - $discount;
          $total = $amount + $sgst_amount + $cgst_amount + $igst_amount - $discount;
          $sgst_amount_final = $sgst_amount + $sgst_amount_final;
          $cgst_amount_final = $cgst_amount + $cgst_amount_final;
          $igst_amount_final = $igst_amount + $igst_amount_final;
          $total_final = $amount_with_discount + $total_final;
          $tax_amount = $sgst_amount + $cgst_amount + $igst_amount;

          
          $final_tax_amount = $final_tax_amount + $tax_amount;
          $final_total_with_tax_amount = $final_total_with_tax_amount + $total;
          
          
          ?>
              <tr>
                <td><?php echo $item;?></td>
                <td><?php echo $tax_rate;?></td>
                <td><?php echo $rate;?></td>                
                <td><?php echo $qty;?></td>                
                <td><?php echo $discount;?></td>
                <td><?php echo number_format($amount,2);?></td>
                <td><?php echo number_format($amount_with_discount,2);?></td>
                
            </tr>
            <?php
          }
            ?>
            <tr>
              <td colspan="8">
              <table  class="table table-striped table-bordered" >
               <?php  if($type_sales == 'DOMESTIC SALES'){              
              ?>
              <tr>
                <td width="15%"><b>SGST AMOUNT</b></td>
               <td><?php echo number_format($sgst_amount_final,2);?></td>
                
            </tr>
            <tr>
                <td><b>CGST AMOUNT</b></td>
                <td><?php echo number_format($cgst_amount_final,2);?></td>
                
            </tr>
            <?php
          }else{
            ?>
            <tr>
                <td width="15%"><b>IGST AMOUNT</b></td>
                <td><?php echo number_format($igst_amount_final,2);?></td>
            </tr>
            <?php
          }
            ?>
            <tr>
                <td></td>
                <td style="text-align: right"><b>Final Total :</b> <?php echo number_format($final_total_with_tax_amount,2);?></td>
            </tr>
            
              </td>
            </table>
            
           </td>
           </tr>
            </tbody>
            </table>
            </td>
          </tr>
           <?php
          }
          
          ?>
        </tbody>
    </table>

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