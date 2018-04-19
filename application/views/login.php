<!DOCTYPE html>
<html>
<head>
    <title>..:: Total Accounting ::..</title>
    <link href="<?php echo base_url('/assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('/assets/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.js');?>"></script>
    <script src="<?php echo base_url('/assets/js/bootstrap.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/js/script.js');?>"></script>    
</head>
<style type="text/css">
    body {
    font-family: 'Calibri' !important;
    
    background:#ffffff;
}

.form_bg {
    background-color:#3399FF;
    color:#666;
    padding:20px;
    border-radius:0px;
    position: absolute;
    border:1px solid #fff;
    margin: auto;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 380px;
    height: 270px;
}
.error_message {
  margin: 15px 0 0;
  color: #f40707;
  font-weight: bold;
  font-size: 14px;
}
.message{
  color: #ffffff;
  font-weight: bold;
  margin-right: 30px;
  font-size: 14px;
}
.align-center {
    
    text-align:right;
}

.align-center1 {
    
    text-align:center;
}
.input-sm{
        height:30px;
        
    }
</style>
<script type="text/javascript">

function submit_register() {
        var username = $("#txt_username").val();
        var password = $("#txt_passwords").val();
        
         if (username == "") {
            alert('Please enter username');
            $("#txt_username").focus();
            return false;
        }else if (password == "") {
            alert('Please enter password');
            $("#txt_passwords").focus();
            return false;
        } else {
           $("#frm_login").submit();            
        } 
    }
  </script>
<body>
<div class="container">
    <div class="row">
        <div class="form_bg">
            <form class="login-form" id="frm_login" name="frm_login" action="<?php echo site_url('login/user_credential');?>" method="post">
                 <label style="color: #ffffff; margin-bottom: 15px; line-height: 30px; font-size: 18px; border-bottom: 1px solid #fff;  width: 340px;">Signin &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo base_url('/assets/image/Logo_l.PNG');?>"></label>
                <br/>
                <div class="form-group">                                      
                    <input type="text" id="txt_username" class="form-control input-sm"  name="txt_username" placeholder="Username">
                </div>
               
                <div class="form-group">
                    <input type="password" class="form-control input-sm" name="txt_passwords"  id="txt_passwords" placeholder="Password">
                
                </div>
                  <div class="form-group">
                  <select name="cmb_finance_year" id="cmb_finance_year" class="form-control input-sm">
                          <?php foreach ($financial_year as $row) {
                              echo "<option value='".$row['ID']."' >".$row['FINANCIALYEAR']."</option>"; 
                            }?>              
                  </select>
                </div>   
                   <div class="align-center">
                <span ><a href="<?php echo site_url('/login/forgot_password');?>" class="message">Forgot Password</a></span><button type="submit" class="btn btn-primary" id="login" style="background-color: #003366;"  onclick="return submit_register()">LOGIN</button> <button type="reset" class="btn btn-primary" id="login" style="background-color: #003366;" >RESET</button>

                    </div>
                    <?php if(isset($alert)) {
        ?>
                <span class="align-center1 error_message"><?php echo $alert;?></span> 
                <?php } ?>
            </form>
        </div>
    </div>
</div>
</body>
</html>