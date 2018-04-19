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
        <li style="color: red;">BALANCE SHEET</li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>           
    </ol> 
        <div id="profilestatus" style="display:none;">&nbsp;</div>    
    
            <div class="col-md-12 col-sm-12 col-xs-12" style="height: 35px; text-align: center; font-size: 21px;"><b>Balance Sheet As On <?php echo date('d-m-Y');?></b></div>
           <table  class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead> 
          <?php
          //echo '<pre>';
          //print_r($profit_loss);
           $asset_total = 0;
            $liability_total = 0;
            $share_captial_available = 0;
            $reserves_surplus_available = 0;
            $reserves_surplus = 0;
             $share_captial = 0;
             $partners_current_ac_available = 0;
             $partners_current_ac = 0;
          if(!empty($balance_sheet)){
            foreach ($balance_sheet as $value) {
              /* liability  starts */
            if($value['GROUP_NAME'] == 'SHARE CAPITAL'){
              $share_captial_available = 1;
              $share_captial = $value['AMOUNT'];
              if($share_captial == NULL){
                  $share_captial = 0;
                }else{
                  $share_captial = $value['AMOUNT'];
                }
             }

             $reverse = $value['GROUP_NAME'];
             $string = 'RESERVES & SURPLUS';
             if (strpos($reverse,$string) !== false){
              $reserves_surplus_available = 1;
              $reserves_surplus = $value['AMOUNT'];
              
             }


              if($value['GROUP_NAME'] == 'PARTNERS CURRENT ACCOUNT'){
              $partners_current_ac_available = 1;
              $partners_current_ac = $value['AMOUNT'];
              if($partners_current_ac == NULL){
                  $partners_current_ac = 0;
                }else{
                  $partners_current_ac = $value['AMOUNT'];
                }
              
             }

             if($value['GROUP_NAME'] == 'UNSECURED LOANS'){
              $unsecured_loan = $value['AMOUNT'];
              
                if($unsecured_loan == NULL){
                  $unsecured_loan = 0;
                }else{
                  $unsecured_loan = $value['AMOUNT'];
                }
                
             }

              if($value['GROUP_NAME'] == 'SECURED LOANS'){
              $secured_loan = $value['AMOUNT'];
              
                if($secured_loan == NULL){
                  $secured_loan = 0;
                }else{
                  $secured_loan = $value['AMOUNT'];
                }
                
             }
             
             if($value['GROUP_NAME'] == 'CURRENT LIABILITIES'){
              $current_liability = $value['AMOUNT'];
              
                if($current_liability == NULL){
                  $current_liability = 0;
                }else{
                  $current_liability = $value['AMOUNT'];
                }
                
             }

             if($value['GROUP_NAME'] == 'LOANS & ADVANCES'){
              $loan_advances = $value['AMOUNT'];
              
                if($loan_advances == NULL){
                  $loan_advances = 0;
                }else{
                  $loan_advances = $value['AMOUNT'];
                }
                
             }

             if($value['GROUP_NAME'] == 'NET PROFIT'){
              $net_profit = $value['AMOUNT'];
              
                if($net_profit == NULL){
                  $net_profit = 0;
                }else{
                  $net_profit = $value['AMOUNT'];
                }
                
             }


             /* liability  ends */
             /*assets starts */
             if($value['GROUP_NAME'] == 'CURRENT ASSETS'){
              $current_assets = $value['AMOUNT'];
               if($current_assets == NULL){
                  $current_assets = 0;
                }else{
                  $current_assets = $value['AMOUNT'];
                }
                 
             }

            if($value['GROUP_NAME'] == 'FIXED ASSETS'){
              $fixed_assets = $value['AMOUNT'];
               if($fixed_assets == NULL){
                  $fixed_assets = 0;
                }else{
                  $fixed_assets = $value['AMOUNT'];
                }
                 
             }
             
             if($value['GROUP_NAME'] == 'INVESTMENTS & DEPOSITS'){
              $investments_deposits = $value['AMOUNT'];
               if($investments_deposits == NULL){
                  $investments_deposits = 0;
                }else{
                  $investments_deposits = $value['AMOUNT'];
                }
                 
             }

           if($value['GROUP_NAME'] == 'LOANS & ADVANCES ( ASSETS)'){
              $loans_advances = $value['AMOUNT'];
               if($loans_advances == NULL){
                  $loans_advances = 0;
                }else{
                  $loans_advances = $value['AMOUNT'];
                }
                 
             }
             
             
             

             if($value['GROUP_NAME'] == 'Difference In Opening Trial Balance'){
              $net_loss = $value['AMOUNT'];
               if($net_loss == NULL){
                  $net_loss = 0;
                }else{
                  $net_loss = $value['AMOUNT'];
                }
                 
             }

             if($value['GROUP_NAME'] == 'CASH'){
              $cash = $value['AMOUNT'];
               if($cash == NULL){
                  $cash = 0;
                }else{
                  $cash = $value['AMOUNT'];
                }
                 
             }

             if($value['GROUP_NAME'] == 'BANK'){
              $bank = $value['AMOUNT'];
               if($bank == NULL){
                  $bank = 0;
                }else{
                  $bank = $value['AMOUNT'];
                }
                 
             }

             if($value['GROUP_NAME'] == 'SUNDRY DEBTORS'){
              $sundry_debtors = $value['AMOUNT'];
               if($sundry_debtors == NULL){
                  $sundry_debtors = 0;
                }else{
                  $sundry_debtors = $value['AMOUNT'];
                }
                 
             }

             if($value['GROUP_NAME'] == 'Closing Stock'){
              $closing_stock = $value['AMOUNT'];
               if($closing_stock == NULL){
                  $closing_stock = 0;
                }else{
                  $closing_stock = $value['AMOUNT'];
                }
                 
             }

             
            }
             /*assets ends */
              
            $asset_total = $net_loss + $current_assets + $fixed_assets + $investments_deposits + $loans_advances;
            $liability_total = $share_captial + $unsecured_loan + $secured_loan + $current_liability + $loan_advances + $net_profit + $reserves_surplus + $partners_current_ac;
            
            ?>      
            <tr style="background-color: #cccccc;">
                <th class="no-sort" colspan="2">LIABILITIES</th>
                <th class="no-sort" colspan="2">ASSETS</th>
            </tr>
        </thead>
        <tbody>
        <tr>
         <td><b>DESCRIPTION </b></td>
          <td><b>Amount</b></td>
          <td><b>DESCRIPTION </b></td>
          <td><b>Amount</b></td>
          </tr>
          <tr>
            <td>SHARE CAPITAL</td>
            <td>
              <?php
              if($share_captial > 0){
              ?>
                 <a href="<?php echo site_url('/report/profit_n_loss/');?>"><?php echo number_format($share_captial,2);?></a>
                <?php
              }else{
                echo number_format($share_captial,2);
              }
              ?>
             
            </td>
            <td>FIXED ASSETS</td>
            <td><?php
              if($fixed_assets > 0){
              ?>
              <a href="<?php echo site_url('/report/fixed_asset/');?>"><?php echo number_format( $fixed_assets,2);?></a>
              <?php
              }else{
                echo number_format( $fixed_assets,2);
              }
              ?>
          </td>
          </tr>
          <tr>
            <?php 
              if($reserves_surplus_available == 1 ){
            ?>
            <td>RESERVES & SURPLUS</td><td>
              <?php
              if(abs($reserves_surplus) > 0){
                ?>
                 <a href="<?php echo site_url('/report/profit_n_loss/');?>"><?php echo number_format($reserves_surplus,2);?></a></td>
              <?php
              }else{
                echo number_format($reserves_surplus,2);
              }
              ?>
            </td>
          <?php
            }else if($partners_current_ac_available == 1) {
          ?>
            <td>PARTNERS CURRENT ACCOUNT</td><td>
          <?php
              if(abs($partners_current_ac) > 0){
                ?>
                 <a href="<?php echo site_url('/report/profit_n_loss/');?>"><?php echo number_format($partners_current_ac,2);?></a></td>
                <?php
              }else{
                echo number_format($partners_current_ac,2);
              }
              ?></td>
          <?php
        }else{
          ?>
          <td></td><td></td>
          <?php
        }
        ?>
          <td>CURRENT ASSETS</td><td><?php
           if(abs($current_assets) > 0){
            ?>
              <a href="<?php echo site_url('/report/current_asset/');?>"><?php echo number_format($current_assets,2);?></a>
            <?php
           }else{
            echo number_format($current_assets,2);
           }
            ?></td> 
          </tr>
          <tr>
          <td>LOANS & ADVANCES</td>
          <td>
          <?php
          if(abs($loan_advances) > 0){
            ?><a href="<?php echo site_url('/report/loan_advance/');?>"><?php echo number_format($loan_advances,2);?></a>
              <?php
            }else{
              echo number_format($loan_advances,2);
            }

              ?>
            </td>
           <td>INVESTMENTS & DEPOSITS</td>
           <td> <?php
              if(abs($investments_deposits) > 0){
              ?>
                <a href="<?php echo site_url('/report/investments_deposit/');?>"><?php echo number_format($investments_deposits,2);?></a>
              <?php
              }else{
                echo number_format($investments_deposits,2);
              }
              ?>
              </td> 
          </tr>
          <tr>
          <td>SECURED LOANS</td>
          <td><?php
          if(abs($secured_loan) > 0){
            ?><a href="<?php echo site_url('/report/secured_loan/');?>"><?php echo number_format($secured_loan,2);?></a>
            <?php }else{
              echo number_format($secured_loan,2);
              }  ?>
            </td>
           <td>LOANS & ADVANCES ( ASSETS)</td><td>
           <?php
          if(abs($loans_advances) > 0){
            ?><a href="<?php echo site_url('/report/loan_advance_asset/');?>"><?php echo number_format($loans_advances,2);?></a>
            <?php }else{
              echo number_format($loans_advances,2);
              }  ?>
          </td> 
          </tr>
          <tr>
          <td>UNSECURED LOANS</td>
          <td><?php
          if(abs($unsecured_loan) > 0){
            ?><a href="<?php echo site_url('/report/un_secured_loan/');?>"><?php echo number_format($unsecured_loan,2);?></a>
            <?php }else{
              echo number_format($unsecured_loan,2);
              }  ?>
          </td>
          <?php 
          if($net_loss == 0 || $net_loss == NULL){
            ?>
             <td></td><td></td>
            <?php
          }else{
            ?>
             <td>Difference In Opening Trial Balance</td><td><?php echo number_format($net_loss,2);?></td>
            <?php
          }
          ?>
        
          </tr>
          <tr>
          <td>CURRENT LIABILITIES</td><td>
          <?php
          if(abs($current_liability) > 0){
            ?>
             <a href="<?php echo site_url('/report/current_liability/');?>"><?php echo number_format($current_liability,2);?></a>
            <?php
          }else{
            echo number_format($current_liability,2);
          }
          ?>
          
            
          </td>
         <td></td><td></td>
          </tr>
          <tr>
          <td style="height: 40px;"></td><td></td>
          <td></td><td></td>
          </tr>
        <tr style="font-weight: bold; background-color: #cccccc;">
         <td>TOTAL</td><td><?php echo number_format($liability_total,2);?></td>
         <td> TOTAL </td><td><?php echo number_format($asset_total,2);?></td>
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
 $("#cmb_account_group").change(function(){ 
  var ledger = $('#cmb_account_group').val();
    window.location='<?php echo site_url(); ?>report/general_ledger/' + ledger;
  });
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