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
<script type="text/javascript">
  <?php
if(isset($_GET['msg'])){
  ?>
 // var msg = '<?php echo $_GET['msg'];?>'
  alert('Saved Successfully');
  window.location='<?php echo site_url(); ?>entry/manage_sales_quotation/';
  <?php
}
?>
</script>
<div class="container category" style="min-height: 560px;">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">MANAGE PAYMENT VOUCHER</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>         
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>             
    </ol> 
     <div id="profilestatus" style="text-align: right;">
       <?php
     if($islock != 1) {
      ?>
     <a href="<?php echo site_url('/entry/payment_voucher');?>" alt="ADD SALES QUOTATION" title="ADD SALES QUOTATION"><img src="https://test.newui.myddf.info/images/add.png" alt="ADD SALES QUOTATION" title="ADD SALES QUOTATION"/> ADD PAYMENT VOUCHER</a>
      <?php
     }
     ?>
     </div>   
<table id="myTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Receipt Voucher No</th>            
                <th>Financial Year</th>
                 <th>Total</th>
                <th class="no-sort">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($receipt_voucher as $val) { 
             $prefix_id = $val['PREFIX'].$val['VOUCHER_ID'];
             $no_cnt = 0;  
             if(isset($recp_cont[$prefix_id])){
              $no_cnt = $recp_cont[$prefix_id];  
             }
                       
        ?>
            <tr>
                <td><?php echo date("d-m-Y",strtotime($val['DATE']));?></td>
                <td><?php echo $val['PREFIX'].$val['V_ID'];?></td>
                <td><?php echo $financial_year_selected;?></td>
                <td><?php echo $val['Total'];?></td>
                <td><a href="<?php echo site_url('/entry/edit_payment_voucher/'.$val['VOUCHER_ID'].'/?pre='.urlencode($val['PREFIX']));?>"><span class="fa fa-pencil-square-o"></span></a>&nbsp;&nbsp;<?php if($no_cnt == 1){?><a href="<?php echo site_url('/entry/print_payment_voucher/'.$val['VOUCHER_ID'].'/'.'/?pre='.urlencode($val['PREFIX']));?>"><span class="fa fa-print" aria-hidden="true"></span></a><?php }?></td>
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