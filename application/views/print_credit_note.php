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
        <li style="color: red;">CREDIT NOTE</li>  
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
                <input type="hidden" name="voucher_id"  id="voucher_id" value="<?php echo $sn;?>">
                <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix;?>">
                <?php
                 if($company_data[0]['Logo'] == '' || $company_data[0]['Logo'] == 'NULL'){
                  ?>
                   <img src="<?php echo base_url('/assets/image/log.JPG');?>" alt="Logo" style="height:100px; width:160px;" />
                   <?php
                   }else{
                    $logo = $company_data[0]['Logo']; 
                    ?>
                      <img src="<?php echo base_url('/assets/photo/'.$logo);?>" alt="Logo"/>
                      <?php
                    } ?>                
                </td>
              </tr>
            </table>
          </td>
          <td valign="top">
        <table border="0" style="width: 100%; font-size:13px; font-weight: bold; text-align: center; ">
          <tr>
            <td style="font-size:19px; padding-top: 5px;"><span ><?php echo $company_data[0]['NAME'];?></span></td>
          </tr>
          <tr>
            <td><span  style="font-size:13px;"><?php echo $company_data[0]['ADDRESS1'].' '.$company_data[0]['ADDRESS2'].' '.$company_data[0]['CITY'].' ,'.$company_data[0]['STATE'];?></span><br><span  style="font-size:13px;"><?php echo 'Email :-  '.$company_data[0]['EMAIL'].'    &nbsp;&nbsp;Phone :- '.$company_data[0]['OWNER_TELNO'];?></span></td>
            </tr>
            <tr>
            <td>
            <span ><b>GSTIN: </b><?php echo $company_data[0]['VATCODE'];?></span></td>
            </tr></table>
            </td>
          </tr>
          <tr style="text-align: right; font-size:12px;">
            <td colspan="2" style="padding-right:10px;"><span ><input type="checkbox" name="chk" checked="checked" disabled="true"><b>Orginal for Buyer</b></span></td>
          </tr>
        </table>
      </td>
    </tr>
      <tr>
   <td style="height: 10px;">
   </td>
   </tr>
   <tr>
        <td style="border-top: 1px solid #555555; height: 50px;" >
        
          <table width="100%" align="center" >
            <tr>
              <td style="text-align: left;  width:25%; font-size: 15px; font-weight: bold;">NO: <span><?php echo $prefix.$ref_num;?></span></td>
              <td style="padding-left:50px;text-align: center; font-size: 18px; font-family: Arial; font-weight: bold; width:50%; ">CREDIT NOTE</td>
              <td style="text-align: left;  width:25%; font-size: 15px; font-weight: bold;">DATE: <span><?php echo $date;?></span></td>
                <!--<table style="font-family: Arial; font-size: 15px;width:100%; font-weight: bold;">
                  <tr>
                    <td style="width: 50%; font-size: 15px;">INVOICE NO :- <span style="font-size: 16px;"><?php echo $prefix.$sno;?></span></td></td>
                  </tr>
                  <tr>
                    <td style="width: 50%; font-size: 15px;">INVOICE DATE :- <span><?php echo $date;?></span></td>
                  </tr>
                  
                </table>-->
              
            </tr>
        </table>
      </td> 
    </tr>
        <tr>
          
          <td valign="top" style="border-top: 1px solid #555555" >
        <table  style="width: 100%; font-size:13px;  text-align: left; " cellspacing="0" cellpadding="0">
          <tr>
          <td style=" width: 49%; border-right: 0px solid #555555" height="50px;">
            <table width="100%" style="font-family: Arial; font-size: 14px; " >
              <tr>
                <td style="font-weight:bold; width:30%" >Reverse Charge:</td>
                <td><?php if($reverse_charge == 0 || $reverse_charge == '' || $reverse_charge == NULL){
                   echo 'NO';
                }else{
                   echo 'YES';
                }?></td>
              </tr>
                          
          </table>
        </td>
        <td style="width: 50%">
          <table width="100%" style="font-family: Arial; font-size: 14px;">           
           <?php if($party_address['vendorcode'] == ''){
            ?>
            <tr>
              <td style="font-weight:bold; width:25%; "> </td>
              <td style="text-align: left;"></td>
            </tr>
            <?php

            }else{
              ?>
              <tr>
              <td style="font-weight:bold; width:25%; ">Vendor code : </td>
              <td style="text-align: left;"><?php echo $party_address['vendorcode'];?></td>
            </tr>
              <?php

              }?>  
             
          </table>
        </td>
      </tr>
         
            
            </table>
           
          </tr>
          
        </table>
      </td>
    </tr>
    <tr>  
   </tr>
      <tr>
      <td style="border-top: 1px solid #555555">
        <table width="100%"  cellpadding="0" cellspacing="0">
          <tr>
          <td style="width: 50%; border-right: 0px solid #555555">
            <table width="100%" style="font-family: Arial; font-size: 14px; font-weight: bold;">
              <tr>
                <td style="text-align: left;">Billed To</td>
              </tr>
              <tr>
               
                <td style="font-size: 15px;"><span ><?php echo 'M/s: '.$ACCOUNT;?></span></td>
              </tr>
              <tr>                
                <td style="word-wrap: break-word; vertical-align:text-top; height:60px;" colspan="3"><span><?php echo $party_address['ADDRESS1'].', '.$party_address['ADDRESS2'].'<br>'.$party_address['DISTRICT'].' - '.$party_address['PINCODE'];?></span></td>
              </tr>
             <tr>
             <td > GSTIN:- <?php echo $tinno;?></td>
             </tr>
             <tr>
             <td > </td>
             </tr>             
          </table>
      </td>
      <td style="width: 50%; vertical-align: top;">
         
      </td>
    </tr>
    </table>
    </td>
  </tr>  
  <tr>
    <td style="width: 100%">
      <div>
        <table  border="0" style=" font-family:Arial; font-size:14px; text-decoration:none; width:100%; color: black;">
          <tr align="center" style=" height:35px; background-color: #000000; color: #ffffff; border-top: 1px solid #555555; border-bottom: 1px solid #555555">
            <th style="text-align: center;">#</th>
            <th >HSN/SAC</th>
            <th >Description</th>
            <th >Qty</th>
            <th >Rate</th>
            <th >Amount</th>
            <th >Discount <br></th>
            <?php $tax_type = stristr($type_sales, "DOMESTIC");

            if( $tax_type != ''){              
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
          if(!empty($debit_note_detail)) {
            $i = 1;
          $total_final =0;
          $final_tax_amount = 0;
          $final_total_with_tax_amount  = 0;
          foreach ($debit_note_detail as  $value) {
          $item = $value['ITEM'];
          $unit = $value['UNIT']; 
          $rate = $value['RATE'];
          $qty = $value['QUANTITY'];
          $discount = round($value['DISCOUNT'],2);
          $amount = $value['AMOUNT'];
          $amnt = $qty * $rate;
          $hsn = $value['HSNCODE'];
          if($hsn == 'NULL'){
            $hsn = '';
          }
          $total_final = $amount + $total_final;
          $cgst_per = round($value['CGSTPERCENT'],2);
          $cgst_amt = ($amnt - $discount) * ($cgst_per/100);
          $sgst_per = round($value['SGSTPERCENT'],2);
          $sgst_amt = ($amnt - $discount) * ($sgst_per/100);
          $igst_per = round($value['IGSTPERCENT'],2);
          $igst_amt = ($amnt - $discount) * ($igst_per/100);
          $tax_amount = $cgst_amt + $sgst_amt + $igst_amt;
          $total_final_with_tax = $amnt + $cgst_amt + $sgst_amt + $igst_amt - $discount;
          $final_tax_amount = $final_tax_amount + $tax_amount;
          $final_total_with_tax_amount = $final_total_with_tax_amount + $total_final_with_tax;
          ?>
          <tr style="height:35px; ">
            <td style="width: 30px; text-align: center;"><?php echo $i;?></td>
            <td style="width: 90px; font-weight: bold;"><?php echo $hsn;?></td>
            <td style="font-weight: bold;"><?php echo $item;?></td>
            
            <td  align="left" style="width: 70px; font-weight: bold;"><?php echo $qty.'<br>'.$unit;?></td>
            <td align="left" style="width: 70px; font-weight: bold;"><?php echo $rate;?></td>
            <td align="left" style="width: 90px; font-weight: bold;"><?php echo $amount;?></td>
            <td align="left" style="width: 70px; font-weight: bold;"><?php echo $discount;?></td>
            <?php  $tax_type = stristr($type_sales, "DOMESTIC");

            if( $tax_type != ''){             
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
            
            <td align="left" style="width: 100px; font-weight:bold; "><?php echo $total_final_with_tax;?></td>
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
        <td style="width: 50%; font-size: 16px;">Total in Words: <br><b><span><?php echo 'Rupees '.ucwords($final_word);?> Only</span></b>
          
        </td>
        <td style="width: 50%; text-align: right;">
          <table style="font-family: Arial; font-size: 15px; width: 100%; border: solid 0px black;" cellspacing="0">
            <tr>
              <td colspan="2">
                <div>
                  <table cellspacing="0" border="0" style="font-size:13px;width:100%; border-collapse:collapse;">
                    <tr>
                      <td>Total Amount  Before Tax</td>
                      <td align="right" style="width: 100px; font-size: 14px; font-weight:bold;"><?php echo number_format($total_final,2);?></td>
                    </tr>
                    <tr>
                      <td>Total Amount: GST</td>
                      <td align="right" style="font-size: 14px; font-weight:bold;"><?php echo number_format($final_tax_amount,2);?></td>
                    </tr>
                    <tr>
                      <td>Round off</td>
                      <td align="right" style="font-size: 14px; font-weight:bold;">--</td>
                    </tr>
                    <tr>
                      <td>Total Amount After Tax</td>
                      <td align="right" style="font-size: 15px; font-weight:bold;"><?php echo number_format($final_total_with_tax_amount,2);?></td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>

          </table>
        </td>
      </tr>     
    </table>
    <hr>
  </td>
</tr>
<tr>

</tr>
<tr>
  <td>
    <table width="100%" style="font-family: Arial;">
      <tr>
        <td style="text-align: center; vertical-align: text-top; text-align: center; width: 70%">
          <table width="100%" border="0" style="text-align: center;font-family:Arial;font-size:13px;">
            <tr style="text-align: left" >
              <td colspan="2" style="text-align: left; font-weight: bold;">Bank Details                
              </td>            
            </tr>
            <tr style="text-align: left" >
              <td style="width: 90px; ">Bank Name :</td>
              <td><span ><b><?php echo  $company_data[0]['BANK'];?></span></b></td>
            </tr>
             <tr style="text-align: left">
              <td style="width: 70px; ">Account No :</td>
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
              <td colspan="2" style=" font-size: 16px; height: 15px; text-align: left; "></td>
            </tr>           
           <tr>
              <td colspan="2" style=" font-size: 16px;">
                  <table width="100%">
                   <tr>
              <td width="50%" style=" font-size: 16px; text-align: left; "><?php echo $termsnconditions;?></td>
              <td width="50%" style=" font-size: 16px;text-align: left; "></td>
            </tr>
                    <tr>
                    <td width="50%" style="text-align: left;" >                   
                    Terms and Conditions
                    </td>
                    <td style="text-align: right;">
                     Authorised Signatory
                    </td>
                    </tr>
                  </table>
              </td>
            </tr>           
                <tr>
              <td colspan="2" style=" font-size: 16px; height: 25px; ">-: Subject to <?php echo $company_data[0]['JURISDICTION'];?> Jurisdiction :-</td>
            </tr> 
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
             <button class="btn btn-primary hidden-print" id='btn_print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>&nbsp;&nbsp;<input type="button" class="btn btn-primary  hidden-print" name="reset" id="reset" value="Close" onclick="window.location.href='<?php echo site_url('entry/manage_creditnote');?>'" />

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
$("#btn_print").click(function () {
  var prefix  = $("#prefix").val();
  var voucher_id  = $("#voucher_id").val();
  $.ajax({
            url  :"<?php echo site_url(); ?>/entry/add_print_data",
            data : {prefix: prefix, voucher_id: voucher_id, voucher_type: 'CN'},
            success: function(ret){
              print_fun('print_panel');
            }
          });
     });
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