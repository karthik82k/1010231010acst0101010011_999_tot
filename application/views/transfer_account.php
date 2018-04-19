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
             
        var group = $("#cmb_group").val();
        var to_account = $("#cmb_account_to").val();
        var from_account = $("#cmb_account_from").val();
         if (group == "") {
            alert('Please select Group');
            $("#cmb_group").focus();
            return false;
        }else if (from_account == "") {
            alert('Please select From Account');
            $("#cmb_account_from").focus();
            return false;
        }else if (to_account == "") {
            alert('Please select To Account');
            $("#cmb_account_to").focus();
            return false;
        } else {
           $("#frm_create_account").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">TRANSFER ACCOUNT</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #519CFF;"><?php echo $comp_name ;?></b></li>        
    </ol>     
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:3px 3px 3px 3px;">
            <form name="frm_create_account" id="frm_create_account" method="post" action="<?php echo site_url('definition/add_transfer_account');?>" >
            <div class="textdata_myaccount">
              <fieldset class="well" id="well1">
                <div class="row" id="row1">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group col-md-8 col-sm-6 col-xs-6" style="text-align: center;">
                            <label> From Party / Account </label>
                        </div>
                        <div class="col-md-10 col-sm-9 col-xs-9">
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="col-md-2 control-label" style="text-align: right; margin-top: 0px;">Account Group<span class="textspandata">*</span></label>
                                <div class="col-md-8 col-sm-9 col-xs-9" style="align:left;">
                                <select id="cmb_group" name="cmb_group" class="form-control input-sm">
                                    <option value="" selected>Select Group</option>
                                        <?php foreach ($account_group as $row) {
                                            echo "<option value='".$row['groupid']."'>".$row['groupname']."</option>"; 
                                        }?>
                                </select>
                                </div>
                            </div>                           
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="col-md-2 control-label" style="text-align: right; margin-top: 0px;">From Account <span class="textspandata">*</span></label>
                                <div class="col-md-8 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_account_from" name="cmb_account_from" class="form-control input-sm">
                                        <option value="" selected>Select From Account</option>
                                           
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-9 col-sm-6 col-xs-6" style="text-align: center;">
                               <label> To Party / Account </label>
                            </div>
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="col-md-2 control-label" style="text-align: right; margin-top: 0px;">To Account<span class="textspandata">*</span></label>
                                <div class="col-md-8 col-sm-9 col-xs-9" style="align:left;">
                                     <select id="cmb_account_to" name="cmb_account_to" class="form-control input-sm">
                                        <option value="" selected>Select To Account</option>
                        
                                    </select>
                                </div>
                            </div>
                           
                        </div>
                   </div>
                </fieldset> 
                                      
                    <div style="height:2px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Transfer" onclick="transfer_account();"/>&nbsp;&nbsp;
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
  $(document).ready(function() {
    $("#cmb_group").change(function () {
      var group = $(this).val();
      $('#cmb_account_from').empty();
      $('#cmb_account_to').empty();      
      $.ajax({
            url  :"<?php echo site_url(); ?>/definition/get_accountname",
            data : {group: group},
            success: function(ret){

                ret = jQuery.parseJSON(ret);
                $('#cmb_account_from').empty();
                var district = $('#cmb_account_from');
                var opt1 = document.createElement('option');
                opt1.text = 'Select From Account';
                opt1.value = '';
                district.append(opt1);
                $('#cmb_account_to').empty();
                var state = $('#cmb_account_to');
                var opt = document.createElement('option');
                opt.text = 'Select To Account';
                opt.value = '';
                state.append(opt);
                for(var key in ret){ 
                    var opt1 = document.createElement('option');
                    opt1.text = ret[key]['ACCOUNTDESC'];
                    opt1.value = ret[key]['ID'];
                    district.append(opt1);
                    var opt = document.createElement('option');
                    opt.text = ret[key]['ACCOUNTDESC'];
                    opt.value = ret[key]['ID'];
                    state.append(opt);
                }
            }
        })

    });  
   

  });
  </script>
</body>
</html>