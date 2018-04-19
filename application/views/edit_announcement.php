<!DOCTYPE html>
<html lang="en">
<head>
<link rel="shortcut icon" href="" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="msapplication-config" content="none"/>       
<title>..:: Total Accounting ::..</title>

<link href="<?php echo base_url('/assets/css/bootstrap.min.css');?>" rel="stylesheet">
<link href="<?php echo base_url('/assets/css/modern-business.css');?>" rel="stylesheet">  
<link href="<?php echo base_url('/assets/css/style.css');?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/script.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/jquery.dataTables.min.css');?>"></style>
<script type="text/javascript"  src="<?php echo base_url('/assets/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('/assets/js/bootstrap.min.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/slick.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/slick-theme.css');?>">
<link href="<?php echo base_url('/assets/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('/assets/css/bootstrap-datepicker.min.css');?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('/assets/js/jquery.alerts.js');?>" type="text/javascript"></script>
<link href="<?php echo base_url('/assets/css/jquery.alerts.css');?>" rel="stylesheet" type="text/css" media="screen" />

<script src="<?php echo base_url('/assets/js/bootstrap-datepicker.min.js');?>" type="text/javascript"></script>
<link href="<?php echo base_url('/assets/css/bootstrap-datepicker.min.css');?>" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" /> -->
<style type="text/css">
    label {
    margin-left: 0%;
    margin-top: 1%;
}
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

.datepicker.dropdown-menu {
  background-color:#FFF;
}

</style>
<body>
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
</style>
<script type="text/javascript">
function add_tax() {
        var company = $("#cmb_company").val();
        var start_date = $("#txt_date").val();
        var end_date = $("#txt_end_date").val();
        var announcement = $("#txt_announcement").val();
         if (company == "") {
            alert('Please select Company');
            $("#cmb_company").focus();
            return false;
        } else if (start_date == "") {
            alert('Please select Start Date');
            $("#txt_date").focus();
            return false;
        }else if (end_date == "") {
            alert('Please select End Date');
            $("#txt_end_date").focus();
            return false;
        }else if (announcement == "") {
            alert('Please enter Announcement');
            $("#txt_announcement").focus();
            return false;
        } else {
           $("#newregisterfrm").submit();            
        } 
    }
  </script>

<div class="container category">        
  <div class="row">
     <ol class = "breadcrumb">                
        <li style="color: red;">EDIT ANNOUNCEMENT</li>         
    </ol>      
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:3px 3px 3px 3px;">
            <form name="newregisterfrm" id="newregisterfrm" method="post" action="<?php echo site_url('admin/update_announcement');?>" >
        <div class="textdata_myaccount">
              <fieldset class="well" id="well1">
                        <div class="row" id="row1">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-10 col-sm-9 col-xs-9">
                            
                            <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Company<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                <input type="hidden" name="announcement_id" id="announcement_id" value="<?php echo $announcement[0]['ANNOUNCE_ID'];?>">
                                    <select id="cmb_company" name="cmb_company" class="form-control input-sm">
                                        <option value="0" selected="selected">ALL Company</option>
                                          <?php foreach ($company_data_list as $row) {
                                            if($announcement[0]['COMPANY_ID'] == $row['ID']){
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
                                <label for="emirate" class="control-label">Start Date<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo date("d-m-Y",strtotime($announcement[0]['STARTDATE']));?>">
                                </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">End Date<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                   <input type="text" class="form-control input-sm  input-group date" id="txt_end_date" name="txt_end_date" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo date("d-m-Y",strtotime($announcement[0]['ENDDATE']));?>">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label for="emirate" class="control-label">Announcement<span class="textspandata">*</span></label>
                                <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                                    <textarea cols="100" rows="5" id="txt_announcement" name="txt_announcement" class="txtarahg" style="height: 42px;width: 90%;" maxlength="450"><?php echo $announcement[0]['Announcement'];?></textarea>
                                </div>
                            </div>                         
                            
                            <!--<dir>
                              
                              <div class="input-group input-daterange">
    <input type="text" class="form-control" value="2012-04-05">
    <div class="input-group-addon">to</div>
    <input type="text" class="form-control" value="2012-04-19">
</div>
                            </dir>-->
                           
                            </div>
                           
                            </div>
                   </div>
                    </fieldset>                    
                    <div style="height:2px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="add_tax();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" /> <input type="button" class="btn btn-primary" name="reset" id="reset" value="Cancel" onclick="window.location.href='<?php echo site_url('master/manage_tax');?>'" />
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
  
 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
 //$('.input-group.date').datepicker('setDate', 'now');
 $('.input-group.date').datepicker('setStartDate', 'now');  

/* $('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});*/

</script>
</body>
</html>