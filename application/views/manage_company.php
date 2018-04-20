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

    .well {
        padding: 5px;
    }
</style>
<div class="container category" style="min-height: 560px;">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">MANAGE COMPANY</li>         
    </ol> 
     <div id="profilestatus" style="text-align: right;"><a href="<?php echo site_url('/admin/create_company');?>" alt="ADD Company" title="ADD Company"><img src="https://test.newui.myddf.info/images/add.png" alt="ADD Company" title="ADD Company"/> ADD COMPANY</a></div>   
<table id="myTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Company Name</th>
                <th style="width: 20px;">Username</th>
                <th>Address I</th>
                <th>Address II</th>
                <!--<th>District</th> -->
                <th>City</th>
                <th>State</th>
                <th>Country</th>
                <th class="no-sort">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($company_list as $val) {
                
        ?>
            <tr>
                <td><?php echo $val['NAME'] ;?></td>
                <td ><?php echo $val['USERNAME'] ;?></td>
                <td><?php echo $val['ADDRESS1'] ;?></td>
                <td><?php echo $val['ADDRESS2'] ;?></td>
               <!-- <td><?php// echo $val['DISTRICT'] ;?></td> -->
                <td><?php echo $val['CITY'] ;?></td>
                <td><?php echo $val['STATE'] ;?></td>
                <td><?php echo $val['COUNTRY'] ;?></td>
                <td><a href="<?php echo site_url('/admin/edit_company/'.$val['ID']);?>"><span class="fa fa-pencil-square-o"></span></a></td>
            </tr>
            <?php
        }
            ?>
        </tbody>
    </table>

 </div>
 </div>

<div style="height:25px;"></div>                
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

</body>
<script>
$(document).ready(function(){
    $('#myTable').dataTable({
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ]
    });
});
</script>
</html>