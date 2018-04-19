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
    }
    .well {
        padding: 5px;
    }
    .col-md-2 {
    width: 12%;
}
</style>
<script type="text/javascript">

    function update_password() {
      
       var password_old = $("#txt_password").val(); 
        var password = $("#txt_new_password").val(); 
         var confirm = $("#txt_confirm_password").val();         

         if (password_old == "") {
            alert('Please enter old password');
            $("#txt_password").focus();
            return false;
        } else if (password == "") {
            alert('Please enter new password');
            $("#txt_new_password").focus();
            return false;
        } else if (password != confirm) {
            alert('Please enter password and confirm password are same.');
            $("#txt_new_password").focus();
            return false;
        } else {
           $("#newregisterfrm").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">CHANGE PASSWORD</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="newregisterfrm" id="newregisterfrm"  method="post" action="<?php echo site_url('master/update_password');?>" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1">
            <div class="row" id="row1">
                <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-2 control-label">Current Password<span class="textspandata">*</span></label>
                    <div class="col-md-4 col-sm-9 col-xs-9" style="align:left;">
                        <input type="password" class="form-control input-sm" id="txt_password" name="txt_password" maxlength="20">
                    </div>
                </div>
                <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-2 control-label">New Password<span class="textspandata">*</span></label>
                    <div class="col-md-4 col-sm-9 col-xs-9" style="align:left;">
                        <input type="password" class="form-control input-sm" id="txt_new_password" name="txt_new_password" maxlength="20">
                    </div>
                </div>
               <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-2 control-label">Confirm Password<span class="textspandata">*</span></label>
                    <div class="col-md-4 col-sm-9 col-xs-9" style="align:left;">
                        <input type="password" class="form-control input-sm" id="txt_confirm_password" name="txt_confirm_password" maxlength="20">
                    </div>
                </div>
                
            </div>
              <div style="height:10px;"></div>
                  <div class="text-left" style="margin-left: 50px;" class="col-md-4 control-label">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="update_password();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />
                    
                </div>    
            </fieldset>
                  
                    
                             
            </div>  
        </form>       
    </div>    
  </div>
</div>
  <div style="height:5px;"></div>                
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
    

     $("#txt_prefix").blur(function () {
       var prefix = $(this).val();
        $.ajax({
            url  :"<?php echo site_url(); ?>master/prefix",
            data : {prefix: prefix},
            success: function(ret){              
              if(ret == 'exists') {
                alert("The prefix is already exits!!");
                $("#txt_prefix").val('');
              }
            }
        })
    });

    
  });
  </script>
</body>
</html>