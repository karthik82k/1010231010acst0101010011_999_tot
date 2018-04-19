<?php $this->load->view('include/header'); ?>
<script type="text/javascript">

function update_user() {
        var first_name = $("#txt_first_name").val();
        var last_name = $("#txt_last_name").val();
        var display_name = $("#txt_display_name").val();
        var username = $("#txt_username").val();
        var password = $("#txt_password").val();
        var confirm_password = $("#txt_confirm_password").val();
        var email = $("#txt_email").val();
        var role = $("#cmb_role").val();

         if (first_name == "") {
            alert('Please enter first name');
            $("#txt_first_name").focus();
            return false;
        }else if (last_name == "") {
            alert('Please enter last name');
            $("#txt_last_name").focus();
            return false;
        }else if (display_name == "") {
            alert('Please enter display name');
            $("#txt_display_name").focus();
            return false;
        }else if (username == "") {
            alert('Please enter username');
            $("#txt_username").focus();
            return false;
        }else if (email == "") {
            alert('Please enter email id');
            $("#txt_email").focus();
            return false;
        }else if (role == "") {
            alert('Please select role');
            $("#cmb_role").focus();
            return false;
        } else {
           $("#newregisterfrm").submit();            
        } 
    }
  </script>
   <form name="newregisterfrm" id="newregisterfrm" method="post" action="<?php echo site_url('master/update_user');?>" >
    <div id="adcontent">
      <div class="adcontent_ttl">Edit User</div>
        <div class="adcnt_cell">
        <div class="ademadd_txt">First Name</div>
            <div class="ademadd_fld">             
              <input name="txt_first_name" type="text" class="txtara" id="txt_first_name" value="<?php echo $user_data[0]['FIRSTNAME'];?>" />
              <input name="user_id" type="hidden" id="user_id" value="<?php echo $user_data[0]['user_id'];?>" />
               <input name="member_id" type="hidden" id="member_id" value="<?php echo $user_data[0]['mem_id'];?>" />
              
            </div>
        </div>
        <div class="adcnt_cell">
        <div class="ademadd_txt">Middle Name</div>
            <div class="ademadd_fld">             
              <input name="txt_middle_name" type="text" class="txtara" id="txt_middle_name" value="<?php echo $user_data[0]['MIDDLENAME'];?>"/>
            </div>
        </div>
        <div class="adcnt_cell">
        <div class="ademadd_txt">Last Name</div>
            <div class="ademadd_fld">             
              <input name="txt_last_name" type="text" class="txtara" id="txt_last_name" value="<?php echo $user_data[0]['LASTNAME'];?>"/>
            </div>
        </div>
        <div class="adcnt_cell">
        <div class="ademadd_txt">Display Name</div>
            <div class="ademadd_fld">             
              <input name="txt_display_name" type="text" class="txtara" id="txt_display_name" value="<?php echo $user_data[0]['DISPLAYNAME'];?>"/>
            </div>
        </div>
        <div class="adcnt_cell">
        <div class="ademadd_txt">Username</div>
            <div class="ademadd_fld">             
              <input name="txt_username" type="text" class="txtara" id="txt_username" value="<?php echo $user_data[0]['USERNAME'];?>" readonly />
            </div>
        </div>        
        <div class="adcnt_cell">
           <div class="ademadd_txt">Secret Question</div>
           <div class="ademadd_fld"><input name="txt_question" type="text" class="txtara" id="txt_question"  value="<?php echo $user_data[0]['PASSWORDQUESTION'];?>"/></div>
        </div>
        <div class="adcnt_cell">
           <div class="ademadd_txt">Secret Answer</div>
           <div class="ademadd_fld"><input name="txt_answer" type="text" class="txtara" id="txt_answer"  value="<?php echo $user_data[0]['PASSWORDANSWER'];?>"/></div>
        </div>
        <div class="adcnt_cell">
           <div class="ademadd_txt">Mobile PIN</div>
           <div class="ademadd_fld"><input name="txt_mobile_pin" type="text" class="txtara" id="txt_mobile_pin"  value="<?php echo $user_data[0]['MOBILEPIN'];?>"/></div>
        </div>
        <div class="adcnt_cell">
           <div class="ademadd_txt">Mobile Number</div>
           <div class="ademadd_fld"><input name="txt_mobile" type="text" class="txtara" id="txt_mobile"  value="<?php echo $user_data[0]['MOBILENO'];?>" /></div>
        </div>
        <div class="adcnt_cell">
           <div class="ademadd_txt">Email id</div>
           <div class="ademadd_fld"><input name="txt_email" type="text" class="txtara" id="txt_email"  value="<?php echo $user_data[0]['EMAIL'];?>"/></div>
        </div>        
        <div class="adcnt_cell">
           <div class="ademadd_txt">Role</div>
           <div class="ademadd_fld"><select name="cmb_role" id="cmb_role" class="txtara_lst_acc_cr">
              <option value="" >Select Role</option>
                <?php foreach ($role_data_list as $row) {
                  if($user_data[0]['ROLE_ID'] == $row['ID']){
                    echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                  }else{
                   echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";  
                  }
                  
                }
                ?>
              </select></div>
        </div>
        <div class="adcnt_cell">
        <div class="ademadd_txt"></div>
         <div class="tmtask_fld_btn">
          <input name="button1" id="button1" type="button" value="Update" onclick="return update_user()"/>
          <input name="button2" id="button2" type="reset" value="Cancel"/>          
          </div></div>
      </div>
     </form>
   <?php $this->load->view('include/footer'); ?>
<script type="text/javascript">
  /*$(document).ready(function() {    
    $("#txt_username").blur(function () {
       var username = $(this).val();
        $.ajax({
            url  :"<?php echo site_url(); ?>/admin/check_username",
            data : {username: username},
            success: function(ret){              
              if(ret == 'exists') {
                alert('The username is already exits!!');
              }
            }
        })

    });
  });*/
  </script>