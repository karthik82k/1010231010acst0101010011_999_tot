<?php $this->load->view('include/header_grid'); 

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
label {
    margin-left: 2%;
    margin-top: 0%;
    }
    .well {
        padding: 1px;
    }
    .col-md-12{
      padding: 5px;
      padding-bottom: 0px;
    margin: 0px;
    padding-top: 0px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
      padding: 2px;
    }
    .input_grid{
      height: 23px;
    }
</style>
<script type="text/javascript">

 


function create_salesvoucher() {
  var currency = $("#cmb_currency").val();
  var conversion = $("#txt_conversion").val();
  
   if(currency == ''){
    alert("Please Select Currency");
     $("#cmb_currency").focus();
    return false;
  }else if(conversion == ''){
    alert("Please Enter Conversion Rate");
     $("#conversion").focus();
    return false;
  }else{
    $("#frm_sales_voucher").submit();
  }
  
    
  
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>PRINT EXPORT INVOICE</b></li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li> 
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>         
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:1px 1px 1px 1px;">
            <form name="frm_sales_voucher" id="frm_sales_voucher" action="<?php echo site_url('entry/print_export_invoice');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">            
                <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Voucher No<span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $prefix; ?>" readonly  style="width:35%;    border: 1px solid #555555 !important;  background-color: #f5f5f5;"> 
                        <input type="hidden"  name="txt_serial_no" id="txt_serial_no" value="<?php echo $sn; ?>">             
                        <input type="text" class=" input-sm" name="txt_serial" id="txt_serial" value="<?php echo '0000'.$sn; ?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5;">
                        <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Bill No<span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <input type="text" name="txt_bill_no" id="txt_bill_no" class="form-control input-sm"  maxlength = "15" value="<?php echo $bill_no;?>" readonly >
                    </div>
                   
                </div>
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Amount<span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                    <?php
                      $sgst_amount_final = 0;
          $cgst_amount_final = 0;
          $igst_amount_final = 0;
          $total_final = 0;
                    foreach ($purchase_order_detail as  $value) {
          
          $discount = round($value['DISCOUNT'],2);
          $amount = round($value['AMOUNT'],2);
          
          $sgst_amount = round($value['SGSTAMOUNT'],2);
          $cgst_amount = round($value['CGSTAMOUNT'],2);
          $igst_amount = round($value['IGSTAMOUNT'],2);
          $total = $amount + $sgst_amount + $cgst_amount + $igst_amount - $discount;
          $sgst_amount_final = $sgst_amount + $sgst_amount_final;
          $cgst_amount_final = $cgst_amount + $cgst_amount_final;
          $igst_amount_final = $igst_amount + $igst_amount_final;
          $total_final = $total + $total_final;
        }
                    ?>
                     <input type="text" name="txt_amount" id="txt_amount" class="form-control input-sm"  maxlength = "15" value="<?php echo $total_final;?>" readonly >
                       
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Currency<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <select id="cmb_currency" name="cmb_currency" class="form-control input-sm"  >
                       <option value="">Select Currency</option>
                          <?php foreach ($currency as $row) {
                                          echo "<option value='".$row['ID']."'>".$row['NAME'].'( '.$row['CODE'].' )'."</option>"; 
                                        }?> 
                     </select>
                       
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Conversion Rate<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                     <input type="number" name="txt_conversion" id="txt_conversion" class="form-control input-sm"  value="0"  maxlength = "6" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  >
                       
                    </div>
                </div>
                
                </div>
                
                 
                
            </div>         
            
           
  
                  
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_salesvoucher();"/>
                </div>          
            </div>  
        </form>       
    </div>    
  </div> 
</div>
  <div style="height:30px;"></div>                
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
</html>