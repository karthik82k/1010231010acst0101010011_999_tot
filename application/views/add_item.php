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

    function create_item() {
        var item_name = $("#txt_item_name").val();
        var group = $("#cmb_group").val();
        var unit = $("#cmb_unit").val();

        if (item_name == "") {
            alert("Please enter item name");
            $("#txt_item_name").focus();
            return false;
        }else if (group == "") {
            alert("Please select group.");
            $("#cmb_group").focus();
            return false;
        }else if (unit == "") {
            alert("Please select unit.");
            $("#cmb_unit").focus();
            return false;
        } else {
           $("#frm_item").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">ADD ITEM</li>         
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li> 
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>     
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_item" id="frm_item" action="<?php echo site_url('inventory/create_item');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1">
            <div class="row" id="row1">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Item Name<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                    <input type="hidden"  name="cmb_financial" id="cmb_financial" value="<?php echo $financial_year_id;?>">
                        <input type="text" class="form-control input-sm" id="txt_item_name" name="txt_item_name" maxlength="50">
                         <input type="hidden" id="txt_page" name="txt_page" value="<?php echo $page;?>">
                        
                    </div>
                </div>
                 <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Group<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <select name="cmb_group" id="cmb_group" class="form-control input-sm">
              <option value="" selected="selected">Select Group</option>
              <?php foreach ($account_group as $row) {
                  echo "<option value='".$row['ID']."'>".$row['GROUPNAME']."</option>"; 
                }?>                
              </select>
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Item Code<span class="textspandata"></span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <input type="text" class="form-control input-sm" id="txt_item_code" name="txt_item_code" maxlength="10">
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">HSN/SAC Code<span class="textspandata"></span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <input type="text" class="form-control input-sm" id="txt_hsn_code" name="txt_hsn_code" maxlength="10">
                    </div>
                </div>                
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Unit<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <select name="cmb_unit" id="cmb_unit"  class="form-control input-sm">
                            <option value="" selected="selected">Select Unit Type</option><?php
                              foreach ($unit as $data) {
                                echo "<option value='".$data['ID']."'>".$data['NAME']."</option>";  
                              }
                              ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Opening Quanity<span class="textspandata"></span></label>
                    <div class="col-md-7 col-sm-7 col-xs-9" style="align:left;">
                        <input type="number" class="form-control input-sm" id="txt_opening_stock" name="txt_opening_stock" maxlength="10"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                    </div>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Opening Value<span class="textspandata"></span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <input type="number" class="form-control input-sm" id="txt_opening_value" name="txt_opening_value" maxlength="12"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                    </div>
                </div>
                <!--<div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Rate<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <input type="number" class="form-control input-sm" id="txt_rate" name="txt_rate" maxlength="10"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                    </div>
                </div> -->
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-4 control-label">Selling Rate<span class="textspandata"></span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                        <input type="number" class="form-control input-sm" id="txt_selling_rate" name="txt_selling_rate" maxlength="12"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                    </div>
                </div>
                
               
            </div>
            </div>            
            </fieldset>
                  
                    
                    <div style="height:10px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_item();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />
                    <input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('inventory/manage_item');?>'" />
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
        $.ajax({
            url  :"<?php echo site_url(); ?>/inventory/item_name",
            data : {item_name: item_name},
            success: function(ret){              
              if(ret == 'exists') {
                alert("The item name is already exits!!");
                $("#txt_item_name").val('');
              }
            }
        })
    });

    
  });
  </script>
</body>
</html>