<?php $this->load->view('include/header_grid'); 

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
label {
    margin-left: 2%;
    margin-top: 0%;
    }
    .well {
        padding: 1px;
    }
    .col-md-12{
      padding: 5px;
      padding-bottom: 0px;
    margin: 0px;
    padding-top: 0px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
      padding: 2px;
    }
    .input_grid{
      height: 23px;
    }
</style>
<script type="text/javascript">

 


function create_salesvoucher() {
  var currency = $("#cmb_account").val();
  
   if(currency == ''){
    alert("Please Select Account");
     $("#cmb_account").focus();
    return false;
  }else{
    $("#frm_sales_voucher").submit();
  }
  
    
  
     
}
  </script>

<div class="container category">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;"><b>RECEIVABLE BILLWISE</b></li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li> 
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>         
    </ol> 
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12" style="background-color:#FFFACD;border-radius: 5px 5px 5px 5px; border: solid 1px #000000; padding:1px 1px 1px 1px;">
            <form name="frm_sales_voucher" id="frm_sales_voucher" action="<?php echo site_url('mis_report/recievable_billwise');?>" method="post" >
        <div class="textdata_myaccount">
            <fieldset class="well" id="well1" style="background-color: #cae1f4">
            <div class="row" id="row1">

            <div class="col-md-12 col-sm-12 col-xs-12">            
                
                
                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Account Name<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                      <select id="cmb_account" name="cmb_account" class="form-control input-sm"  >
                       <option value="">select Account</option>
                          <?php foreach ($ledger as $row) {
                            if($account_id == $row['ID']){
                              echo "<option value='".$row['ID']."'  selected >".$row['ACCOUNTDESC']."</option>"; 
                            }else{
                              echo "<option value='".$row['ID']."'>".$row['ACCOUNTDESC']."</option>";
                            }
                                          
                         }?> 
                     </select>
                       
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-6 col-xs-6">
                <label for="emirate" class="col-md-12 control-label ">Days<span class="textspandata">*</span></label>
                    <div class="col-md-12 col-sm-9 col-xs-9">
                     <input type="number" name="txt_days" id="txt_days" class="form-control input-sm"  value="0"  maxlength = "6" min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  >
                       
                    </div>
                </div>
                
                </div>
                
                 
                
            </div>         
            

  
                  
          </fieldset>      
            <div style="height:5px;"></div>
                  <div class="text-center">
                    <input type="button" name="update" id="update" class="btn btn-primary" value="Submit" onclick="create_salesvoucher();"/>
                </div>          
            </div>  

        </form>       
    </div> 
    <?php if(!empty($payable)){ ?> 
     <table  class="table table-striped table-bordered" cellspacing="0" width="100%">           
          <thead>
          <tr>
                <th class="no-sort" colspan="6" style="text-align: center; height: 65px; vertical-align: middle; font-size: 18px;"><?php echo $account[0]['ACCOUNTDESC'].'(Receivable Billwise Details)';?></th>
            </tr>
            <tr style="height: 35px;">
                <th class="no-sort">Bill No</th>
                <th class="no-sort">Bill Date</th>
                <th class="no-sort">Bill Amount</th>
                <th class="no-sort">Adjusted Amount</th>
                <th class="no-sort">Balance Amount</th>
                <th class="no-sort">Age</th>
            </tr>
        </thead>
        <?php 
          foreach ($payable as $key ) {
            
        ?>
        <tr>
                <td><?php echo $key['PREFIX'].$key['REFNUM'];?></td>
                <td><?php echo date("d-M-Y",strtotime($key['DATE']));?></td>
                <td><?php echo $key['TOTAL_AMOUNT'];?></td>
                <td><?php echo $key['SETTLED_AMOUNT'];?></td>
                <td><?php echo $key['BALANCE_AMOUNT'];?></td>
                <td><?php
                $bal = $key['AGE'] - $key['CREDITPERIOD'];
                if($bal > 0){
                  echo $key['AGE'];
                }else{
                  echo 'No Due';
                }
                  ?></td>
            </tr>
            <?php
          }
            ?>
        </table>  
        <?php
        }
        ?>
  </div> 
</div>
  <div style="height:30px;"></div>                
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
</html>