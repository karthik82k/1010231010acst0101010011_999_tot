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
        <li style="color: red;">BANK BOOK</li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>           
    </ol> 
       
<table  class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <?php
           if(!empty($bank_book)){           
          ?>
            <tr>
                <th class="no-sort">Account Name</th>
                <th class="no-sort">Opening Balance</th>
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
          $final_close = 0;
          $a = 0;
            foreach ($bank_book as $val) {
               $opening_bal = $val['OPENING'];
              
               $balance_row = 0;
               $balance = 0;
               $account = $val['ACCOUNTDESC'];
               $total_dr =  $total_dr + round($val['DEBITS'],2);
               $total_cr =  $total_cr + round($val['CREDITS'],2);
               
                if($opening_bal < 0){
               $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2) + abs($opening_bal);
               $balance_total =  $total_cr - $total_dr + abs($opening_bal);
             }else{
              $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2) - abs($opening_bal);
              $balance_total =  $total_cr - $total_dr - abs($opening_bal);
             }
               if($balance_total >=0){               
                if($balance_total == 0){
                   $closing = $balance_total;
                 }else{
                  $closing = $balance_total.' Cr';
                 } 
               }else{      
                if($opening_bal < 0){
                $balance_total = $total_dr - $total_cr - $opening_bal ;
               }else{
                $balance_total = $total_dr - $total_cr + $opening_bal ;
               }           
                //$balance_total = $total_dr - $total_cr;
                 $closing = $balance_total.' Dr';
               }

               if($balance >=0){               
                if($balance == 0){
                  $balance_row = $balance;
                 }else{
                  $balance_row = $balance.' Cr'; 
                 } 
               }else{   

               if($opening_bal < 0){
                $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2) - $opening_bal ;
               }else{
                $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2) + $opening_bal ;
               }        
                             
                // $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2);
                 $balance_row = $balance.' Dr';
               }
               $debit = round($val['DEBITS'],2);
               $credit = round($val['CREDITS'],2);
               $type_op = 'Dr';
              
              if($opening_bal < 0){
                $type_op = 'Cr';
              }

              if($balance_row > 0) {
                 $balance_row1 = explode(' ', $balance_row);
              $close_cr = $balance_row1[0];
              if($balance_row1[1] == 'Cr'){
                
                $final_close = $final_close + $close_cr * -1;
              }else{
                 $final_close = $final_close + $close_cr;
              }
              }
             
        ?>
            <tr>
                
                <td><?php echo $val['ACCOUNTDESC'];?></td>
                <td><?php echo round($opening_bal,2).' '.$type_op;?></td>
                <td><?php echo  round($val['DEBITS'],2);?></td>
                <td><?php echo round($val['CREDITS'],2);?></td>
               <td><?php echo $balance_row;if($debit == 0 &&  $credit == 0){
                 echo '&nbsp;&nbsp;View Details';
              }else{
                 ?>
               &nbsp;&nbsp;<a href="<?php echo site_url('/report/bank_book/'.$val['ACCOUNT_ID'].'/?close='.urlencode($opening_bal));?>">View Details</a>
               <?php
             }
             ?></td>
            </tr>
            <?php
        }
            ?>
            <tr>                
                <td><b>Total</b></td>
                 <td></td>
                <td><b><?php echo $total_dr;?></b></td>
                <td><b><?php echo $total_cr;?></b></td>
               <td><b><?php 
                  if($final_close < 0) {
                   $a = -1 * $final_close;
                   echo $a.' Cr';
                  }else{
                     echo $final_close.' Dr';
                  }
                  ?></b></td>
            </tr>
            <?php
          }else if(!empty($bank_book_details)){
            ?>
            <tr>
            <th colspan="4" style="text-align: center; font-weight: bold; font-size: 16px;" >
            <?php
            $type_op = 'Dr';
              if($opening_bal < 0){
                $type_op = 'Cr';
              }

             echo 'OPENING BALANCE : '.abs($opening_bal).' '.$type_op;
             $opening_bal_row = abs($opening_bal).' '.$type_op;?>
            </th>
            </tr>
            <tr>
                <th class="no-sort">Month</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Closing Balance</th>
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
          // $balance_total = 0;
           if($opening_bal < 0){
            // $balance_row = -1 * abs($opening_bal);
            $balance_total = -1 * abs($opening_bal);
          }else{
           
            $balance_total = $opening_bal;
           //  $balance_row =  $opening_bal;
          }
            foreach ($bank_book_details as $val) {
               $balance_row = 0;
               $balance = 0;
               $account = $val['MONTH'];
               $total_dr =  $total_dr + round($val['DEBITS'],2);
               $total_cr =  $total_cr + round($val['CREDITS'],2);
               $balance_total = $balance_total + $total_cr - $total_dr;
                if($opening_bal < 0){
                  $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2) + abs($opening_bal);
                }else{           
                  $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2) - abs($opening_bal);
                }
              // $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2);
               if($balance_total >=0){   
                if($balance_total == 0){
                   $closing = $balance_total;
                 }else{
                  $closing = $balance_total.' Cr';
                 }             
               
               }else{               
                $balance_total = $total_dr - $total_cr;
                 $closing = $balance_total.' Dr';
               }

               if($balance >=0){    
                 if($balance == 0){
                  $balance_row = $balance;
                 }else{
                  $balance_row = $balance.' Cr'; 
                 }           
                
               }else{               
                 //$balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2);

                if($opening_bal < 0){  
                  $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2) - abs($opening_bal); 
              }else{ 
               $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2) + abs($opening_bal);

                } 
                 $balance_row = $balance.' Dr';
               }

               $debit = round($val['DEBITS'],2);
               $credit = round($val['CREDITS'],2);
        ?>
            <tr>
                
                <td><?php echo $val['Name'];?></td>
                <td><?php echo  round($val['DEBITS'],2);?></td>
                <td><?php echo round($val['CREDITS'],2);?></td>
               <td><?php  echo $balance_row;
               if($debit == 0 &&  $credit == 0){
                 echo 'View Details';
              }else{
                ?>&nbsp;&nbsp;<a href="<?php echo site_url('/report/bank_book/'.$ACCOUNT_ID.'/'.$val['MONTH'].'/?close='.urlencode($opening_bal_row));?>">View Details</a>
                <?php
               
              }
                ?>
                </td>
            </tr>
            <?php
            $opening_bal_row =  $balance_row; 
        }
            ?>
            <tr>                
                <td><b>Total</b></td>
                <td><b><?php echo $total_dr;?></b></td>
                <td><b><?php echo $total_cr;?></b></td>
               <td><b><?php echo $balance_row;?></b></td>
            </tr>
          <?php            
          }else if(!empty($bank_book_month)){
            ?>
              <tr>
            <th colspan="4" style="text-align: center; font-weight: bold; font-size: 16px;" >
            <?php
           

             echo 'OPENING BALANCE : '.$opening_bal;

             $op = explode(' ', $opening_bal);
             if($op[1] == 'Cr'){
              $opening_bal1 = -1 * $op[0];
             }else{
              $opening_bal1 =  $op[0];
             }

             ?>
            </th>
             <tr>
                <th class="no-sort">Date</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Closing Balance</th>
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
            foreach ($bank_book_month as $val) {
              $balance_row = 0;
               $balance = 0;
               $account = $val['DATE'];
               $total_dr =  $total_dr + round($val['DEBITS'],2);
               $total_cr =  $total_cr + round($val['CREDITS'],2);
               
                 if($opening_bal1 < 0){
            $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2) + abs($opening_bal1);
            $balance_total =  $total_cr - $total_dr + abs($opening_bal1);
          }else{
           
           $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2) - abs($opening_bal1);
           $balance_total =  $total_cr - $total_dr - abs($opening_bal1);
          }
              // $balance =  round($val['CREDITS'],2) - round($val['DEBITS'],2);
               if($balance_total >=0){   
                if($balance_total == 0){
                   $closing = $balance_total;
                 }else{
                  $closing = $balance_total.' Cr';
                 }             
               
               }else{  
                if($opening_bal1 < 0){
                   $balance_total = $total_dr - $total_cr - abs($opening_bal1);
                }else{
                   $balance_total = $total_dr - $total_cr + abs($opening_bal1);
                }             
               
                 $closing = $balance_total.' Dr';
               }

               if($balance >=0){    
                 if($balance == 0){
                  $balance_row = $balance;
                 }else{
                  $balance_row = $balance.' Cr'; 
                 }           
                
               }else{ 
               if($opening_bal1 < 0){
                 $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2) - abs($opening_bal1);
               }else{
                 $balance =  round($val['DEBITS'],2) - round($val['CREDITS'],2) + abs($opening_bal1);
               }              
                
                 $balance_row = $balance.' Dr';
               }
        ?>
            <tr>
                
                <td><?php echo date("d-M-Y",strtotime($val['DATE']));?></td>
                <td><?php echo  round($val['DEBITS'],2);?></td>
                <td><?php echo round($val['CREDITS'],2);?></td>
               <td><?php echo $closing;?></td>
            </tr>
            <?php
        }
            ?>
            <tr>                
                <td><b>Total</b></td>
                <td><b><?php echo $total_dr;?></b></td>
                <td><b><?php echo $total_cr;?></b></td>
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