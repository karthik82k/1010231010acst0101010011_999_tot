<?php $this->load->view('include/header');

  $role_id =  $this->session->userdata('user_role');
  $user_id =  $this->session->userdata('user_id');
  $name = $this->session->userdata('name');
  $comp_name = $this->session->userdata('company_name');  
  $financial_year_selected = $this->session->userdata('financial_year');
  $financial_year_id = $this->session->userdata('financial_id');
  $financial_year_from = $this->session->userdata('financial_from');
  $financial_year_to = $this->session->userdata('financial_to');
  $islock = $this->session->userdata('islock');
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

    .well {
        padding: 5px;
    }
</style>
<div class="container category" style="min-height: 560px;">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">EXPORT INVOICE</li>
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>           
    </ol> 
     <div id="profilestatus" style="text-align: right;">
     
     </div>   
<table id="myTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sales Voucher No</th>
                <th>Bill No</th>                
                <th>Account Name</th>
                             
                <th>Financial Year</th>
                   <th>Currency</th>
                    <th>Conv Rate</th>
                 <th>Total Amount</th>
                <th class="no-sort">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
          foreach ($export_bill as $val) {   
             $inr_total = number_format($val['Total'],2);         
        ?>
            <tr>
                <td><?php echo date("d-m-Y",strtotime($val['DATE']));?></td>
                <td><?php echo $val['PREFIX'].$val['V_ID'];?></td>
                <td><?php echo $val['PREFIX'].'/'.$val['REFNUM'];?></td>                
                <td><?php echo $val['ACCOUNT'];?></td>
                <td><?php echo $financial_year_selected;?></td>
                <td><?php echo $val['Export_currency'];?></td>
                <td><?php echo $val['CONVERTION_RATE'];?></td>
                <td><?php echo $inr_total;?></td>
                <td><a href="<?php echo site_url('/entry/export_invoice/'.$val['VOUCHER_ID'].'/?pre='.urlencode($val['PREFIX']));?>"><span class="fa fa-print" aria-hidden="true"></span></a></td>
            </tr>
        <?php
         }
        ?>
        </tbody>
    </table>

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