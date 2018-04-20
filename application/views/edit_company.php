<!DOCTYPE html>
<html lang="en">
<head>
<link rel="shortcut icon" href="" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="msapplication-config" content="none"/>       
<title>..:: Total Accounting ::..</title>
<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url('/assets/css/bootstrap.min.css');?>" rel="stylesheet">
<link href="<?php echo base_url('/assets/css/modern-business.css');?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/script.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.custom.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/flexigrid.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/js/flexgridcss/flexigrid/flexigrid.css');?>">
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.multiselect.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.multiselect.filter.js');?>"></script>
<script src="<?php echo base_url('/assets/js/jQuery.bPopup.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('/assets/js/slick.js');?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url('/assets/js/bootstrap-datepicker.min.js');?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.autosize.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/jquery-ui.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('/assets/css/jquery.multiselect.css');?>" type="text/css" media="screen" />
<link href="<?php echo base_url('/assets/css/notification.css');?>" rel="stylesheet" type="text/css" media="screen" />
<script src="<?php echo base_url('/assets/js/jquery.alerts.js');?>" type="text/javascript"></script>
<link href="<?php echo base_url('/assets/css/jquery.alerts.css');?>" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/slick.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/slick-theme.css');?>">
<link href="<?php echo base_url('/assets/css/bootstrap-datepicker.min.css');?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('/assets/js/bootstrap.min.js');?>"></script>
<link href="<?php echo base_url('/assets/css/style.css');?>" rel="stylesheet">
</head>
<style>
.navbar-custom {
    background-color: #003166;
    border-color: #003166;
}
.dropdown-menu {
    background-color: #003166;
}
.navbar{
    min-height : 42px !important;
}
.navbar-nav > li > a, .navbar-brand {padding-top: 10px !important;padding-bottom:0 !important;height: 38px;}

.empty-spacer {
    height: 42px;
}
.navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand {
    margin-left: -115px;
    margin-right: 19px;
}
.navbar-right .dropdown-menu {
    width: 183px;
    right: 0;
    left: auto;
}
</style>
<body>
  <!--  header logo code starts here -->
  <?php
  $role_id =  $this->session->userdata('user_role');
  $user_id =  $this->session->userdata('user_id');
  $name = $this->session->userdata('name');
  ?>
<div class="imgwrapper">  
   <nav class="navbar navbar-inverse navbar-custom" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>  
               <p class="navbar-brand" >
                <span style="font-size: 1.8em; color: red">t</span><u style="color: red; font-size:1.3em;">otal</u> <span style="font-size: 1.8em; color: #519CFF"><b>a</b></span><span style="color: #519CFF; font-size:1.3em;">ccounting</span></p>              
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="<?php echo site_url('/home/');?>" alt="Home Page" title="Home Page">Home</a></li>
                     <li></li>
                     <?php
                        if($role_id == 1) {
                    ?>
                      <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administrator<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                            <li>
                                <a href="<?php echo site_url('/admin/manage_company');?>" alt="Manage Company" title="Manage Company">Manage Company</a>
                            </li>      
                            <li>
                                <a href="<?php echo site_url('/admin/manage_announcements');?>" alt="Manage Announcements" title="Manage Announcements">Manage Announcements</a>
                            </li>                                                                                                
                        </ul>
                    </li>
                        <?php
                    }
                    if($role_id != 1) {
                    ?>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Entry Mode<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                            <li>
                                <a href="<?php echo site_url('/entry/sales_voucher');?>" alt="Sales Voucher" title="Sales Voucher">Sales Voucher</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/entry/purchase_voucher');?>" alt="Purchase Voucher" title="Purchase Voucher">Purchase Voucher</a>
                            </li> 
                             <li>
                                <a href="#" alt="Payment Voucher" title="Payment Voucher">Payment Voucher</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Reciept Voucher" title="Reciept Voucher">Reciept Voucher</a>
                            </li>
                             <li>
                                <a href="#" alt="Debit Note" title="Debit Note">Debit Note</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Credit Note" title="Credit Note">Credit Note</a>
                            </li> 
                             <li>
                                <a href="#" alt="Journal Voucher" title="Journal Voucher">Journal Voucher</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Stock Journal" title="Stock Journal">Stock Journal</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/entry/purchase_order');?>" alt="Purchase Order" title="Purchase Order">Purchase Order</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/entry/sales_order');?>" alt="Sales Order" title="Sales Order">Sales Order</a>
                            </li>                                           
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Definitions<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                             <li>
                                <a href="#" alt="Add Accounts" title="Add Accounts">Add Accounts</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Add Group" title="Add Group">Add Group</a>
                            </li>  
                             <li>
                                <a href="#" alt="Transfer Accounts" title="Transfer Accounts">Transfer Accounts</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Close Financial Year" title="Close Financial Year">Close Financial Year</a>
                            </li>
                             <li>
                                <a href="#" alt="Download" title="Download">Download</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Verify A/C" title="Verify A/C">Verify A/C</a>
                            </li> 
                                                                               
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                            <li>
                                <a href="<?php echo site_url('/inventory/manage_item');?>" alt="Manage Items" title="Manage Items">Manage Items</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/inventory/manage_item_group');?>" alt="Manage Items" title="Manage Items">Manage Item Group</a>
                            </li>                         
                            <li>
                                <a href="#" alt="Stock Ledger" title="Stock Ledger">Stock Ledger</a>
                            </li>  
                             <li>
                                <a href="#" alt="Inventory Report" title="Inventory Report">Inventory Report</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Purchase List" title="Purchase List">Purchase List</a>
                            </li>
                             <li>
                                <a href="#" alt="GST e-Filing" title="GST e-Filing">GST e-Filing</a>
                            </li>                                                 
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report<b class="caret"></b></a>
                        <ul class="dropdown-menu">  
                            <li>
                                <a href="<?php echo site_url('/entry/purchase_order_report');?>" alt="Purchase order Report" Purchase order="Invoice Report">Purchase order Report</a>
                            </li>
                             <li>
                                <a href="<?php echo site_url('/entry/sales_order_report');?>" alt="Sales order Report" title="Sales order Report">
                                Sales order Report</a>
                            </li>                        
                            <li>
                                <a href="#" alt="Invoice Report" title="Invoice Report">Invoice Report</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Sales Register" title="Sales Register">Sales Register</a>
                            </li>  
                             <li>
                                <a href="#" alt="Purchase Register" title="Purchase Register">Purchase Register</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Cash Day Book" title="Cash Day Book">Cash Day Book</a>
                            </li>
                             <li>
                                <a href="#" alt="Bank Book" title="Bank Book">Bank Book</a>
                            </li>                          
                            <li>
                                <a href="#" alt="General Ledger" title="General Ledger">General Ledger</a>
                            </li> 
                             <li>
                                <a href="#" alt="Journal Voucher" title="Journal Voucher">Journal Voucher</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Trial Balance" title="Trial Balance">Trial Balance</a>
                            </li>
                             <li>
                              <a href="#" alt="Profit & Loss A/c" title="Profit & Loss A/c">Profit & Loss A/c</a>
                            </li> 
                             <li>
                                <a href="#" alt="Balance Sheet" title="Balance Sheet">Balance Sheet</a>
                            </li>                                                    
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">MIS Report<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                             <li>
                                <a href="#" alt="Receiveable" title="Receivaeble">Receiveable</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Payable" title="Payable">Payable</a>
                            </li>  
                             <li>
                                <a href="#" alt="Credit Note Register" title="Credit Note Register">Credit Note Register</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Debit Note Register" title="Debit Note Register">Debit Note Register</a>
                            </li>
                             <li>
                                <a href="#" alt="Journal Chk. List" title="Journal Chk. List">Journal Chk. List</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Stock Jrnl. Chk.List" title="Stock Jrnl. Chk.List">Stock Jrnl. Chk.List</a>
                            </li>                            
                             <li>
                                <a href="#" alt="Purchase Order" title="Purchase Order">Purchase Order</a>
                            </li>                                                   
                        </ul>
                    </li>
                    <?php
                }
                    ?>
                          </ul>
                <ul class="nav navbar-nav navbar-right">
                   <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome <?php echo $name;?><b class="caret"></b></a>
                     <ul class="dropdown-menu"><li><a href="<?php echo site_url('/login/logout');?>" alt="logout" title="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li></ul>
                  </li>              
              </ul>
            </div>           
        </div>        
    </nav>
                        <!-- menu code ends here -->
</div>
                  
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

function submit_register() {
     
   
       var company_name = $("#txt_company_name").val();
        var address1 = $("#txt_address1").val();
        var txt_country = $("#cmb_country").val();
        var txt_city = $("#txt_city").val();
        var txt_state = $("#cmb_state").val();
        var txt_district = $("#cmb_district").val();
        var txt_financial = $("#cmb_financial").val();
        var pan = $("#txt_pan").val();
        var acc_type = $("#cmb_acc_type").val();
        var currency = $("#cmb_currency").val();
       // $("#newregisterfrm").submit();
        if (company_name == "") {
          alert("Please enter Company Name");
            $("#txt_company_name").focus();
            return false;  
        }
        else if (address1 == "") {
            alert("Please enter Address");
            $("#txt_address1").focus();
            return false;
        }else if (txt_country == "") {
            alert("Please select country");
            $("#cmb_country").focus();
            return false;
        }else if (txt_state == "") {
            alert("Please select state");
            $("#cmb_state").focus();
            return false;
        }else if (txt_city == "") {
            alert("Please enter city");
            $("#txt_city").focus();
            return false;
        } else if (txt_financial == "") {
            alert("Please select finacial year");
            $("#cmb_financial").focus();
            return false;
        }else if (pan == "") {
            alert("Please enter PAN number");
            $("#txt_pan").focus();
            return false;
        }else if (acc_type == "") {
            alert("Please select Account type");
            $("#cmb_acc_type").focus();
            return false;
        }else if (currency == "") {
            alert("Please select currency");
            $("#cmb_currency").focus();
            return false;
        }else {
           $("#newregisterfrm").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">EDIT COMPANY</li>         
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
        <form name="newregisterfrm" id="newregisterfrm" method="post" action="<?php echo site_url('admin/update_company');?>" enctype="multipart/form-data" >
        <div class="textdata_myaccount">
              <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Company Name<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_company_name" name="txt_company_name" value="<?php echo $company_data_list[0]['NAME'];?>" maxlength="100" >
                                    <input name="company_id" type="hidden" id="company_id" value="<?php echo $company_data_list[0]['ID'];?>" />
                                    <input name="txt_company_user_id" type="hidden" id="txt_company_user_id" value="<?php echo $company_data_list[0]['USERID'];?>" />
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Bussiness Type<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_comp_type" name="cmb_comp_type" class="form-control input-sm">                                        
                                        <option value="" selected>Select Bussiness Type</option>
                                         <?php foreach ($company_type as $row) {
                                         if($company_data_list[0]['COMPANYTYPE_ID'] == $row['ID']){
                                          echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>";
                                         }else{
                                          echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";
                                         }
                                         
                                      }
                                      ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Constitution<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_comp_sub_type" name="cmb_comp_sub_type" class="form-control input-sm">
                                        <option value="">Select Company Sub-type</option>
                                        <?php foreach ($sub_company_type as $row) {
                                          if($company_data_list[0]['COMPANYSUBTYPE_ID'] == $row['ID']){
                                             echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                                          }else{
                                             echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
                                          }                 
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Address I<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_address1" name="txt_address1" value="<?php echo $company_data_list[0]['ADDRESS1'];?>" maxlength="200">
                                </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Address II<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_address2" name="txt_address2" value="<?php echo $company_data_list[0]['ADDRESS2'];?>" maxlength="200">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Phone<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_phone" name="txt_phone" value="<?php echo $company_data_list[0]['OWNER_TELNO'];?>" maxlength="30">
                                </div>
                            </div>                            
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">State<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_state" name="cmb_state" class="form-control input-sm">
                                        <option value="">Select State</option>
                                        <?php foreach ($state as $row) {
                                          if($company_data_list[0]['STATE_ID'] == $row['ID']){
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
                                <label for="emirate" class="control-label">District<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_district" name="cmb_district" class="form-control input-sm">
                                        <option value="" selected="selected">Select District</option>
                                        <?php foreach ($distict as $row) {
                                        if($company_data_list[0]['DISTRICT_ID'] == $row['ID']){
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
                                <label for="emirate" class="control-label">City<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_city" name="txt_city" value="<?php echo $company_data_list[0]['CITY'];?>" maxlength="200">
                                </div>
                            </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                             <div class="form-group col-md-12 col-sm-6 col-xs-6" style="height: 30px;">
                            </div>
                             <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <div class="col-md-9 col-sm-9 col-xs-9" style="text-align:center; ;border-radius: 1px 1px 1px 1px; border: solid 1px #000000; padding:1px 1px 1px 1px;">
                                    <label class="btn btn-default">
                                    <?php $checked_gst = '';
                                    $checked_vat = '';
                                    if ($company_data_list[0]['ISGST'] == 1){
                                      $checked_gst = 'checked="checked"';
                                    }else{
                                      $checked_vat = 'checked="checked"';
                                    }

                                    ?>
                                        <input type="radio" class="chk_gst" id="vat_1" name="chk_gst"  value="1"  <?php echo $checked_gst;?> /> GST</label>
                                         <label class="btn btn-default">
                                        <input type="radio" class="chk_gst" id="vat_2" name="chk_gst"  value="2" <?php echo $checked_vat;?>/> VAT</label>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Country<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                   <select id="cmb_country" name="cmb_country" class="form-control input-sm">
                                        <option value="">Select Country</option>
                                         <?php foreach ($country as $row) {
                                          if($company_data_list[0]['COUNTRY_ID'] == $row['ID']){
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
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Pin Code<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_pin_code" name="txt_pin_code" value="<?php echo $company_data_list[0]['PIN'];?>"  maxlength = "6" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                </div>
                            </div>
                            </div>
                            </div>
                   </div>
                    </fieldset>
                    <div style="height:1px;"></div>
                    <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Owner Name<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_name" name="txt_name" value="<?php echo $company_data_list[0]['OWNER'];?>"  maxlength="100">
                                </div>
                            </div>
                           <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Owner Mobile<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_mobile" name="txt_mobile" value="<?php echo $company_data_list[0]['OWNER_MOBILE'];?>"  maxlength="10">
                                </div>
                            </div>
                          
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Owner Email<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_emailid" name="txt_emailid" value="<?php echo $company_data_list[0]['EMAIL'];?>"  maxlength="100">
                                </div>
                            </div>
                              <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Jurisdiction<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_jurisdiction" name="txt_jurisdiction" value="<?php echo $company_data_list[0]['JURISDICTION'];?>"  maxlength="25">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">RC<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_tax_regime" name="txt_tax_regime" value="<?php echo $company_data_list[0]['RC'];?>"  maxlength="20"/>
                                </div>
                            </div>                           
                             <div class="form-group col-md-2 col-sm-6 col-xs-6">
                                 <label for="emirate" class="control-label">Lock Period<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                   <input type="number" class="form-control input-sm" id="txt_lock_period" name="txt_lock_period"  value="<?php echo $company_data_list[0]['LockingPeriod'];?>" >
                                </div>
                            </div>
                            <div class="form-group col-md-1 col-sm-6 col-xs-6">
                                 <label for="emirate" class="control-label">Lock<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                <?php 
                                $checked_lock = "";                                   
                                if($company_data_list[0]['islocked'] == 1){
                                        $checked_lock = 'checked="checked"';
                                    }
                                ?>
                                   <input type="checkbox" value="1" name="chck_lock" id="chck_lock" <?php echo $checked_lock;?> >
                                </div>
                            </div>
                            <div class="form-group col-md-1 col-sm-6 col-xs-6">
                                 <label for="emirate" class="control-label">Active<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                 <?php 
                                $checked_active = "";                                   
                                if($company_data_list[0]['ISACTIVE'] == 1){
                                        $checked_active = 'checked="checked"';
                                    }
                                
                                ?>
                                   <input type="checkbox" value="1" name="chck_status" id="chck_status" <?php echo $checked_active;?> >
                                </div>
                            </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                            <div style="height: 10px;"></div>
                             <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="col-md-2 control-label">PAN<span class="textspandata">*</span></label>
                                <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_pan" name="txt_pan" value="<?php echo $company_data_list[0]['PANNO'];?>"  maxlength="10"  placeholder="XXXXXX9999X">
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="col-md-2 control-label" id='gst_lbl'>GST<span class="textspandata">*</span></label>
                                <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_vat_code" name="txt_vat_code" value="<?php echo $company_data_list[0]['VATCODE'];?>"  maxlength="20" placeholder="99XXXXX9999X9X9">
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-6 col-xs-6">
                                <label for="emirate" class="col-md-2 control-label">TIN<span class="textspandata">*</span></label>
                                <div class="col-md-7 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_tin" name="txt_tin" value="<?php echo $company_data_list[0]['TINNO'];?>"  maxlength="20"  placeholder="XXX-XX-XXXX">
                                </div>
                            </div>
                            </div>
                            </div>
                         </div>
                    </fieldset>
                    <div style="height:1px;"></div>
                    <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Financial Year<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                     <select id="cmb_financial" name="cmb_financial" class="form-control input-sm">
                                        <option value="">Select Financial year</option>
                                         <?php foreach ($financial_year as $row) {
                                        if($company_data_list[0]['FINANCIALYEAR_ID'] == $row['ID']){
                                          echo "<option value='".$row['ID']."' selected>".$row['FINANCIALYEAR']."</option>"; 
                                        }else {
                                          echo "<option value='".$row['ID']."'>".$row['FINANCIALYEAR']."</option>"; 
                                        }
                                      }?>
                                         </select>
                                </div>
                            </div>
                           <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Type<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select id="cmb_acc_type" name="cmb_acc_type" class="form-control input-sm">
                                    <option value="">Select Account Type</option>
                                        <?php 
                                          $account_type = array('Accounting and Inventory' => 'Accounting and Inventory', 'Inventory only' => 'Inventory only', 'Finance only' => 'Finance only');
                                          foreach ($account_type as $key => $value) {
                                            if($company_data_list[0]['LICENSETYPE'] == $value){
                                               echo "<option value='".$key."' selected>".$value."</option>";  
                                            }else{
                                             echo "<option value='".$key."'>".$value."</option>";               
                                            }
                                           }
                                          ?>  
                                        </select> 
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Currency<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <select name="cmb_currency" id="cmb_currency"  class="form-control input-sm">
                                      <option value="" selected="selected">Select Currency</option>
                                       <?php foreach ($currency as $row) {
                                        if($company_data_list[0]['CURRENCYCODE'] == $row['CODE']){
                                            echo "<option value='".$row['CODE']."' selected>".$row['CODE']."</option>"; 
                                        }else{
                                          echo "<option value='".$row['CODE']."'>".$row['CODE']."</option>"; 
                                        }
                                        }
                                        ?>                                
                                      </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Bank Name<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_bank" name="txt_bank" value="<?php echo $company_data_list[0]['BANK'];?>"  maxlength="100">
                                </div>
                            </div>
                              <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Bank Branch<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_branch" name="txt_branch" value="<?php echo $company_data_list[0]['BRANCH'];?>" maxlength="100">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Account Number<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_account_no" name="txt_account_no" value="<?php echo $company_data_list[0]['BANKACCNUM'];?>" maxlength="20">
                                </div>
                            </div>                           
                            
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                             <input type="hidden" name="logo_id" id="logo_id" value="<?php echo $company_data_list[0]['Logo'];?>" />
                            <!--<div class="form-group col-md-12 col-sm-6 col-xs-6" style="height: 75px;">
                                <label for="emirate" class="control-label">Company Logo<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="file" name="logo_upload" id="logo_upload" />
                                </div>
                            </div> -->
                            <div class="form-group col-md-6 col-sm-6 col-xs-6" style="height: 75px;">
                                <label for="emirate" class="control-label">TAN<span class="textspandata"></span></label><br>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_tan" name="txt_tan" maxlength="10" value="<?php echo $company_data_list[0]['TAN'];?>">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-6" style="height: 75px;">
                                <label for="emirate" class="control-label">Managed by<span class="textspandata"></span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_managed_by" name="txt_managed_by" value="<?php echo $company_data_list[0]['MANAGEDBY'];?>" maxlength="50">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">IFSC Code<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_ifsc" name="txt_ifsc" value="<?php echo $company_data_list[0]['IFSC'];?>" maxlength="15">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Username<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm" id="txt_user_name" name="txt_user_name" value="<?php echo $company_data_list[0]['USERNAME'];?>" readonly>
                                </div>
                            </div>
                            </div>
                            </div>
                         </div>
                    </fieldset>
                    <div style="height:2px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Update" onclick="submit_register();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" /> <input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('admin/manage_company');?>'" />
                </div>          
            </div>  
        </form>       
    </div>    
  </div>
</div>
  <div style="height:15px;"></div>                
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
                console.log(ret);
                $('#cmb_state').empty();
                $('#cmb_district').empty();
                var district = $('#cmb_district');
                var opt1 = document.createElement('option');
                opt1.text = 'Select District';
                opt1.value = '';
                district.append(opt1);
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
    $(".chk_gst").click(function(){
    if ($('#vat_1').is(':checked')){
        $('#gst_lbl').text('GST');
    }
    if ($('#vat_2').is(':checked')){
        $('#gst_lbl').text('VAT');
    }
    });
    if ($('#vat_1').is(':checked')){
        $('#gst_lbl').text('GST');
    }
    if ($('#vat_2').is(':checked')){
        $('#gst_lbl').text('VAT');
    }
  });
  </script>
</body>
</html>