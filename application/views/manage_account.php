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
        <li style="color: red;">MANAGE PARTY ADDRESS</li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>        
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>              
    </ol> 
     <div id="profilestatus" style="text-align: right;"><a href="<?php echo site_url('/definition/add_accounts');?>" alt="ADD ACCOUNT" title="ADD ACCOUNT"><img src="https://test.newui.myddf.info/images/add.png" alt="ADD ACCOUNT" title="ADD ACCOUNT"/> ADD ACCOUNT</a></div>   
<table id="myTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Account Name</th>
                <th style="width: 150px">Account Group</th>
                <th style="width: 170px">Created Financial Year</th>
                <th style="width: 110px">Current Year <br> Opening Balance</th>
                <th >Closing Balance</th>                
                <th class="no-sort">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($account_list as $val) {
                $type = '';
                $type_op = '';
        ?>
            <tr>
                <td><?php echo $val['ACCOUNTDESC'];?></td>
                <td><?php echo $account_group_list[$val['GROUP_ID']];?></td>
                <td><?php echo $financial_year[$val['FINANCIALYEAR_ID']];?></td>
                <td style="text-align: right;"><?php if($val['OPBALANCE'] > 0){
                 $type_op = 'Dr';
                }else if($val['OPBALANCE'] < 0){
                 $type_op = 'Cr';
                }
                echo number_format(abs($val['OPBALANCE']),2).' '.$type_op;?></td>
                <td style="text-align: right;"><?php if($val['CURRENTBALANCE'] > 0){
                 $type = 'Dr';
                }else if($val['CURRENTBALANCE'] < 0){
                 $type = 'Cr';
                }


                echo number_format(abs($val['CURRENTBALANCE']),2).' '.$type;?></td>
                <td><a href="<?php echo site_url('/definition/edit_accounts/'.$val['ID']);?>"><span class="fa fa-pencil-square-o"></span></a>&nbsp;&nbsp;<a href="<?php echo site_url('/definition/delete_account/'.$val['ID']);?>" onclick="if (confirm('Delete selected Account?')){return true;}else{event.stopPropagation(); event.preventDefault();};"><span class="fa fa-trash" aria-hidden="true"></span></a></td>
            </tr>
            <?php
        }
            ?>
        </tbody>
    </table>

 </div>
 </div>

<div style="height:15px;"></div>                
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