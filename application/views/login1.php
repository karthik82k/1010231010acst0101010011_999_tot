<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
  <style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Roboto:300);

.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #05116b;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #081fa2;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}

.form .error_message {
  margin: 15px 0 0;
  color: #f40707;
  font-weight: bold;
  font-size: 12px;
}

.form .message a {
  color: #02051c;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  background: #76b852; /* fallback for old browsers */
  background: -webkit-linear-gradient(right, #76b852, #8DC26F);
  background: -moz-linear-gradient(right, #76b852, #8DC26F);
  background: -o-linear-gradient(right, #76b852, #8DC26F);
  background: linear-gradient(to left, #f2f2f2, #b3b3b3);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;      
}

  </style>
  <script type="text/javascript">
    
    $('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
  </script>
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
</head>
<body>
  <div class="login-page">
  <div class="form">
    <!--<form class="register-form" >
      <input type="text" placeholder="name"/>
      <input type="password" placeholder="password"/>
      <input type="text" placeholder="email address"/>
      <button>create</button>
      <p class="message">Already registered? <a href="#">Sign In</a></p>
    </form> -->
    <form class="login-form" id="frm_login" name="frm_login" action="<?php echo site_url('login/user_credential');?>" method="post">
      <input type="text" placeholder="username" name="txt_username" id="txt_username" />
      <input type="password" placeholder="password" name="txt_passwords" id="txt_passwords"/>
      <button onclick="return submit_register()">login</button>
      <p class="message"><a href="#">Forget Password</a></p>
      <?php if(isset($alert)) {
        ?>
      <p class="error_message"><?php echo $alert;?></p>
      <?php
    }
    ?>
    </form>
  </div>
</div>
</body>
</html>