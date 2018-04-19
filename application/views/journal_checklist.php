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
        <li style="color: red;">JOURNAL CHECKLIST</li>   
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
       
<table  class="table table-striped table-bordered" cellspacing="0" width="100%">        
        <?php       
           if(!empty($stock_journal)){
          ?>
          <thead>
            <tr>
                <th class="no-sort">Month</th>
                <th class="no-sort">Amount</th>
                <th class="no-sort"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($stock_journal as $val) {              
        ?>
            <tr>
                
                <td><?php echo $val['MONTHNAME'].' '.$val['year'];?></td>
                <td><?php echo $val['Amount'];?></td>
               <td><a href="<?php echo site_url('/mis_report/journal_checklist/'.$val['month']);?>">View Details</a></td>
            </tr>
            <?php
        }
            
           
          }else if(!empty($stock_journal_details)){
            ?>
          <thead>
            <tr>
                <th class="no-sort">Voucher Id</th>
                <th class="no-sort">Amount</th>
                <th class="no-sort"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($stock_journal_details as $val) {
             
        ?>
            <tr>
                
                <td><?php echo $val['PREFIX'].'00000'.$val['VOUCHER_ID'];?></td>
                <td><?php echo  $val['Amount'];?></td>
               <td><a href="<?php echo site_url('/mis_report/journal_checklist/'.$val['MONTH'].'/'.$val['VOUCHER_ID'].'/?pre='.urlencode($val['PREFIX']));?>">View Details</a></td>
            </tr>
            <?php
        }
      
                       
          }else if(!empty($stock_journal_prefix)){
           ?>          
           <thead>
           <tr>
            <th colspan="6" style="text-align: center;">STOCK JOURNAL CHECKLIST</th>
          </tr>
          </thead>
           <tbody>
          <tr>
            <td width="10%"><b>Voucher No</b></td><td><?php echo $voucher_pre;?></td>
            <td><b>Refernce No</b></td><td><?php echo $bill_no;?></td>
            <td width="10%"><b>Date</b></td><td><?php echo $date;?></td>
          </tr>           
           <tr>
            <td colspan="6">
            <table  class="table table-striped table-bordered">
                <thead>
             <tr>
                <th class="no-sort">PARTICULARS</th>
                <th class="no-sort">DEBIT AMOUNT</th>
                <th class="no-sort">CREDIT AMOUNT</th>
            </tr>
            </thead>
            <tbody>
            <?php
           
          foreach ($stock_journal_prefix as  $value) {

            $type = $value['TYPE'];
            $debit = 0;
            $credit = 0;
            if($type == 'Credit'){
              $credit = $value['AMOUNT'];
            }else{
              $debit = $value['AMOUNT'];
            }
            

          ?>
              <tr>
                <td><?php echo $value['ACCOUNT'];?></td>
                <td><?php echo $debit;?></td>  
                <td><?php echo $credit;?></td>   
                
            </tr>
            <?php
          }
            ?>
            <tr>
              <td colspan="6">
               <b>Narration :</b><br>
               <?php echo $narration;
               ?>
              </td>
              </tr>
              </tbody>
            </table>
            
           </td>
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