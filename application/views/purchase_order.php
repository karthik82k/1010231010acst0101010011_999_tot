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
   // Items   
    $('.tf4').on('change', function() {
      
      var id = this.id;
      var string = this.id;      
      var val1 = string.split('_');
       var item_id = $('#'+id) .val();
        $(".tf4").each(function() {           
          var item =  $(this).val();  
          var id1 = $(this);
          var val_id = (id1[0].id);
          
          if(item == item_id && id != val_id){
                  alert('item already selected');
                  $('#'+id) .val('');
                  return false;
          }else{
            if(item_id != '') {      
              $.ajax({
                url  :"<?php echo site_url(); ?>/entry/get_rate",
                data : {item_id: item_id},
                success: function(ret){

                    ret = jQuery.parseJSON(ret);
                    var rate = ret['rate'];
                    var unit = ret['unit_id'];
                     var tax_id = ret['tax_id'];
                    var hsn_code = ret['hsn_code'];  
                    if(val1[1] == ''){
                      $('#txtunitprice_') .val(rate);  
                      $('#cmbunit_').val(unit);
                      $('#txthsn_').val(hsn_code);
                      $('#txttax_').val(tax_id);                    
                    }else{
                      var id1 = val1[1];
                      $('#txtunitprice_'+id1) .val(rate);
                      $('#cmbunit_'+id1).val(unit); 
                      $('#txthsn_'+id1).val(hsn_code);
                      $('#txttax_'+id1).val(tax_id);  
                    }
              }
            })        
        }
          }
        });
        
    });    
  // quantity
    $('.tf5').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var qty = $('#'+id) .val();
     if(qty == ''){
        qty = 0;
        $('#'+id) .val(0);
      }
      if(val1[1] == ''){
      
      var unit_price = $('#txtunitprice_') .val();
      var discount = $('#txtdiscount_') .val();
      var total = parseFloat(qty) * parseFloat(unit_price);
      var total = parseFloat(Number(total).toFixed(2));
        $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var unit_price = $('#txtunitprice_'+id1) .val();
      var discount = $('#txtdiscount_'+id1) .val();
      var total = parseFloat(qty) * parseFloat(unit_price);
       var total = parseFloat(Number(total).toFixed(2));     
       
        $('#txttotal_'+id1) .val(total);
      }

    calc();
  });
  // unity Price
   $('.tf7').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var unit_price = $('#'+id) .val();
     if(unit_price == ''){
        unit_price = 0;
        $('#'+id) .val(0);
      }
      if(val1[1] == ''){
     
      var  qty = $('#txtqty_') .val();
      var discount = $('#txtdiscount_') .val();
      var total = parseFloat(qty) * parseFloat(unit_price);
       var total = parseFloat(Number(total).toFixed(2));
      
     
        $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var  qty = $('#txtqty_'+id1) .val();
      var discount = $('#txtdiscount_'+id1) .val();
      var total = parseFloat(qty) * parseFloat(unit_price);  
       var total = parseFloat(Number(total).toFixed(2));    
       
        $('#txttotal_'+id1) .val(total);
      }

    calc(); 
  }); 
});


function calc(){
  
  var l = 0;
  $(".tf14").each(function() {
    var m =  $(this).val();
    l = parseInt(l) + parseInt(m);
  }); 
  
  var final_totl = l.toFixed(2);
  
  $('#txt_final_total').val(final_totl);  
}


   function create_purchaseorder() {
  var flag = '';
   $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseInt(m);   
   if(l <= 0){
      flag = 'empty';      
   }
  });
  var bill_no = $("#txt_bill_no").val();
  var po_date = $("#txt_date").val();
  var txt_validity = $("#txt_validity").val();
  var account_name = $("#cmb_account_group").val();
  var ledger = $("#cmb_ledger").val();
  
  if(po_date == ''){
    alert("Please Select Date");
     $("#txt_date").focus();
    return false;
  }else if(txt_validity == ''){
    alert("Please enter Validity");
     $("#txt_validity").focus();
    return false;
  }else if(account_name == ''){
    alert("Please Select Account");
     $("#cmb_account_group").focus();
    return false;
  }else if(ledger == ''){
    alert("Please Select Ledger");
     $("#cmb_ledger").focus();
    return false;
  }else if(flag == 'empty') {
    alert("Please enter valid entry in grid");
      return false;
  } else{
    $("#frm_purchaseorder").submit();
  }
     
}
 
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>PURCHASE ORDER</b></li> 
         <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>             
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_purchaseorder" id="frm_purchaseorder" action="<?php echo site_url('entry/add_purchaseorder');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">
            
                 <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Voucher No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $sn[0]['prefix']; ?>" readonly  style="width:35%;    border: 1px solid #555555 !important;  background-color: #f5f5f5; font-size: 15px;"  maxlength = "20">                    
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="<?php echo '0000'.$sn[0]['SN']; ?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5; font-size: 15px;">
                        <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
                    </div>
                </div>
                <div class="form-group col-md-8 col-sm-6 col-xs-6">
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">P.O. No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="input-sm" id="txt_bill_no" style="font-size: 16px; width:100%; border: 1px solid #555555 !important;" name="txt_bill_no" maxlength="35">
                      <input type="hidden"  id="txt_ref_num" name="txt_ref_num" value="">
                      <input type="hidden" class="input-group date" id="txt_reference_date" name="txt_reference_date" >
                       
                    </div>
                </div>
                   <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">P.O. Date<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" style="font-size: 16px;">
                       
                    </div>
                </div>
                 
                <!--<div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Quotation Ref. No.<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm" id="txt_ref_num" name="txt_ref_num" class="form-control">
                       
                    </div>
                </div>
                    <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Quote. Ref. Dt.<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm  input-group date" id="txt_reference_date" name="txt_reference_date" class="form-control" placeholder="dd-mm-yyyy">
                       
                    </div>
                </div> -->
                 
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Credit Period<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="number" class="form-control input-sm" id="txt_validity" name="txt_validity" class="form-control" >
                       
                    </div>
                </div>
                </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
            
                
                <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Account Name<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:90%;    border: 1px solid #555555 !important; font-size: 16px;">
                        <option value="" selected="selected">Select Account Name</option>
                          <?php foreach ($account as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>             
                      </select>
                    </div>
                   
                </div>
                <div class="form-group col-md-8 col-sm-6 col-xs-6">
                 
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Vendors Code<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9"> 
                      <input type="text" class="form-control input-sm" id="txt_vendor_code" name="txt_vendor_code" class="form-control" maxlength="25">
                       
                    </div>
                </div>
                    <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Reference Project<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm" id="txt_project_ref" name="txt_project_ref" class="form-control"">
                       
                    </div>
                </div>
                 <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Test Certificate Ref.<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm" id="txt_test_certificate" name="txt_test_certificate" class="form-control">
                       
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Mode of Transportation<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm" id="txt_transport" name="txt_transport" class="form-control">
                       
                    </div>
                </div>
                </div>
                </div>
                 <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Ledger<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-8 col-xs-9" style="align:left;">
                        <select id="cmb_ledger" name="cmb_ledger" class="form-control input-sm" style="font-size: 16px;" >
                        <option selected="selected" value="">select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>            
                      </select>
                    </div>
                   
                </div>
                
             <div class="form-group col-md-8 col-sm-6 col-xs-6">
                 <input type="hidden"  id="txt_jurisdiction" name="txt_jurisdiction" value="">
                 <!--<div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Jurisdiction<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm" id="txt_jurisdiction" name="txt_jurisdiction" class="form-control">
                       
                    </div>
                </div> -->
                    <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Shipping Address<span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_shipping" id="cmb_shipping" class="input-sm" style="width:96%; border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Shipping address</option>
                      </select>
                    </div>
                </div>
                  <div class="col-md-5 col-sm-9 col-xs-9" style="text-align:center; ;border-radius: 1px 1px 1px 1px; border: solid 1px #000000;  margin-top: 15px;">
                                    <label class="btn btn-default">
                                        <input type="radio" id="order_1" class="chk_gst" name="chk_type"  value="1"  /> Sales Order</label>
                                         <label class="btn btn-default">
                                        <input type="radio" id="order_2" class="chk_gst" name="chk_type"  value="0" checked="checked"/> Purchase Order</label>
                                </div>
                
                </div>
                </div>
                 <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <!--<label for="emirate" class="col-md-12 control-label ">Billing Address<span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_billing" id="cmb_billing" class="input-sm" style="width:90%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Billing address</option>
                      </select>
                    </div> -->
                   
                </div>
                
             <div class="form-group col-md-8 col-sm-6 col-xs-6">
                 
                 <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 " style="width:250px">This Scheduled supply on / in between </label>
                    <div class="col-md-2 col-sm-9 col-xs-9" style="width: 108px; padding-right: 0px;padding-left: 0px;">
                      <input type="text" class="input-sm  input-group date" id="txt_delivery_from_date" name="txt_delivery_from_date" style="  border: 1px solid #555555 !important;"  placeholder="dd-mm-yyyy">
                       
                    </div> <label for="emirate" class="col-md-1" style="width: 20px;padding-right: 1px;padding-left: 8px;">to</label><div class="col-md-2 col-sm-9 col-xs-9">
                      <input type="text" class="input-sm input-group date" id="txt_delivery_to_date" name="txt_delivery_to_date" placeholder="dd-mm-yyyy" style="border: 1px solid #555555 !important;"> 
                       
                    </div>
                </div>
                   
                 
                
                </div>
                </div>
            </div>         
            
            <div style="height: 160px; overflow: auto; background-color: #ffffff;" >
               <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 12px; background-color: #003166; color: #ffffff;">        
                    <th style="width: 350px">Item Name</th>
                    <th>HSN Code</th>
                    <th>Tax Rate</th>
                    <th>Unit</th>                    
                    <th style="width: 35px">Qty</th>
                    <th style="width: 35px">Rate</th>
                    <th style="width: 50px; text-align: center;">Total Amount</th>
                    <th></th>
                </tr>
              </thead>
          <tbody>
          <tr class="to_clone1">
          <td>
            <select id="cmbitemname_" name="cmb_item_name[]" class="tf4 txtara_lst input_grid" style="width:340px;" >
              <option value="" selected>select Item</option>
                <?php foreach ($item as $row) {
                  echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
                }?>
            </select
          </td>
          <td>
             <input name="txt_hsn[]" type="text" class="tf10 addeb_input_txtfld input_grid" id="txthsn_" style="pointer-events: none;" />
          </td>
          <td>
            <select name="txt_tax[]" class="tf9 txtara_lst input_grid" id="txttax_" style="width:75px;font-size: 12px; pointer-events: none;">
              <?php
                foreach ($tax as $data) {
                  echo "<option value='".$data['ID']."'>".$data['TAXTYPE']."</option>";  
                }
              ?>
              </select>
          </td>
          <td>
              <select id="cmbunit_" name="cmb_unit[]" class="tf6 txtara_lst input_grid" style="width:75px; font-size: 12px; pointer-events: none;">
                            <option value="" selected="selected"> Unit</option><?php
                              foreach ($unit as $data) {
                                echo "<option value='".$data['ID']."'>".$data['NAME']."</option>";  
                              }
                              ?>
              </select>
          </td> 
          <td>
              <input name="txt_qty[]" type="number" class="tf5 addeb_input_txtfld input_grid" id="txtqty_" value="0"   maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>         
           <td>
              <input name="txt_unit_price[]" type="number" class="tf7 addeb_input_txtfld input_grid" id="txtunitprice_" value="0"   maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>
          
          <!--<td>
              <input name="txt_discount[]" type="number" class="tf9 addeb_input_txtfld input_grid" id="txtdiscount_" value="0"/>
          </td> -->
          <td>
              <input name="txt_total[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="txttotal_" value="0" readonly style=" background-color: #f5f5f5;" />
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
              <div class="form-group col-md-9 col-sm-6 col-xs-6" >
                <label for="emirate" class="col-md-12 control-label">Terms and Conditions <span class="textspandata"></span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                         <textarea name="txt_terms" id="txt_terms" cols="10" rows="5" class="txtarahg" style="height: 42px;width: 90%;"  maxlength="250"></textarea>
                    </div>
                </div> 
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">GRAND TOTAL<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_final_total" type="number" id="txt_final_total" class="input-sm" readonly style="    background-color: #f5f5f5; border: 1px solid #555555 !important; font-size: 16px;" >
                    </div>
                </div>
            </div>            
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_purchaseorder();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('entry/manage_purchase_order');?>'" />
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
 $(document).ready(function() {
 $(".chk_gst").click(function(){
   var val = '';
    if ($('#order_1').is(':checked')){
        val = 'sales';
    }
    if ($('#order_2').is(':checked')){
         val = 'purchase';
    }
    $('#cmb_account_group').empty();
    $('#cmb_ledger').empty();    
    if(val != '') {
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_account",
            data : {val: val},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                  $('#cmb_account_group').empty();
                var district = $('#cmb_account_group');
                var opt = document.createElement('option');
                opt.text = 'Select Account';
                opt.value = '';               
                district.append(opt);
                for(var key in ret){ 
                    var opt = document.createElement('option');
                    opt.text = ret[key]['ACCOUNTDESC'];
                    opt.value = ret[key]['ID'];
                    district.append(opt);
                }
           
          }
        }); 

        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_ledger",
            data : {val: val},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
               // console.log(ret);
                  $('#cmb_ledger').empty();
                var ledger = $('#cmb_ledger');
                var opt_leg = document.createElement('option');
                opt_leg.text = 'Select Ledger';
                opt_leg.value = '';               
                ledger.append(opt_leg);
                for(var key in ret){ 
                    var opt_leg = document.createElement('option');
                    opt_leg.text = ret[key]['ACCOUNTDESC'];
                    opt_leg.value = ret[key]['ID'];
                    ledger.append(opt_leg);
                }
           
          }
        });
              $('#cmb_shipping').empty();
                var ship = $('#cmb_shipping');
                var shipopt = document.createElement('option');
                shipopt.text = 'Select Shipping address';
                shipopt.value = '';
                ship.append(shipopt); 

    }
    });
});
     var id = 1;
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
        item_unit = '';      
        $(".tf14").each(function() {
          var m =  $(this).val();
          l = parseInt(m);   
          if(l <= 0){
            flag = 'empty';      
          }
        });
        $(".tf4").each(function() {
          var item =  $(this).val();   
          if(item == ''){
            item_flag = 'empty';      
          }
        });

         $(".tf6").each(function() {
          var unit =  $(this).val();   
          if(unit == ''){
            item_unit = 'empty';      
          }
        });

     if(flag == 'empty' || item_flag == 'empty' || item_unit == 'empty'){
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

 $("#cmb_account_group").change(function () {
    var account_id =  $(this).val();
    $('#cmb_shipping').empty();
    if(account_id != '') {
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_shipping",
            data : {account_id: account_id},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                console.log(ret);
                $('#cmb_shipping').empty();
                var district = $('#cmb_shipping');
                var opt = document.createElement('option');
                opt.text = 'Select Shipping address';
                opt.value = '';
                district.append(opt);
                for(var key in ret){ 
                    var opt = document.createElement('option');
                    opt.text = ret[key]['ADDRESS1'];
                    opt.value = ret[key]['PartyAddress_ID'];
                    district.append(opt);
                }
           
          }
        }); 
        

    }
}); 
 
 // var maxDate = new Date('<?php echo $financial_year_to;?>');
  //var minDate = new Date('<?php echo $financial_year_from;?>');
   
 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
  $('.input-group.date').datepicker('setDate', 'now');
 // $('.input-group.date').datepicker('setStartDate', minDate);
 // $('.input-group.date').datepicker('setEndDate', maxDate); 
    </script>
</body>
</html>