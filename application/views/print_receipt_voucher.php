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
    td, th {
    padding: 1px;
}
</style>
<div class="container category" style="min-height: 560px;">        
  <div class="row">
    <ol class = "breadcrumb">                
        <li style="color: red;">RECEIPT VOUCHER</li> 
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li> 
        <li style="color: #00008b;"><b style="margin-left: 20px; margin-right: 20px; font-size: 20px; text-align: right; color: #00008b;"><?php echo $comp_name ;?></b></li>             
    </ol> 
 <div class="table-responsive" id="print_panel" style="background-color: #cccccc;">
     <table border="0" style="font-family: Arial;font-size:10px; background-color: #ffffff;" align="center" width="900px">
      <tr>
      <td align="center" colspan="2">
      <table width="100%" style="font-family: Arial; font-size: 14px; margin-left: 5px;">
      <tr>
      <td align="center" colspan="2">
      <table width="100%" style="font-family: Arial; font-size: 14px;">
        <tr>
          <td style="width: 160px;" valign="top">
            <table border="0">
              <tr>
                <td>
                <!--<input type="hidden" name="voucher_id"  id="voucher_id" value="<?php echo $sn;?>">
                <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix;?>"> -->
                <?php
                 if($company_data[0]['Logo'] == '' || $company_data[0]['Logo'] == 'NULL'){
                  ?>
                   
                   <?php
                   }else{
                    $logo = $company_data[0]['Logo']; 
                    ?>
                      <img src="<?php echo base_url('/assets/photo/'.$logo);?>" alt="Logo"  style="height:100px;width:160px;"/>
                      <?php
                    } ?>                
                </td>
              </tr>
            </table>
          </td>
          <td valign="top">
        <table border="0" style="width: 100%; font-size:13px; font-weight: bold; text-align: center; ">
          <tr>
            <td style="font-size:23px; padding-top: 5px; padding-right: 160px;"><span ><?php echo $company_data[0]['NAME'];?></span></td>
          </tr>
          <tr>
            <td><span  style="font-size:13px; padding-right: 160px;"><?php echo $company_data[0]['ADDRESS1'].' '.$company_data[0]['ADDRESS2'].' '.$company_data[0]['CITY'].' ,'.$company_data[0]['STATE'];?></span><br><span  style="font-size:13px; padding-right: 160px;"><?php echo 'Email :-  '.$company_data[0]['EMAIL'].'    &nbsp;&nbsp;Phone :- '.$company_data[0]['OWNER_TELNO'];?></span></td>
            </tr>
            <tr>
            <td style="padding-right: 160px;">
            <span ><b>GSTIN: </b><?php echo $company_data[0]['VATCODE'];?></span></td>
            </tr></table>
            </td>
          </tr>
          
        </table>
      </td>
    </tr>
      <tr>
   <td style="height: 10px;">
   </td>
   </tr>
    <tr>
        <td style="border-top: 0px solid #555555; height: 50px; text-align: center; font-size: 18px; font-family: Arial; font-weight: bold;" >
        
          <table width="100%" align="center" >
            <tr>
              <td style="text-align: left;  width:35%; font-size: 15px; font-weight: bold;">NO: <span style="font-size: 17px;"><?php echo $prefix.'00000'.$sn;?></span></td>
              <td style="padding-left:50px;text-align: center; font-size: 18px; font-family: Arial; font-weight: bold; width:30%; ">RECEIPT VOUCHER</td>
              <td style="text-align: right;  width:35%; font-size: 15px; font-weight: bold;">DATE: <span><?php echo date("d-M-Y",strtotime($date)).'&nbsp;';?></span></td>
                
              
            </tr>
        </table> 
      </td> 
    </tr>
     <tr>
          
          <td valign="top" style="border-top: 0px solid #555555" height="1px;"></td>
        </tr>
         <tr>
   <td style="height: 10px;">
   </td>
   </tr>
        <tr>
          
          <td valign="top" width="100%">
            <table width="100%">
              <tr>
                <td style="width: 14%; font-weight: bold; height: 30px; font-size: 16px; vertical-align:bottom;">Credit A/c</td>
                <td style="width: 50%; border-bottom: 1px solid #555555; vertical-align:bottom; font-weight: bold;"><?php echo $ledger;?></td>
                <td style="width: 5%; font-weight: bold; text-align: center; font-size: 16px;"></td>
                <td style="width: 25%"></td>
              </tr>
              
            </table>
          </td>
        </tr>
         
        <tr>
       <td valign="top" width="100%">

            <table width="100%">
            <tr>
                 <td style="width: 14%;""></ td><td style="width: 51%;"></ td> <td style="width: 7%;"></td>   <td> <?php if($tax > 0){ echo 'TAX APPLICABLE Y'; }?></td>
              </tr>
            <tr>
                <td style="width: 14%; font-weight: bold; height: 25px; font-size: 16px; vertical-align:bottom;">Ledger Name</td>
                <td style="width: 51%; border-bottom: 1px solid #555555; vertical-align:bottom; "><?php echo $account;?></td>
                <td style="width: 7%; text-align: center;"><?php if($tax > 0){ ?> <b>Tax Rate</b><?php }else{ echo '&nbsp;'; } ?></td><td style="width: 22%; padding-right: 5px; ">
                <?php if($tax > 0){
                  ?>
                  <table width="100%" border="1">
                  
                  <tr>
                      <td colspan="3">GSTN</td>
                    </tr>
                    <tr>
                      <td width="33%">SGST</td><td width="33%">CGST</td><td width="33%">IGST</td>
                    </tr>
                      <tr>
                      <td><?php echo number_format($sgst,2);?></td><td><?php echo number_format($cgst,2);?></td><td><?php echo number_format($igst,2);?></td>
                    </tr>
                  </table>
                  <?php
                }else{
                  ?>
                  <table width="100%" border="0">
                  
                  <tr>
                      <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="33%">&nbsp;</td><td width="33%">&nbsp;</td><td width="33%">&nbsp;</td>
                    </tr>
                      <tr>
                      <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                  </table>

                                    <?php
                }


                  ?>
                </td>
              </tr>
              <tr>
              <td style="width: 14%; font-weight: bold; height: 55px; font-size: 16px; vertical-align:bottom; ">Towards</td>
                <td style="width: 51%; border-bottom: 1px solid #555555; vertical-align:bottom; "><?php echo $NARRATION;?></td>
                <td style="width: 7%; text-align: center;"></td><td style="width: 22%; padding-right: 5px; "></td>
   </td>
   </tr>
    <tr>
              <td style="width: 14%; font-weight: bold; height: 15px; "></td>
                <td style="width: 51%;"></td>
                <td style="width: 7%; text-align: center;"></td><td style="width: 22%; padding-right: 5px; "></td>
   </td>
   </tr>
            </table>
        </td>
        </tr>
         
  </table>
    </td>
     </tr>

     </table>    
       
          <table  border="0" style=" font-family:Arial; font-size:14px; text-decoration:none; width:900px; color: black; background-color: #ffffff;" align="center" ><tr style="page-break-inside:avoid; page-break-after:auto ">
  <td >
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial; font-size: 15px;">
      <tr>
        <td style="width: 60%; font-size: 16px;">
        <table  width="100%">
          <tr>
            <td style="width: 35%"><b> Mode of Payment</b>
            </td><td style="text-align: left; width: 35%"><?php echo $type;?></td><td></td><td style="width: 15%"><b>Ref. No.</b></td><td style="text-align: left"><?php echo $ref_no;?></td>
          </tr>
          <tr>
            <td colspan="4" style="height: 20px;">
           
            </td>
          </tr>
           <tr>
            <td> 
            </td><td></td><td></td><td></td><td></td>
          </tr>
          <tr>
            <td colspan="4">
            <b>Rupees: </b><span><?php echo ucwords($final_word);?> Only</span></b>
            </td>
          </tr>
        </table>
       
          
        </td>
        <td style="text-align: right;">
          <table style="font-family: Arial; font-size: 15px; width: 100%; border: solid 0px black;" cellspacing="0">
            <tr>
              <td colspan="2">
                <div>
                  <table cellspacing="0" border="0" style="font-size:13px;width:100%; border-collapse:collapse; ">
                    <tr>
                      <td><b>Total Amount  Before Tax</b></td>
                      <td align="right" style="width: 100px; font-size: 14px; font-weight:bold;"><?php echo number_format($total_amount,2);?></td>
                    </tr>
                    <tr>
                      <td><b>Total Amount: GST</b></td>
                      <td align="right" style="font-size: 14px; font-weight:bold;"><?php echo number_format($tax_amount,2);?></td>
                    </tr>                    
                    <tr>
                      <td><b>Total Amount After Tax</b></td>
                      <td align="right" style="font-weight:bold; font-size: 15px"><?php echo number_format($total_amount+$tax_amount,2);?></td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>

          </table>
        </td>
      </tr>     
    </table>
  </td>
</tr>
<tr style="page-break-inside:avoid; page-break-after:auto ">
  <td>
    <table width="100%" style="font-family: Arial;">
      <tr>
        <td style="text-align: center; vertical-align: text-top; text-align: center; width: 100%">
          <table width="100%" border="0" style="text-align: center;font-family:Arial;font-size:16px; font-weight: bold;">
                      
              <tr>
                    <td width="25%" style="text-align: left; height: 30px" >                   
                    
                    </td>
                    <td width="25%" style="text-align: left;" >                   
                   
                    </td>
                    <td width="25%" style="text-align: left;" >                   
                   
                    </td>
                    <td style="text-align: right;">
                      
                    </td>
                    </tr>
                    <tr>
                     <td width="25%" style="text-align: left; height: 30px; " >                   
                   <span style="border-bottom: 1px solid"> RS: <?php echo number_format($total_amount+$tax_amount,2).' /-';?></span>
                    </td>
                    <td width="25%" style="text-align: left; height: 30px" >                   
                    Authorized by
                    </td>
                    <td width="25%" style="text-align: left;" >                   
                    Prepared by
                    </td>
                    <td style="text-align: right;">
                      Receivers Signature
                    </td>
                    </tr>
                  </table>
              </td>
            </tr>           
               <!-- <tr>
                <td>
                www.totalaccounting.in
                </td>
              <td  style=" font-size: 16px; height: 25px; text-align: center; ">-</td>
            </tr> -->
              </table>
             
              </td>
            </tr>
            
          </table>
        </td>
        
      </tr>
    </table>
  </td>
</tr>
      </table>
<br>
<center>
           <button class="btn btn-primary hidden-print" id='btn_print'><span class="glyphicon glyphicon-print "  aria-hidden="true"></span> Print</button>&nbsp;&nbsp;<input type="button" class="btn btn-primary  hidden-print" name="reset" id="reset" value="Close" onclick="window.location.href='<?php echo site_url('entry/manage_receipt_voucher');?>'" />

      </center>
      <br>
      <br>
</div>
</div>
</div>
<div>

</div>
<div style="height:35px;"></div>                
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
 $("#btn_print").click(function () {
  
              print_fun('print_panel');
            
     });
$(".check_class").click(function() {
  $(".check_class").attr("checked", false); //uncheck all checkboxes
  $(this).attr("checked", true);  //check the clicked one
});
</script>
</html>