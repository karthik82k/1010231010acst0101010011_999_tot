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
        <li style="color: red;">TRIAL BALANCE</li> 
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; "><?php echo $comp_name ;?></b></li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>          
    </ol> 
       
<table  class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
          <th></th>
          <th colspan="2" style="text-align: center; font-size: 17px;">Opening Balance</th>
          <th colspan="2" style="text-align: center; font-size: 17px;">Current Period</th>
          <th colspan="2" style="text-align: center; font-size: 17px;">Closing Balance</th>
          </tr>
            <tr>
                <th class="no-sort">Account Name</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
                <th class="no-sort">Debit</th>
                <th class="no-sort">Credit</th>
            </tr>
        </thead>
        <tbody>
        <?php
          $closing = 0;
          $closing_cr = 0;
          $closing_dr = 0;
          $total_cr = 0;
          $total_dr = 0;
            foreach ($trail_balance as $val) {
               $account = $val['ACCOUNT'];
               $total_dr = round($val['DEBITOPENING'],2) + round($val['DEBITS'],2);
               $total_cr = round($val['CREDITOPENING'],2) + round($val['CREDITS'],2);
               $closing =  $total_cr - $total_dr;
               if($closing >=0){
                $closing_dr = 0;
                $closing_cr = $closing;
               }else{
                $closing_cr = 0;
                $closing_dr = $total_dr - $total_cr;
               }
        ?>
            <tr>
                
                <td><?php if($account == 'Total Amount'){
                  echo '<b>'.$val['ACCOUNT'].'</b>';
                } else{
                  echo $val['ACCOUNT'];
                }
                ?></td>
                <td><?php echo  round($val['DEBITOPENING'],2);?></td>
                <td><?php echo round($val['CREDITOPENING'],2);?></td>
                <td><?php 
                 if($account == 'Total Amount'){echo '<b>'.$val['DEBITS'].'</b>';}else{
                 ?>
                  <a href="<?php echo site_url('/report/trail_balance/'.$val['GROUP_ID']);?>"><?php echo round($val['DEBITS'],2);?></a>
                 <?php
                 }
                  ?></td>
                <td><?php if($account == 'Total Amount'){
                  echo '<b>'. round($val['CREDITS'],2).'</b>';
                  }else{
                    ?>
                 <a href="<?php echo site_url('/report/trail_balance/'.$val['GROUP_ID']);?>"><?php echo round($val['CREDITS'],2);?></a>
                <?php
                }?></td>
                <td><?php
                 if($account == 'Total Amount'){
                  echo '<b>'.round($closing_dr,2).'</b>';
                  }else{
                     echo round($closing_dr,2);
                  }
                  ?>                    
                  </td>
                <td><?php 
                if($account == 'Total Amount'){
                  echo '<b>'.round($closing_cr,2).'</b>';
                }else{
                 echo round($closing_cr,2) ;
                }

                  ?></td>
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