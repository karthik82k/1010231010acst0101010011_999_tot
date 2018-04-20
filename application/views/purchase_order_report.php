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
<link href="https://test.newui.myddf.info/css/bootstrap.min.css" rel="stylesheet">
<link href="https://test.newui.myddf.info/css/modern-business.css" rel="stylesheet">
<link href="<?php echo base_url('/assets/css/style.css');?>" rel="stylesheet">
<link href="https://test.newui.myddf.info/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.js"></script>
<script src="https://test.newui.myddf.info/js/bootstrap.min.js"></script>

<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.min.js"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/script.js"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/flexigrid.js"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.multiselect.js"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.multiselect.filter.js"></script>

<script src="https://test.newui.myddf.info/js/jquery.alerts.js" type="text/javascript"></script>
<script src="https://test.newui.myddf.info/js/jQuery.bPopup.js" type="text/javascript"></script>
<script src="https://test.newui.myddf.info/slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.autosize.js"></script>
<link rel="stylesheet" href="https://test.newui.myddf.info/css/jquery-ui.css" type="text/css" media="screen" />
<link rel="stylesheet" href="https://test.newui.myddf.info/css/jquery.multiselect.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="https://test.newui.myddf.info/js/flexgridcss/flexigrid/flexigrid.css">
<link href="https://test.newui.myddf.info/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<link href="https://test.newui.myddf.info/css/notification.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="https://test.newui.myddf.info/slick/slick.css">
<link rel="stylesheet" type="text/css" href="https://test.newui.myddf.info/slick/slick-theme.css">
<link href="https://test.newui.myddf.info/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
</head>
<style>
.navbar-custom {
    background-color: #003166;
    border-color: #003166;
}
.dropdown-menu {
    background-color: #003166;
}

.dropdown-menu-custom {
   background-color: #003166;;
}

#f5f5f5
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
  <!--  header logo code starts here -->
  <?php
  $role_id =  $this->session->userdata('user_role');
  $user_id =  $this->session->userdata('user_id');
  $name = $this->session->userdata('name');
  $comp_name = $this->session->userdata('company_name');  
  $financial_year_selected = $this->session->userdata('financial_year');
  $financial_year_id = $this->session->userdata('financial_id');
  $financial_year_from = $this->session->userdata('financial_from');
  $financial_year_to = $this->session->userdata('financial_to');

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
                                <a href="<?php echo site_url('/entry/debit_note');?>" alt="Debit Note" title="Debit Note">Debit Note</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/entry/credit_note');?>" alt="Credit Note" title="Credit Note">Credit Note</a>
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
    margin-top: 0%;
    }
    .well {
        padding: 1px;
    }
    .col-md-12{
      padding: 5px;
      padding-bottom: 0px;
    margin: 0px;
    padding-top: 0px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
      padding: 2px;
    }
    .input_grid{
      height: 23px;
    }
</style>
<script type="text/javascript">

function create_salesvoucher() {
  
  var so_no = $("#cmb_so_number").val();

  if(so_no == ''){
    alert("Please PURCHASE Order Number");
     $("#cmb_so_number").focus();
    return false;
  }else{
    $("#frm_sales_order").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>PURCHASE ORDER REPORT</b></li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo $comp_name;?></b></li>
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>          
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_sales_order" id="frm_sales_order" action="<?php echo site_url('entry/purchase_order_report_det');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            
                <div class="col-md-12 col-sm-12 col-xs-12">
            
                
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">PO Number<span class="textspandata">*</span></label>
                    <div class="col-md-11 col-sm-9 col-xs-9">                   
                       <select name="cmb_so_number" id="cmb_so_number" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select PO Number</option>
                        <?php
                          foreach ($purchase_order as $key) {
                             if($purchase_id == $key['VOUCHER_ID']){
                              echo "<option value='".$key['VOUCHER_ID']."' selected>".$key['PREFIX'].$key['id']."</option>";
                             }else{
                              echo "<option value='".$key['VOUCHER_ID']."'>".$key['PREFIX'].$key['id']."</option>";
                            }                            
                          }
                        ?>
                      </select> 
                    </div>
                </div>
                
                </div>
                
                <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="View" onclick="create_salesvoucher();"/>
                </div>
            </div>         
            <div style="height:5px;"></div>
            <div style="width:100%; background-color: #ffffff;" >

             
          <?php
           if(empty($purchase_order_detail)) {
         }else {?>
         <table class="table table-bordered" >
         <tr>
            <td><b>Sales Order No</b></td><td><?php echo $so_number;?></td>
            </tr>
           <tr>
            <td><b>Date</b></td><td><?php echo date("m-d-Y",strtotime($date));?></td>
            </tr>
            <tr>
            <td><b>Account Name</b></td><td><?php echo $account_id;?></td>
            </tr>
            <tr>
            <td><b>Ledger Name</b></td><td><?php echo $ledger;?></td>
           </tr>
           <tr>
            <td><b>Validity</b></td><td><?php echo $validtity;?></td>
           </tr>
         </table>
           <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 12px; background-color: #003166; color: #ffffff;">        
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Rate</th>                    
                    <th>Qty</th>                   
                    <th>Total</th>
                </tr>
              </thead>
          <tbody>
          <?php
           $total_final =0;
          foreach ($purchase_order_detail as  $value) {
          $item = $value['ITEM_NAME'];
          $unit = $value['UNIT']; 
          $rate = $value['RATE'];
          $qty = $value['QTY'];
          $amount = $value['AMOUNT'];
         
          $total_final = $total_final + $amount;
         
          ?>
        <tr class="to_clone1">
          <td><?php echo $item;?>
           
          </td>
          <td ><?php echo $unit;?>            
              
          </td>
          <td><?php echo $rate;?>
            
          </td>
                   
          <td><?php echo $qty;?>
             
          </td>
          <td><?php echo $amount;?>
             
          </td>
         
          
        </tr>
        <?php 
        
        }
        ?>
        <tr >
          <td>
           
          </td>
          <td >           
              
          </td>
          <td>
            
          </td>
                   
          <td>
             
          </td>
          <td><?php echo $total_final;?>
             
          </td>
         
          
        </tr>
        <?php
        } ?>     
      </tbody>
    </table>
  </div> 
                   
          </fieldset>      
                      
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

  var maxDate = new Date('03-30-2018');
  var minDate = new Date('04-01-2017');
   
 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
  $('.input-group.date').datepicker('setStartDate', minDate);
  $('.input-group.date').datepicker('setEndDate', maxDate); 
    </script>
</body>
</html>