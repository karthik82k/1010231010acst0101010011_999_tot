<!DOCTYPE html>
<html>
<head>
    <title></title>

   <link href="https://test.newui.myddf.info/css/bootstrap.min.css" rel="stylesheet">

<link href="https://test.newui.myddf.info/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.js"></script>
<script src="https://test.newui.myddf.info/js/bootstrap.min.js"></script>

<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.min.js"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/script.js"></script>
    
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
  margin-left: 30px;
  font-size: 14px;
}
.align-center {
    
    text-align:left;
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
            <form class="login-form" id="frm_login" name="frm_login" action="" method="post">
                 <label style="color: #ffffff; margin-bottom: 15px; line-height: 30px; font-size: 18px; border-bottom: 1px solid #fff;  width: 340px;">Retrieve Password</label>
                <br/>
                <div class="form-group">                                      
                    <input type="text" id="txt_username" class="form-control input-sm"  name="txt_username" placeholder="Username">
                </div>
               
                 
                   <div class="align-center">
                <button type="submit" class="btn btn-primary" id="login" style="background-color: #003366;"  onclick="return submit_register()">Submit</button> <span ><a href="<?php echo site_url('/login/');?>" class="message">Login</a></span>

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