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
        <li style="color: red;">PURCHASE ORDER</li> 
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
                <input type="hidden" name="voucher_id"  id="voucher_id" value="<?php echo $prefix_voucher_id;?>">
                <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix_voucher_type;?>">
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
            <td style="font-size:23px; padding-top: 5px;"><span ><?php echo $company_data[0]['NAME'];?></span></td>
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
            <td colspan="2" ><span ><input type="checkbox" class="check_class" name="chk" checked="checked" ><b>Orginal for Buyer</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--<br><span ><input type="checkbox" class="check_class" name="chk" ><b>Duplicate</b></span><span ><input type="checkbox" class="check_class" name="chk"><b>Triplicate</b></span>&nbsp;--></td>
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
              <td style="text-align: left;  width:35%; font-size: 15px; font-weight: bold;"><!--NO: <span style="font-size: 17px;"><?php echo $prefix.''.$bill_no;?></span> --></td>
              <td style="padding-left:50px;text-align: center; font-size: 18px; font-family: Arial; font-weight: bold; width:30%; ">PURCHASE ORDER</td>
              <td style="text-align: right;  width:35%; font-size: 15px; font-weight: bold;"><!--DATE: <span><?php echo $date.'&nbsp;';?></span>--></td>
                
              
            </tr>
        </table>
      </td> 
    </tr>
    
       <!-- <tr>
          
          <td valign="top" style="border-top: 1px solid #555555" height="90px;">
        <table  style="width: 100%; font-size:13px;  text-align: left; " cellspacing="0" cellpadding="0">
          <tr>
          <td style=" width: 49%; " height="90px;">
            <table width="100%" style="font-family: Arial; font-size: 14px; " >
              <tr>
                <td style="font-weight:bold; width:30%" >Reverse Charge:</td>
                <td><?php if($reverse_charge == 0 || $reverse_charge == '' || $reverse_charge == NULL){
                   echo 'NO';
                }else{
                   echo 'YES';
                }?></td>
              </tr>
               <tr>
              <td style="font-weight:bold;">Transport Mode : </td>
              <td><span ><?php echo $transport_mode;?></span></td>
            </tr> 
              <tr>
              <td style="font-weight:bold;">Vehicle Number : </td>
              <td><span ><?php echo $vehicle_no;?></span></td>
            </tr> 
            </tr> 
              <tr>
              <td style="font-weight:bold;">DC Number : </td>
              <td><span ><?php echo $dc_no;?></span></td>
            </tr>             
          </table>
        </td>
        <td style="width: 50%; vertical-align: top;">
          <table width="100%" style="font-family: Arial; font-size: 14px;">
           <tr>
              <td style="font-weight:bold; width:33%">Purchase Order No :</td>
              <td><span ><?php if($purchase_id != 0 || $po_ref_no != ''){ echo $po_ref_no; }?></span></td>
            </tr>
            <tr>
              <td style="font-weight:bold;">Date of Supply : </td>
              <td style="text-align: left;"><span ><?php echo $date_of_supply;?></span></td>
            </tr>            
           </tr> 
              <tr>
              <td style="font-weight:bold;">Credit Period : </td>
              <td><span ><?php echo $credit_period;?></span></td>
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
   </tr> -->
      <tr>
      <td style="border-top: 1px solid #555555">
        <table width="100%"  cellpadding="0" cellspacing="0">
          <tr>
          <td style="width: 50%;">
            <table width="100%" style="font-family: Arial; font-size: 14px; font-weight: bold;">
            <?php if($order_type == 0){
              ?>
              <tr>
                <td style="text-align: left;">Supplier</td>
              </tr>
              <?php
            }else{
              ?>
               <tr>
                <td style="text-align: left;">Vendor</td>
              </tr>
              <?php
              } ?>
             
              
              <tr>
               
                <td style="font-size: 15px;"><span ><?php echo 'M/s: '.$account;?></span></td>
              </tr>
              <tr>                
                <td style="word-wrap: break-word; vertical-align:text-top; height:60px;" colspan="3"><span><?php echo $party_address['ADDRESS1'].', <br>'.$party_address['ADDRESS2'].'<br>'.$party_address['DISTRICT'].' - '.$party_address['PINCODE'];?></span></td>
              </tr>
             <tr>
             <td > GSTIN:- <?php echo $tinno;?></td>
             </tr>
             <tr>
             <td > Vendor code:- <?php echo $vendor_code;?></td>
             </tr>
             <tr>
             <td > </td>
             </tr>
          </table>
      </td>
      <td style="width: 50%; vertical-align: top;">
         <table width="100%" style="font-family: Arial; font-size: 14px;  font-weight: bold;">
              <tr>
                <td style="text-align: left;">To be quoted on all documents/correspondence </td>
              </tr>
             <tr>
             <td ></td>
             </tr>
             <tr>
              <td > PO. Ref. No. : <?php echo $po_no;?></td>
            
             </tr>
             <tr>
              <td > Date : <?php echo $po_date;?></td>
            
             </tr>
             <tr>
              <td > Project Refernce : <?php echo $project_ref;?></td>
            
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
        <table  border="1" style=" font-family:Arial; font-size:14px; text-decoration:none; width:900px; color: black; background-color: #ffffff;" align="center" >
      
          <tr align="center" style=" height:40px; background-color: #000000; color: #ffffff; border-top: 1px solid #555555; border-bottom: 1px solid #555555">
            <th style="text-align: center;">Sl.<br>No.</th>
            <th >HSN/SAC <br> Code No.</th>
            <th style="text-align: center; vertical-align: top">Description</th>
            <th style="vertical-align: top">QtyUOM</th>
            <th style="vertical-align: top">UOM</th>
            <th style="vertical-align: top">RATE</th>           
            <th style="text-align: right;">Total Amount <br> INR</th>
          </tr>
          <?php
          if(!empty($purchase_order_det)) {
           $height_td = 450;
          $i = 1;
          $total_final =0;
          $final_tax_amount = 0;
          $final_total_with_tax_amount  = 0;
           $sgst_amount_final = 0;
          $cgst_amount_final = 0;
          $igst_amount_final = 0;
           $total = 0;
           $total_qty  = 0;
           $total_discount = 0;
          foreach ($purchase_order_det as  $value) {
            $item = $value['ITEMNAME'];
            $unit = $value['UNIT']; 
            $rate = $value['RATE'];
            $qty = round($value['QTY'],2);
            $total_qty  = $qty + $total_qty;
            $amount = round($value['AMOUNT'],2);

            $rate = $value['RATE'];
            $qty = round($value['QTY']);
            $total_final = $total_final + $value['AMOUNT'];
          

          $hsn = $value['HSNCODE'];
          if($hsn == 'NULL'){
            $hsn = '';
          }
          
          ?>
          <tr style="height:36px; page-break-inside: avoid !important;">
            <td style="width: 30px; text-align: center;"><?php echo $i;?></td>
            <td style="width: 90px; font-weight: bold;"><?php echo $hsn;?></td>
            <td style="font-weight: bold;"><?php echo $item;?></td>            
            <td  align="left" style="width: 70px; font-weight: bold;"><?php echo $qty;?></td>
            <td  align="left" style="width: 70px; font-weight: bold;"><?php echo $unit;?></td>
            <td align="left" style="width: 70px; font-weight: bold; text-align: center;"><?php echo $rate;?></td>            
            <td align="left" style="width: 100px; font-weight:bold; text-align: right;"><?php echo $amount;?></td>
          </tr>
          <?php
          if($i % 20 == 0){
            ?>
             <tr align="center" style="height:43px; page-break-inside: avoid !important; background-color: #000000; color: #ffffff; border-top: 1px solid #555555; border-bottom: 1px solid #555555">
            <th style="text-align: center;">Sl.<br>No.</th>
            <th >HSN/SAC <br> Code No.</th>
            <th style="text-align: center; vertical-align: top">Description</th>
            <th style="vertical-align: top">QtyUOM</th>
            <th style="vertical-align: top">UOM</th>
            <th style="vertical-align: top">RATE</th>           
            <th style="text-align: right;">Total Amount <br> INR</th>
          </tr>
            <?php
          }
          $i = $i +1;
          $height_td = $height_td - 36;

        }}
         if($height_td < 0){
           $height_td1 = 0;
        }else{
           $height_td1 = 1;
        }
        $height_td = $height_td.'px';
        if($height_td1 != 0){
        ?>
        <tr style="height: <?php echo $height_td?>; ">
           <td style="width: 30px; text-align: center;">&nbsp;</td>
            <td style="width: 90px; font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;">&nbsp;</td>            
            <td  align="left" style="width: 70px; font-weight: bold;">&nbsp;</td>
            <td  align="left" style="width: 70px; font-weight: bold;">&nbsp;</td>
            <td align="left" style="width: 70px; font-weight: bold; text-align: center;">&nbsp;</td>            
            <td align="left" style="width: 100px; font-weight:bold; text-align: right;">&nbsp;</td>
          </tr>
          <?php
        }
          ?>
          <tr style="height:50px; page-break-inside:avoid; page-break-after:auto">
            <td colspan="3" style=" text-align: center; font-weight: bold;">TOTAL</td>
            <td style="font-weight: bold;"><?php echo $total_qty;?></td>            
            <td  align="left" style="width: 70px; font-weight: bold;">&nbsp;</td>
            <td  align="left" style="width: 70px; font-weight: bold;">&nbsp;</td>
            
            <td align="left" style="width: 100px; font-weight:bold; text-align: right;"><?php echo number_format($total_final,2);?></td>
          </tr>

          </table>
           <table  border="0" style=" font-family:Arial; font-size:14px; text-decoration:none; width:900px; color: black; background-color: #ffffff;" align="center" ><tr style="page-break-inside:avoid; page-break-after:auto ">
  <td >
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
                      <td>Total Amount</td>
                      <td align="right" style="width: 100px; font-size: 14px; font-weight:bold;"><?php echo number_format($total_final,2);?></td>
                    </tr>
                   <!-- <tr>
                      <td>Total Amount: GST</td>
                      <td align="right" style="font-size: 14px; font-weight:bold;"><?php echo number_format($final_tax_amount,2);?></td>
                    </tr>
                    <tr>
                      <td>Round off</td>
                      <td align="right" style="font-size: 14px; font-weight:bold;"><?php echo number_format($round_off,2);?></td>
                    </tr>
                    <tr>
                      <td>Total Amount After Tax</td>
                      <td align="right" style="font-size: 15px; font-weight:bold;"><?php echo number_format($final_total_with_tax_amount+$round_off,2);?></td>
                    </tr>-->
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
<tr style="page-break-inside:avoid; page-break-after:auto ">
  <td>
    <table width="100%" style="font-family: Arial;">
      <tr>
        <td style="text-align: center; vertical-align: text-top; text-align: center; width: 70%">
          <table width="100%" border="0" style="text-align: center;font-family:Arial;font-size:13px;">
            <tr style="text-align: left" >
              <td colspan="2" style="text-align: left; font-weight: bold;"><u>Terms & Condition:</u></td>            
            </tr>
            <tr style="text-align: left" >
              <td style="width: 150px; "><b>Payment :</b></td>
              <td>After 60 Days from the date of material acceptance.</td>
            </tr>
             <tr style="text-align: left">
              <td ><b>Delivery date :</b></td>
              <td><span ><?php echo $delivery_from;?></span></td>
            </tr>
            <tr>
              <td style="text-align:left; "><b>Test Certificate :</b> </td>
              <td style="text-align:left"><span><?php echo $test_certificate;?></span></td>
            </tr>
            <tr style="text-align: left; ">
              <td style=""><b>Rates & Taxes :</b></td>
              <td><span >Ex-works - Bangalore.</span></td>
            </tr>
            <tr style="text-align: left; ">
              <td style=""><b>Shipping Address :</b></td>
              <td><span><?php echo $party_shipping_address['ADDRESS1'].', '.$party_shipping_address['ADDRESS2'].', '.$party_shipping_address['DISTRICT'].' - '.$party_shipping_address['PINCODE'];?></span></td>
            </tr>
              <tr style="text-align: left; ">
              <td style=""><b>Confirmation of Order :</b></td>
              <td></td>
            </tr>
            <tr style="text-align: left; ">
              <td style=""><b>Terms & Condition :</b></td>
              <td><span ><span><?php echo $termsnconditions;?></span></span></td>
            </tr>
            <tr>
              <td colspan="2" style=" font-size: 16px; height: 15px; text-align: left; "></td>
            </tr>           
           <tr>
              <td colspan="2" style=" font-size: 16px;">
                  <table width="100%">
                    <tr>
                    <td width="50%" style="text-align: left;" >                   
                    Prepared & Checked by :
                    </td>
                    <td style="text-align: right;">
                     Authorised Signatory
                    </td>
                    </tr>
                  </table>
              </td>
            </tr>           
                <!--<tr>
                <td>
                www.totalaccounting.in
                </td>
              <td  style=" font-size: 16px; height: 25px; text-align: center; ">-: Subject to <?php echo $company_data[0]['JURISDICTION'];?> Jurisdiction :-</td>
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
             <button class="btn btn-primary hidden-print" id='btn_print'><span class="glyphicon glyphicon-print "  aria-hidden="true"></span> Print</button>&nbsp;&nbsp;<input type="button" class="btn btn-primary  hidden-print" name="reset" id="reset" value="Close" onclick="window.location.href='<?php echo site_url('entry/manage_purchase_order');?>'" />&nbsp;&nbsp;<!--<input type="button" class="btn btn-primary hidden-print" name="btn_cancel" id="btn_cancel" value="Cancel Invoice"  onclick=" if (confirm('Do you want cancel this invoice?')){window.location.href='<?php echo site_url('/entry/cancel_sales_voucher/'.$sn.'/'.'/?pre='.urlencode($prefix));?>';}else{event.stopPropagation(); event.preventDefault();}; " style="background-color:#c03c39; border-color:#c03c39; "/> -->

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
  var prefix  = $("#prefix").val();
  var voucher_id  = $("#voucher_id").val();
  $.ajax({
            url  :"<?php echo site_url(); ?>/entry/add_print_data",
            data : {prefix: prefix, voucher_id: voucher_id, voucher_type: 'SA'},
            success: function(ret){
              print_fun('print_panel');
            }
          });
     });
$(".check_class").click(function() {
  $(".check_class").attr("checked", false); //uncheck all checkboxes
  $(this).attr("checked", true);  //check the clicked one
});
</script>
</html>