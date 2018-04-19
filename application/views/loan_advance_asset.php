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

    .well {
        padding: 5px;
    }
</style>
<div class="container category" style="min-height: 560px;">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">LOANS & ADVANCES ( ASSETS)</li>   
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
       
<table  class="table table-striped table-bordered" cellspacing="0" width="100%">        
        <?php       
           if(!empty($loan_advance_asset)){
          ?>
          <thead>
            <tr>
                <th class="no-sort">Description</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Balance</th>
            </tr>
        </thead>
        <tbody>
        <?php
          $closing = 0;
          $closing_cr = 0;
          $closing_dr = 0;
          $total_cr = 0;
          $total_dr = 0;
          $balance_total = 0;
            foreach ($loan_advance_asset as $val) {
              $credit =0;
              $debit =0;
               $balance_row = 0;
               $balance = 0;
               $account = $val['ACCOUNTDESC'];
               $account_type = $val['ACCOUNTTYPE'];
               if($account_type == 'Debit'){
                $debit = $val['OPBALANCE'] + $val['DEBITS'];
                $credit =  $val['CREDITS'];
               }else{
                $credit = $val['OPBALANCE'] + $val['CREDITS'];
                 $debit =  $val['DEBITS'];
               }
               $total_dr =  $total_dr + round($debit,2);
               $total_cr =  $total_cr + round($credit,2);
               $balance_total =  $total_cr - $total_dr;
               $balance =  round($credit,2) - round($debit,2);
               if($balance_total >=0){   
                if($balance_total == 0){
                   $closing = $balance_total;
                 }else{
                  $closing = number_format($balance_total,2).' Cr';
                 }             
               
               }else{               
                $balance_total = $total_dr - $total_cr;
                 $closing = number_format($balance_total,2).' Dr';
               }

               if($balance >=0){    
                 if($balance == 0){
                  $balance_row = $balance;
                 }else{
                  $balance_row = number_format($balance,2).' Cr'; 
                 }           
                
               }else{               
                 $balance =  round($debit,2) - round($credit,2);
                 $balance_row = number_format($balance,2).' Dr';
               }
              // $debit = round($val['DEBITS'],2);
               //$credit = round($val['CREDITS'],2);
        ?>
            <tr>
                
                <td><?php echo $val['ACCOUNTDESC'];?></td>
                <td><?php echo  number_format($debit,2);?></td>
                <td><?php echo number_format($credit,2);?></td>
               <td><?php echo $balance_row;
                   if($debit == 0 &&  $credit == 0){
                 echo '&nbsp;&nbsp;View Details';
              }else{
                 ?>

               &nbsp;&nbsp;<a href="#">View Details</a>
               <?php
             }
             ?>
               </td>
            </tr>
            <?php
        }
            ?>
            <tr>                
                <td><b>Total</b></td>
                <td><b><?php echo number_format($total_dr,2);?></b></td>
                <td><b><?php echo number_format($total_cr,2);?></b></td>
               <td><b><?php echo $closing;?></b></td>
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