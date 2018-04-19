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
        <li style="color: red;">TAX INVOICE</li> 
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; "><?php echo $comp_name ;?></b></li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>          
    </ol> 
     <div class="table-responsive" id="print_panel" style="background-color: #cccccc;">
     <table border="0" style="font-family: Arial;font-size:10px; background-color: #ffffff;" align="center" width="900px">
      <tr>
      <td align="center" colspan="2">
      <table width="100%" style="font-family: Arial; font-size: 14px; margin-left: 5px;">
      
      <tr>
   <td style="height: 10px;">
   </td>
   </tr>
        <tr>
          
          <td valign="top">
        <table border="0" style="width: 100%; font-size:13px;  text-align: left;">
          <tr>
             <td style="font-size: 18px; font-family: Arial; font-weight: bold;">
           <?php echo $company_data[0]['NAME'];?>
           <p style="font-size: 14px; font-family: Arial; font-weight: normal;   "><?php echo $company_data[0]['ADDRESS1'].'<br> '.$company_data[0]['ADDRESS2'].' <br>'.$company_data[0]['CITY'].' <br>'.$company_data[0]['STATE'];?><br>GSTIN: <?php echo $company_data[0]['VATCODE'];?><br><?php echo $company_data[0]['EMAIL'].'   <br> '.$company_data[0]['OWNER_TELNO'];?></p>
          </td>
           </td>
           <td style="vertical-align: top;" width="40%">
           <table width="100%">
             <tr>
            <td style=" font-size: 20px; font-family: Arial; font-weight: bold; vertical-align: top; text-align: right; padding-right: 10px;">
           TAX INVOICE
           <p style=" font-size: 16px; font-family: Arial; font-weight: normal;   ">Invoice No# <?php echo $prefix.$sno;?></p>
          </td>
          </tr>
          <tr>
          <td style="height: 25px">
          </td>
          </tr>
          <tr>
          <td>
          <table border="1px" style="font-family: Arial; font-size: 12px;width:98%; border:solid 1px black; ">
                  <tr>
                    <td style="width: 10%; "><div style="border:solid 1px black; width: 90%;">&nbsp;&nbsp;</div></td>
                    <td style="border:solid 1px black; color: black">Original for Buyer</td>
                  </tr>
                  <tr>
                    <td style="width: 12px;"><div style="border:solid 1px black; width: 90%;">&nbsp;&nbsp;</div></td>
                    <td style="width: 150px;border:solid 1px black; color: black  ">Duplicate for Transporter</td>
                  </tr>
                  <tr>
                    <td style="width: 12px;"><div style="border:solid 1px black; width: 90%;">&nbsp;&nbsp;</div></td>
                    <td style="border:solid 1px black;  color: black">Triplicate for Supplier</td>
                   </tr>
                </table>
          </td>
             </tr>
           </table>
          
           </td>
          </tr>
         
            
            </table>
           
          </tr>
          
        </table>
      </td>
    </tr>
    <tr>
   <td style="height: 10px;">
   </td>
   </tr>
      <tr>
      <td>
        <table width="100%"  cellpadding="0" cellspacing="0">
          <tr>
          <td style="width: 45%">
            <table width="100%" style="font-family: Arial; font-size: 14px;">
              <tr>
                <td style="text-align: left;"><b>Bill To</b></td>
              </tr>
              <tr>
               
                <td><span ><?php echo $ACCOUNT;?></span></td>
              </tr>
              <tr>                
                <td style="word-wrap: break-word; vertical-align:text-top; height:60px;" colspan="3"><span><?php echo $party_address['ADDRESS1'].', '.$party_address['ADDRESS2'].'<br>'.$party_address['DISTRICT'].' - '.$party_address['PINCODE'];?></span></td>
              </tr>
             <tr>
             <td > GSTIN <?php echo $tinno;?></td>
             </tr>
          </table>
      </td>
      <td style="width: 50%">
         <table width="100%" style="font-family: Arial; font-size: 14px;">
              <tr>
                <td style="text-align: left;"><b>Shipped To,</b></td>
              </tr>
              
              <tr>
               
                <td style="word-wrap: break-word; vertical-align:text-top; height:60px;" colspan="3"><span><?php echo $party_shipping_address['ADDRESS1'].', '.$party_shipping_address['ADDRESS2'].'<br>'.$party_shipping_address['DISTRICT'].' - '.$party_shipping_address['PINCODE'];?></span></td>
              </tr>
             <tr>
             <td > Place of Supply <?php echo $party_shipping_address['STATE'];?>(29)</td>
             </tr>
          </table>
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
    <td>
      <table width="100%" >
        <tr>
          <td>

            <table width="100%" style="font-family: Arial; font-size: 14px;">
               <tr align="center"  style=" height:35px; background-color: #000000; color: #ffffff;">
            <th >Invoice Date</th>
            <th >Purchase Order No#</th>
            <th >Reverse Charge</th>
              <!--<tr>
                <td style="font-weight:bold; width:30%" >Reverse Charge:</td>
                <td> YES / NO</td>
              </tr> -->
              
              <tr>                
                <td><span><?php echo $date;?></span></td>
                <td><span>-</span></td>
                <td><span>No</span></td>
              </tr>
             
          </table>
        </td>
       
      </tr>
    </table>
    </td>
    </tr>
   <tr>
   <td style="height: 20px;">
   </td>
   </tr>
  <tr>
    <td style="width: 100%">
      <div>
        <table  border="0" style=" font-family:Arial; font-size:13px; text-decoration:none; width:100%; color: black;">
          <tr align="center"  style=" height:35px; background-color: #000000; color: #ffffff;">
            <th style="text-align: center;">#</th>
            <th >HSN/SAC</th>
            <th >Description</th>
            <th >Qty</th>
            <th >Rate</th>
            <th >Amount</th>
            <th >Discount</th>
            <?php  if($type_sales == 'DOMESTIC SALES'){              
              ?>
            <th >SGST</th>            
            <th >CGST</th>            
            <?php
            }else{
              ?>
              <th >IGST</th>
              <?php
            }
           ?>
            <th >Total</th>
          </tr>
          <?php
          if(!empty($purchase_order_detail)) {
          $i = 1;
          $total_final =0;
          $final_tax_amount = 0;
          $final_total_with_tax_amount  = 0;
           $sgst_amount_final = 0;
          $cgst_amount_final = 0;
          $igst_amount_final = 0;
          $total_final = 0;
           $total = 0;
          foreach ($purchase_order_detail as  $value) {
          $item = $value['ITEM'];
          $unit = $value['UNIT']; 
          $rate = $value['RATE'];
          $qty = round($value['QUANTITY']);
          $discount = round($value['DISCOUNT']);
          $amount = $value['AMOUNT'];

          $rate = $value['RATE'];
          $qty = round($value['QUANTITY']);
          $discount = round($value['DISCOUNT']);
          $amount = $value['AMOUNT'];
          $sgst_per = round($value['SGSTPERCENT']);
          $cgst_per = round($value['CGSTPERCENT']);          
          $igst_per = round($value['IGSTPERCENT']);
          $sgst_amount = round($value['SGSTAMOUNT']);
          $cgst_amount = round($value['CGSTAMOUNT']);
          $igst_amount = round($value['IGSTAMOUNT']);
          $amount_with_discount = $amount - $discount;
          $total = $amount + $sgst_amount + $cgst_amount + $igst_amount - $discount;
          $sgst_amount_final = $sgst_amount + $sgst_amount_final;
          $cgst_amount_final = $cgst_amount + $cgst_amount_final;
          $igst_amount_final = $igst_amount + $igst_amount_final;
          $total_final = $amount_with_discount + $total_final;
          $tax_amount = $sgst_amount + $cgst_amount + $igst_amount;

          
          $final_tax_amount = $final_tax_amount + $tax_amount;
          $final_total_with_tax_amount = $final_total_with_tax_amount + $total;
          

          $hsn = $value['HSNCODE'];
          if($hsn == 'NULL'){
            $hsn = '';
          }
          
          ?>
          <tr style="height:35px; ">
            <td style="width: 30px; text-align: center;"><?php echo $i;?></td>
            <td style="width: 90px; font-weight: bold;"><?php echo $hsn;?></td>
            <td style="font-weight: bold;"><?php echo $item;?></td>
            
            <td  align="left" style="width: 70px; font-weight: bold;"><?php echo $qty;?></td>
            <td align="left" style="width: 70px; font-weight: bold;"><?php echo $rate;?></td>
            <td align="left" style="width: 90px; font-weight: bold;"><?php echo $amount;?></td>
            <td align="left" style="width: 70px; font-weight: bold;"><?php echo $discount;?></td>
            <?php  if($type_sales == 'DOMESTIC SALES'){              
              ?>
              <td align="left" style="width: 70px; "><b><?php echo $sgst_amount;?></b><br><?php echo $sgst_per?>%</td>
            <td align="left" style="width: 70px;"><b><?php echo $cgst_amount;?></b><br><?php echo $cgst_per?>%</td>
              <?php
              }else{
                ?>
            <td align="left" style="width: 70px;"><b><?php echo $igst_amount;?></b><br><?php echo $igst_per?>%</td>
              <?php 
              }
              ?>
            
            <td align="left" style="width: 100px; font-weight:bold; "><?php echo $total;?></td>
          </tr>
          <?php
          $i = $i +1;
        }}
        ?>
        </table>
        <hr>
      </div>
    </td>
</tr>
<tr>
  <td>
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial; font-size: 15px;">
      <tr>
        <td style="width: 50%">
          
        </td>
        <td style="width: 50%; text-align: right;">
          <table style="font-family: Arial; font-size: 15px; width: 100%; border: solid 0px black;" cellspacing="0">
            <tr>
              <td colspan="2">
                <div>
                  <table cellspacing="0" border="0" style="font-size:13px;width:100%; border-collapse:collapse;">
                    <tr>
                      <td>Sub Total</td>
                      <td align="center" style="width: 100px;"><?php echo $total_final;?></td>
                    </tr>
                    <tr>
                      <td>GST</td>
                      <td align="center"><?php echo $final_tax_amount;?></td>
                    </tr>
                    <tr>
                      <td>Total </td>
                      <td align="center" style="font-weight:bold;"><?php echo $final_total_with_tax_amount;?></td>
                    </tr>
                    
                    <tr>
                      <td colspan="2" style="height: 20px;" ></td>
                    </tr>
                    <tr>
                      <td colspan="2" style="text-align: right; font-size: 16px;">Total in Words: <b><span><?php echo ucwords($final_word);?> Only</span></b></td>
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
<tr>
  <td>
    <table width="100%" style="font-family: Arial;">
      <tr>
        <td style="text-align: center; vertical-align: text-top; text-align: center; width: 70%">
          <table width="100%" border="0" style="text-align: center;font-family:Arial;font-size:13px;">
           
            <tr style="text-align: left" >
              <td colspan="2" style="text-align: left; font-weight: bold;"><?php echo $company_data[0]['BANK'];?>                
              </td>            
            </tr>
             <tr style="text-align: left">
              <td style="width: 70px; ">C/Ac.No :</td>
              <td><span ><b><?php echo $company_data[0]['BANKACCNUM'];?></span></b></td>
            </tr>
            <tr>
              <td style="text-align:left; ">Branch</td>
              <td style="text-align:left"><span><?php echo $company_data[0]['BRANCH'];?></span></td>
            </tr>
            <tr style="text-align: left; ">
              <td style="">IFS Code:</td>
              <td><span ><?php echo $company_data[0]['IFSC'];?></span></td>
            </tr>
            <tr>
              <td colspan="2" style="height: 20px;"></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: left; font-size: 16px;">Authorised Signature___________________</td>
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
             <button class="btn btn-primary hidden-print" onclick="print_fun('print_panel');"><span class="glyphicon glyphicon-print " aria-hidden="true"></span> Print</button>&nbsp;&nbsp;<input type="button" class="btn btn-primary  hidden-print" name="reset" id="reset" value="Close" onclick="window.location.href='<?php echo site_url('entry/manage_sales_voucher');?>'" />

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
</script>
</html>