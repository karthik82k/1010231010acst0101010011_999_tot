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
label {
    margin-left: 2%;
    margin-top: 1%;
    margin-bottom: 1%;
    }
    .well {
        padding: 5px;
    }
    select.input-sm {
        height:28px;
        line-height:28px;
    }
    .input-sm{
        height:28px;
        padding: 5px 4px;
    }

  input[type="radio"] {margin: 0px !important;width: 15px!important;}
</style>
<script type="text/javascript">

 function create_account() {
         var financial_year = $("#cmb_financial_year").val();
        var account_name = $("#txt_account_name").val();        
        var group = $("#cmb_group").val();

         if (financial_year == "") {
            alert('Please select Financial Year');
            $("#cmb_financial_year").focus();
            return false;
        }else if (account_name == "") {
            alert('Please enter account name');
            $("#txt_account_name").focus();
            return false;
        }else if (group == "") {
            alert('Please select Group');
            $("#cmb_group").focus();
            return false;
        } else {
           $("#frm_create_account").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">EDIT ACCOUNT</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>         
    </ol>     
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:3px 3px 3px 3px;">
            <form name="frm_create_account" id="frm_create_account" method="post" action="<?php echo site_url('definition/update_accounts');?>" >
        <div class="textdata_myaccount">
              <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-10 col-sm-9 col-xs-9">
                            
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Opened FY<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_financial_year" name="cmb_financial_year" class="form-control input-sm">
                                         <option value="" >Select Financial Year</option><?php foreach ($financial_year as $row) {
                                        if($account_list[0]['FINANCIALYEAR_ID'] == $row['ID'] ){
                                            echo "<option selected value='".$row['ID']."'>".$row['FINANCIALYEAR']."</option>"; 
                                        }else{
                                            echo "<option value='".$row['ID']."'>".$row['FINANCIALYEAR']."</option>";   
                                        }
                                        }?>
                                    </select>
                                    <input type="hidden" name="company_id" id="company_id" value="<?php echo $account_list[0]['COMPANY_ID'];?>">
                                    <input type="hidden" name="account_id" id="account_id" value="<?php echo $account_list[0]['ID'];?>">
                                </div>
                            </div>
                           
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Name<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_account_name" name="txt_account_name" maxlength="250"  value="<?php echo $account_list[0]['ACCOUNTDESC'];?>">
                                </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Group<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                     <select id="cmb_group" name="cmb_group" class="form-control input-sm" style="pointer-events: none;">
                                        <option value="" >Select Group</option>
                                     <?php foreach ($account_group as $row) {
                                            if($account_list[0]['GROUP_ID'] == $row['groupid'] ){
                                                echo "<option selected value='".$row['groupid']."'>".$row['groupname']."</option>"; 
                                            }else{
                                                     echo "<option value='".$row['groupid']."'>".$row['groupname']."</option>"; 
                                            }                                      
                                        
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Reverse Charge Applicable<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;"> <?php
                                     $reverse = $account_list[0]['REVERSE_CHARGES_APPLICABLE'];
                                     $selected_no = 'selected';
                                     $selected_yes = '';
                                     if($reverse == 1){
                                      $selected_yes = 'selected';
                                       $selected_no = '';
                                     }
                                     ?>
                                     <select id="cmb_reverse_charge" name="cmb_reverse_charge" class="form-control input-sm">
                                        <option value="1" <?php echo $selected_yes;?>>YES</option>
                                        <option value="0" <?php echo $selected_no;?>>NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6" id="div_billwise">
                                <label for="emirate" class="control-label">Maintain billwise details<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                 <?php
                                     $billwise = $account_list[0]['MAINTAIN_BILLWISE_DETAILS'];
                                     $bill_no = '';
                                     $bill_yes = '';
                                     if($billwise == 1){
                                      $bill_yes = 'selected';
                                     }elseif($billwise == 0){
                                        $bill_no = 'selected';
                                     }
                                     ?>
                                     <select id="cmb_billwise" name="cmb_billwise" class="form-control input-sm">

                                        <option value="" selected>Select billwise details</option>
                                        <option value="1" <?php echo $bill_yes;?>>YES</option>
                                        <option value="0" <?php echo $bill_no;?>>NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6" id='div_reg_num'>
                                <label for="emirate" class="control-label">Registration Number<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                <input type="text" class="form-control input-sm" id="txt_tin_num" name="txt_tin_num" maxlength="20" value="<?php echo $account_list[0]['TINNO'];?>">
                                   
                                </div>
                            </div>                            
                            <!--<div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Type<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                <?php 
                                    $checked_sales = "";
                                    $checked_purchase = "";
                                    $checked_others = "";
                                    if($account_list[0]['BILLOFSALE_ID'] == 2){
                                        $checked_sales = 'checked="checked"';
                                    }else if($account_list[0]['BILLOFSALE_ID'] == 1){
                                        $checked_purchase = 'checked="checked"';
                                    }else{
                                         $checked_others = 'checked="checked"';
                                    }
                                 ?>
                                   <input type="radio" id="rtn_type1" class="chk_gst" name="rtn_type"  value="2" <?php echo $checked_sales;?> /> Sales &nbsp;&nbsp;<input type="radio" id="rtn_type2" class="chk_gst" name="rtn_type"  value="1" <?php echo $checked_purchase;?> /> Purchase &nbsp;&nbsp;<input type="radio" id="rtn_type3" class="chk_gst" name="rtn_type"  value="0" <?php echo $checked_others;?> /> Others
                                </div>
                            </div> -->

                            <div class="form-group col-md-4 col-sm-6 col-xs-6" id="div_opening_bal">
                                <label for="emirate" class="control-label">Opening Balance<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="number" class="form-control input-sm" id="txt_opening_bal" name="txt_opening_bal" maxlength="250" value="<?php echo $account_list[0]['OPBALANCE'];?>">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6" id="div_debit_credit">
                                <label for="emirate" class="control-label">&nbsp;</label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                <?php 
                                    $checked_crd = "";
                                    $checked_dbt = "";
                                    if($account_list[0]['ACCOUNTTYPE'] == 'Credit'){
                                        $checked_crd = 'checked="checked"';
                                    }else{
                                         $checked_dbt = 'checked="checked"';
                                    }
                                 ?>
                                   <input type="radio" id="rtn_credit1" class="chk_gst" name="rtn_credit"  value="Debit" <?php echo $checked_dbt;?> />  Debit &nbsp;&nbsp;<input type="radio" id="rtn_credit2" class="chk_gst" name="rtn_credit"  value="Credit" <?php echo $checked_crd;?> /> Credit
                                </div>
                            </div>
                            
                            </div>
                           
                            </div>
                   </div>
                    </fieldset>
                   <!-- <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-10 col-sm-9 col-xs-9">
                            
                            
                           
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Address I<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_address1" name="txt_address1" maxlength="250" value="<?php echo $address1;?>">
                                </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Address II<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_address2" name="txt_address2" maxlength="250" value="<?php echo $address2;?>">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Country<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_country" name="cmb_country" class="form-control input-sm">
                                        <option value="" >Select Country</option>
                                          <?php foreach ($country as $row) {
                                          if($country_id == $row['ID']){
                                            echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                                          }
                                          else{
                                           echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";  
                                          }                  
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">State<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_state" name="cmb_state" class="form-control input-sm">
                                        <option value="" selected="selected">Select State</option>
                                        <?php foreach ($state as $row) {
                                          if($state_id == $row['ID']){
                                            echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                                          }
                                          else{
                                           echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";  
                                          }                  
                                        }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">District<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_district" name="cmb_district" class="form-control input-sm">
                                        <option value="" selected="selected">Select District</option>
                                        <?php foreach ($distict as $row) {
                                        if($district_id == $row['ID']){
                                          echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                                        }
                                        else{
                                         echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";  
                                        }                  
                                      }
                                      ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Pincode<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_pin_code" name="txt_pin_code"  maxlength="20" value="<?php echo $pincode;?>">
                                </div>
                            </div>                           
                            </div>
                           
                            </div>
                   </div>
                    </fieldset>    -->                  
                    <div style="height:2px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_account();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" /> <input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('definition/manage_account');?>'" />
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

    var hide_type = '<?php echo $groups_hide;?>';
    if( hide_type == 'false') {
        $('#div_opening_bal').show();
        $('#div_debit_credit').show();        
    } else {
       $('#div_opening_bal').hide();
       $('#div_debit_credit').hide(); 
       $('.chk_gst').attr('checked', false);     
    }
    var reg_hide = '<?php echo $reg_hide;?>';
    if(reg_hide == 'true') {
        $('#div_reg_num').hide();
         $('#div_billwise').hide(); 
    } else {
       $('#div_reg_num').show();
        $('#div_billwise').show(); 
    }

    $("#cmb_country").change(function () {
      var country = $(this).val();
      $('#cmb_state').empty();             
        $.ajax({
            url  :"<?php echo site_url(); ?>/admin/get_state",
            data : {country: country},
            success: function(ret){

                ret = jQuery.parseJSON(ret);
                $('#cmb_district').empty();
                var district = $('#cmb_district');
                var opt1 = document.createElement('option');
                opt1.text = 'Select District';
                opt1.value = '';
                district.append(opt1);
                $('#cmb_state').empty();
                var state = $('#cmb_state');
                var opt = document.createElement('option');
                opt.text = 'Select state';
                opt.value = '';
                state.append(opt);
                for(var key in ret){ 
                    var opt = document.createElement('option');
                    opt.text = ret[key]['NAME'];
                    opt.value = ret[key]['ID'];
                    state.append(opt);
                }
            }
        })

    });

    $("#cmb_state").change(function () {
      var state = $(this).val();
      $('#cmb_district').empty();             
        $.ajax({
            url  :"<?php echo site_url(); ?>/admin/get_district",
            data : {state: state},
            success: function(ret){

                ret = jQuery.parseJSON(ret);
                console.log(ret);
                $('#cmb_district').empty();
                var district = $('#cmb_district');
                var opt = document.createElement('option');
                opt.text = 'Select District';
                opt.value = '';
                district.append(opt);
                for(var key in ret){ 
                    var opt = document.createElement('option');
                    opt.text = ret[key]['NAME'];
                    opt.value = ret[key]['ID'];
                    district.append(opt);
                }
            }
        })

    });
   
     $("#cmb_group").change(function () {
   // alert('hai');
    var grp_type = $('#cmb_group option:selected').text();
    var group = ['SALES','PURCHASE','EXPENDITURE INDIRECT','EXPENDITURE DIRECT','DIRECT INCOME','INDIRECT INCOME','INCOME','EXPENDITURE','INCOME DIRECT','INCOME INDIRECT'];
    var group1 = ['SUNDRY DEBTORS','SUNDRY CREDITORS'];
    if(jQuery.inArray(grp_type, group1) == -1) {
        $('#div_reg_num').hide();
         $('#div_billwise').hide(); 
        $('#txt_tin_num').val('');
         $('#cmb_billwise').val('');
    } else {
       $('#div_reg_num').show();        
        $('#div_billwise').show();
    }
    if(jQuery.inArray(grp_type, group) == -1) {
        $('#div_opening_bal').show();   
         $('#rtn_credit1').attr('checked', true);
        $('#div_debit_credit').show();    
    } else {
       $('#div_opening_bal').hide();
       $('#div_debit_credit').hide();
        $('.chk_gst').attr('checked', false);
       $('#txt_opening_bal').val(0);
    }

   });    

  });
  </script>
</body>
</html>