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

 $(document).ready(function() { 
 $('#chk_tax_app').change(function() {
     if(this.checked) {
        $('#cmb_tax').attr("style", "pointer-events: all;");
        $('#cmb_type').attr("style", "pointer-events: all;");
        $('#txt_tin').attr("style", "pointer-events: all;");
      }else{
        $('#cmb_tax').attr("style", "pointer-events: none;");
        $('#cmb_type').attr("style", "pointer-events: none;");
        $('#txt_tin').attr("style", "pointer-events: none;");
        $('#cmb_type').val('');
         $('#cmb_tax').val('');
         $('#txt_sgst').val(0);
         $('#txt_cgst').val(0);
          $('#txt_igst').val(0);     
           calc();    
      }       
    });   
  // amount
    $('.tf14').on('change', function() {
    
     var l =  0;
    var a =  $('#txt_amount').val();
    var amt =  parseFloat(a);
     $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseFloat(l) + parseFloat(m);
});
    if( amt <  l){
      alert('Amount is not match');
      $(this).val('');
    }
  });

     $('#cmb_ledger').on('change', function() {

      var item_id = $('#cmb_ledger').val();
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_invoice_payment",
            data : {accound_id: item_id},
            success: function(ret){
               ret = jQuery.parseJSON(ret);
               
                  $('#cmbinvoice_').empty();
                var bill = $('#cmbinvoice_');
             
                var opt = document.createElement('option');
                opt.text = 'Select Orginal Bill';
                opt.value = '';               
                bill.append(opt);
                for(var key in ret){ 
                    var opt = document.createElement('option');
                    opt.text = ret[key]['PREFIX']+ret[key]['REFNUM']+'  - AMOUNT :'+ret[key]['AMOUNT']+'  - Date :'+ret[key]['Display_date'];
                    opt.value = ret[key]['REFNUM']+'~'+ret[key]['AMOUNT']+'~'+ret[key]['DATE']+'~'+ret[key]['ID'];
                    bill.append(opt);
                }
                
            }
        });
      
          
    });

     $('.tf4').on('change', function() {
      var amt = $('#txt_amount').val();
      var id = this.id;
      var string = this.id;      
      var val1 = string.split('_');
       var item_id = $('#'+id) .val();
        $(".tf4").each(function() {           
          var item =  $(this).val();  
          var id1 = $(this);
          var val_id = (id1[0].id);
          if(amt == ''){
             alert('Please enter Voucher amount');                 
              return false;
          }
          
          if(item == item_id && id != val_id){
                  alert('Ledger already selected');
                  $('#'+id) .val('');
                  return false;
          }
        });
      });
});

function create_receipt() {
  var flag = '';
  var item_flag =  '';
  var n =  0;
   $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseInt(m);   
    n = parseFloat(n) + parseFloat(m);
   if(l <= 0 || l == ''){
      flag = 'empty';      
   }
  });
   $(".tf4").each(function() {
          var item =  $(this).val();   
          if(item == ''){
            item_flag = 'empty';      
          }
        });
   var a =  $('#txt_amount').val();
  var amt =  parseFloat(a);
    var payment_type = $("#cmb_payment_type").val();
    var voucher_type = $("#cmb_voucher_type").val();
    var financial_year = $("#cmb_finance_year").val();
    var bill_no = $("#txt_bill_no").val();
    var debit_date = $("#txt_date").val();
    var account_name = $("#cmb_account_group").val();
    var ledger = $("#cmb_ledger").val();
    

  if(payment_type == ''){
     alert("Please Select Payment Type");
     $("#cmb_payment_type").focus();
    return false;
  } else if(voucher_type == ''){
    alert("Please Select Voucher Type");
     $("#cmb_voucher_type").focus();
    return false;
  } else if(financial_year == ''){
    alert("Please Select Financial Year");
     $("#cmb_finance_year").focus();
    return false;
  }else if(bill_no == ''){
    alert("Please enter Reference no.");
     $("#txt_bill_no").focus();
    return false;
  }else if(debit_date == ''){
    alert("Please Select Date");
     $("#txt_date").focus();
    return false;
  }else if(account_name == ''){
    alert("Please Select Account");
     $("#cmb_account_group").focus();
    return false;
  } else if(flag == 'empty') {
    alert("Please enter valid entry in grid");
      return false;
  } else if(item_flag == 'empty'){
    alert("Please enter valid entry in grid");
      return false;
  } else if(amt != n){
    alert("Billwise amount is not match.");
      return false;
  } else{
    $("#frm_debit").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>BILLWISE RECEIPT VOUCHER</b></li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_debit" id="frm_debit" action="<?php echo site_url('entry/add_receipt_voucher_billwise');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">
            <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
            <!--<div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Financial Year<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_finance_year" id="cmb_finance_year" class="form-control input-sm">
                          <?php foreach ($financial_year as $row) {
                              echo "<option value='".$row['FINANCIALYEAR_ID']."' selected>".$row['FINANCIALYEAR']."</option>"; 
                            }?>              
                          </select>
                    </div>
                   
                </div>-->
                 <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Voucher No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">  
                        <select id="cmb_payment_type"  class="input-sm" name="cmb_payment_type" style="width:35%;    border: 1px solid #555555 !important;  font-size: 15px;">
                          <option value="" selected="">Select Type</option>
                          <option value="CR" >Cash Receipt</option>
                          <option value="BR" >Bank Receipt</option>                         
                        </select>                 
                        <select id="cmb_voucher_type"  class="input-sm" name="cmb_voucher_type" style="width:25%;    border: 1px solid #555555 !important;  font-size: 15px;">
                          <option value="" selected="">Select Type</option>                          
                        </select>                   
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="" readonly style="    border: 1px solid #555555 !important; width:30%;  background-color: #f5f5f5;  font-size: 15px;">
                    </div>
                </div>
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Reference No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                   
                       <input type="text" class="form-control input-sm"  name="txt_bill_no"  id="txt_bill_no" maxlength="10" style=" font-size: 16px;"> 
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Date<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                       <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" style=" font-size: 16px;">
                    </div>
                </div>
                <!-- <div class="form-group col-md-3 col-sm-6 col-xs-6">
                          <label >Tax Applicable<input type="checkbox" id="chk_tax_app" name="chk_tax_app" value="1"  > </label><input type="text" class="form-control input-sm" id="txt_tin" name="txt_tin" class="form-control" placeholder="GST NUMBER" style="font-size: 16px; pointer-events: none;" >
                                </div>  -->
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
            
                
                <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Account Name<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:90%;    border: 1px solid #555555 !important;  font-size: 16px;">
                        <option value="" selected="selected">Select Account Name</option>       
                      </select>
                    </div>
                   
                </div>
                <!-- <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Tax Rate<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                      <select name="cmb_tax" id="cmb_tax"  class="form-control input-sm" style="pointer-events: none;">
                           <option value="" selected>Select Tax Rate</option> 
                  <?php foreach ($tax as $row) {
                      echo "<option value='".$row['ID']."'>".$row['TAXTYPE']."</option>"; 
                    }?>  
                        </select>
                        </div>
                        <div class="col-md-6 col-sm-9 col-xs-9">
                         <select name="cmb_type" id="cmb_type"  class="form-control input-sm" style="pointer-events: none;">
                           <option value="" selected>Select Type </option> 
                            <option value="DOMESTIC">DOMESTIC</option>
                            <option value="INTER STATE">INTER STATE</option>
                        </select>
                       
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">SGST<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input name="txt_sgst" id="txt_sgst" type="number" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; width: 75%"  value="0">
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">CGST<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                       <input name="txt_cgst" id="txt_cgst" type="number"  class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; width: 75%"  value="0">
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">IGST<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                       <input name="txt_igst" id="txt_igst" type="number" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; width: 75%" value="0">
                    </div>
                </div>-->

                <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Ledger<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                      
                          <select id="cmb_ledger" name="cmb_ledger" class="form-control input-sm" style="font-size: 16px;"  >
                        <option selected="selected" value="">select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>            
            </select>
                    </div>
                   
                </div> <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Amount<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                       <input name="txt_amount" id="txt_amount" type="number" class="input-sm form-control" >
                        </div>
                        
                </div>
                </div>
                   <div class="col-md-12 col-sm-12 col-xs-12">
            
                 
                <!--<div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Ledger<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                      
                          <select id="cmb_ledger" name="cmb_ledger" class="form-control input-sm" style="font-size: 16px;"  >
                        <option selected="selected" value="">select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>            
            </select>
                    </div>
                   
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Amount<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                       <input name="txt_amount" id="txt_amount" type="number" class="input-sm form-control" >
                        </div>
                        
                </div> -->
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                
                </div>
                </div>              
                  
            </div>            
            
            <div style="height: 160px; overflow: auto; background-color: #ffffff;" >
             <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 12px; background-color: #003166; color: #ffffff;">        
                    <th>Billwise Details </th> 
                    <th>Amount</th> 
                    <th></th>
                </tr>
              </thead>
          <tbody>
          <tr class="to_clone1">
          <td>
            <select id="cmbinvoice_" name="cmb_invoice[]" class="tf4 form-control input-sm" style="font-size: 16px; width: 250px;"  >
                     <option selected="selected" value="">select Bill</option>             
            </select>
          </td>
                    
          <td>
              <input name="txt_total[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="txttotal_" value="0"  maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" style="font-size: 16px;" />
          </td>
          <td align="center" style="width:45px;">
              <img src="https://test.newui.myddf.info/images/add.png" name="multiple" id="multiple" class="f_moreFilter" alt="Add Multiple" title="Add Multiple" /></span> 
              <span class="f_deleteimg"></span>
          </td>
        </tr>     
      </tbody>
    </table>
  </div> 
  <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Narration<span class="textspandata">*</span></label>
                <div class="col-md-12 col-sm-9 col-xs-9">  
                  <textarea name="txt_narration" id="txt_narration" cols="5" rows="5" class="txtarahg" style="height: 42px;width: 90%;"  maxlength="250"></textarea>
                  </div>
                  </div>
                  </div>
     <div class="col-md-12 col-sm-12 col-xs-12">               
                
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                  <!-- <label for="emirate" class="col-md-12 control-label">SGST AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                        <input name="txt_total_sgst" type="number" id="txt_total_sgst" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important;  font-size: 16px;">
                    </div> -->
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                 <!--  <label for="emirate" class="col-md-12 control-label">CGST AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                        <input name="txt_total_cgst" type="number" id="txt_total_cgst" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important;  font-size: 16px;" >
                    </div> -->
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                  <!-- <label for="emirate" class="col-md-12 control-label">IGST AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                        <input name="txt_total_igst" type="number" id="txt_total_igst" class="input-sm" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important;  font-size: 16px;">
                    </div> -->
                </div>
                <div class="form-group col-md-3">
                <label for="emirate" class="col-md-12 control-label" style="text-align: right;  margin-top: 20px;"> GROSS TOTAL</label>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">TOTAL AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_final_total" type="number" id="txt_final_total" class="input-sm" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important;  font-size: 16px;" >
                    </div>
                </div>
            </div>            
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_receipt();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('entry/manage_receipt_voucher_billwise');?>'" />
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
<script type="text/javascript">

     var id = 1;
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
        $(".tf14").each(function() {
          var m =  $(this).val();
          l = parseInt(m);   
          if(l <= 0 || l == ''){
            flag = 'empty';      
          }
        });
        $(".tf4").each(function() {
          var item =  $(this).val();   
          if(item == ''){
            item_flag = 'empty';      
          }
        });
      if(flag == 'empty' || item_flag == 'empty' ){
        alert("Please enter proper value in Grid.");
        return false;
      }
        var new_div1 = $(this).closest('table').find('.to_clone1').eq(0).clone(true).appendTo("#textdata");

        var tf4 = new_div1.find('.tf4').eq(0);
        var tf5 = new_div1.find('.tf5').eq(0);
        var tf6 = new_div1.find('.tf6').eq(0);
        var tf7 = new_div1.find('.tf7').eq(0);
        var tf8 = new_div1.find('.tf8').eq(0);
        var tf9 = new_div1.find('.tf9').eq(0);
        var tf10 = new_div1.find('.tf10').eq(0);
        var tf11 = new_div1.find('.tf11').eq(0);
        var tf12 = new_div1.find('.tf12').eq(0);
        var tf13 = new_div1.find('.tf13').eq(0);
        var tf14 = new_div1.find('.tf14').eq(0);

        tf4.val('').css('height', 'auto');
        tf5.val(0).css('height', 'auto');
        tf6.val('').css('height', 'auto');
        tf7.val(0).css('height', 'auto');
        tf8.val(0).css('height', 'auto');
        tf9.val('').css('height', 'auto');
        tf10.val('').css('height', 'auto');
        tf11.val('').css('height', 'auto');
        tf12.val('').css('height', 'auto');
        tf13.val('').css('height', 'auto');
        tf14.val(0).css('height', 'auto');

        fdp8_ = tf4.attr('id');
        tf4.attr('id', fdp8_ + id);

        fdp9_ = tf5.attr('id');
        tf5.attr('id', fdp9_ + id);

        fdp10_ = tf6.attr('id');
        tf6.attr('id', fdp10_ + id);

        fdp11_ = tf7.attr('id');
        tf7.attr('id', fdp11_ + id);

        fdp12_ = tf8.attr('id');
        tf8.attr('id', fdp12_ + id);

        fdp13_ = tf9.attr('id');
        tf9.attr('id', fdp13_ + id);

        fdp14_ = tf10.attr('id');
        tf10.attr('id', fdp14_ + id);

        fdp15_ = tf11.attr('id');
        tf11.attr('id', fdp15_ + id);

        fdp16_ = tf12.attr('id');
        tf12.attr('id', fdp16_ + id);

        fdp17_ = tf13.attr('id');
        tf13.attr('id', fdp17_ + id);

        fdp18_ = tf14.attr('id');
        tf14.attr('id', fdp18_ + id);

        var img = $('<img></img>', {
            src: "https://test.newui.myddf.info/images/close.png",
            class: "f_deleteFilter1",
            width: 18,
            height: 18,
            style: "cursor:  pointer;",
            alt: "Delete",
            title: "Delete"
        });
        new_div1.find('span.f_deleteimg').append(img);

        id++;
    });

    $(".f_deleteFilter1").live('click', function() {
        $(this).closest('tr').remove();
        id--;
        calc();
    });

   
$("#cmb_payment_type").change(function () {
      var payment_type = $(this).val();
      
      if(payment_type != '') {


        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_voucher",
            data : {payment_type: payment_type},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
              //  console.log(ret['ledger_name']);
                $('#cmb_voucher_type').empty();
                $('#txt_serial_no').val('');                
                $('#cmb_account_group').empty();
                var district = $('#cmb_voucher_type');
                var opt = document.createElement('option');
                opt.text = 'Select Type';
                opt.value = '';
                district.append(opt);
                var account_group = $('#cmb_account_group');
                var opt_acc = document.createElement('option');
                opt_acc.text = 'Select Party';
                opt_acc.value = '';
                account_group.append(opt_acc);
                $.each(ret['voucher_type'], function(key, value) {  
               // console.log( value,key);    
                var opt = document.createElement('option');
                        opt.text = value['prefix'];
                        opt.value = value['prefix'];                        
                        district.append(opt);
                    
                });

                $.each(ret['ledger_name'], function(key1, value1) {  
                    //console.log( value1['ID']);  
                        var opt_acc = document.createElement('option');
                        opt_acc.text = value1['ACCOUNTDESC'];
                        opt_acc.value = value1['ID'];
                        
                        account_group.append(opt_acc);  
                    
                });
               
            }
        })
        }else{
        $('#cmb_voucher_type').empty();
        $('#cmb_account_group').empty();
        $('#txt_serial_no').val('');
        var district = $('#cmb_voucher_type');
        var opt = document.createElement('option');
        opt.text = 'Select Type';
        opt.value = '';
        district.append(opt);
         var account_group = $('#cmb_account_group');
                var opt_acc = document.createElement('option');
                opt_acc.text = 'Select Party';
                opt_acc.value = '';
                account_group.append(opt_acc);
      } 

    });

$('#cmb_voucher_type').on('change', function() {
    var type = $(this).val(); 
    if(type != '') {      
          $.ajax({
            url  :"<?php echo site_url(); ?>/entry/new_sn",
            data : {type: type},
            success: function(ret){
               ret = jQuery.parseJSON(ret);
              var sn = ret['sn'];
              $('#txt_serial_no').val('00000'+sn);
            }
          });}

   });

$("#cmb_tax").change(function () {
      var tax = $(this).val();
      var leg = $('#cmb_type option:selected').text(); 
      var leg_id = $('#cmb_type').val(); 
      
       if(tax != '' && leg_id != '') {         
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_tax",
            data : {tax: tax, leg: leg},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                var cgst = ret['cgst'];
                var igst = ret['igst'];
                var sgst = ret['sgst'];
                $('#txt_sgst').val(sgst);
                $('#txt_igst').val(igst);
                $('#txt_cgst').val(cgst);
                 calc();
                
            }
        });
      }else{
            $('#txt_sgst').val(0);
            $('#txt_igst').val(0);
            $('#txt_cgst').val(0);
            calc(); 
      }
    });

$("#cmb_type").change(function () {
      var leg_id = $(this).val();
      var leg = $('#cmb_type option:selected').text(); 
      var tax = $('#cmb_tax').val(); 
      
       if(tax != '' && leg_id != '') {         
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_tax",
            data : {tax: tax, leg: leg},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                var cgst = ret['cgst'];
                var igst = ret['igst'];
                var sgst = ret['sgst'];
                $('#txt_sgst').val(sgst);
                $('#txt_igst').val(igst);
                $('#txt_cgst').val(cgst);
                 calc();
                
            }
        });
      }else{
            $('#txt_sgst').val(0);
            $('#txt_igst').val(0);
            $('#txt_cgst').val(0);
            calc(); 
      }

    });

$("#txt_amount").change(function () {
  calc();
});

  function calc(){
   var m =  $('#txt_amount').val();
  var l = parseFloat(m);

   //var sgst = $('#txt_sgst').val();
   var sgst = 0;
  var sgst_val = parseFloat(sgst) / 100;
  var sgst_totals = l*sgst_val;
  var sgst_total = sgst_totals.toFixed(2);

  //var igst = $('#txt_igst').val();
   var igst = 0;
  var igst_val = parseFloat(igst) / 100;
  var igst_totals = l*igst_val;
  var igst_total = igst_totals.toFixed(2);

  //var cgst = $('#txt_cgst').val();
   var cgst = 0;
  var cgst_val = parseFloat(cgst) / 100;  
  var cgst_totals = l*cgst_val;
  var cgst_total = cgst_totals.toFixed(2);
  var final_totls = parseFloat(cgst_total) + parseFloat(sgst_total) + parseFloat(igst_total) + parseFloat(l);
  var final_totl = final_totls.toFixed(2);

 // $('#txt_total_sgst').val(sgst_total);
  //$('#txt_total_igst').val(igst_total);
  //$('#txt_total_cgst').val(cgst_total);
  $('#txt_final_total').val(final_totl); 
  }

 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
 $('.input-group.date').datepicker('setDate', 'now');
 $('.input-group.date').datepicker('setStartDate', '<?php echo $financial_year_from;?>');
 $('.input-group.date').datepicker('setEndDate', '<?php echo $financial_year_to;?>'); 
    </script>
</body>
</html>