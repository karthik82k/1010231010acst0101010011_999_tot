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
</style>
<script type="text/javascript">

    function create_item_group() {
        var item_name = $("#txt_item_name").val();
        var tax_rate = $("#cmb_tax").val();

         if (item_name == "") {
            alert("Please enter group name");
            $("#txt_item_name").focus();
            return false;
        }else if (tax_rate == "") {
            alert("Please select tax rate");
            $("#cmb_tax").focus();
            return false;
        } else {
           $("#frm_item").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">EDIT GROUP TAX</li>
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_item" id="frm_item" action="<?php echo site_url('inventory/update_item_group');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1">
            <div class="row" id="row1">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Name<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <input type="text" class="form-control input-sm" id="txt_item_name" name="txt_item_name" maxlength="50" value="<?php echo $group_item[0]['GROUPNAME'];?>">
                        <input name="item_group_id" type="hidden" id="item_group_id" value="<?php echo $group_item[0]['ID'];?>"/>
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Tax Rate<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <select name="cmb_tax" id="cmb_tax"  class="form-control input-sm">
                           <option value="" selected>Select Tax Rate</option> 
                                <?php foreach ($tax as $row) {
                                    if($group_item[0]['CompanyTax_ID'] == $row['ID']){
                                        echo "<option value='".$row['ID']."' selected>".$row['TAXTYPE']."</option>"; 
                                    }else{
                                        echo "<option value='".$row['ID']."'>".$row['TAXTYPE']."</option>";
                                    }
                                }?>  
                        </select>
                    </div>
                </div>
               
                
            </div>
            </div>            
            </fieldset>
                  
                    
                    <div style="height:10px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_item_group();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />
                    <input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('inventory/manage_item_group');?>'" />
                </div>          
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
    

    $("#txt_item_name").blur(function () {
       var item_name = $(this).val();
       var item_id = $('#item_group_id').val();
        $.ajax({
            url  :"<?php echo site_url(); ?>/inventory/item_group_name_update",
             data : {item_name: item_name, item_id: item_id},
            success: function(ret){              
              if(ret == 'exists') {
                alert("The group name is already exits!!");
                $("#txt_item_name").val('');
              }
            }
        })
    });

    
  });
  </script>
</body>
</html>