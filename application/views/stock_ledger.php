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
        <li style="color: red;">STOCK LEDGER</li>   
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li> 
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #519CFF;"><?php echo $comp_name ;?></b></li>         
    </ol> 
        <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_salesorder" id="frm_salesorder" action="" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">
              <div class="col-md-12 col-sm-12 col-xs-12">            
               
                <label for="emirate" class="col-md-1 control-label" style="margin-left:0px; margin-bottom:0px; margin-top:0px; margin-right:0px; text-align:right; ">Item<span class="textspandata">*</span></label>
                    <div class="col-md-8 col-sm-9 col-xs-9">                   
                         <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Item</option>
                          <?php foreach ($item as $row) {
                            if($item_id == $row['ID']){
                              echo "<option value='".$row['ID']."' selected>".$row['NAME']."</option>"; 
                            }else{
                              echo "<option value='".$row['ID']."'>".$row['NAME']."</option>"; 
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
                <th class="no-sort">Month</th>
                <th class="no-sort">Item Name</th>
                <th class="no-sort">Inward</th>
                <th class="no-sort">Outward</th>
                <th class="no-sort">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php

        

            foreach ($ledger_report as $val) {
             
        ?>
            <tr>
                
                <td><?php echo $val['MONTH'].' '.$val['YEAR'];?></td>
                <td><?php echo  $val['ITEM_NAME'];?></td>
                <td><?php echo $val['INCOMING'];?></td>
                <td><?php echo $val['OUTGOING']?></td>
                <td><a href="<?php echo site_url('/inventory/stock_ledger/'.$val['ITEM_ID'].'/'.$val['MONTH']);?>">View Details</a></td>
            </tr>
            <?php
        }
           
          }elseif(!empty($ledger_report_details)){
             ?>      
            <tr>
                <th class="no-sort">Date</th>
                <th class="no-sort">Voucher Id</th>
                 <th class="no-sort">Item Name</th>
                 <th class="no-sort">HSN Code</th>
                <th class="no-sort">Inward</th>
                <th class="no-sort">Outward</th>
                <th class="no-sort">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php

         

            foreach ($ledger_report_details as $val) {
             $month_name = $month;
        ?>
            <tr>
                
                <td><?php echo date("d-M-Y",strtotime($val['DATE']));?></td>
                <td><?php echo  $val['PREFIX'].'0000'.$val['VOUCHER_ID'];?></td>
                 <td><?php echo  $val['ITEM_NAME'];?></td>
                  <td><?php echo  $val['HSNCODE'];?></td>
                <td><?php echo $val['INCOMING'];?></td>
                <td><?php echo $val['OUTGOING']?></td>
                 <td><a href="<?php echo site_url('/inventory/stock_ledger/'.$val['ITEM_ID'].'/'.$month_name.'?pre='.urlencode($val['PREFIX']).'&voucher_id='.$val['VOUCHER_ID']);?>">View Details</a> </td>
            </tr>
            <?php
        }
           

          }elseif(!empty($ledger_report_prefix)){
             ?>      
            <tr>
                <th class="no-sort">Date</th>
                <th class="no-sort">Voucher Id</th>
                 <th class="no-sort">Item Name</th>
                 <th class="no-sort">HSN Code</th>
                <th class="no-sort">Inward</th>
                <th class="no-sort">Outward</th>
                <th class="no-sort">Rate</th>
                <th class="no-sort">Amount</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($ledger_report_prefix as $val) {
        ?>
            <tr>
                
                <td><?php echo date("d-M-Y",strtotime($val['DATE']));?></td>
                <td><?php echo  $val['PREFIX'].'0000'.$val['VOUCHER_ID'];?></td>
                 <td><?php echo  $val['ITEM_NAME'];?></td>
                  <td><?php echo  $val['HSNCODE'];?></td>
                <td><?php echo $val['INCOMING'];?></td>
                <td><?php echo $val['OUTGOING']?></td>
                <td><?php echo $val['RATE']?></td>
                <td><?php echo $val['AMOUNT']?></td>
            </tr>
            <?php
        }
           

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
    window.location='<?php echo site_url(); ?>inventory/stock_ledger/' + ledger;
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