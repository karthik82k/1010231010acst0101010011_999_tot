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

    $("#cmb_ledger").change(function () {
      
      var leg = $('#cmb_ledger option:selected').text(); 
       var leg_id = $('#cmb_ledger').val(); 
        $(".tf4").each(function() { 
           var item_id =  $(this).val();
           if(item_id != '') {
             var id1 = $(this);
            var string = (id1[0].id);
                
            var val1 = string.split('_');
               
          $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_item_tax",
            data : {item_id: item_id, leg:leg},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
               
                 var cgst = ret['cgst'];
                var igst = ret['igst'];
                var sgst = ret['sgst'];
                
                if(val1[1] == ''){
                 
                  
                                   
                  $('#txtsgstper_').val(sgst);
                  $('#txtigstper_').val(igst);
                  $('#txtcgstper_').val(cgst);
                  var  qty = $('#txtqty_') .val();
                  var unit_price = $('#txtunitprice_').val();
                  var discount = $('#txtdiscount_').val();
                  var amount = parseFloat(qty) * parseFloat(unit_price);
                  var amount = parseFloat(Number(amount).toFixed(2));
                  var discount_per = parseFloat(discount);
                  var amount_after_discount = amount - discount_per;
                  var sgst_per = $('#txtsgstper_').val();
                  var sgst_pers = parseFloat(sgst_per) / 100;
                  var sgst_amount = amount_after_discount * sgst_pers;
                  var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
                  $('#txtsgst_').val(sgst_amount);
                  var igst_per = $('#txtigstper_').val();
                  var igst_pers = parseFloat(igst_per) / 100;
                  var igst_amount = amount_after_discount * igst_pers;
                  var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
                  $('#txtigst_').val(igst_amount);
                  var cgst_per = $('#txtcgstper_').val(); 
                  var cgst_pers = parseFloat(cgst_per) / 100;       
                  var cgst_amount = amount_after_discount * cgst_pers;
                  $('#txtcgst_').val(cgst_amount);

                  var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
                  var total = total_amount - discount_per; 
                  var total = parseFloat(Number(total).toFixed(2));
                  $('#txtamount_') .val(amount);
                  $('#txttotal_') .val(total);                
                }else{
                  var id1 = val1[1];
                 
                  $('#txtsgstper_'+id1).val(sgst);
                  $('#txtigstper_'+id1).val(igst);
                  $('#txtcgstper_'+id1).val(cgst); 
                  var  qty = $('#txtqty_'+id1) .val();
                  var unit_price = $('#txtunitprice_'+id1).val();
                  var discount = $('#txtdiscount_'+id1).val();
                  var amount = parseFloat(qty) * parseFloat(unit_price);
                  var amount = parseFloat(Number(amount).toFixed(2));
                  var discount_per = parseFloat(discount);
                  var amount_after_discount = amount - discount_per;
                  var sgst_per = $('#txtsgstper_'+id1).val();
                  var sgst_pers = parseFloat(sgst_per) / 100;
                  var sgst_amount = amount_after_discount * sgst_pers;
                  var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
                  $('#txtsgst_'+id1).val(sgst_amount);
                  var igst_per = $('#txtigstper_'+id1).val();
                  var igst_pers = parseFloat(igst_per) / 100;
                  var igst_amount = amount_after_discount * igst_pers;
                  var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
                  $('#txtigst_'+id1).val(igst_amount);
                  var cgst_per = $('#txtcgstper_'+id1).val(); 
                  var cgst_pers = parseFloat(cgst_per) / 100;       
                  var cgst_amount = amount_after_discount * cgst_pers;
                  $('#txtcgst_'+id1).val(cgst_amount);

                  var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
                  var total = total_amount - discount_per;
                  var total = parseFloat(Number(total).toFixed(2)); 
                  $('#txtamount_'+id1) .val(amount);
                  $('#txttotal_'+id1) .val(total);   
                }
                calc();
          }
        })        
      
           } 
          
        });
      
 });
   // Items
    $('.tf4').on('change', function() {
      var leg = $('#cmb_ledger option:selected').text(); 
      var leg_id = $('#cmb_ledger').val(); 
     
      var id = this.id;
      var string = this.id;      
      var val1 = string.split('_');
       var item_id = $('#'+id) .val();
        if(leg_id == '') {
          alert('Please Purchase Type');
          $('#'+id).val('');
          return false;
        }
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
            url  :"<?php echo site_url(); ?>/entry/get_item_tax",
            data : {item_id: item_id, leg:leg},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                var rate = ret['rate'];
                 var cgst = ret['cgst'];
                var igst = ret['igst'];
                var sgst = ret['sgst'];
                var unit_id = ret['unit_id']; 
                var tax_id = ret['tax_id'];
                var stock = ret['stock'];
                if(val1[1] == ''){
                  $('#txtunitprice_').val(rate);
                  $('#txtsgstper_').val(sgst);
                  $('#txtigstper_').val(igst);
                  $('#txtcgstper_').val(cgst);
                  $('#cmbunit_').val(unit_id); 
                   $('#cmbtax_').val(tax_id);
                   $('#txtstock_').attr('title','Current Stock :'+stock);  
                    $('#txtstock_').attr('alt','Current Stock :'+stock);
                  var  qty = $('#txtqty_') .val();
                  var unit_price = $('#txtunitprice_').val();
                  var discount = $('#txtdiscount_').val();
                  var amount = parseFloat(qty) * parseFloat(unit_price);
                  var amount = parseFloat(Number(amount).toFixed(2));
                  var discount_per = parseFloat(discount);
                  var amount_after_discount = amount - discount_per;
                  var sgst_per = $('#txtsgstper_').val();
                  var sgst_pers = parseFloat(sgst_per) / 100;
                  var sgst_amount = amount_after_discount * sgst_pers;
                  var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
                  $('#txtsgst_').val(sgst_amount);
                  var igst_per = $('#txtigstper_').val();
                  var igst_pers = parseFloat(igst_per) / 100;
                  var igst_amount = amount_after_discount * igst_pers;
                  var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
                  $('#txtigst_').val(igst_amount);
                  var cgst_per = $('#txtcgstper_').val(); 
                  var cgst_pers = parseFloat(cgst_per) / 100;       
                  var cgst_amount = amount_after_discount * cgst_pers;
                  $('#txtcgst_').val(cgst_amount);

                  var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
                  var total = total_amount - discount_per;
                  var total = parseFloat(Number(total).toFixed(2)); 
                  $('#txtamount_') .val(amount);
                  $('#txttotal_') .val(total);                
                }else{
                  var id1 = val1[1];
                  $('#txtunitprice_'+id1).val(rate);
                  $('#txtsgstper_'+id1).val(sgst);
                  $('#txtigstper_'+id1).val(igst);
                  $('#txtcgstper_'+id1).val(cgst); 
                  $('#cmbunit_'+id1).val(unit_id);
                   $('#cmbtax_'+id1).val(tax_id);
                  $('#txtstock_'+id1).attr('title','Current Stock :'+stock);
                  $('#txtstock_'+id1).attr('alt','Current Stock :'+stock);
                  var  qty = $('#txtqty_'+id1) .val();
                  var unit_price = $('#txtunitprice_'+id1).val();
                  var discount = $('#txtdiscount_'+id1).val();
                  var amount = parseFloat(qty) * parseFloat(unit_price);
                  var amount = parseFloat(Number(amount).toFixed(2));
                  var discount_per = parseFloat(discount);
                  var amount_after_discount = amount - discount_per;
                  var sgst_per = $('#txtsgstper_'+id1).val();
                  var sgst_pers = parseFloat(sgst_per) / 100;
                  var sgst_amount = amount_after_discount * sgst_pers;
                  var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
                  $('#txtsgst_'+id1).val(sgst_amount);
                  var igst_per = $('#txtigstper_'+id1).val();
                  var igst_pers = parseFloat(igst_per) / 100;
                  var igst_amount = amount_after_discount * igst_pers;
                  var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
                  $('#txtigst_'+id1).val(igst_amount);
                  var cgst_per = $('#txtcgstper_'+id1).val(); 
                  var cgst_pers = parseFloat(cgst_per) / 100;       
                  var cgst_amount = amount_after_discount * cgst_pers;
                  $('#txtcgst_'+id1).val(cgst_amount);

                  var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
                  var total = total_amount - discount_per; 
                  var total = parseFloat(Number(total).toFixed(2));
                  $('#txtamount_'+id1) .val(amount);
                  $('#txttotal_'+id1) .val(total);   
                }
                calc();
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
      
      var unit_price = $('#txtunitprice_').val();
      var discount = $('#txtdiscount_').val();
      var amount = parseFloat(qty) * parseFloat(unit_price);
      var amount = parseFloat(Number(amount).toFixed(2));
      var discount_per = parseFloat(discount);
      //var discount_amount = amount * discount_per;
      var amount_after_discount = amount - discount_per;
      var sgst_per = $('#txtsgstper_').val();
      var sgst_pers = parseFloat(sgst_per) / 100;
      var sgst_amount = amount_after_discount * sgst_pers;
      var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
      $('#txtsgst_').val(sgst_amount);
      var igst_per = $('#txtigstper_').val();
      var igst_pers = parseFloat(igst_per) / 100;
      var igst_amount = amount_after_discount * igst_pers;
      var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
      $('#txtigst_').val(igst_amount);
      var cgst_per = $('#txtcgstper_').val(); 
      var cgst_pers = parseFloat(cgst_per) / 100;       
      var cgst_amount = amount_after_discount * cgst_pers;
      $('#txtcgst_').val(cgst_amount);

      var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
      var total = total_amount - discount_per; 
      var total = parseFloat(Number(total).toFixed(2));
      $('#txtamount_') .val(amount);
      $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var unit_price = $('#txtunitprice_'+id1).val();
      var discount = $('#txtdiscount_'+id1).val();
      var amount = parseFloat(qty) * parseFloat(unit_price);
      var amount = parseFloat(Number(amount).toFixed(2));
      var discount_per = parseFloat(discount);
      var amount_after_discount = amount - discount_per;
      //var discount_amount = amount * discount_per;
      var sgst_per = $('#txtsgstper_'+id1).val();
      var sgst_pers = parseFloat(sgst_per) / 100;
      var sgst_amount = amount_after_discount * sgst_pers;
      var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
      $('#txtsgst_'+id1).val(sgst_amount);
      var igst_per = $('#txtigstper_'+id1).val();
      var igst_pers = parseFloat(igst_per) / 100;
      var igst_amount = amount_after_discount * igst_pers;
      var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
      $('#txtigst_'+id1).val(igst_amount);
      var cgst_per = $('#txtcgstper_'+id1).val(); 
      var cgst_pers = parseFloat(cgst_per) / 100;
      var cgst_amount = amount_after_discount * cgst_pers;
      $('#txtcgst_'+id1).val(cgst_amount);
      var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
      var total = total_amount - discount_per;  
      var total = parseFloat(Number(total).toFixed(2));       
        $('#txtamount_'+id1).val(amount);
        $('#txttotal_'+id1).val(total);
      }

    calc();
  });
  // unity Price
   $('.tf7').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var unit_price = $('#'+id).val();
     if(unit_price == ''){
        unit_price = 0;
        $('#'+id) .val(0);
      }

      if(val1[1] == ''){
     
      var  qty = $('#txtqty_') .val();
      var discount = $('#txtdiscount_').val();
      var amount = parseFloat(qty) * parseFloat(unit_price);
      var amount = parseFloat(Number(amount).toFixed(2));
      var discount_per = parseFloat(discount);
      var amount_after_discount = amount - discount_per;
        var sgst_per = $('#txtsgstper_').val();
      var sgst_pers = parseFloat(sgst_per) / 100;
      var sgst_amount = amount_after_discount * sgst_pers;
      var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
       $('#txtsgst_').val(sgst_amount);
      var igst_per = $('#txtigstper_').val();
      var igst_pers = parseFloat(igst_per) / 100;
      var igst_amount = amount_after_discount * igst_pers;
      var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
       $('#txtigst_').val(igst_amount);
      var cgst_per = $('#txtcgstper_').val(); 
      var cgst_pers = parseFloat(cgst_per) / 100;       
      var cgst_amount = amount_after_discount * cgst_pers;
      $('#txtcgst_').val(cgst_amount);
      var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
      var total = total_amount - discount_per; 
      var total = parseFloat(Number(total).toFixed(2));
        $('#txtamount_').val(amount);
     
        $('#txttotal_').val(total);

      }else {
        var id1 = val1[1];
        // var id = this.id;
      
      var  qty = $('#txtqty_'+id1).val();
      var discount = $('#txtdiscount_'+id1) .val();
      var amount = parseFloat(qty) * parseFloat(unit_price);
      var amount = parseFloat(Number(amount).toFixed(2));
      var discount_per = parseFloat(discount);
      var amount_after_discount = amount - discount_per;
     // var discount_amount = amount * discount_per;
      var sgst_per = $('#txtsgstper_'+id1).val();
      var sgst_pers = parseFloat(sgst_per) / 100;
      var sgst_amount = amount_after_discount * sgst_pers;
      var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
      $('#txtsgst_'+id1).val(sgst_amount);
      var igst_per = $('#txtigstper_'+id1).val();
      var igst_pers = parseFloat(igst_per) / 100;
      var igst_amount = amount_after_discount * igst_pers;
      var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
      $('#txtigst_'+id1).val(igst_amount);
      var cgst_per = $('#txtcgstper_'+id1).val(); 
      var cgst_pers = parseFloat(cgst_per) / 100;
      var cgst_amount = amount_after_discount * cgst_pers;
      $('#txtcgst_'+id1).val(cgst_amount);
      var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
      var total = total_amount - discount_per; 
      var total = parseFloat(Number(total).toFixed(2));
       $('#txtamount_'+id1).val(amount);
        $('#txttotal_'+id1).val(total);
      }

    calc(); 
  }); 

  // discount
  $('.tf8').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var discount = $('#'+id) .val();
      if(discount == ''){
        discount = 0;
        $('#'+id) .val(0);
      }
      if(val1[1] == ''){      
      var  qty = $('#txtqty_') .val();
      var unit_price = $('#txtunitprice_') .val();
      var amount = parseFloat(qty) * parseFloat(unit_price);
      var amount = parseFloat(Number(amount).toFixed(2));
      var discount_per = parseFloat(discount);
      var amount_after_discount = amount - discount_per;
     // var discount_amount = amount * discount_per;
      var sgst_per = $('#txtsgstper_').val();
      var sgst_pers = parseFloat(sgst_per) / 100;
      var sgst_amount = amount_after_discount * sgst_pers;
      var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
       $('#txtsgst_').val(sgst_amount);
      var igst_per = $('#txtigstper_').val();
      var igst_pers = parseFloat(igst_per) / 100;
      var igst_amount = amount_after_discount * igst_pers;
      var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
       $('#txtigst_') .val(igst_amount);
      var cgst_per = $('#txtcgstper_').val(); 
      var cgst_pers = parseFloat(cgst_per) / 100;       
      var cgst_amount = amount_after_discount * cgst_pers;
      $('#txtcgst_').val(cgst_amount);
      var total_amount = amount + sgst_amount + igst_amount + cgst_amount;
      var total = total_amount - discount_per;
      var total = parseFloat(Number(total).toFixed(2));  
     $('#txtamount_').val(amount);
        $('#txttotal_').val(total);

      } else {
      var id1 = val1[1];      
      var  qty = $('#txtqty_'+id1) .val();
      var unit_price = $('#txtunitprice_'+id1) .val();
      var amount = parseFloat(qty) * parseFloat(unit_price);
      var amount = parseFloat(Number(amount).toFixed(2));
      var discount_per = parseFloat(discount);
      var amount_after_discount = amount - discount_per;
      //var discount_amount = amount * discount_per;
      var sgst_per = $('#txtsgstper_'+id1).val();
      var sgst_pers = parseFloat(sgst_per) / 100;
      var sgst_amount = amount_after_discount * sgst_pers;
      var sgst_amount = parseFloat(Number(sgst_amount).toFixed(2));
      $('#txtsgst_'+id1).val(sgst_amount);
      var igst_per = $('#txtigstper_'+id1).val();
      var igst_pers = parseFloat(igst_per) / 100;
      var igst_amount = amount_after_discount * igst_pers;
      var igst_amount = parseFloat(Number(igst_amount).toFixed(2));
      $('#txtigst_'+id1).val(igst_amount);
      var cgst_per = $('#txtcgstper_'+id1).val(); 
      var cgst_pers = parseFloat(cgst_per) / 100;
      var cgst_amount = amount_after_discount * cgst_pers;
      $('#txtcgst_'+id1).val(cgst_amount);
      var total_amount =  amount + sgst_amount + igst_amount + cgst_amount;
      var total = total_amount - discount_per;
      var total = parseFloat(Number(total).toFixed(2));
       $('#txtamount_'+id1).val(amount);
        $('#txttotal_'+id1).val(total);
      }
      calc();
  });

 
});

function create_salesvoucher() {
  var flag = '';
   $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseInt(m);   
   if(l <= 0){
      flag = 'empty';      
   }
  });
 
  var debit_date = $("#txt_date").val();
  var account_name = $("#cmb_account_group").val();
  var ledger = $("#cmb_ledger").val();
  var voucher = $("#cmb_voucher_type").val();
  if(voucher == ''){
    alert("Please Select Voucher Type");
     $("#cmb_voucher_type").focus();
    return false;

  } else if(debit_date == ''){
    alert("Please Select Date");
     $("#txt_date").focus();
    return false;
  }else if(account_name == ''){
    alert("Please Select Account");
     $("#cmb_account_group").focus();
    return false;
  }  else if(ledger == ''){
    alert("Please Purchase Type");
     $("#cmb_ledger").focus();
    return false;
  }else if(flag == 'empty') {
    alert("Please enter valid entry in grid");
      return false;
  } else{
    $("#frm_sales_voucher").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>EDIT PURCHASE VOUCHER</b></li>
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:1px 1px 1px 1px;">
            <form name="frm_sales_voucher" id="frm_sales_voucher" action="<?php echo site_url('entry/update_purchasevoucher');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">            
                <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Purchase Voucher No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $prefix; ?>" readonly  style="width:35%;  font-size: 15px;  border: 1px solid #555555 !important;  background-color: #f5f5f5;">                    
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="<?php echo '0000'.$sn; ?>" readonly style=" font-size: 15px;  border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5;">
                        <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
                    </div>
                </div>
                
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Bill No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
<<<<<<< HEAD
                       <input type="text" name="txt_bill_no" id="txt_bill_no" class="input-sm" style="width:92%; border: 1px solid #555555 !important; font-size: 18px;" maxlength = "25" value="<?php echo $bill_no;?>"readonly >
=======
                       <input type="number" name="txt_bill_no" id="txt_bill_no" class="input-sm" style="width:92%; border: 1px solid #555555 !important; font-size: 18px;" maxlength = "25" value="<?php echo $bill_no;?>"readonly >
>>>>>>> master
                    </div>
                   
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Date<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo $date;?>" style="font-size: 14px;">
                       
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
               <!-- <input type="button" class="btn btn-primary" name="reset" id="reset" value="Add Account" onclick="window.location.href='<?php echo site_url('definition/add_accounts/sales');?>'" style="height: 20px; padding-top:0px; padding-right:5px; padding-left:5px;"/>-->
               <div id="feedback"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#account-modal" style="height: 20px; padding-top:0px; padding-right:5px; padding-left:5px;">Add Account</button></div>
               <div id="feedback">
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#item-modal" style="height: 20px; padding-top:0px; padding-right:5px; padding-left:5px;">Add Item</button></div>
                </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Account Name<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:90%;    border: 1px solid #555555 !important; font-size: 16px;">
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
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Current Balance<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <input type="text" class="form-control input-sm" id="txt_bance"  style=" font-size: 16px;" name="txt_bance" class="form-control" readonly value="<?php echo $ac_cur_val;?>">
                    </div>
                   
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">PO Number <span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9"> 
                     <?php
                      $po_pre_id =$purchase_id_prefix."-".$purchase_id;
                    ?>
                       <input name="cmb_so_number" id="cmb_so_number" type="hidden" value="<?php echo $po_pre_id;?>">                  
                       <select name="cmb_so_number1" id="cmb_so_number1" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;" disabled="true">
                        <option value="" selected="selected " >Select PO Number</option>
                        <?php
                          foreach ($purchase_order as $key) {
                            $voucher_id = $key['PREFIX']."-".$key['VOUCHER_ID'];
                             if($po_pre_id == $voucher_id){
                              echo "<option value='".$key['PREFIX']."-".$key['VOUCHER_ID']."' selected>".$key['PREFIX'].$key['id']."</option>";
                             }else{
                              echo "<option value='".$key['VOUCHER_ID']."'>".$key['PREFIX'].$key['id']."</option>";
                            }                            
                          }
                        ?>
                      </select> 
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Credit Period<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                      <input type="number" name="txt_validity" id="txt_validity" class="input-sm" style="width:96%; border: 1px solid #555555 !important;" value="<?php echo $credit_period;?>">
                    </div>
                   
                </div>
                </div>
                 <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Purchase Type<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-8 col-xs-9" style="align:left;">
                        <select id="cmb_ledger" name="cmb_ledger" class="form-control input-sm" style="font-size: 16px;" >
                        <option selected="selected" value="">select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            if($ledger_id == $row['ID']){
                              echo "<option value='".$row['ID']."' selected>".$row['ACCOUNTDESC']."</option>";
                            }else{
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>";  
                            }
                             
                          }?>            
                      </select>
                    </div>
                   
                </div>
              <div class="form-group col-md-6 col-sm-6 col-xs-6">
              <!--  <label for="emirate" class="col-md-12 control-label ">Shipping Address<span class="textspandata"></span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_shipping" id="cmb_shipping" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" >Select Shipping address</option>
                        <?php 
                       /* foreach ($party_address as $value) {
                          if($value['PartyAddress_ID'] == $PartyAddress_ID ){
                           echo "<option value='".$value['PartyAddress_ID']."' selected>".$value['ADDRESS1']."</option>";
                          }else{
                            echo "<option value='".$value['PartyAddress_ID']."' >".$value['ADDRESS1']."</option>";
                          }
                        }*/
                        ?>
                      </select>
                    </div> -->
                   
                </div>
                </div>
                
            </div>         
            
            <div style="height: 200px; width:100%; overflow: auto; background-color: #ffffff;" >
              <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 13px; background-color: #003166; color: #ffffff;">        
                    <th>Item Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stock</th>                    
                    <th>Unit</th> 
                    <th>Tax Rate</th>
                    <th>Qty</th>                 
                    <!--<th>Stock</th> -->
                   
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Discount</th>
                    <th style="text-align: center;">SGST</th>
                    <th style="text-align: center;">CGST</th>
                    <th style="text-align: center;">IGST</th>
                    <th style="text-align: center;">Total Amount</th>
                    <th></th>
                </tr>
              </thead>
          <tbody>
          <?php
           if(!empty($purchase_order_detail)) {
             
          $sgst_amount_final = 0;
          $cgst_amount_final = 0;
          $igst_amount_final = 0;
          $total_final = 0;
          $i = 0;
          foreach ($purchase_order_detail as  $value) {
          $item_id = $value['ITEM_ID'];
          $unit_id = $value['UNIT_ID']; 
          $tax_id = $value['CompanyTax_ID'];
          $rate = $value['RATE'];
          $qty = $value['QUANTITY'];
          $discount = round($value['DISCOUNT'],2);
          $amount = $value['AMOUNT'];
          $sgst_per = round($value['SGSTPERCENT'],2);
          $cgst_per = round($value['CGSTPERCENT'],2);          
          $igst_per = round($value['IGSTPERCENT'],2);
          $sgst_amount = round($value['SGSTAMOUNT'],2);
          $cgst_amount = round($value['CGSTAMOUNT'],2);
          $igst_amount = round($value['IGSTAMOUNT'],2);
          $total = $amount + $sgst_amount + $cgst_amount + $igst_amount - $discount;
          $sgst_amount_final = $sgst_amount + $sgst_amount_final;
          $cgst_amount_final = $cgst_amount + $cgst_amount_final;
          $igst_amount_final = $igst_amount + $igst_amount_final;
          $total_final = $total + $total_final;
          if($i == 0){
            $j = '';
          }else{
            $j = $i;
          }
          ?>
        <tr class="to_clone1">
          <td style="width:330px;" >
            <select id="cmbitemname_<?php echo $j;?>" name="cmb_item_name[]" class="tf4 txtara_lst input_grid" style="width:310px;" >
              <option value="" selected>select Item</option>
                <?php foreach ($item as $row) {
                  if($item_id == $row['ID']) {
                    echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                  }else{
                     echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";
                  }
                }?>
            </select><a href="#" title="<?php echo 'Current Stock: '.$value['STOCK'];?>" alt="<?php echo 'Current Stock: '.$value['STOCK'];?>" id="txtstock_"<?php echo $j;?>" class="tf9" ><i class="fa fa-th"  aria-hidden="true"></i></a>
          </td>
          <td >
             <select id="cmbunit_<?php echo $j;?>" name="cmb_unit[]" class="tf6 txtara_lst input_grid" style="width:50px;  pointer-events: none;">
                  <option value="" selected="selected">Select Unit Type</option><?php
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
          <select name="cmb_tax[]" id="cmbtax_" class="tf3 txtara_lst input_grid" style="width:75px; font-size: 12px;  pointer-events: none;" >
          <option value="" selected="selected">Tax</option><?php
                    foreach ($tax as $data) {
                      if($tax_id == $data['ID']) {
                        echo "<option value='".$data['ID']."' selected>".$data['TAXTYPE']."</option>";
                      }else{
                        echo "<option value='".$data['ID']."'>".$data['TAXTYPE']."</option>";
                      }
                        
                    }
                ?>
          </select>
          </td>  
           <td>
              <input name="txt_qty[]" type="number" class="tf5 addeb_input_txtfld input_grid" id="txtqty_<?php echo $j;?>" value="<?php echo $qty;?>" style="width:72px;"/>
          </td>
          <td>
              <input name="txt_unit_price[]" type="number" class="tf7 addeb_input_txtfld input_grid" id="txtunitprice_<?php echo $j;?>" value="<?php echo $rate;?>" style="width:72px;"/>
          </td>
          <!-- <td>
              <input name="txt_stock[]" type="number" class="tf9 addeb_input_txtfld input_grid" id="txtstock_<?php echo $j;?>" value="0"  style="width:40px;" readonly />
          </td>  -->
          <td>
              <input name="txt_amount[]" type="text" class="tf10 addeb_input_txtfld input_grid" id="txtamount_<?php echo $j;?>" value="<?php echo $amount;?>" readonly style="width:90px;"/>
          </td>
          <td>
              <input name="txt_discount[]" type="number" class="tf8 addeb_input_txtfld input_grid" id="txtdiscount_<?php echo $j;?>"  style="width:50px;" value="<?php echo $discount;?>"/>
          </td>
          <td>
              <input name="txt_sgst[]" type="text" class="tf11 addeb_input_txtfld input_grid" id="txtsgst_<?php echo $j;?>" value="<?php echo $sgst_amount;?>" readonly style="width:75px;"/>
              <input name="txt_sgst_per[]" type="hidden" class="tf15 addeb_input_txtfld input_grid" id="txtsgstper_<?php echo $j;?>" value="<?php echo $sgst_per;?>" />
          </td>
          <td>
              <input name="txt_cgst[]" type="number" class="tf12 addeb_input_txtfld input_grid" id="txtcgst_<?php echo $j;?>" value="<?php echo $cgst_amount;?>" readonly style="width:75px;"/>
              <input name="txt_cgst_per[]" type="hidden" class="tf16 addeb_input_txtfld input_grid" id="txtcgstper_<?php echo $j;?>" value="<?php echo $cgst_per;?>"/>
          </td>
          <td>
              <input name="txt_igst[]" type="number" class="tf13 addeb_input_txtfld input_grid" id="txtigst_<?php echo $j;?>" value="<?php echo $igst_amount;?>" readonly style="width:75px;"/>
              <input name="txt_igst_per[]" type="hidden" class="tf17 addeb_input_txtfld input_grid" id="txtigstper_<?php echo $j;?>" value="<?php echo $igst_per;?>" />
          </td>
          <td>
              <input name="txt_total[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="txttotal_<?php echo $j;?>" value="<?php echo $total;?>" readonly style="width:90px;"/>
          </td>
          <td align="center">
              <img src="https://test.newui.myddf.info/images/add.png" name="multiple" id="multiple" class="f_moreFilter" alt="Add Multiple" title="Add Multiple" /></span> 
              <?php  if($i == 0){
                ?>
                 <span class="f_deleteimg"></span>
            <?php
          }else{
            ?>
             <span class="f_deleteimg"><img src="https://test.newui.myddf.info/images/close.png" class="f_deleteFilter1" style="cursor:  pointer;" alt="Delete" title="Delete"></span>
            <?php
          }?>
             
          </td>
        </tr>
        <?php 
        $i = $i +1;
        }} ?>     
      </tbody>
    </table>
  </div> 
      <div class="col-md-12 col-sm-12 col-xs-12">   
                 
                <div class="form-group col-md-1">
                <label for="emirate" class="col-md-12 control-label">DISCOUNT <span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_total_discount" type="number" id="txt_total_discount" class="input-sm form-control" readonly style="   background-color: #f5f5f5;  border: 1px solid #555555 !important; font-size: 16px;" value="0">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">SGST <span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_total_sgst" type="number" id="txt_total_sgst" class="input-sm form-control" readonly style="   background-color: #f5f5f5;  border: 1px solid #555555 !important; font-size: 16px;" value="<?php echo $sgst_amount_final;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">CGST <span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_total_cgst" type="number" id="txt_total_cgst" class="input-sm form-control" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important; font-size: 16px;" value="<?php echo $cgst_amount_final;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">IGST <span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_total_igst" type="number" id="txt_total_igst" class="input-sm form-control" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; font-size: 16px;" value="<?php echo $igst_amount_final;?>">
                    </div>
                </div>
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">GROSS TOTAL<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_gross_total" type="number" id="txt_gross_total" class="input-sm form-control" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important;  font-size: 16px;" value="<?php echo $total_final;?>">
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">R. OFF<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_round_off" type="text" id="txt_round_off" class="input-sm form-control" readonly style="   background-color: #f5f5f5;  border: 1px solid #555555 !important;  font-size: 16px;" value="<?php echo $round_off;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">NET TOTAL<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                        <input name="txt_final_total" type="number" id="txt_final_total" class="input-sm form-control" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; font-size: 16px;" value="<?php echo $total_final+$round_off;?>">
                    </div>
                </div>
            </div>              
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_salesvoucher();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('entry/manage_purchase_voucher');?>'" />&nbsp;&nbsp;<!--<input type="button" class="btn btn-primary" name="btn_delete" id="btn_delete" value="Delete"  onclick=" if (confirm('Do you want delete this voucher?')){window.location.href='<?php echo site_url('/entry/delete_purchase_voucher/'.$sn.'/'.'/?pre='.urlencode($prefix));?>';}else{event.stopPropagation(); event.preventDefault();}; "/> -->
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
<div id="account-modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3>Add Account</h3>
</div>
<div class="modal-body">
<form class="frm_account" name="frm_account">
<?php $this->load->view('add_accounts_pop');?>
<input type="hidden" name="type" id="type" value="purchase">
</form>
</div>
<div class="modal-footer">
<button class="btn btn-success" id="pop_submit">Submit</button>
<a href="#" class="btn" data-dismiss="modal">Close</a>
</div>
</div>
</div>
</div>  

<div id="item-modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3>Add Item</h3>
</div>
<div class="modal-body">
<form class="frm_item" name="frm_item">
<?php $this->load->view('add_items_pop');?>
</form>
</div>
<div class="modal-footer">
<button class="btn btn-success" id="pop_submit_item">Submit</button>
<a href="#" class="btn" data-dismiss="modal">Close</a>
</div>
</div>
</div>
</div> 
<script type="text/javascript">
$(document).ready(function(){
$("#pop_submit").click(function(){
var account_name = $('#txt_account_name').val();
if(account_name == ''){
 alert("Please enter account name"); 
 return false;
}else{
$.ajax({
type: "POST",
url: "<?php echo site_url('definition/create_accounts_pop');?>",
data: $('form.frm_account').serialize(),
success: function(message){
    message = jQuery.parseJSON(message);
    var acc_g = $('#cmb_account_group');
    for(var key in message){ 
        var opt = document.createElement('option');
        opt.text = message[key]['ACCOUNTDESC'];
        opt.value = message[key]['ID'];
        acc_g.append(opt);
    }
    

$('#txt_tin_num').val('');
$('#txt_account_name').val('');
$('#txt_opening_bal').val(0);
$('#cmb_billwise').val('');

alert("Account Name Added");


},
error: function(){
alert("Not Added");
}
});
}
});

$("#pop_submit_item").click(function(){
var item_name = $('#txt_item_name').val();
var group = $('#cmb_group').val();
var unit = $('#cmb_unit').val();
if(item_name == ''){
 alert("Please enter Item name"); 
 return false;
}else if(group == ''){
 alert("Please select group"); 
 return false;
}else if(unit == ''){
 alert("Please select unit"); 
 return false;
}else{
$.ajax({
type: "POST",
url: "<?php echo site_url('inventory/create_item_pop');?>",
data: $('form.frm_item').serialize(),
success: function(msg){
    msg = jQuery.parseJSON(msg);
    var item_g = $('.tf4');
    for(var key in msg){ 
        var opt = document.createElement('option');
        opt.text = msg[key]['NAME'];
        opt.value = msg[key]['ID'];
        item_g.append(opt);
    }
    

$('#txt_item_name').val('');
$('#cmb_group').val('');
$('#txt_item_code').val('');
$('#txt_hsn_code').val('');
$('#cmb_unit').val('');
$('#txt_opening_stock').val('');
$('#txt_opening_value').val('');
$('#txt_selling_rate').val('');
alert("Item Name Added");


},
error: function(){
alert("Not Added");
}
});
}
});

});
 $("#cmb_so_number").change(function(){
  var value = $(this).val();
    window.location='<?php echo site_url(); ?>entry/purchase_voucher/' + value;
  });
     var id = '<?php echo count($purchase_order_detail);?>';
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
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
      if(flag == 'empty' || item_flag == 'empty' ){
        alert("Please enter proper value in Grid.");
        return false;
      }
        var new_div1 = $(this).closest('table').find('.to_clone1').eq(0).clone(true).appendTo("#textdata");
        var tf3 = new_div1.find('.tf3').eq(0);
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
         var tf15 = new_div1.find('.tf15').eq(0);
        var tf16 = new_div1.find('.tf16').eq(0);
        var tf17 = new_div1.find('.tf17').eq(0);

        tf4.val('').css('height', 'auto');
        tf5.val(0).css('height', 'auto');
        tf6.val('').css('height', 'auto');
        tf7.val(0).css('height', 'auto');
        tf8.val(0).css('height', 'auto');
        tf9.val(0).css('height', 'auto');
        tf10.val(0).css('height', 'auto');
        tf11.val(0).css('height', 'auto');
        tf12.val(0).css('height', 'auto');
        tf13.val(0).css('height', 'auto');
        tf14.val(0).css('height', 'auto');
         tf15.val(0).css('height', 'auto');
        tf16.val(0).css('height', 'auto');
        tf17.val(0).css('height', 'auto');
         tf3.val('').css('height', 'auto');

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

         fdp19_ = tf15.attr('id');
        tf15.attr('id', fdp19_ + id);

        fdp20_ = tf16.attr('id');
        tf16.attr('id', fdp20_ + id);

        fdp21_ = tf17.attr('id');
        tf17.attr('id', fdp21_ + id);

        fdp22_ = tf3.attr('id');
        tf3.attr('id', fdp22_ + id);

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
        }) 
    }
});
/*
$("#cmb_ledger").change(function () {
      var tax = $("#cmb_tax").val();
      var leg = $('#cmb_ledger option:selected').text(); 
       var leg_id = $('#cmb_ledger').val(); 
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
        })        
    }else{
        $('#txt_sgst').val(0);
        $('#txt_igst').val(0);
        $('#txt_cgst').val(0); 
        calc();
  }
 });  */
  function calc(){
  var final_totls = 0;
  var sgst_totls = 0;
  var cgst_totls = 0;
  var igst_totls = 0;
  var discount_totls = 0;

  $(".tf14").each(function() {
   var m =  $(this).val();
   final_totls = parseFloat(final_totls) + parseFloat(m);
  });
  
  $(".tf12").each(function() {
   var c =  $(this).val();
   cgst_totls = parseFloat(cgst_totls) + parseFloat(c);
  });

  $(".tf11").each(function() {
   var s =  $(this).val();
   sgst_totls = parseFloat(sgst_totls) + parseFloat(s);
  });

  $(".tf13").each(function() {
   var i =  $(this).val();
   igst_totls = parseFloat(igst_totls) + parseFloat(i);
  });

  $(".tf8").each(function() {
   var d =  $(this).val();
   discount_totls = parseFloat(discount_totls) + parseFloat(d);
  });
 
  var final_totl = final_totls.toFixed();
  var sgst_total = sgst_totls.toFixed(2);
  var igst_total = igst_totls.toFixed(2);
  var cgst_total = cgst_totls.toFixed(2);  
  var discount_totl = discount_totls.toFixed(2);
  var gross_totl = final_totls.toFixed(2);
  //var discount_totl = roundTo(discount_totls,2);
  var roun_offs = parseFloat(final_totl) - parseFloat(gross_totl);
  var roun_off = roun_offs.toFixed(2);
  

  $('#txt_total_sgst').val(sgst_total);
  $('#txt_total_igst').val(igst_total);
  $('#txt_total_cgst').val(cgst_total);
  $('#txt_final_total').val(final_totl);
   $('#txt_gross_total').val(gross_totl);  
  $('#txt_total_discount').val(discount_totl);
  $('#txt_round_off').val(roun_off);   
  }

 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
// $('.input-group.date').datepicker('setDate', 'now');
 $('.input-group.date').datepicker('setStartDate', '<?php echo $financial_year_from;?>');
 $('.input-group.date').datepicker('setEndDate', '<?php echo $financial_year_to;?>'); 
    </script>
</body>
</html>