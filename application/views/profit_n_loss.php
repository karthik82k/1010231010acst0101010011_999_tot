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
        <li style="color: red;">PROFIT & LOSS A/C</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li> 
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>             
    </ol> 
        <div id="profilestatus" style="display:none;">&nbsp;</div>    
    
            <div class="col-md-12 col-sm-12 col-xs-12" style="height: 35px; text-align: center; font-size: 21px;"><b>Profit & Loss Account for the period ending <?php echo date('d-m-Y');?></b></div>
           <table  class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead> 
          <?php
          //echo '<pre>';
          //print_r($profit_loss);
           $expenditure_total_1 = 0;
             $expenditure_total_2 = 0;
             $income_total_1 = 0;
             $income_total_2 = 0; 
          if(!empty($profit_loss)){
            foreach ($profit_loss as $value) {
             if($value['GROUP_NAME'] == 'OPENING STOCK'){
              $opening_stock = $value['AMOUNT'];
             }

             if($value['GROUP_NAME'] == 'SALES'){
              $sales = $value['AMOUNT'];
             }

             if($value['GROUP_NAME'] == 'PURCHASE'){
              $purchase = $value['AMOUNT'];
             }

             if($value['GROUP_NAME'] == 'INCOME DIRECT'){
              $income_direct = $value['AMOUNT'];
             }

             if($value['GROUP_NAME'] == 'EXPENDITURE DIRECT'){
              $expenditure_direct = $value['AMOUNT'];
             }

             if($value['GROUP_NAME'] == 'Closing Stock'){
              $close_stock = $value['AMOUNT'];
              if($close_stock == NULL){
                  $close_stock = 0;
                }else{
                  $close_stock = $value['AMOUNT'];
                }
             }

              if($value['GROUP_NAME'] == 'GROSS  PROFIT'){
                $gross_profit = $value['AMOUNT'];
                if($gross_profit == NULL){
                  $gross_profit = 0;
                }else{
                  $gross_profit = $value['AMOUNT'];
                }
              
             }

             if($value['GROUP_NAME'] == 'GROSS LOSS'){
                $gross_loss = $value['AMOUNT'];
                if($gross_loss == NULL){
                  $gross_loss = 0;
                }else{
                  $gross_loss = $value['AMOUNT'];
                }
              
             }

             if($value['GROUP_NAME'] == 'EXPENDITURE INDIRECT'){
              $expenditure_indirect = $value['AMOUNT'];
             }

             if($value['GROUP_NAME'] == 'INCOME INDIRECT'){
              $income_indirect = $value['AMOUNT'];
             }
              
              if($value['GROUP_NAME'] == 'GROSS PROFIT B/F'){
              $net_profit_bf = $value['AMOUNT'];

                if($net_profit_bf == NULL){
                  $net_profit_bf = 0;
                }else{
                  $net_profit_bf = $value['AMOUNT'];
                }
                
             }

              if($value['GROUP_NAME'] == 'NET PROFIT C/F'){
              $net_profit_cf = $value['AMOUNT'];
              
                if($net_profit_cf == NULL){
                  $net_profit_cf = 0;
                }else{
                  $net_profit_cf = $value['AMOUNT'];
                }
                
             }

             if($value['GROUP_NAME'] == 'GROSS LOSS B/F'){
              $net_loss_bf = $value['AMOUNT'];
              
                if($net_loss_bf == NULL){
                  $net_loss_bf = 0;
                }else{
                  $net_loss_bf = $value['AMOUNT'];
                }
                
             }

              if($value['GROUP_NAME'] == 'NET LOSS'){
              $net_loss = $value['AMOUNT'];
               if($net_loss == NULL){
                  $net_loss = 0;
                }else{
                  $net_loss = $value['AMOUNT'];
                }
                 
             }

             
            }

            $expenditure_total_1 = $opening_stock + $purchase + $expenditure_direct +  $gross_profit;
             $expenditure_total_2 =  $net_loss_bf + $expenditure_indirect + $net_profit_cf;
             $income_total_1 = $sales + $income_direct + $close_stock +  $gross_loss;
             $income_total_2 = $net_profit_bf + $income_indirect + $net_loss;
            ?>      
            <tr style="background-color: #cccccc;">
                <th class="no-sort" colspan="2">EXPENDITURE</th>
                <th class="no-sort" colspan="2">INCOME</th>
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
            <td>OPENING STOCK </td><td><?php echo number_format($opening_stock,2);?></td><td>SALES </td><td> <?php echo number_format($sales,2);?> </td>
         </tr>
        <tr>
          <td>PURCHASE </td><td> <?php echo number_format($purchase,2);?> </td><td>INCOME DIRECT </td><td> <?php echo number_format($income_direct,2);?></td>
        </tr>
        <tr>
         <td>EXPENDITURE DIRECT </td><td><?php echo number_format($expenditure_direct,2);?>  </td><td>CLOSING STOCK</td><td><?php echo number_format($close_stock,2);?></td>
        </tr>
        <tr> <?php if($gross_profit == 0) {
          ?>
           <td></td><td></td>
          <?php
        }else{
        ?>
        <td>GROSS PROFIT </td><td><?php echo number_format($gross_profit,2);?> </td>
        <?php
        }
        ?>
        
        <?php if($gross_loss == 0) {
          ?>
          <td></td><td></td>
          <?php
        }else{
          ?>
          <td>GROSS LOSS </td><td><?php echo number_format($gross_loss,2);?> </td>
        <?php
        }
        ?>
        </tr>
        <tr style="font-weight: bold; background-color: #cccccc;">
         <td> </td><td><?php echo number_format($expenditure_total_1,2);?></td><td> </td><td><?php echo number_format($income_total_1,2);?></td>
        </tr>
        <tr>
           <?php if($net_loss_bf == 0) {
          ?>
          <td></td><td></td>
          <?php
        }else{
          ?>
          <td>GROSS LOSS B/F</td><td><?php echo number_format($net_loss_bf,2);?></td>
          <?php
          }?>
          <?php if($net_profit_bf == 0) {
          ?>
          <td></td><td></td>
          <?php
        }else{
          ?>
          <td>GROSS PROFIT B/F </td><td><?php echo number_format($net_profit_bf,2);?></td>
          <?php
          }?>
          
        </tr>
        <tr>
          <td>EXPENDITURE INDIRECT </td><td><?php echo number_format($expenditure_indirect,2);?></td><td>  INCOME INDIRECT </td><td><?php echo number_format($income_indirect,2);?></td>
        </tr>
        <tr>
          <?php if($net_profit_cf == 0) {
            ?>
         <td> </td><td></td>
         <?php
          }else{
            ?>
            <td>NET PROFIT C/F  </td><td><?php echo number_format($net_profit_cf,2);?></td>
            <?php
          }
          ?>
         
         <?php if($net_loss == 0) {
          ?>
         <td> </td><td></td>
         <?php
          }else{?>
         <td> NET LOSS </td><td><?php echo number_format($net_loss,2);?></td>
         <?php
       }
         ?>
         </tr>
        <tr style="font-weight: bold; background-color: #cccccc;">
         <td>TOTAL  </td><td><?php echo number_format($expenditure_total_2,2);?>  </td><td> TOTAL </td><td> <?php echo number_format($income_total_2,2);?></td>
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