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
  // amount
    $('.tf14').on('change', function() {
    
  });
});

function create_payment() {
  var flag = '';
  var item_flag =  '';
  var type_flag =  '';
  
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
   $(".tf13").each(function() {
          var type =  $(this).val();   
          if(type == ''){
            type_flag = 'empty';      
          }
        });
    
    var voucher_type = $("#cmb_voucher_type").val();
    var financial_year = $("#cmb_finance_year").val();
    var bill_no = $("#txt_bill_no").val();
    var je_date = $("#txt_date").val();
    var credit = $("#txt_credit_total").val();
    var debit = $("#txt_debit_total").val();
  if(voucher_type == ''){
    alert("Please Select Voucher Type");
     $("#cmb_voucher_type").focus();
    return false;
  } else if(financial_year == ''){
    alert("Please Select Financial Year");
     $("#cmb_finance_year").focus();
    return false;
  }else if(bill_no == ''){
    alert("Please enter Ref. no.");
     $("#txt_bill_no").focus();
    return false;
  }else if(je_date == ''){
    alert("Please Select Date");
     $("#txt_date").focus();
    return false;
  }else if(flag == 'empty') {
    alert("Please enter valid entry in grid");
      return false;
  } else if(item_flag == 'empty'){
    alert("Please enter valid entry in grid");
      return false;
  }  else if(type_flag == 'empty'){
    alert("Please enter valid entry in grid");
      return false;
  } else if(parseInt(credit) != parseInt(debit)){
    alert("Debit Total Amount and Credit Total Amount should be match.");
      return false;
  } else{
    $("#frm_debit").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>JOURNAL VOUCHER</b></li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>        
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>             
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
             <form name="frm_debit" id="frm_debit" action="<?php echo site_url('entry/add_journalvoucher');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">
            <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
                 <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Journal Voucher No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9"> 
                         <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $sn[0]['prefix']; ?>" readonly style="width:35%;    border: 1px solid #555555 !important;  background-color: #f5f5f5; font-size: 15px;" maxlength = "20">                    
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="<?php echo '0000'.$sn[0]['SN']; ?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5; font-size: 15px;">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Reference No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                   
                       <input type="text" class="form-control input-sm"  name="txt_bill_no"  id="txt_bill_no" maxlength="10" style="font-size: 16px;" > 
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Reference Date<span class="textspandata">*</span></label>
                    <div class="col-md-11 col-sm-9 col-xs-9">                   
                       <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" style="font-size: 16px;">
                    </div>
                </div>
                </div>                               
                  
            </div>            
            
            <div style="height: 260px; overflow: auto; background-color: #ffffff;" >
              <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 12px; background-color: #003166; color: #ffffff;">        
                    <th style="width: 500px;" >Particulars</th>                   
                    <th>Amount</th>
                    <th>Debit/Credit</th>
                    <th></th>
                </tr>
              </thead>
          <tbody>
          <tr class="to_clone1">
          <td>
            <select id="cmbledger_" name="cmb_ledger[]" class="tf4 form-control input-sm" style="width: 480px; font-size: 16px;" >
                        <option selected="selected" value="">select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>            
            </select>
          </td>         
          <td>
              <input name="amount[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="amount_" value="0"  maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" style="font-size: 16px;" />
          </td>
          <td>
             <select id="cmbtype_" name="cmb_type[]" class="tf13 form-control input-sm" style="font-size: 16px;" >
                        <option selected="selected" value="">select</option>
                        <option value='credit'>Credit</option>";                          
                        <option value='debit'>Debit</option>"; 
                                   
            </select>  
              
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
                <div class="form-group col-md-3">
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">TOTAL DEBIT AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_debit_total" type="number" id="txt_debit_total" class="input-sm" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important; font-size: 16px;" value="0" >
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">TOTAL CREDIT AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_credit_total" type="number" id="txt_credit_total" class="input-sm" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important; font-size: 16px;" value="0" >
                    </div>
                </div>
            </div>        
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_payment();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />
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
                  alert('Account already selected');
                  $('#'+id) .val('');
                  return false;
          }
        });
      });

     var id = 1;
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
        var type_flag = '';
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
        $(".tf13").each(function() {
          var type =  $(this).val();   
          if(type == ''){
            type_flag = 'empty';      
          }
        });
        
      if(flag == 'empty' || item_flag == 'empty' || type_flag == 'empty'){
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

     $('.tf13').on('change', function() {
      calc();
     });
     $('.tf14').on('change', function() {
      calc();
     });

      $('.tf14').keyup(function() {
      calc();
     });

function calc(){
  var credit_total = 0;
  var debit_total = 0;

    $(".tf14").each(function() {
        var val = $(this);          
        var amnt_id = (val[0].id);
         var val1 = amnt_id.split('_');
         if(val1[1] == ''){
          var amount = $('#amount_') .val();
          var type = $('#cmbtype_') .val();
          if(amount == ''){
            amount = 0;
          }

          if(type =='credit'){
            
            credit_total = parseFloat(credit_total) +  parseFloat(amount);
          }else if(type =='debit'){
             debit_total = parseFloat(debit_total) + parseFloat(amount);
            
          }
         }else{
           var id1 = val1[1];
          var amount = $('#amount_'+id1) .val();
          if(amount == ''){
            amount = 0;
          }
          var type = $('#cmbtype_'+id1) .val();
          if(type =='credit'){
             credit_total = parseFloat(credit_total) + parseFloat(amount);
          }else if(type =='debit'){
             debit_total = parseFloat(debit_total) + parseFloat(amount);
          }
         }
        });
    $('#txt_credit_total').val(credit_total);
    $('#txt_debit_total').val(debit_total);

}
  
   $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
 $('.input-group.date').datepicker('setDate', 'now');
 $('.input-group.date').datepicker('setStartDate', '<?php echo $financial_year_from;?>');
 $('.input-group.date').datepicker('setEndDate', '<?php echo $financial_year_to;?>'); 
    </script>
</body>
</html>