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
        <li style="color: red;">GSTN FILLING REPORT</li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>        
    </ol> 
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:8px 8px 8px 8px;">
            <form name="frm_salesorder" id="frm_salesorder" action="<?php echo site_url('mis_report/export_excel_gstr');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">
              <div class="col-md-12 col-sm-12 col-xs-12">            
               
                <label for="emirate" class="col-md-3 control-label" style="margin-left:0px; margin-bottom:0px; margin-top:0px; margin-right:0px; text-align:right; ">GSTN FILLING for the Month <span class="textspandata">*</span></label>
                    <div class="col-md-5 col-sm-9 col-xs-9">                   
                         <select name="cmb_account_group" id="cmb_account_group" class="input-sm" style="width:96%;    border: 1px solid #555555 !important;">
                        <option value="" selected="selected">Select Month</option>
                        <?php foreach ($month_list as $row) {
                            if($month == $row['Name']){
                              echo "<option value='".$row['Name']."' selected>".$row['Name']."</option>"; 
                            }else{
                              echo "<option value='".$row['Name']."'>".$row['Name']."</option>"; 
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
        <div>
         <br>
    <?php
         if($month !='' && !empty($gstr3b) ){
        ?>
<center>
            <input type="submit" name="export_excel" id="export_excel" class="btn btn-primary" value="Export to Excel" onclick="add_tax();"/>
             <button class="btn btn-primary hidden-print" id='btn_print' onclick="print_fun('print_panel');"> <span class="glyphicon glyphicon-print "  aria-hidden="true"></span> Print</button>

      </center>
      <?php
    }
      ?>
      <br>
        </div>
        <div id="print_panel">
<table  class="table-responsive table table-striped table-bordered" cellspacing="0" width="100%" style="width: 1250px;">        
        <?php       
           if(!empty($gstr3b)){
            ?>
            <thead>
            <tr>
                <th class="no-sort">GSTR-3B</th>
                <th class="no-sort">TAX</th>
                <th class="no-sort"></th>
                <th class="no-sort" colspan="2" style="text-align: center;">TAXABLE</th>
                <th class="no-sort" colspan="3" style="text-align: center;">TAX</th>  
                <?php if($export_col == 'y' || $sez_col  == 'y' || $zero_col  == 'y'){
                  ?>
                  <th class="no-sort"  colspan="3" style="text-align: center;">NON TAXABLE</th>
                  <?php
                }
                ?>
                
            </tr>           
             <tr>
                <th class="no-sort"></th>
                <th class="no-sort">RATE %</th>
                <th class="no-sort">TOTAL VALUE</th>
                <th class="no-sort">DOMESTIC</th>
                <th class="no-sort">INTERSTATE</th>
                <th class="no-sort">SGST</th>
                <th class="no-sort">CGST</th>                
                <th class="no-sort">IGST</th>
                <?php if($export_col == 'y' ){
                  ?>                
                <th class="no-sort">EXPORT </th>
                <?php }
                  if($sez_col  == 'y'){
                  ?>
                <th class="no-sort">SEZ</th>
                <?php } 
                  if($zero_col  == 'y'){
                  ?>
                <th class="no-sort">ZERO RATE</th>
                <?php
                }
              ?>
            </tr>
        </thead>
         <?php 
            foreach ($gstr3b as $value) {
              $interstate_amount = 0;
              $domestic_amount = 0;
              $export_amount = 0;
              $sez_amount = 0;
              $zero_amount = 0;
               $ledger = $value['LEDGER'];
             $tax_rate = $value['CGSTPERCENT'] + $value['CGSTPERCENT'] + $value['IGSTPERCENT'];
            $total_amount = $value['AMOUNT'] + $value['SGSTAMOUNT'] + $value['CGSTAMOUNT'] + $value['IGSTAMOUNT'];
            $type = $value['SGSTPERCENT'] + $value['CGSTPERCENT'];
            if($type > 0 ){
              $domestic_amount = abs($value['AMOUNT']) - abs($value['DISCOUNT']);
            }elseif($value['IGSTPERCENT'] > 0) {
              $interstate_amount = abs($value['AMOUNT']) -  abs($value['DISCOUNT']);
            }elseif($tax_rate == 0){

               if(stristr($ledger, "EXPORT") != '' || stristr($ledger, "IMPORT") != '' ){
              $export_amount = $value['AMOUNT'];
            }

            if(stristr($ledger, "SEZ") != '' ){
              $sez_amount = $value['AMOUNT'];
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_amount = $value['AMOUNT'];
            }
            }
            ?>
         <tr>
                <td width="210px"><?php echo $value['LEDGER'];?></td>
                <td><?php echo $tax_rate;?></td>
                <td><?php echo number_format(abs($total_amount),2);?></td>
                <td><?php echo number_format($domestic_amount,2);?></td>
                <td><?php echo number_format($interstate_amount,2);?></td>
                <td><?php echo number_format(abs($value['SGSTAMOUNT']),2);?></td>
                <td><?php echo number_format(abs($value['CGSTAMOUNT']),2);?></td>                
                <td><?php echo number_format(abs($value['IGSTAMOUNT']),2);?></td>
                <?php if($export_col == 'y' ){
                  ?>                
                <td><?php echo number_format($export_amount,2);?> </td>
                <?php }
                  if($sez_col  == 'y'){
                  ?>
                <td><?php echo number_format($sez_amount,2);?></td>
                <?php } 
                  if($zero_col  == 'y'){
                  ?>
                <td><?php echo number_format($zero_amount,2);?></td>
                <?php
                }
              ?>
            </tr>
       <?php
            }    }




            if($month !='' && empty($gstr3b) ){
        ?>
        <tr><td><b>No Record</b></td></tr>
        <?php
      }
            ?>
            </tr>
        </tbody>
    </table>
    <table  class="table-responsive table table-striped table-bordered" cellspacing="0" width="100%" style="width: 1250px;">  
    <?php
    if(!empty($gstr1b)){
            ?>
            <thead>
            <tr>
                <th class="no-sort">GSTR-1</th>
                <th class="no-sort">HSN CODE</th>
                <th class="no-sort">DESCRIPTION</th>
                <th class="no-sort">UOM</th>
                <th class="no-sort">TOTAL QUANTITY</th>
                <th class="no-sort" >TOTAL VALUE</th>
                <th class="no-sort" >TOTAL TAXABLE VALUE</th>
            </tr>
        </thead>
        <?php 
        foreach ($gstr1b as $key) {

          $total_amount = $key['AMOUNT'] + $key['CENTRAL_TAX'] + $key['STATE_TAX'] + $key['INTEGRATED_TAX'];
          ?>

        <tr>
                <th class="no-sort"><?php echo $key['SALETYPE'];?></th>
                <th class="no-sort"><?php echo $key['HSNCODE'];?></th>
                <th class="no-sort"><?php echo $key['DESCRIPTION'];?></th>
                <th class="no-sort"><?php echo $key['UNIT'];?></th>
                <th class="no-sort"><?php echo $key['TOTALQUANTITY'];?></th>
                <th class="no-sort" ><?php echo number_format($total_amount,2);?></th>
                <th class="no-sort" ><?php echo number_format($key['AMOUNT'],2);?></th>
            </tr>
         <?php 
       }
       }
       ?>
       </tr>
        </tbody>
    </table>
    </div>

</div>
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
function add_tax(){
  var ledger = $('#cmb_account_group').val();
    window.location='<?php echo site_url(); ?>mis_report/export_excel_gstr/' + ledger;
}
 $("#cmb_account_group").change(function(){ 
  var ledger = $('#cmb_account_group').val();
    window.location='<?php echo site_url(); ?>mis_report/gstn_filling/' + ledger;
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

<script type="text/javascript">
  function print_fun(ids){
    var defaultpage = document.body.innerHTML;
    var printpage = document.getElementById(ids).innerHTML;
    //var n = printpage.length;
    //alert(n);return false;
  
    document.body.innerHTML = printpage;
    window.print() ;
    document.body.innerHTML = defaultpage;
}
</script>
</html>