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
                                <a href="<?php echo site_url('/entry/manage_sales_voucher');?>" alt="Sales Voucher" title="Sales Voucher">Sales Voucher</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/entry/manage_purchase_voucher');?>" alt="Purchase Voucher" title="Purchase Voucher">Purchase Voucher</a>
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
                                <a href="<?php echo site_url('/entry/manage_purchase_order');?>" alt="Purchase Order" title="Purchase Order">Purchase Order</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/entry/manage_sales_order');?>" alt="Sales Order" title="Sales Order">Sales Order</a>
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
                     <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master<b class="caret"></b></a>
                        <ul class="dropdown-menu">                          
                             <li>
                                <a href="<?php echo site_url('/master/manage_serial');?>" alt="Receiveable" title="Receivaeble">Manage Prefix</a>
                            </li>                          
                            <li>
                                <a href="<?php echo site_url('/master/manage_party');?>" alt="Payable" title="Payable">Manage Party Address</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('/master/manage_user');?>" alt="User" title="User">Manage User</a>
                            </li>
                             <li>
                                <a href="<?php echo site_url('/master/manage_tax');?>" alt="Tax" title="Tax">Manage Tax</a>
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

 $(document).ready(function() {
   // Items
    $('.tf4').on('change', function() {
      
      var id = this.id;
      var string = this.id;      
      var val1 = string.split('_');
       var item_id = $('#'+id) .val();
        $(".tf4").each(function() {           
          var item =  $(this).val();  
          var id1 = $(this);
          var val_id = (id1[0].id);
          
          if(item == item_id && id != val_id){
                  alert('item already selected');
                  $('#'+id) .val('');
                  return false;
          }
        });
        if(item_id != '') {      
          $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_rate",
            data : {item_id: item_id},
            success: function(ret){

                ret = jQuery.parseJSON(ret);
                var rate = ret['rate'];
                if(val1[1] == ''){
                  $('#txtunitprice_') .val(rate);                  
                }else{
                  var id1 = val1[1];
                  $('#txtunitprice_'+id1) .val(rate);
                }
          }
        })        
      }
    });    
  // quantity
    $('.tf5').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var qty = $('#'+id) .val();
     if(qty == ''){
        qty = 0;
        $('#'+id) .val(0);
      }
      if(val1[1] == ''){
      
      var unit_price = $('#txtunitprice_') .val();
      var discount = $('#txtdiscount_') .val();
      var amount = parseInt(qty) * parseInt(unit_price);
      var discount_per = parseInt(discount);
      //var discount_amount = amount * discount_per;
      var total = amount - discount_per;  
     
        $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var unit_price = $('#txtunitprice_'+id1) .val();
      var discount = $('#txtdiscount_'+id1) .val();
      var amount = parseInt(qty) * parseInt(unit_price);
      var discount_per = parseInt(discount);
      //var discount_amount = amount * discount_per;
      var total = amount - discount_per;  
      //alert()
       
        $('#txttotal_'+id1) .val(total);
      }

    calc();
  });
  // unity Price
   $('.tf7').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var unit_price = $('#'+id) .val();
     if(unit_price == ''){
        unit_price = 0;
        $('#'+id) .val(0);
      }
      if(val1[1] == ''){
     
      var  qty = $('#txtqty_') .val();
      var discount = $('#txtdiscount_') .val();
      var amount = parseInt(qty) * parseInt(unit_price);
      var discount_per = parseInt(discount);
      //var discount_amount = amount * discount_per;
      var total = amount - discount_per;  
     
        $('#txttotal_') .val(total);

      } else {
        var id1 = val1[1];
        // var id = this.id;
      
      var  qty = $('#txtqty_'+id1) .val();
      var discount = $('#txtdiscount_'+id1) .val();
      var amount = parseInt(qty) * parseInt(unit_price);
      var discount_per = parseInt(discount);
     // var discount_amount = amount * discount_per;
      var total = amount - discount_per;  
      //alert()
       
        $('#txttotal_'+id1) .val(total);
      }

    calc(); 
  }); 

  // discount
  $('.tf8').on('change', function() {
    var id = this.id;
    var string = this.id;
     var val1 = string.split('_');
     var discount = $('#'+id) .val();
      if(discount == ''){
        discount = 0;
        $('#'+id) .val(0);
      }
      if(val1[1] == ''){      
      var  qty = $('#txtqty_') .val();
      var unit_price = $('#txtunitprice_') .val();
      var amount = parseInt(qty) * parseInt(unit_price);
      var discount_per = parseInt(discount);
     // var discount_amount = amount * discount_per;
      var total = amount - discount_per;  
     
        $('#txttotal_') .val(total);

      } else {
      var id1 = val1[1];      
      var  qty = $('#txtqty_'+id1) .val();
      var unit_price = $('#txtunitprice_'+id1) .val();
      var amount = parseInt(qty) * parseInt(unit_price);
      var discount_per = parseInt(discount);
      //var discount_amount = amount * discount_per;
      var total = amount - discount_per;  
      //alert()
       
        $('#txttotal_'+id1) .val(total);
      }
      calc();
  });

 
});

   function create_debitnote() {
  var flag = '';
   $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseInt(m);   
   if(l <= 0){
      flag = 'empty';      
   }
  });
  var financial_year = $("#cmb_finance_year").val();
  var bill_no = $("#txt_bill_no").val();
  var debit_date = $("#txt_date").val();
  var account_name = $("#cmb_account_group").val();
  var ledger = $("#cmb_ledger").val();
  var tax_type = $("#cmb_tax").val();
  if(financial_year == ''){
    alert("Please Select Financial Year");
     $("#cmb_finance_year").focus();
    return false;
  }else if(bill_no == ''){
    alert("Please enter bill no.");
     $("#txt_bill_no").focus();
    return false;
  }else if(debit_date == ''){
    alert("Please Select Date");
     $("#txt_date").focus();
    return false;
  }else if(account_name == ''){
    alert("Please Select Account");
     $("#cmb_account_group").focus();
    return false;
  }  else if(ledger == ''){
    alert("Please Select Ledger");
     $("#cmb_ledger").focus();
    return false;
  }else if(tax_type == ''){
    alert("Please Select Tax Type");
     $("#cmb_tax").focus();
    return false;
  }else if(flag == 'empty') {
    alert("Please enter valid entry in grid");
      return false;
  } else{
    $("#frm_debit").submit();
  }
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>DEBIT NOTE</b></li> 
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; "><?php echo $comp_name ;?></b></li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>         
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_debit" id="frm_debit" action="<?php echo site_url('entry/add_debitnote');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">
            <input type="hidden"  name="cmb_finance_year" id="cmb_finance_year" value="<?php echo $financial_year_id;?>">
            <!--<div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Financial Year<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_finance_year" id="cmb_finance_year" class="form-control input-sm">
                          <?php foreach ($financial_year as $row) {
                              echo "<option value='".$row['FINANCIALYEAR_ID']."' selected>".$row['FINANCIALYEAR']."</option>"; 
                            }?>              
                          </select>
                    </div>
                   
                </div>-->
                 <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Vouche No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input type="text" class="input-sm" id="cmb_voucher_type" name="cmb_voucher_type" value="<?php echo $sn[0]['prefix']; ?>" readonly style="width:35%;    border: 1px solid #555555 !important;  background-color: #f5f5f5;">                    
                        <input type="text" class=" input-sm" name="txt_serial_no" id="txt_serial_no" value="<?php echo '0000'.$sn[0]['SN']; ?>" readonly style="    border: 1px solid #555555 !important; width:54%;  background-color: #f5f5f5;">
                    </div>
                </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
            
                 <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Bill No<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                   
                       <input type="text" class="form-control input-sm"  name="txt_bill_no"  id="txt_bill_no" maxlength="10" > 
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Bill Date<span class="textspandata">*</span></label>
                    <div class="col-md-11 col-sm-9 col-xs-9">                   
                       <input type="text" class="form-control input-sm  input-group date" id="txt_date" name="txt_date" class="form-control" placeholder="dd-mm-yyyy">
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Account Name<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9" style="align:left;">
                       <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Account Name</option>
                          <?php foreach ($account as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>             
                      </select>
                    </div>
                   
                </div>
                </div>
                 <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-5 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Ledger<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-8 col-xs-9" style="align:left;">
                        <select id="cmb_ledger" name="cmb_ledger" class="form-control input-sm"  >
                        <option selected="selected" value="">select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>"; 
                          }?>            
                      </select>
                    </div>
                   
                </div>
                 <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Tax Rate<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <select name="cmb_tax" id="cmb_tax"  class="form-control input-sm">
                           <option value="" selected>Select Tax Rate</option> 
              <?php foreach ($tax as $row) {
                  echo "<option value='".$row['ID']."'>".$row['TAXTYPE']."</option>"; 
                }?>  
                        </select>
                       
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">SGST<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                        <input name="txt_sgst" id="txt_sgst" type="number" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; width: 75%" >
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">CGST<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                       <input name="txt_cgst" id="txt_cgst" type="number"  class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; width: 75%" >
                    </div>
                </div>
                <div class="form-group col-md-1 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">IGST<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">                   
                       <input name="txt_igst" id="txt_igst" type="number" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important; width: 75%">
                    </div>
                </div>
                </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group col-md-12 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Narration<span class="textspandata">*</span></label>
                <div class="col-md-12 col-sm-9 col-xs-9">  
                  <textarea name="txt_narration" id="txt_narration" cols="5" rows="5" class="txtarahg" style="height: 42px;width: 90%;"  maxlength="250"></textarea>
                  </div>
                  </div>
                  </div>
            </div>            
            
            <div style="height: 160px; overflow: auto; background-color: #ffffff;" >
              <table class="table table-bordered" style="background-color: #ffffff; margin-bottom:5px;" id="textdata">
                <thead>
                  <tr style="font-size: 12px; background-color: #003166; color: #ffffff;">        
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Total Amount</th>
                    <th></th>
                </tr>
              </thead>
          <tbody>
          <tr class="to_clone1">
          <td>
            <select id="cmbitemname_" name="cmb_item_name[]" class="tf4 txtara_lst input_grid" style="width:230px;" >
              <option value="" selected>select Item</option>
                <?php foreach ($item as $row) {
                  echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
                }?>
            </select
          </td>
          <td>
              <input name="txt_qty[]" type="number" class="tf5 addeb_input_txtfld input_grid" id="txtqty_" value="0"  maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>
          <td>
              <input name="txt_unit_price[]" type="number" class="tf7 addeb_input_txtfld input_grid" id="txtunitprice_" value="0"  type = "number" maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>
          <td>
              <input name="txt_discount[]" type="number" class="tf8 addeb_input_txtfld input_grid" id="txtdiscount_" value="0"  maxlength = "10" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
          </td>
          <td>
              <input name="txt_total[]" type="number" class="tf14 addeb_input_txtfld input_grid" id="txttotal_" value="0" readonly style=" background-color: #f5f5f5;" />
          </td>
          <td align="center">
              <img src="https://test.newui.myddf.info/images/add.png" name="multiple" id="multiple" class="f_moreFilter" alt="Add Multiple" title="Add Multiple" /></span> 
              <span class="f_deleteimg"></span>
          </td>
        </tr>     
      </tbody>
    </table>
  </div> 
     <div class="col-md-12 col-sm-12 col-xs-12">               
                <div class="form-group col-md-3">
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">SGST AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                        <input name="txt_total_sgst" type="number" id="txt_total_sgst" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important;">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">CGST AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                        <input name="txt_total_cgst" type="number" id="txt_total_cgst" class="input-sm" readonly style="  background-color: #f5f5f5;   border: 1px solid #555555 !important;">
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">IGST AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-6 col-sm-9 col-xs-9">
                        <input name="txt_total_igst" type="number" id="txt_total_igst" class="input-sm" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important;"y>
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label">Total AMOUNT<span class="textspandata">*</span></label>
                    <div class="col-md-7 col-sm-9 col-xs-9">
                        <input name="txt_final_total" type="number" id="txt_final_total" class="input-sm" readonly style=" background-color: #f5f5f5;    border: 1px solid #555555 !important;" >
                    </div>
                </div>
            </div>            
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_debitnote();"/>&nbsp;&nbsp;
                    <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reset" />
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

     var id = 1;
    $(".f_moreFilter").click(function() {
        var flag = '';
        var item_flag = '';
        $(".tf14").each(function() {
          var m =  $(this).val();
          l = parseInt(m);   
          if(l <= 0){
            flag = 'empty';      
          }
        });
        $(".tf4").each(function() {
          var item =  $(this).val();   
          if(item == ''){
            item_flag = 'empty';      
          }
        });
      if(flag == 'empty' || item_flag == 'empty' ){
        alert("Please enter proper value in Grid.");
        return false;
      }
        var new_div1 = $(this).closest('table').find('.to_clone1').eq(0).clone(true).appendTo("#textdata");

        var tf4 = new_div1.find('.tf4').eq(0);
        var tf5 = new_div1.find('.tf5').eq(0);
        var tf6 = new_div1.find('.tf6').eq(0);
        var tf7 = new_div1.find('.tf7').eq(0);
        var tf8 = new_div1.find('.tf8').eq(0);
        var tf9 = new_div1.find('.tf9').eq(0);
        var tf10 = new_div1.find('.tf10').eq(0);
        var tf11 = new_div1.find('.tf11').eq(0);
        var tf12 = new_div1.find('.tf12').eq(0);
        var tf13 = new_div1.find('.tf13').eq(0);
        var tf14 = new_div1.find('.tf14').eq(0);

        tf4.val('').css('height', 'auto');
        tf5.val(0).css('height', 'auto');
        tf6.val('').css('height', 'auto');
        tf7.val(0).css('height', 'auto');
        tf8.val(0).css('height', 'auto');
        tf9.val('').css('height', 'auto');
        tf10.val('').css('height', 'auto');
        tf11.val('').css('height', 'auto');
        tf12.val('').css('height', 'auto');
        tf13.val('').css('height', 'auto');
        tf14.val(0).css('height', 'auto');

        fdp8_ = tf4.attr('id');
        tf4.attr('id', fdp8_ + id);

        fdp9_ = tf5.attr('id');
        tf5.attr('id', fdp9_ + id);

        fdp10_ = tf6.attr('id');
        tf6.attr('id', fdp10_ + id);

        fdp11_ = tf7.attr('id');
        tf7.attr('id', fdp11_ + id);

        fdp12_ = tf8.attr('id');
        tf8.attr('id', fdp12_ + id);

        fdp13_ = tf9.attr('id');
        tf9.attr('id', fdp13_ + id);

        fdp14_ = tf10.attr('id');
        tf10.attr('id', fdp14_ + id);

        fdp15_ = tf11.attr('id');
        tf11.attr('id', fdp15_ + id);

        fdp16_ = tf12.attr('id');
        tf12.attr('id', fdp16_ + id);

        fdp17_ = tf13.attr('id');
        tf13.attr('id', fdp17_ + id);

        fdp18_ = tf14.attr('id');
        tf14.attr('id', fdp18_ + id);

        var img = $('<img></img>', {
            src: "https://test.newui.myddf.info/images/close.png",
            class: "f_deleteFilter1",
            width: 18,
            height: 18,
            style: "cursor:  pointer;",
            alt: "Delete",
            title: "Delete"
        });
        new_div1.find('span.f_deleteimg').append(img);

        id++;
    });

    $(".f_deleteFilter1").live('click', function() {
        $(this).closest('tr').remove();
        id--;
    });

    $("#cmb_tax").change(function () {
      var tax = $(this).val();
      var leg = $('#cmb_ledger option:selected').text(); 
      var leg_id = $('#cmb_ledger').val(); 

       if(tax != '' && leg_id != '') {         
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_tax",
            data : {tax: tax, leg: leg},
            success: function(ret){
                ret = jQuery.parseJSON(ret);
                var cgst = ret['cgst'];
                var igst = ret['igst'];
                var sgst = ret['sgst'];
                $('#txt_sgst').val(sgst);
                $('#txt_igst').val(igst);
                $('#txt_cgst').val(cgst);
                 calc();
                
            }
        });
      }else{
            $('#txt_sgst').val(0);
            $('#txt_igst').val(0);
            $('#txt_cgst').val(0);
            calc(); 
      }
    });

$("#cmb_ledger").change(function () {
      var tax = $("#cmb_tax").val();
      var leg = $('#cmb_ledger option:selected').text(); 
       var leg_id = $('#cmb_ledger').val(); 
       if(tax != '' && leg_id != '') {       
        $.ajax({
            url  :"<?php echo site_url(); ?>/entry/get_tax",
            data : {tax: tax, leg: leg},
            success: function(ret){

                ret = jQuery.parseJSON(ret);
                var cgst = ret['cgst'];
                var igst = ret['igst'];
                var sgst = ret['sgst'];
                $('#txt_sgst').val(sgst);
                $('#txt_igst').val(igst);
                $('#txt_cgst').val(cgst);
                calc();
           
          }
        })        
    }else{
        $('#txt_sgst').val(0);
        $('#txt_igst').val(0);
        $('#txt_cgst').val(0); 
        calc();
  }
 });  
  function calc(){
    var l = 0;
   $(".tf14").each(function() {
   var m =  $(this).val();
   l = parseInt(l) + parseInt(m);
});
  
  var sgst = $('#txt_sgst').val();
  var sgst_val = parseInt(sgst) / 100;
  var sgst_totals = l*sgst_val;
  var sgst_total = sgst_totals.toFixed(2);

  var igst = $('#txt_igst').val();
  var igst_val = parseInt(igst) / 100;
  var igst_totals = l*igst_val;
  var igst_total = igst_totals.toFixed(2);

  var cgst = $('#txt_cgst').val();
  var cgst_val = parseInt(cgst) / 100;  
  var cgst_totals = l*cgst_val;
  var cgst_total = cgst_totals.toFixed(2);
  var final_totls = sgst_totals + igst_totals + cgst_totals + l;
  var final_totl = final_totls.toFixed(2);

  $('#txt_total_sgst').val(sgst_total);
  $('#txt_total_igst').val(igst_total);
  $('#txt_total_cgst').val(cgst_total);
  $('#txt_final_total').val(final_totl);  
  }
  var maxDate = new Date('03-30-2018');
  var minDate = new Date('04-01-2017');
   
 $('.input-group.date').datepicker({format: "dd-mm-yyyy", autoclose: true,defaultDate: new Date()});
  $('.input-group.date').datepicker('setStartDate', minDate);
  $('.input-group.date').datepicker('setEndDate', maxDate); 
    </script>
</body>
</html>