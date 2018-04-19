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
        <li style="color: #003166;"><b style="margin-left: 20px; margin-right: 20px; "><?php echo $comp_name ;?></b></li>  
        <li style="color: #003166;"><b style="margin-left: 20px;"><?php echo " FINANCIALYEAR :- ".$financial_year_selected ." ";?></b></li>          
    </ol> 
     <div class="table-responsive" id="print_panel">
     <table border="1" style="border:solid 1px black; font-family: Arial;font-size:10px;" align="center" width="900px">
      <tr>
      <td align="center" colspan="2">
      <table width="100%" style="font-family: Arial; font-size: 14px;">
        <tr>
          <td style="width: 160px;" valign="top">
            <table border="0">
              <tr>
                <td><?php
                 if($company_data[0]['Logo'] == '' || $company_data[0]['Logo'] == 'NULL'){
                  ?>
                   <img src="<?php echo base_url('/assets/image/log.JPG');?>" alt="Logo" style="height:100px;width:160px;" />
                   <?php
                   }else{
                    $logo = $company_data[0]['Logo']; 
                    ?>
                      <img src="<?php echo base_url('/assets/photo/'.$logo);?>" alt="Logo" />
                      <?php
                    } ?> </td>
              </tr>
            </table>
          </td>
          <td valign="top">
        <table border="0" style="width: 100%; font-size:13px; font-weight: bold; text-align: center;">
          <tr>
            <td style="font-size:17px;"><span ><?php echo $company_data[0]['NAME'];?></span></td>
          </tr>
          <tr>
            <td><span  style="font-size:13px;"><?php echo $company_data[0]['ADDRESS1'].' '.$company_data[0]['ADDRESS2'].' '.$company_data[0]['CITY'].' ,'.$company_data[0]['STATE'];?></span><br><span  style="font-size:13px;"><?php echo 'Email :-  '.$company_data[0]['EMAIL'].'    &nbsp;&nbsp;Phone :- '.$company_data[0]['OWNER_TELNO'];?></span></td></tr></table>
            </td>
          </tr>
          <tr style="text-align: center; font-size:13px;">
            <td colspan="2"><span ><b>GSTIN: </b><?php echo $company_data[0]['VATCODE'];?></span></td>
          </tr>
        </table>
      </td>
    </tr>
      <tr>
        <td>
          <table width="100%" align="center" style="border:solid 1px black; " >
            <tr>
              <td style="padding-left:50px;text-align: center; font-size: 15px; font-family: Arial; font-weight: bold; width:70%; border:solid 1px black;">CREDIT NOTE</td>
              <td style="text-align: left">
                <table border="1px" style="font-family: Arial; font-size: 12px;width:100%; border:solid 1px black; ">
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
    <tr>
    <td>
      <table width="100%" style="border: solid 1px black;">
        <tr>
          <td style=" width: 50%">
            <table width="100%" style="font-family: Arial; font-size: 14px;">
              <tr>
                <td style="font-weight:bold; width:30%" >Reverse Charge:</td>
                <td> YES / NO</td>
              </tr>
              <tr>
                <td  style="font-weight:bold;">Debitnote No:</td>
                <td><span ><?php echo $prefix.$sno;?></span></td>
              </tr>
              <tr>
                <td style="font-weight:bold;">Date:</td>
                <td><span><?php echo $date;?></span></td>
              </tr>
              <tr>
                <td style="font-weight:bold;">State:</td>
                <td><span >Karnataka</span></td>
                <td> <span style="font-weight:bold;">State Code: </span><span style="width:20px;">29</span></td>
              </tr>
          </table>
        </td>
        <td style="width: 50%">
          <table width="100%" style="font-family: Arial; font-size: 14px;">
            <tr>
              <td style="font-weight:bold; width: 40%">Transport Mode : </td>
              <td><span ></span></td>
            </tr>
            <tr>
              <td style="font-weight:bold;">Vehicle Number : </td>
              <td><span ></span></td>
            </tr>
            <tr>
              <td style="font-weight:bold;">Date of Supply : </td>
              <td style="text-align: left;"><span ><?php echo $date;?></span></td>
            </tr>
            <tr>
              <td style="font-weight:bold;">Place of Supply : </td>
              <td><span><?php echo $party_address['DISTRICT'];?></span></td>
            </tr>
            <tr>
              <td style="font-weight:bold;">Purchase Order No :</td>
              <td><span ></span></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    </td>
    </tr>
    <tr>
      <td>
        <table width="100%" style="border: solid 1px black;" cellpadding="0" cellspacing="0">
          <tr>
          <td style="width: 45%">
            <table width="100%" style="font-family: Arial; font-size: 14px;">
              <tr>
                <td colspan="2" style="text-align: left;"><b>To,</b></td>
              </tr>
              <tr>
                <td style="width: 15%; font-weight:bold;">Name:</td>
                <td><span ><?php echo $ACCOUNT;?></span></td>
              </tr>
              <tr>
                <td style="vertical-align:text-top;font-weight:bold;  ">Address:</td>
                <td style="word-wrap: break-word; vertical-align:text-top; height:60px;" colspan="3"><span><?php echo $party_address['ADDRESS1'].', '.$party_address['ADDRESS2'].', '.$party_address['DISTRICT'].' - '.$party_address['PINCODE'];?></span></td>
              </tr>
              <tr>
                <td style="font-weight:bold;"> GSTIN:</td>
                <td><span><?php echo $tinno;?></span>
                </td>
                <td style="width:95px; font-weight:bold;">Vendor Code:</td>
                <td><span></span></td>
              </tr>
              <tr>
                <td style="font-weight:bold;">State:</td>
                <td><span><?php echo $party_address['STATE'];?></span></td>
                <td style="width:90px; font-weight:bold;">State Code:</td>
                <td><span>29</span></td>
            </tr>
          </table>
      </td>
      <td style="width: 50%">
        <table width="100%" style="font-family: Arial; font-size: 15px;">
          <tr>
            <td colspan="2" style="text-align: center;">&nbsp;</td>
          </tr>
          <tr>
            <td style="width: 15%;">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style="vertical-align:text-top">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="width:90px;">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="width: 100%">
      <div>
        <table cellspacing="3" rules="all" border="1" style="border: solid 1px black; font-family:Arial; font-size:13px; text-decoration:none; width:100%; color: black;">
          <tr align="center">
            <th scope="col">Sl No</th>
            <th scope="col">HSN SAC</th>
            <th scope="col">Item Name</th>
            <th scope="col">UOM</th>
            <th scope="col">Quantity</th>
            <th scope="col">Rate</th><th scope="col">Amount</th>
            <th scope="col">Discount</th>
            <th scope="col">Taxable Amount</th>
            <th scope="col">CGST</th>
            <th scope="col">CGST Amount</th>
            <th scope="col">SGST</th>
            <th scope="col">SGST Amount</th>
            <th scope="col">IGST</th>
            <th scope="col">IGST Amount</th>
            <th scope="col">Total</th>
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
          $qty = round($value['QUANTITY']);
          $discount = round($value['DISCOUNT']);
          $amount = $value['AMOUNT'];
          $amnt = $qty * $rate;
          $hsn = $value['HSNCODE'];
          if($hsn == 'NULL'){
            $hsn = '';
          }
          $total_final = $amount + $total_final;
          $cgst_per = round($value['CGSTPERCENT']);
          $cgst_amt = ($amnt - $discount) * ($cgst_per/100);
          $sgst_per = round($value['SGSTPERCENT']);
          $sgst_amt = ($amnt - $discount) * ($sgst_per/100);
          $igst_per = round($value['IGSTPERCENT']);
          $igst_amt = ($amnt - $discount) * ($igst_per/100);
          $tax_amount = $cgst_amt + $sgst_amt + $igst_amt;
          $total_final_with_tax = $amnt + $cgst_amt + $sgst_amt + $igst_amt - $discount;
          $final_tax_amount = $final_tax_amount + $tax_amount;
          $final_total_with_tax_amount = $final_total_with_tax_amount + $total_final_with_tax;
          ?>
          <tr style="height:3px;">
            <td><?php echo $i;?></td>
            <td><?php echo $hsn;?></td>
            <td><?php echo $item;?></td>
            <td><?php echo $unit;?></td>
            <td align="right"><?php echo $qty;?></td>
            <td align="right"><?php echo $rate;?></td>
            <td align="right"><?php echo $amnt;?></td>
            <td align="right"><?php echo $discount;?></td>
            <td align="right"><?php echo $tax_amount;?></td>
            <td align="right"><?php echo $cgst_per;?></td>
            <td align="right"><?php echo $cgst_amt;?></td>
            <td align="right"><?php echo $sgst_per;?></td>
            <td align="right"><?php echo $sgst_amt;?></td>
            <td align="right"><?php echo $igst_per;?></td>
            <td align="right"><?php echo $igst_amt;?></td>
            <td align="right"><?php echo $total_final_with_tax;?></td>
          </tr>
          <?php
          $i = $i +1;
        }}
        ?>
        </table>
      </div>
    </td>
</tr>
<tr>
  <td>
    <table width="100%" cellpadding="0" cellspacing="0" style="border: solid 1px black; font-family: Arial; font-size: 15px;">
      <tr>
        <td style="width: 50%">
          <table style="font-family: Arial; font-size: 14px; width: 100%">
            <tr>
              <td style="text-align: center; vertical-align: text-top;">Invoice Amount In Words</td>
            </tr>
            <tr>
              <td style="text-align: center; vertical-align: text-top;"><b><span><?php echo ucwords($final_word);?> Only</span></b></td>
            </tr>
          </table>
        </td>
        <td style="width: 50%">
          <table style="font-family: Arial; font-size: 15px; width: 100%; border: solid 0px black;" cellspacing="0">
            <tr>
              <td colspan="2">
                <div>
                  <table cellspacing="0" rules="all" bordercolor="Black" border="1" style="border-color:Black;border-style:Solid; font-size:13px;width:100%; border-collapse:collapse;">
                    <tr>
                      <td>Total Amount Before Tax</td>
                      <td align="right"><?php echo $total_final;?></td>
                    </tr>
                    <tr>
                      <td>Total Amount: GST</td>
                      <td align="right"><?php echo $final_tax_amount;?></td>
                    </tr>
                    <tr>
                      <td>Total Amount After Tax</td>
                      <td align="right" style="font-weight:bold;"><?php echo $final_total_with_tax_amount;?></td>
                    </tr>
                    <tr>
                      <td>GST Payable on Reverse Charge</td>
                      <td align="right">&nbsp;</td>
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
    <table width="100%" style="border: solid 1px black; font-family: Arial;">
      <tr>
        <td style="text-align: center; vertical-align: text-top; text-align: center; width: 70%">
          <table width="100%" border="0" style="text-align: center;font-family:Arial;font-size:13px;">
            <tr>
              <td colspan="2" style="text-align: center; height: 50px; font-size: 15px;"><b>Bank Details:</b></td>
            </tr>
            <tr style="text-align: left">
              <td style="text-align: left; font-weight: bold;">Bank:</td>
              <td><span ><?php echo $company_data[0]['BANK'];?></span></td>
            </tr>
            <tr>
              <td style="text-align:left;  font-weight: bold;">Branch</td>
              <td style="text-align:left"><span><?php echo $company_data[0]['BRANCH'];?></span></td>
            </tr>
            <tr style="text-align: left; ">
              <td style="width: 30%;  font-weight: bold;">IFSC Code:</td>
              <td><span ><?php echo $company_data[0]['IFSC'];?></span></td>
            </tr>
            <tr style="text-align: left">
              <td style=" font-weight: bold;">Account Number:</td>
              <td><span ><?php echo $company_data[0]['BANKACCNUM'];?></span></td>
            </tr>
            <tr>
              <td colspan="2" style=" font-size: 14px;"><br /><br />Terms and condtions</td>
            </tr>
            <tr>
              <td colspan="2"><span style="width:100%;"></span><br /><br /></td>
            </tr>
          </table>
        </td>
        <td style="width: 30%; font-size: 14px;"><br /><br /><br />Authorised Signatory</td>
      </tr>
    </table>
  </td>
</tr>
</table>
</div>
</div>
</div>
<div>
<br>
<center>
             <button class="btn btn-primary hidden-print" onclick="print_fun('print_panel');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="reset" id="reset" value="Close" onclick="window.location.href='<?php echo site_url('entry/manage_creditnote');?>'" />

      </center>
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