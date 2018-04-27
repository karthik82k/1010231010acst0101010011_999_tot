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
                                <a href="<?php echo site_url('/entry/manage_sales_voucher');?>" alt="Sales Voucher" title="Sales Voucher">Sales Voucher</a>
                            </li>                                                  
                            <li>
                                <a href="<?php echo site_url('/entry/manage_purchase_voucher');?>" alt="Purchase Voucher" title="Purchase Voucher">Purchase Voucher</a>
                            </li>   
                            <li>
                                <a href="<?php echo site_url('/entry/manage_payment_voucher');?>" alt="Payment Voucher" title="Payment Voucher">Payment Voucher</a>
                            </li>   
                            <li>
                                <a href="<?php echo site_url('/entry/manage_receipt_voucher');?>" alt="Reciept Voucher" title="Reciept Voucher">Reciept Voucher</a>
                            </li>                       
                            <li>
                                <a href="<?php echo site_url('/entry/manage_receipt_voucher_billwise');?>" alt="Billwise Reciept Voucher" title="Billwise Reciept Voucher">Billwise Reciept Voucher</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/entry/manage_payment_voucher_billwise');?>" alt="Billwise Payment Voucher" title="Billwise Payment Voucher">Billwise Payment Voucher</a>
                            </li> 
                            <li>
                                <a href="<?php echo site_url('/entry/manage_debitnote');?>" alt="Debit Note" title="Debit Note">Debit Note</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/entry/manage_creditnote');?>" alt="Credit Note" title="Credit Note">Credit Note</a>
                            </li> 
                             <li>
                                <a href="<?php echo site_url('/entry/manage_journal_voucher');?>" alt="Journal Voucher" title="Journal Voucher">Journal Voucher</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/entry/manage_stock_journal');?>" alt="Stock Journal" title="Stock Journal">Stock Journal</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/entry/manage_purchase_order');?>" alt="Purchase Order" title="Purchase Order">Purchase Order</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/entry/manage_sales_quotation');?>" alt="PROFORMA INVOICE" title="PROFORMA INVOICE">Proforma Invoice</a>
                            </li>                                           
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Definitions<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                             <li>
                                <a href="<?php echo site_url('/definition/manage_account');?>" alt="Add Accounts" title="Add Accounts">Manage Accounts</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Manage Group" title="Manage Group">Manage Group</a>
                            </li>
                            <!--<li>
                                <a href="#" alt="Transfer Group" title="Transfer Group">Transfer Group</a>
                            </li>  -->
                            <li>
                                <a href="<?php echo site_url('/definition/transfer_account');?>" alt="Merge Accounts" title="Merge Accounts">Merge Accounts</a>
                            </li>
                            <li>
                                <a href="#" alt="Fixed Asset Schedule" title="Fixed Asset Schedule">Fixed Asset Schedule</a>
                            </li>
                              <li>
                                <a href="<?php echo site_url('/entry/export_bill');?>" alt="Export Invoice" title="Export Invoice">Export Invoice</a>
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
                                <a href="<?php echo site_url('/inventory/manage_item_group');?>" alt="Manage Group Tax" title="Manage Group Tax">Manage Group Tax</a>
                            </li>                      
                            <li>
                                <a href="<?php echo site_url('/inventory/stock_ledger');?>" alt="Stock Ledger" title="Stock Ledger">Stock Ledger</a>
                            </li>  
                             <li>
<<<<<<< HEAD
                                <a href="<?php echo site_url('inventory/inventory_report');?>" alt="Inventory Report" title="Inventory Report">Inventory Report</a>
=======
                                <a href="#" alt="Inventory Report" title="Inventory Report">Inventory Report</a>
>>>>>>> master
                            </li>                                                      
                            <li>
                                <a href="<?php echo site_url('/mis_report/stock_journal_checklist');?>" alt="Stock Jrnl. Chk.List" title="Stock Jrnl. Chk.List">Stock Jrnl. Chk.List</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/inventory/manage_goods_recieved_note');?>" alt="Goods Received Note" title="Goods Received Note">Goods Received Note</a>
                            </li>                                                
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Register<b class="caret"></b></a>
                        <ul class="dropdown-menu">                        
                            <li>
                                <a href="<?php echo site_url('/report/sales_register');?>" alt="Sales Register" title="Sales Register">Sales Register</a>
                            </li>  
                             <li>
                                <a href="<?php echo site_url('/report/purchase_register');?>" alt="Purchase Report" title="Purchase Register">Purchase Register</a>
                            </li>                                                      
                            <li>
                                <a href="<?php echo site_url('/report/cash_book');?>" alt="Cash Day Book" title="Cash Day Book">Cash Day Book</a>
                            </li>
                             <li>
                                <a href="<?php echo site_url('/report/bank_book');?>" alt="Bank Book" title="Bank Book">Bank Book</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/report/general_ledger');?>" alt="General Ledger" title="General Ledger">General Ledger</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/report/credit_note_register');?>" alt="Credit Note Register" title="Credit Note Register">Credit Note Register</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/report/debit_note_register');?>" alt="Debit Note Register" title="Debit Note Register">Debit Note Register</a>
                            </li>                             
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">MIS Report<b class="caret"></b></a>
                        <ul class="dropdown-menu">  
                            <li>
                                <a href="<?php echo site_url('/mis_report/sales_report');?>" alt="Sales Register" title="Sales Register">Sales Report</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/mis_report/purchase_report');?>" alt="Purchase Register" title="Purchase Register">Purchase Report</a>
                            </li>                            
                            <li>
                                <a href="<?php echo site_url('/mis_report/debitnote_report');?>" alt="Debit Note Report" title="Debit Note Report">Debit Note Report</a>
                            </li>  
                             <li>
                                <a href="<?php echo site_url('/mis_report/creditnote_report');?>" alt="Credit Note Report" title="Credit Note Report">Credit Note Report</a>
                            </li>  
                            <li>
                                <a href="<?php echo site_url('/mis_report/journal_checklist');?>" alt="Journal Chk. List" title="Journal Chk. List">Journal Chk. List</a>
                            </li>                           
                            
                            <li>
                                <a href="<?php echo site_url('/mis_report/gstn_filling/');?>" alt="GSTN FILLING" title="GSTN FILLING">GSTN FILLING</a>
                            </li> 
                            <li>
                                <a href="<?php echo site_url('/mis_report/payable_billwise/');?>" alt="Payable Billwise" title="Payable Billwise">Payable Billwise</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/mis_report/recievable_billwise/');?>" alt="Receivable Billwise" title="Receivable Billwise">Receivable Billwise</a>
                            </li> 
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Financial Statement<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo site_url('/report/trail_balance');?>" alt="Trial Balance" title="Trial Balance">Trial Balance</a>
                            </li>
                             <li>
                              <a href="<?php echo site_url('/report/profit_n_loss');?>" alt="Profit & Loss A/c" title="Profit & Loss A/c">Profit & Loss A/c</a>
                            </li> 
                             <li>
                                <a href="<?php echo site_url('/report/balance_sheet');?>" alt="Balance Sheet" title="Balance Sheet">Balance Sheet</a>
                            </li>
                           <li>
                                <a href="#" alt="Receivable" title="Receivable">Receivable</a>
                            </li>                          
                            <li>
                                <a href="#" alt="Payable" title="Payable">Payable</a>
                            </li>                         
                            <li>
                                <a href="#" alt="Fixed Asset Schedule" title="Fixed Asset Schedule">Fixed Asset Schedule</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                             <li>
                                <a href="<?php echo site_url('/master/manage_serial');?>" alt="Prefix" title="Prefix">Manage Prefix</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/master/manage_party');?>" alt="Manage Party Address" title="Manage Party Address">Manage Party Address</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/master/add_terms_condition');?>" alt="Terms and Conditions" title="Terms and Conditions">Terms and Conditions</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/master/manage_user');?>" alt="User" title="User">Manage User</a>
                            </li>
                             <li>
                                <a href="<?php echo site_url('/master/manage_tax');?>" alt="Tax" title="Tax">Manage Tax</a>
                            </li>
                             <li>
                                <a href="#" alt="Close Financial Year" title="Close Financial Year">Close Financial Year</a>
                            </li>                           
                            <li>
                                <a href="<?php echo site_url('/mis_report/cancel_sales_report');?>" alt="Cancelled / Del Invoice Report" title="Cancelled / Del Invoice Report">Cancelled / Del Invoice Report</a>
                            </li>                                            
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Online Portal<b class="caret"></b></a>
                        <ul class="dropdown-menu">  
                             <li>
                                <a href="https://cbec-gst.gov.in/gst-goods-services-rates.html" alt="GST HSN/SAC Details" title="GST HSN/SAC Details" target="_blank">GST HSN/SAC Details</a>
                            </li>                         
                             <li>
                                <a href="https://www.gst.gov.in" alt="GST website" title="GST website" target="_blank">GST website</a>
                            </li>                          
                            <li>
                                <a href="https://www.incometaxindiaefiling.gov.in/home" alt="Income TAX website" title="Income TAX website" target="_blank">Income Tax website</a>
                            </li>
                            <li>
                                <a href="https://ewaybill2.nic.in/ewbnat2/" alt="E-Way Bill" title="E-Way Bill" target="_blank">E-Way Bill</a>
                            </li>
                             <li>
                                <a href="http://www.esic.in/ESICInsurance1/ESICInsurancePortal/PortalLogin.aspx" alt="ESIC Website" title="ESIC Website" target="_blank">ESIC Website</a>
                            </li>
                             <li>
                                <a href="https://unifiedportal-emp.epfindia.gov.in/epfo/" alt="Provident Fund " title="Provident Fund " target="_blank">Provident Fund</a>
                            </li>
                             <li>
                                <a href="https://www.pt.kar.nic.in" alt="Professional TAX" title="Professional TAX" target="_blank">Professional TAX</a>
                            </li> 
                             <li>
                                <a href="http://www.vat.kar.nic.in/" alt="KVAT" title="KVAT" target="_blank">KVAT</a>
                            </li>                                                   
                        </ul>
                    </li>
                    <?php
                }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                   <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome <?php echo $name;?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php                    
                        if($role_id != 1) {
                        ?>
                        <li>
                            <a href="<?php echo site_url('/master/change_password');?>" alt="Change Password" title="Change Password">Change Password</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/master/my_account');?>" alt="My Account" title="My Account">My Account</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li>
                            <a href="<?php echo site_url('/login/logout');?>" alt="logout" title="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
                        </li>
                    </ul>
                  </li>              
              </ul>
            </div>           
        </div>        
    </nav>
                        <!-- menu code ends here -->
</div>