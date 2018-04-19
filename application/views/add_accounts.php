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
        <li style="color: red;">ADD ACCOUNT</li>
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>         
    </ol>     
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:3px 3px 3px 3px;">
            <form name="frm_create_account" id="frm_create_account" method="post" action="<?php echo site_url('definition/create_accounts');?>" >
        <div class="textdata_myaccount">
              <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-10 col-sm-9 col-xs-9">
                            
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Opened FY<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_financial_year" name="cmb_financial_year" class="form-control input-sm"><?php foreach ($financial_year as $row) {
                                            if($financial_year_id == $row['ID']){
                                                echo "<option value='".$row['ID']."' selected>".$row['FINANCIALYEAR']."</option>";
                                            }                   
                                        }?>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Name<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_account_name" name="txt_account_name" maxlength="250">
                                </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Group<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                     <select id="cmb_group" name="cmb_group" class="form-control input-sm">
                                        <option value="" selected>Select Group</option>
                        <?php foreach ($account_group as $row) {
                                echo "<option value='".$row['groupid']."'>".$row['groupname']."</option>"; 
                            }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Reverse Charge Applicable<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                     <select id="cmb_reverse_charge" name="cmb_reverse_charge" class="form-control input-sm">
                                        <option value="1">YES</option>
                                        <option value="0" selected>NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6" id="div_billwise">
                                <label for="emirate" class="control-label">Maintain billwise details<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                     <select id="cmb_billwise" name="cmb_billwise" class="form-control input-sm">
                                        <option value="" selected>Select billwise details</option>
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6" id="div_reg_num">
                                <label for="emirate" class="control-label">Registration Number<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                <input type="text" class="form-control input-sm" id="txt_tin_num" name="txt_tin_num" maxlength="20" >
                                   
                         <input type="hidden" id="txt_page" name="txt_page" value="<?php echo $page;?>">
                                </div>
                            </div>                            
                           <!-- <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Type<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                   <input type="radio" id="rtn_type1" class="chk_gst" name="rtn_type"  value="2" checked="checked" /> Sales &nbsp;&nbsp;<input type="radio" id="rtn_type2" class="chk_gst" name="rtn_type"  value="1" checked="checked" /> Purchase &nbsp;&nbsp;<input type="radio" id="rtn_type3" class="chk_gst" name="rtn_type"  value="0" checked="checked" /> Others
                                </div>
                            </div> -->
                            <div class="form-group col-md-3 col-sm-6 col-xs-6" id="div_opening_bal">
                                <label for="emirate" class="control-label">Opening Balance<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_opening_bal" name="txt_opening_bal" maxlength="250" value="0">
                                </div>
                            </div>
                            <div class="form-group col-md-2 col-sm-6 col-xs-6" id="div_debit_credit">
                                <label for="emirate" class="control-label">&nbsp;</label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                   <input type="radio" id="rtn_credit1" class="chk_gst" name="rtn_credit"  value="Debit" checked="checked" /> Debit &nbsp;&nbsp;<input type="radio" id="rtn_credit2" class="chk_gst" name="rtn_credit"  value="Credit" checked="checked" /> Credit
                                </div>
                            </div>
                            
                            </div>
                           
                            </div>
                   </div>
                    </fieldset> 
                    <!--<fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-10 col-sm-9 col-xs-9">
                            
                           
                           
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Address I<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_address1" name="txt_address1" maxlength="250">
                                </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Address II<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_address2" name="txt_address2" maxlength="250" >
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Country<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_country" name="cmb_country" class="form-control input-sm">
                                        <option value="" selected="selected">Select Country</option>
                                         <?php foreach ($country as $row) {
                                            echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
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
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">District<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_district" name="cmb_district" class="form-control input-sm">
                                        <option value="" selected="selected">Select District</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Pincode<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_pin_code" name="txt_pin_code"  maxlength="20">
                                </div>
                            </div>
                           
                            </div>
                           
                            </div>
                   </div>
                    </fieldset>  -->                   
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
    var group = ['SALES','PURCHASE','EXPENDITURE INDIRECT','EXPENDITURE DIRECT','DIRECT INCOME','INDIRECT INCOME','INCOME DIRECT','INCOME INDIRECT','INCOME','EXPENDITURE'];
    var group1 = ['SUNDRY DEBTORS','SUNDRY CREDITORS'];
    if(jQuery.inArray(grp_type, group1) == -1) {
        $('#div_reg_num').hide();
        $('#div_billwise').hide();        
        $('#txt_tin_num').val('');
        $('#cmb_billwise').val('');
    } else {
       $('#div_reg_num').show();       
       $('#div_billwise').show();
       $('#txt_tin_num').val('');
       $('#cmb_billwise').val('');
    }
    if(jQuery.inArray(grp_type, group) == -1) {
        $('#div_opening_bal').show();
        $('#rtn_credit1').attr('checked', true);
        $('#div_debit_credit').show();
        $('#txt_opening_bal').val(0);
    } else {
       $('#div_opening_bal').hide();
       $('.chk_gst').attr('checked', false);
       $('#div_debit_credit').hide();
       $('#txt_opening_bal').val(0);
    }

   });
   

  });
  </script>
</body>
</html>