
<strong>Item Name<span class="textspandata">*</span></strong>
 <input type="text" class=" input-sm" id="txt_item_name" name="txt_item_name" maxlength="50" style="width: 25%; border: 1px solid #555555 !important;">&nbsp;&nbsp;&nbsp;&nbsp;
 <strong>Group<span class="textspandata">*</span></strong> 
 <select name="cmb_group" id="cmb_group" class="input-sm" style="width: 15%; border: 1px solid #555555 !important;">
              <option value="" selected="selected">Select Group</option>
              <?php foreach ($account_group as $row) {
                  echo "<option value='".$row['ID']."'>".$row['GROUPNAME']."</option>"; 
                }?>                
              </select>&nbsp;&nbsp;&nbsp;&nbsp;

<strong> Item Code</strong>
<input type="text" class="input-sm" id="txt_item_code" name="txt_item_code" maxlength="10" style="width: 15%; border: 1px solid #555555 !important;">

 <br><br>
 <strong> HSN/SAC Code</strong>
 <input type="text" class="input-sm" id="txt_hsn_code" name="txt_hsn_code" maxlength="10" style="width: 15%; border: 1px solid #555555 !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                                     
<strong>Unit<span class="textspandata">*</span></strong> 
 <select name="cmb_unit" id="cmb_unit"  class="input-sm" style="width: 15%; border: 1px solid #555555 !important;">
    <option value="" selected="selected">Select Unit Type</option><?php
      foreach ($unit as $data) {
        echo "<option value='".$data['ID']."'>".$data['NAME']."</option>";  
      }
      ?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;

<strong> Opening Quanity</strong>
<input type="number" class="input-sm" id="txt_opening_stock" name="txt_opening_stock" maxlength="10"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"style="width: 15%; border: 1px solid #555555 !important;" style="width: 15%; border: 1px solid #555555 !important;">
 <br><br>

<strong> Opening Value</strong>

 <input type="number" class="input-sm" id="txt_opening_value" name="txt_opening_value" maxlength="12"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" style="width: 15%; border: 1px solid #555555 !important;">&nbsp;&nbsp;&nbsp;&nbsp;

<strong> Selling Rate</strong>

 <input type="number" class="input-sm" id="txt_selling_rate" name="txt_selling_rate" maxlength="12"  min = "0"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" style="width: 15%; border: 1px solid #555555 !important;">
