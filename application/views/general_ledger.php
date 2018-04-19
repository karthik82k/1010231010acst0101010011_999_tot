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
        <li style="color: red;">GENERAL LEDGER</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>           
    </ol> 
        <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_salesorder" id="frm_salesorder" action="<?php echo site_url('entry/add_salesorder');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">
              <div class="col-md-12 col-sm-12 col-xs-12">            
               
                <label for="emirate" class="col-md-1 control-label" style="margin-left:0px; margin-bottom:0px; margin-top:0px; margin-right:0px; text-align:right; ">Ledger<span class="textspandata">*</span></label>
                    <div class="col-md-8 col-sm-9 col-xs-9">                   
                         <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Ledger</option>
                          <?php foreach ($ledger as $row) {
                            if($ledger_id == $row['Account_Id']){
                              echo "<option value='".$row['Account_Id']."' selected>".$row['LedgerAccount']."</option>"; 
                            }else{
                              echo "<option value='".$row['Account_Id']."'>".$row['LedgerAccount']."</option>"; 
                            }
                            
                          }?> 
                         </select>
                    </div>
                
                </div>

               
            </div>
            
            </fieldset>
            </div>
            </form>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="height: 20px;"> </div>
           <table  class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead> 
          <?php
          if(!empty($ledger_report)){
            ?>
            <tr>
            <th colspan="5" style="text-align: center; font-weight: bold; font-size: 16px;" >
            <?php echo 'OPENING BALANCE : '.$opening_bal;?>
            </th>
            </tr>      
            <tr>
                <th class="no-sort">Month</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Closing Balance</th>
                <th class="no-sort">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php

          $closing = 0; 
          $total_closing = 0;          
          $total_cr = 0;
          $total_dr = 0;

            foreach ($ledger_report as $val) {
              $closing = $val['CLOSINGBALANCE'];
              $type = $val['TYPE'];
               $total_closing = $closing + $total_closing;
               $total_cr = $val['CREDIT'] +  $total_cr;
               $total_dr = $val['DEBIT'] +  $total_dr;
        ?>
            <tr>
                
                <td><?php echo $val['MONTHNAME'].' '.$val['YEAR'];?></td>
                <td><?php echo  round($val['DEBIT'],2);?></td>
                <td><?php echo round($val['CREDIT'],2);?></td>
                <td><?php if($val['CLOSINGBALANCE'] != NULL){echo round($val['CLOSINGBALANCE'],2).' '.$val['TYPE'];}else{
                  echo $opening_bal;
                  } ?></td>
                <td><?php if($closing != '' &&  $type != ''){
                  ?>
                  <a href="<?php echo site_url('/report/general_ledger/'.$val['ACCOUNTID'].'/'.$val['ID'].'/'.$val['YEAR']);?>">View Details</a>
                  <?php
                }else{
                  echo "View Details";
                }
                ?>
                </td>
            </tr>
            <?php
        }
            ?>
            <tr>
            <td><b>Total</b></td>
                <td><b><?php echo round($total_dr,2);?></b></td>
                <td><b><?php echo round($total_cr,2);?></b></td>
                <td><b><?php echo round($total_closing,2);?></b></td>
                <td>Closing Balance</td>
            </tr>
            <?php
          }elseif(!empty($ledger_report_details)){
             ?>      
            <tr>
                <th class="no-sort">Date</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Closing Balance</th>
                <th class="no-sort">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php

          $closing = 0; 
          $total_closing = 0;          
          $total_cr = 0;
          $total_dr = 0;

            foreach ($ledger_report_details as $val) {
              $closing = $val['CLOSINGBALANCE'];
              $type = $val['TYPE'];
               $total_closing = $closing + $total_closing;
               $total_cr = $val['CREDIT'] +  $total_cr;
               $total_dr = $val['DEBIT'] +  $total_dr;
        ?>
            <tr>
                
                <td><?php echo date("d-M-Y",strtotime($val['DATE']));?></td>
                <td><?php echo  round($val['DEBIT'],2);?></td>
                <td><?php echo round($val['CREDIT'],2);?></td>
                <td><?php echo round($val['CLOSINGBALANCE'],2).' '.$val['TYPE'];?></td>
                <td>View Details</td>
            </tr>
            <?php
        }
            ?>
            <tr>
            <td><b>Total</b></td>
                <td><b><?php echo round($total_dr,2);?></b></td>
                <td><b><?php echo round($total_cr,2);?></b></td>
                <td><b><?php echo round($total_closing,2);?></b></td>
                <td></td>
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