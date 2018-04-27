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
        <li style="color: red;">INVENTORY REPORT</li>   
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>          
    </ol> 
       
<table  class="table table-striped table-bordered" cellspacing="0" width="100%">        
        
        <?php
            if(!empty($inventory_report)){
           ?>          
           <thead>
           <tr>
            <th colspan="11" style="text-align: center;">INVENTORY REPORT</th>
          </tr>
          </thead>
           <tbody>
         
             <tr>
                <th class="no-sort">HSN CODE</th>
                <th class="no-sort">ITEM CODE</th>                
                <th class="no-sort">ITEM </th>
                <th class="no-sort">OPENING STOCK </th>
                <th class="no-sort">OPENING VALUE </th>
                <th class="no-sort">PURCHASE </th>
                <th class="no-sort">SALES </th>
                <th class="no-sort">CLOSING STOCK </th>
                <th class="no-sort">UNIT</th>
                <!--<th class="no-sort">AVG. RATE</th> -->
                <th class="no-sort">CLOSING VALUE</th>
                <th class="no-sort">ITEM GROUP </th>
            </tr>
            </thead>
            <tbody>
            <?php
           
          foreach ($inventory_report as  $value) {
            
            $hsn = $value['HSNCODE'];
            if($hsn == 'NULL'){
                $hsn = '';
            }   
            $item = $value['NAME'];      
            $item_code = $value['ITEMCODE']; 
            $opening_stock = $value['OPSTOCK']; 
            $opening_value = $value['OPENINGVALUE']; 
            $purchase = $value['PURCHASE']; 
            $sales = $value['SALES']; 
            $closing_stock = $value['CLOSINGSTOCK']; 
            $unit = $value['UNIT']; 
            $closing_value = $value['CLOSINGVALUE']; 
            $group_name = $value['GROUPNAME']; 

         
          
          ?>
              <tr>               
                <td><?php echo $hsn;?></td>
                 <td><?php echo $item_code;?></td>
                <td><?php echo $item;?></td>
                <td><?php echo $opening_stock;?></td>
                <td><?php echo $opening_value;?></td>
                <td><?php echo $purchase;?></td>
                <td><?php echo $sales;?></td>
                <td><?php echo $closing_stock;?></td>                
                <td><?php echo $item;?></td>
                <td><?php echo $closing_value;?></td>   
                <td><?php echo $group_name;?></td>   
                
            </tr>
            <?php
          }
           
          }else{
            ?>
            <thead>
           <tr>
            <th  style="text-align: center;">No ITEM</th>
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