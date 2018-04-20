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
          }
        });
        if(item_id != '') {      
          $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_rate",
            data : {item_id: item_id},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                var rate = ret['rate'];
                var unit_id = ret['unit_id'];
                var stock = ret['stock'];
                if(val1[1] == ''){
                   $('#txtstock_').text(stock);
                  $('#cmbunit_').val(unit_id);                
                }else{
                  var id1 = val1[1];
                  $('#txtstock_'+id1).text(stock);
                  $('#cmbunit_'+id1).val(unit_id);
                }
          }
        })        
      }
    });  
});

   function create_debitnote() {
  var flag = '';
  var item_flag = '';
  var type_flag = '';
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

    $(".tf8").each(function() {
      var type =  $(this).val();   
      if(type == ''){
        type_flag = 'empty';      
      }
    });

  var financial_year = $("#cmb_finance_year").val();
  var bill_no = $("#txt_bill_no").val();
  var debit_date = $("#txt_date").val();
  var inward_total = $("#txt_inward_total").val();
  var outward_total = $("#txt_outward_total").val();

  if(financial_year == ''){
    alert("Please Select Financial Year");
     $("#cmb_finance_year").focus();
    return false;
  }else if(bill_no == ''){
    alert("Please enter bill no.");
     $("#txt_bill_no").focus();
    return false;
  }else if(debit_date == ''){
    alert("Please Select Date");
     $("#txt_date").focus();
    return false;
  }else if(flag == 'empty' || item_flag == 'empty' || type_flag == 'empty' ) {
    alert("Please enter valid entry in grid");
      return false;
  } else{
    $("#frm_debit").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>EDIT STOCK JOURNAL</b></li>   
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>  
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #519CFF;"><?php echo $comp_name ;?></b></li>       
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_debit" id="frm_debit" action="<?php echo site_url('entry/update_stock_journal');?>" method="post" >
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
                       <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $ref_prefix; ?>" readonly  style="width:35%;    border: 1px solid #555555 !important;  background-color: #f5f5f5;"  maxlength = "20">                    
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="<?php echo '0000'.$ref_serial_no; ?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5;">
                        <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Bill No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                   
                       <input type="text" class="form-control input-sm"  name="txt_bill_no"  id="txt_bill_no" maxlength="10" value="<?php echo $bill_no;?>"> 
                    </div>
                </div>
                 <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Bill Date<span class="textspandata">*</span></label>
                    <div class="col-md-11 col-sm-9 col-xs-9">                   
                       <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo $date;?>">
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
                    <th>Current Stock</th>
                    <th>Qty</th>
                    <th>Inward/ Outward</th>
                    <th></th>
                </tr>
              </thead>
          <tbody>
          <?php if(!empty($jv_details)){
            $qty_in_total = 0;
            $qty_out_total = 0;
            $selected_in = '';
            $selected_out = '';
            $i = 0;
            foreach ($jv_details as $value) {

               if($i == 0){
            $j = '';
          }else{
            $j = $i;
          }
              $qty_in = $value['INQTY'];
              $qty_out = $value['OUTQTY'];
               $qty_in_total = $qty_in + $qty_in_total;
            $qty_out_total = $qty_out + $qty_out_total;
            if($qty_in != 0){
              $qty = $qty_in;
               $selected_in = 'selected';
              $selected_out = '';
            }else{
              $qty = $qty_out;
               $selected_in = '';
            $selected_out = 'selected';
            }
            ?>
          <tr class="to_clone1">
          <td>
            <select id="cmbitemname_<?php echo $j;?>" name="cmb_item_name[]" class="tf4 txtara_lst input_grid" style="width:340px;" >
              <option value="" selected>select Item</option>
                <?php foreach ($item as $row) {
                   if($value['ITEM_ID'] == $row['ID'] ){
                    echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                   }else{
                    echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
                   }
                  
                }?>
            </select>
          </td>
          <td >
             <select name="cmb_unit[]" id="cmbunit_<?php echo $j;?>" class="tf6 txtara_lst input_grid" style="width:140px;">
                  <option value="" selected="selected">Select Unit Type</option><?php
                    foreach ($unit as $data) {
                      if($value['UNIT_ID'] == $data['ID'] ){
                         echo "<option value='".$data['ID']."' selected>".$data['NAME']."</option>";  
                      }else{
                         echo "<option value='".$data['ID']."'>".$data['NAME']."</option>";  
                      }
                     
                    }
                ?>
              </select>
              
          </td>
          <td><span id="txtstock_<?php echo $j;?>" class="tf9" ><?php echo $value['STOCK'];?></span></a></td>
          
         
          
          <td>
              <input name="txt_outward[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="txtoutward_<?php echo $j;?>" value="<?php echo $qty;?>"  maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
          </td>
          <td>
              <select id="cmbtype_<?php echo $j;?>" name="cmb_type[]" class="tf8 form-control input-sm" style="font-size: 16px;" >
                        <option value="">select</option>
                        <option value='inward' <?php echo $selected_in;?> >Inward</option>                      
                        <option value='outward' <?php echo $selected_out;?> >Outward</option>
                                   
            </select>
          </td>
          <td align="center" style="width:45px;">
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
        <?php $i = $i + 1;}
          }
          ?>   
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
    <div class="col-md-12 col-sm-12 col-xs-12">               
                <div class="form-group col-md-3">
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">TOTAL INWARD<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_inward_total" type="number" id="txt_inward_total" class="input-sm" readonly style="background-color: #f5f5f5; border: 1px solid #555555 !important; font-size: 16px;" value="<?php echo $qty_in_total;?>" >
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">TOTAL OUTWARD<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_outward_total" type="number" id="txt_outward_total" class="input-sm" readonly style=" background-color: #f5f5f5; border: 1px solid #555555 !important; font-size: 16px;" value="<?php echo $qty_out_total;?>" >
                    </div>
                </div>
            </div>         
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_debitnote();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('entry/manage_stock_journal');?>'" />&nbsp;&nbsp;<!--<input type="button" class="btn btn-primary" name="btn_delete" id="btn_delete" value="Delete"  onclick=" if (confirm('Do you want delete this voucher?')){window.location.href='<?php echo site_url('/entry/delete_stock_journal/'.$ref_serial_no.'/'.'/?pre='.urlencode($ref_prefix));?>';}else{event.stopPropagation(); event.preventDefault();}; "/> -->
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
                  alert('Item already selected');
                  $('#'+id) .val('');
                  return false;
          }
        });
      });

     var id = '<?php echo count($jv_details);?>';
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
        var type_flag = '';
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

        $(".tf8").each(function() {
          var type =  $(this).val();   
          if(type == ''){
            type_flag = 'empty';      
          }
        });
      if(flag == 'empty' || item_flag == 'empty' || type_flag == 'empty' ){
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
        tf9.text('').css('height', 'auto');
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

     $('.tf8').on('change', function() {
      calc();
     });

     $('.tf14').on('change', function() {
      calc();
     });
 
 $('.tf14').keyup(function() {
      calc();
     });
  function calc(){
    var outward_total = 0;
    var inward_total = 0;

     $(".tf14").each(function() {
        var val = $(this);          
        var amnt_id = (val[0].id);
         var val1 = amnt_id.split('_');
         if(val1[1] == ''){
          var amount = $('#txtoutward_') .val();
          var type = $('#cmbtype_') .val();
          if(amount == ''){
            amount = 0;
          }
          if(type =='inward'){            
            inward_total = parseInt(inward_total) + parseInt(amount);
          }else if(type =='outward'){
             outward_total = parseInt(outward_total) + parseInt(amount);            
          }
         }else{
            var id1 = val1[1];
            var amount = $('#txtoutward_'+id1) .val();
            var type = $('#cmbtype_'+id1) .val();
            if(amount == ''){
              amount = 0;
            }
          if(type =='inward'){
            inward_total = parseInt(inward_total) + parseInt(amount);
          }else if(type =='outward'){
             outward_total = parseInt(outward_total) + parseInt(amount);
          }
         }
        });
      $('#txt_inward_total').val(inward_total);
    $('#txt_outward_total').val(outward_total);

  }
 
 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
// $('.input-group.date').datepicker('setDate', 'now');
 $('.input-group.date').datepicker('setStartDate', '<?php echo $financial_year_from;?>');
 $('.input-group.date').datepicker('setEndDate', '<?php echo $financial_year_to;?>'); 
    </script>
</body>
</html>