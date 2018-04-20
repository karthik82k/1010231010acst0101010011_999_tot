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
    
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
 <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">  
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('/assets/css/style.css');?>" rel="stylesheet">             
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
    .bootstrap-datetimepicker-widget.dropdown-menu {
  background-color:#FFF;
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
          }
        });
        if(item_id != '') {      
          $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_rate",
            data : {item_id: item_id},
            success: function(ret){

                ret = jQuery.parseJSON(ret);
                var rate = ret['rate'];
                var unit = ret['unit_id'];                
                if(val1[1] == ''){
                  $('#txtunitprice_') .val(rate); 
                  $('#cmbunit_').val(unit);                  
                }else{
                  var id1 = val1[1];
                  $('#txtunitprice_'+id1) .val(rate);
                   $('#cmbunit_'+id1).val(unit); 
                }
          }
        })        
      }
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
      var total = parseFloat(qty) * parseFloat(unit_price);
       var total = parseFloat(Number(total).toFixed(2));
        $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var unit_price = $('#txtunitprice_'+id1) .val();
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
      var total = parseFloat(qty) * parseFloat(unit_price);
       var total = parseFloat(Number(total).toFixed(2));
     
        $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var  qty = $('#txtqty_'+id1) .val();
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

  function create_salesorder() {
  var flag = '';
   $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseInt(m);   
   if(l <= 0){
      flag = 'empty';      
   }
  });
  var crn_no = $("#txt_crn_no").val();
  var crn_date = $("#txt_crn_date_time").val();
  var lrn_no = $("#txt_lrn_no").val();
  var gate_pass = $("#txt_gate_pass_no").val();
  var account_name = $("#cmb_account_group").val();
  var po_number = $("#cmb_po_number").val();
  
   if(crn_no == ''){
    alert("Please Enter GRN No");
     $("#txt_crn_no").focus();
    return false;
  }else if(crn_date == ''){
    alert("Please Select GRN Date and Time");
     $("#txt_crn_date_time").focus();
    return false;
  }else if(lrn_no == ''){
    alert("Please enter Lorry No");
     $("#txt_lrn_no").focus();
    return false;
  }else if(account_name == ''){
    alert("Please Select Account");
     $("#cmb_account_group").focus();
    return false;
  }else if(gate_pass == ''){
    alert("Please enter gate pass");
     $("#txt_gate_pass_no").focus();
    return false;
  }else if(po_number == ''){
    alert("Please select PO Number");
     $("#cmb_po_number").focus();
    return false;
  }else if(flag == 'empty') {
    alert("Please enter valid entry in grid");
      return false;
  } else{
    $("#frm_salesorder").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>Edit Goods Received Note</b></li> 
         
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>         
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_salesorder" id="frm_salesorder" action="<?php echo site_url('inventory/update_goods_recieved_note');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12"> 
            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Voucher No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $grn_prefix; ?>" readonly  style="width:35%;    border: 1px solid #555555 !important;  background-color: #f5f5f5;"  maxlength = "20">                    
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="<?php echo '0000'.$grn_voucherno; ?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5;">
                        <input type="hidden" name="vouceher_no" id="vouceher_no" value="<?php echo $grn_voucherno; ?>" >
                        <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
                    </div>
                </div>           
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">GRN No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="form-control input-sm " id="txt_crn_no" name="txt_crn_no" class="form-control" value="<?php echo $grn_do_bill;?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5;">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">GRN Date Time<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="form-control input-sm  input-group date" id="txt_crn_date_time" name="txt_crn_date_time" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo $grn_date_time;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">LRN No.<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="form-control input-sm" id="txt_lrn_no" name="txt_lrn_no" class="form-control" value="<?php echo $lry_no;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Gate Pass No.<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="form-control input-sm" id="txt_gate_pass_no" name="txt_gate_pass_no" class="form-control" value="<?php echo $gate_pass;?>">
                    </div>
                </div>
                
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
            
                 
                
                <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Account Name<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Account Name</option>
                          <?php foreach ($account as $row) {
                            if($account_id == $row['ID']){
                              echo "<option value='".$row['ID']."' selected>".$row['ACCOUNTDESC']."</option>"; 
                            }else{
                              echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>";   
                            }
                           
                          }?>             
                      </select>
                    </div>
                   
                </div>
                <div class="form-group col-md-4 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Purchase Order No.<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                    <input type="hidden"  name="cmb_po_number" id="cmb_po_number" value="<?php echo $purchase_id;?>">
                       <select name="cmb_po_number1" id="cmb_po_number1" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;" disabled="true background-color: #f5f5f5;">
                        <option value="" selected="selected">Select Purchase Order No.</option>
                          <?php foreach ($orders as $key) {
                             if($purchase_id == $key['VOUCHER_ID']){
                              echo "<option value='".$key['VOUCHER_ID']."' selected>".$key['PREFIX'].$key['id']."</option>";
                            }else{
                               echo "<option value='".$key['VOUCHER_ID']."'>".$key['PREFIX'].$key['id']."</option>";
                            }
                                                       
                          }?>             
                      </select>
                    </div>
                </div>
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">P.O. Date<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                        <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" value="<?php  echo $po_date;?>">
                    </div>
                   
                </div>
                </div>
                
                
            </div>         
            
            <div style="height: 160px; overflow: auto; background-color: #ffffff;" >
              <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 12px; background-color: #003166; color: #ffffff;">        
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Qty</th>
                    <!--<th>Amount</th> -->
                    <th>Total</th>
                    <th></th>
                </tr>
              </thead>
          <tbody>
           <?php
           if(!empty($grn_details)) {
           
        $total_final = 0;
        $i = 0;
         foreach ($grn_details as  $value) {
          $item_id = $value['ITEM_ID'];
          $unit_id = $value['UNIT_ID']; 
          $rate = $value['RATE'];
          $qty = $value['QTY'];
          $amount = $value['AMOUNT'];
          $total_final = $total_final + $amount;
          if($i == 0){
            $j = '';
          }else{
            $j = $i;
          }
          ?>
           <tr class="to_clone1">
          <td>
            <select id="cmbitemname_" name="cmb_item_name[]" class="tf4 txtara_lst input_grid" style="width:230px;" >
              <option value="" >select Item</option>
                <?php foreach ($item as $row) {
                  if($item_id == $row['ID']){
                    echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                  }else{
                    echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
                  }
                  
                }?>
            </select
          </td>
          <td>
              <select id="cmbunit_<?php echo $j;?>" name="cmb_unit[]" class="tf6 txtara_lst input_grid" style="width:150px;">
                            <option value="" >Select Unit Type</option><?php
                              foreach ($unit as $data) {
                                if($unit_id == $data['ID']){
                                  echo "<option value='".$data['ID']."' selected>".$data['NAME']."</option>";  
                                }else{
                                 echo "<option value='".$data['ID']."'>".$data['NAME']."</option>";   
                                }
                                
                              }
                              ?>
              </select>
          </td>          
           <td>
              <input name="txt_unit_price[]" type="number" class="tf7 addeb_input_txtfld input_grid" id="txtunitprice_<?php echo $j;?>" value="<?php echo $rate;?>"   maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>
          <td>
              <input name="txt_qty[]" type="number" class="tf5 addeb_input_txtfld input_grid" id="txtqty_<?php echo $j;?>" value="<?php echo $qty;?>"  maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>
          <!--<td>
              <input name="txt_discount[]" type="number" class="tf9 addeb_input_txtfld input_grid" id="txtdiscount_" value="0"/>
          </td> -->
          <td>
              <input name="txt_total[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="txttotal_<?php echo $j;?>" readonly style=" background-color: #f5f5f5;" value="<?php echo $amount;?>" />
          </td>
          <td align="center">
              
          </td>
        </tr> 
          <?php $i = $i +1;
         }

        }?>  
      </tbody>
    </table>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Narration<span class="textspandata">*</span></label>
                <div class="col-md-12 col-sm-9 col-xs-9">  
                  <textarea name="txt_narration" id="txt_narration" cols="5" rows="5" class="txtarahg" style="height: 42px;width: 90%;"  maxlength="250"><?php echo $narration;?></textarea>
                  </div>
                  </div>
                  </div> 
     <div class="col-md-12 col-sm-12 col-xs-12" style="float: right;">               
               
                <div class="form-group col-md-3 col-sm-6 col-xs-6" style="float: right;">
                <label for="emirate" class="col-md-12 control-label">GRAND TOTAL<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_final_total" type="number" id="txt_final_total" class="input-sm" readonly style="    border: 1px solid #555555 !important;  background-color: #f5f5f5;" value="<?php echo $total_final;?>">
                    </div>
                </div>
            </div>            
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_salesorder();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('inventory/manage_goods_recieved_note');?>'" />&nbsp;&nbsp;<!--<input type="button" class="btn btn-primary" name="btn_delete" id="btn_delete" value="Delete"  onclick=" if (confirm('Do you want delete this GRN?')){window.location.href='<?php echo site_url('/inventory/delete_grn/'.$grn_voucherno.'/'.'/?grn_no='.urlencode($grn_do_bill));?>';}else{event.stopPropagation(); event.preventDefault();}; "/> -->
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

 $("#cmb_account_group").change(function () {
    var account_id =  $(this).val();   
    if(account_id != '') {
         $.ajax({
            url  :"<?php echo site_url(); ?>/inventory/get_sales_grn",
            data : {account_id: account_id},
            success: function(ret_order){
                ret_order = jQuery.parseJSON(ret_order);               
                $('#cmb_po_number').empty();
                var order = $('#cmb_po_number');
                var opt = document.createElement('option');
                opt.text = 'Select purchase order';
                opt.value = '';
                order.append(opt);
                for(var key in ret_order){ 
                    var opt = document.createElement('option');
                    opt.text = ret_order[key]['PREFIX']+ret_order[key]['id'];
                    opt.value = ret_order[key]['VOUCHER_ID'];
                    order.append(opt);
                }
           
          }
        });
    }
   
}); 

$("#cmb_po_number").change(function(){
  var value = $(this).val();
  var acc = $('#cmb_account_group').val();
  var crn = $('#txt_crn_no').val();
  var crndt = $('#txt_crn_date_time').val();
  var lrn = $('#txt_lrn_no').val();
  var gtps = $('#txt_gate_pass_no').val();

    window.location='<?php echo site_url(); ?>inventory/goods_recieved_note/'+value+'?crn='+crn +'&crndt='+crndt+'&lrn='+lrn+'&gtps='+gtps+'&acc='+acc;
  });

     var id = 1;
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
        var item_unit = '';
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
 
   
  var maxDate = new Date('<?php echo $financial_year_to;?>');
  var minDate = new Date('<?php echo $financial_year_from;?>');

 $('#txt_date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
 
   // $('#txt_date').datepicker('setDate', 'now');
  
 $('#txt_crn_date_time').datetimepicker({
    format: 'DD-MM-YYYY HH:mm'
});
  $('#txt_date').datepicker('setStartDate', minDate);
  $('#txt_date').datepicker('setEndDate', maxDate); 
 
    </script>
</body>
</html>