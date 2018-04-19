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
        <li style="color: red;">MANAGE USER</li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>            
    </ol> 
     <div id="profilestatus" style="text-align: right;"><a href="<?php echo site_url('/master/create_tax');?>" alt="ADD TAX" title="ADD TAX"><img src="https://test.newui.myddf.info/images/add.png" alt="ADD TAX" title="ADD TAX"/> ADD TAX</a></div>   
<table id="myTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Tax Type</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Export</th>
                <th>Others</th>
                <th class="no-sort">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($company_tax as $val) {
                
        ?>
            <tr>
                <td><?php echo $val['TAXTYPE'] ;?></td>
                <td><?php echo $val['CGST'] ;?></td>
                <td><?php echo $val['SGST'] ;?></td>
                <td><?php echo $val['IGST'] ;?></td>
                <td><?php echo $val['EXPORT'] ;?></td>
                <td><?php echo $val['OTHERS'] ;?></td>
                <td><a href="<?php echo site_url('/master/edit_tax/'.$val['ID']);?>"><span class="fa fa-pencil-square-o"></span></a></td>
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