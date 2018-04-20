<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry extends CI_Controller {	

	function __construct() {
        
        parent::__construct();
        date_default_timezone_set( 'Asia/Kolkata' );
        $this->company_id = $this->session->userdata('company_id');
        $this->user_name = $this->session->userdata('user_name');
        $this->load->model('admin_model');
        if (!$this->session->userdata('is_logged_in'))
      redirect(site_url());
    }

	public function index() {

		$this->load->view('sales_voucher');
	}	

	public function payment_voucher() {

		$this->load->view('payment_voucher');
	}

	public function recipet_voucher() {

		$this->load->view('recipet_voucher');
	}

	public function debit_note() {
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'DN', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'DN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = 1 and  VoucherType = 'DN' and Financialyear_id = 6");
	$this->data['sn'] = $query_sn->result_array();

		$this->load->view('debit_note',$this->data);
	}

	public function add_debitnote() {


		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = 1, @VoucherType = 'DN'");
		$gst = $query_sn->result_array();

		foreach($gst as $val) {
			if($val['GSTAccount'] == 'INPUT CGST'){
				$cgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'INPUT SGST'){
				$sgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'INPUT IGST'){
				$igst_id = $val['GSTAccount_ID'];
			}
		}
		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		$voucher_type = $this->input->post('cmb_voucher_type'); 
		$voucher_type = trim($voucher_type);
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));
		$dr_account_id = $this->input->post('cmb_account_group');
		$cr_account_id = $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$tax_id = $this->input->post('cmb_tax');
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');
		
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$item = $_POST['cmb_item_name'];
		$qty = $_POST['txt_qty'];
		$unit_price = $_POST['txt_unit_price'];
		$discount = $_POST['txt_discount'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;

		$insert = 'insert into AllAccountTran([COMPANY_ID],[DATE], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [PREFIX], [VOUCHER_ID], [REFNUM], [NARRATION], [IsTax], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [FINANCIALYEAR_ID], [CREATEDON],[CREATEDBY], [QUANTITY], [RATE], [UNIT_ID], [DISCOUNT], [ISACTIVE]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit = $unit_price[$i];
		 $discounts = $discount[$i];
		 $totals = $total[$i];
		 $insert .= " ($company_id,''$date'', ''$voucher_type'',$dr_account_id,$cr_account_id,$totals,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 0, NULL, 0,0,$items,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";
		}

		/*(".$company_id.", "'.$date.'", "'.$voucher_type.'", "'.$month.'", "'.$year.'", "'.$dr_account_id.'",  "'.$cr_account_id.'", "'.$totals.'", "'.$voucher_type.'", "@voucherno", "'.$refnum.'", "'.$narration.'", "0", "NULL", "0", "NULL", "'.$items.'", "'.$Financialyear_id.'", getdate(), "'.$user_name.'" ),'; */

		if($sgst_amount != 0)
		{
		 $insert .= " ($company_id,''$date'', ''$voucher_type'',$dr_account_id,$sgst_id,$sgst_amount,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 1, NULL, 0,0,0,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";


		 /* ' ("'.$company_id.'", "'.$date.'", "'.$voucher_type.'", "'.$month.'", "'.$year.'", "'.$dr_account_id.'",  "'.$sgst_id.'",  "'.$sgst_amount.'", "'.$voucher_type.'", "@voucherno", "'.$refnum.'", "'.$narration.'", "1",  "'.$sgst.'", "NULL", "NULL", "NULL", "'.$Financialyear_id.'", getdate(), "'.$user_name.'" ),';*/
		}

		if($igst_amount != 0)
		{
		 $insert .= " ($company_id,''$date'', ''$voucher_type'',$dr_account_id,$igst_id,$igst_amount,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 1, NULL, 0,0,0,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";


		 /* ' ("$company_id", "'.$date.'", "'.$voucher_type.'", "'.$month.'", "'.$year.'", "'.$dr_account_id.'",  "'.$igst_id.'",  "'.$sgst_amount.'", "'.$voucher_type.'", "@voucherno", "'.$refnum.'", "'.$narration.'", "1",  "'.$igst.'", "NULL", "NULL", "NULL", "'.$Financialyear_id.'", getdate(), "'.$user_name.'" ),';*/
		}

		if($cgst_amount != 0)
		{
		 $insert .= "($company_id,''$date'', ''$voucher_type'',$dr_account_id,$cgst_id,$cgst_amount,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 1, NULL, 0,0,0,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";

		 /*' ("'.$company_id.'", "'.$date.'", "'.$voucher_type.'", "'.$month.'", "'.$year.'", "'.$dr_account_id.'",  "'.$cgst_id.'",  "'.$sgst_amount.'", "'.$voucher_type.'", "@voucherno", "'.$refnum.'", "'.$narration.'", "1",  "'.$cgst.'", "NULL", "NULL", "NULL", "'.$Financialyear_id.'", getdate(), "'.$user_name.'" ),';*/
		}
		$insert = rtrim($insert,", ");
		$insert = $insert."; ";
		//echo "EXEC [dbo].[usp_InsDebitNote] @Accounttran = '$insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @Financialyear_id = $Financialyear_id, @TotalAMOUNT = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT = $igst_amount ";
		//exit();
		$result = $this->db->query("EXEC [dbo].[usp_InsDebitNote] @AllAccounttran = '$insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @prefix = '$voucher_type', @Financialyear_id = $Financialyear_id, @TotalAMOUNT = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT = $igst_amount, @CREATEDBY = '$user_name' ");
		if($result){
			redirect(site_url() . '/entry/debit_note/');
		}
		
	}

	
	

	public function credit_note() {
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'CN', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'CN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = 1 and  VoucherType = 'CN' and Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();

		$this->load->view('credit_note',$this->data);
	}

	public function journal_voucher() {

		$this->load->view('journal_voucher');
	}
	public function stock_journal() {

		$this->load->view('stock_journal');
	}
	

	public function get_tax() {
		$tax_type = $this->input->get('tax');
		$leg = $this->input->get('leg');
		
		$query_cmp = $this->db->query("EXEC [dbo].[usp_GetCompanyTax]  @COMPANY_ID = 1, @ISACTIVE = 1, @TAX_ID = '$tax_type', @LEDGERTYPE = '$leg' ");
		$tax = $query_cmp->result('array');
		$tax_val = array('sgst' => $tax[0]['SGST'],'igst' => $tax[0]['IGST'], 'cgst' => $tax[0]['CGST'] );
		echo json_encode($tax_val, JSON_FORCE_OBJECT);
		
	}

	public function get_rate() {
		$item_id = $this->input->get('item_id');		
		$query_cmp = $this->db->query("select SRATE,UNIT_ID from [dbo].[Item] where ID = '$item_id' ");
		$rate = $query_cmp->result('array');
		$rate_val = array('rate' => $rate[0]['SRATE'],'unit_id' => $rate[0]['UNIT_ID'] );
		echo json_encode($rate_val, JSON_FORCE_OBJECT);
		
	}

	public function add_creditnote() {

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = 1, @VoucherType = 'CN'");
		$gst = $query_sn->result_array();

		foreach($gst as $val) {
			if($val['GSTAccount'] == 'OUTPUT CGST'){
				$cgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'OUTPUT SGST'){
				$sgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'OUTPUT IGST'){
				$igst_id = $val['GSTAccount_ID'];
			}
		}
		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		$voucher_type = $this->input->post('cmb_voucher_type'); 
		$voucher_type = trim($voucher_type);
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));
		$cr_account_id = $this->input->post('cmb_account_group');
		$dr_account_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$tax_id = $this->input->post('cmb_tax');
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');
		
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$item = $_POST['cmb_item_name'];
		$qty = $_POST['txt_qty'];
		$unit_price = $_POST['txt_unit_price'];
		$discount = $_POST['txt_discount'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;
		$insert = 'insert into AllAccountTran([COMPANY_ID],[DATE], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [PREFIX], [VOUCHER_ID], [REFNUM], [NARRATION], [IsTax], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [FINANCIALYEAR_ID], [CREATEDON],[CREATEDBY], [QUANTITY], [RATE], [UNIT_ID], [DISCOUNT], [ISACTIVE]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit = $unit_price[$i];
		 $discounts = $discount[$i];
		 $totals = $total[$i];
		 $insert .= " ($company_id,''$date'', ''$voucher_type'',$dr_account_id,$cr_account_id,$totals,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 0, NULL, 0,0,$items,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";
		}

		/*(".$company_id.", "'.$date.'", "'.$voucher_type.'", "'.$month.'", "'.$year.'", "'.$dr_account_id.'",  "'.$cr_account_id.'", "'.$totals.'", "'.$voucher_type.'", "@voucherno", "'.$refnum.'", "'.$narration.'", "0", "NULL", "0", "NULL", "'.$items.'", "'.$Financialyear_id.'", getdate(), "'.$user_name.'" ),'; */

		if($sgst_amount != 0)
		{
		 $insert .= " ($company_id,''$date'', ''$voucher_type'',$cr_account_id,$sgst_id,$sgst_amount,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 1, NULL, 0,0,0,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";
		
		}

		if($igst_amount != 0)
		{
		 $insert .= " ($company_id,''$date'', ''$voucher_type'',$cr_account_id,$igst_id,$igst_amount,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 1, NULL, 0,0,0,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";
		}

		if($cgst_amount != 0)
		{
		 $insert .= "($company_id,''$date'', ''$voucher_type'',$cr_account_id,$cgst_id,$cgst_amount,''$voucher_type'',@voucherno, ''$refnum'', ''$narration'', 1, NULL, 0,0,0,$Financialyear_id, getdate(), ''$user_name'', $qtys, $unit, 0, $discounts, 1 ), ";
		}
		$insert = rtrim($insert,", ");
		$insert = $insert."; ";
		
		$result = $this->db->query("EXEC [dbo].[usp_InsCreditNote] @AllAccounttran = '$insert', @Company_ID = '$company_id', @date = '$date',  @VoucherType = '$voucher_type', @Prefix = '$voucher_type', @Financialyear_id = '$Financialyear_id', @TotalAMOUNT = '$final_amount', @ACCOUNT_ID = '$cr_account_id', @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = '$dr_account_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTAMOUNT = '$sgst_amount', @CGSTAMOUNT = '$cgst_amount', @IGSTAMOUNT = '$igst_amount', @CREATEDBY = '$user_name' ");

		if($result){
			redirect(site_url() . '/entry/credit_note/');
		}
		
	}
	

	public function sales_order() {
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SO', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SO', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = 1 and  VoucherType = 'SO' and Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->load->view('sales_order',$this->data);

 	}

 	public function add_salesorder() {
 		
 		$unit_id = $_POST['cmb_unit'];
		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$validity = $this->input->post('txt_validity');
		$user_name = $this->user_name;
		$final_amount = $this->input->post('txt_final_total');

 		$item = $_POST['cmb_item_name']; 		
		$rate = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];		
		$total = $_POST['txt_total'];

		$k = count($item) - 1;
		

 		$insert = 'insert into [dbo].[Orders] ([DATE], [COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [VALIDITY], [ISSALESORDER], [ACCOUNT_ID], [LEDGER_ID], [ITEM_ID], [UNIT_ID], [RATE], [QTY], [AMOUNT], [CREATEDBY], [CREATEDON] ) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit_id = $unit_id[$i];
		 $rate = $rate[$i];
		 $amount = $total[$i];

		 $insert .= " (''$date'', $company_id, $Financialyear_id,''$voucher_type'', @voucherno, $validity, 1, $account_id, $ledger_id, $items, $unit_id, $rate, $qtys, $amount, ''$user_name'', getdate() ), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";
		echo "EXEC[dbo].[usp_InsOrders] @Orders = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id ";
		exit;
 		$result = $this->db->query("EXEC[dbo].[usp_InsOrders] @Orders = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id ");

 		if($result){
			redirect(site_url() . '/entry/sales_order/');
		}
 	}

 	public function purchase_order() {
 		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'PO', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'PO', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = 1 and  VoucherType = 'PO' and Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->load->view('purchase_order',$this->data);
	}

	public function add_purchaseorder() {
		
		$unit_ids = $_POST['cmb_unit'];
		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$validity = $this->input->post('txt_validity');
		$user_name = $this->user_name;
		$final_amount = $this->input->post('txt_final_total');

 		$item = $_POST['cmb_item_name']; 		
		$rate = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];		
		$total = $_POST['txt_total'];

		$k = count($item) - 1;
		

 		$insert = 'insert into [dbo].[Orders] ([DATE], [COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [VALIDITY], [ISSALESORDER], [ACCOUNT_ID], [LEDGER_ID], [ITEM_ID], [UNIT_ID], [RATE], [QTY], [AMOUNT], [CREATEDBY], [CREATEDON] ) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit_id = $unit_ids[$i];
		 $rate = $rate[$i];
		 $amount = $total[$i];

		 $insert .= " (''$date'', $company_id, $Financialyear_id,''$voucher_type'', @voucherno, $validity, 0, $account_id, $ledger_id, $items, $unit_id, $rate, $qtys, $amount, ''$user_name'', getdate() ), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";
		
 		$result = $this->db->query("EXEC [dbo].[usp_InsOrders] @Orders = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id ");

 		if($result){
			redirect(site_url() . '/entry/purchase_order/');
		}

 	}

 	public function sales_voucher() {
 		if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);			
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = 'PO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id;
			$this->data['date'] = $purchase_order[0]['LEDGER_ID'];
			foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @ID = '$item_id' ");
				$tax = $query_cmp->result('array');
				$string = 'DOMESTIC';
				if (strpos($leg,$string) !== false) {
		    		$purchase_order[$key]['sgst'] = $tax[0]['SGST'];
					$purchase_order[$key]['igst'] = 0;
					$purchase_order[$key]['cgst'] = $tax[0]['CGST'];
				}else{
					$purchase_order[$key]['sgst'] = 0;
					$purchase_order[$key]['igst'] = $tax[0]['IGST'];
					$purchase_order[$key]['cgst'] = 0;
				}
			}
			
		 
		$this->data['purchase_order_detail'] = $purchase_order;
		
		}else{
			$this->data['purchase_order_detail'] =  array();
			$this->data['account_id'] = '';
			$this->data['ledger_id'] = '';
			$this->data['purchase_id'] = '';
		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix from SerialNo where Company_ID = 1 and  VoucherType = 'SA' and Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('sales_voucher',$this->data);
	}

	public function add_salesvoucher() {

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = 1, @VoucherType = 'SA'");
		$gst = $query_sn->result_array();

		foreach($gst as $val) {
			if($val['GSTAccount'] == 'INPUT CGST'){
				$cgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'INPUT SGST'){
				$sgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'INPUT IGST'){
				$igst_id = $val['GSTAccount_ID'];
			}
		}

		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));
		$month =  date("m",strtotime($date1));
		$credit_period = $this->input->post('txt_validity');;
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_so_number');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		
		$validity = 0;
		$user_name = $this->user_name;

		$total_discount = $this->input->post('txt_total_discount');
		$total_sgst= $this->input->post('txt_total_sgst');
		$total_cgst= $this->input->post('txt_total_cgst');
		$total_igst= $this->input->post('txt_total_igst');
		$final_total= $this->input->post('txt_final_total');

		$item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];
		$amounts = $_POST['txt_amount'];
		$discounts = $_POST['txt_discount']; 		
		$sgsts = $_POST['txt_sgst'];
		$sgst_pers = $_POST['txt_sgst_per'];
		$cgsts = $_POST['txt_cgst'];
		$cgst_pers = $_POST['txt_cgst_per']; 		
		$igsts = $_POST['txt_igst'];
		$igst_pers = $_POST['txt_igst_per'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;
		

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $rate = $rates[$i];
		 $unit_id = $unit[$i];		 
		 $amount = $amounts[$i];
		 $discount = $discounts[$i];
		 $cgst_per =$cgst_pers[$i];
		 $sgst_per = $sgst_pers[$i];
		 $igst_per = $igst_pers[$i]; 
		 $vat_per = 0;
		 $sgst = $sgsts[$i];
		 $igst = $igsts[$i];
		 $vat = 0;
		 $cgst = $cgsts[$i];

		 $insert .= " ( $company_id, ''$date'', 1, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name'' ), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";

		$result = $this->db->query("EXEC [dbo].[usp_InsSalesVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 1, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'SA', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @CREATEDBY = '$user_name', @month  = '$month'"); 
		if($result){
			redirect(site_url() . '/entry/manage_sales_voucher/');
		}

	}

	public function get_item_tax() {
		$item_id = $this->input->get('item_id');
		$leg = $this->input->get('leg');
		
		$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @ID = '$item_id' ");
		$tax = $query_cmp->result('array');
		$string = 'DOMESTIC';
		if (strpos($leg,$string) !== false) {
    		$sgst = $tax[0]['SGST'];
			$igst = 0;
			$cgst = $tax[0]['CGST'];
		}else{
			$sgst = 0;
			$igst = $tax[0]['IGST'];
			$cgst = 0;
		}

		$tax_val = array( 'unit_id' => $tax[0]['UNIT_ID'], 'rate' => $tax[0]['SRATE'], 'sgst' => $sgst,'igst' => $igst, 'cgst' => $cgst, 'stock' => $tax[0]['STOCK'] );
		echo json_encode($tax_val, JSON_FORCE_OBJECT);
		
	}

	public function new_sn() {
		$type = $this->input->get('type');
		
		$query_sn = $this->db->query("EXEC	[dbo].[usp_GetNEWSerialNo] @Company_ID = 1, @prefix = '$type', @Financialyear_id = 6");
		$sn = $query_sn->result_array();

		$sn_val = array('sn' => $sn[0]['SN'], );
		echo json_encode($sn_val, JSON_FORCE_OBJECT);
		
	}



	public function purchase_voucher() {
		if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);			
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = 'PO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id;
			foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @ID = '$item_id' ");
				$tax = $query_cmp->result('array');
				$string = 'DOMESTIC';
				if (strpos($leg,$string) !== false) {
		    		$purchase_order[$key]['sgst'] = $tax[0]['SGST'];
					$purchase_order[$key]['igst'] = 0;
					$purchase_order[$key]['cgst'] = $tax[0]['CGST'];
				}else{
					$purchase_order[$key]['sgst'] = 0;
					$purchase_order[$key]['igst'] = $tax[0]['IGST'];
					$purchase_order[$key]['cgst'] = 0;
				}
			}
			
		 
		$this->data['purchase_order_detail'] = $purchase_order;
		
		}else{
			$this->data['purchase_order_detail'] =  array();
			$this->data['account_id'] = '';
			$this->data['ledger_id'] = '';
			$this->data['purchase_id'] = '';
		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'PU', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'PU', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix from SerialNo where Company_ID = 1 and  VoucherType = 'PU' and Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('purchase_voucher',$this->data);
	}

	public function add_purchasevoucher() {

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = 1, @VoucherType = 'PU'");
		$gst = $query_sn->result_array();

		foreach($gst as $val) {
			if($val['GSTAccount'] == 'OUTPUT CGST'){
				$cgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'OUTPUT SGST'){
				$sgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'OUTPUT IGST'){
				$igst_id = $val['GSTAccount_ID'];
			}
		}

		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));
		$month =  date("m",strtotime($date1));
		$credit_period = $this->input->post('txt_validity');
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_so_number');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		
		$validity = $this->input->post('txt_validity');
		$user_name = $this->user_name;

		$total_discount = $this->input->post('txt_total_discount');
		$total_sgst= $this->input->post('txt_total_sgst');
		$total_cgst= $this->input->post('txt_total_cgst');
		$total_igst= $this->input->post('txt_total_igst');
		$final_total= $this->input->post('txt_final_total');

		$item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];
		$amounts = $_POST['txt_amount'];
		$discounts = $_POST['txt_discount']; 		
		$sgsts = $_POST['txt_sgst'];
		$sgst_pers = $_POST['txt_sgst_per'];
		$cgsts = $_POST['txt_cgst'];
		$cgst_pers = $_POST['txt_cgst_per']; 		
		$igsts = $_POST['txt_igst'];
		$igst_pers = $_POST['txt_igst_per'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;
		

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $rate = $rates[$i];
		 $unit_id = $unit[$i];		 
		 $amount = $amounts[$i];
		 $discount = $discounts[$i];
		 $cgst_per =$cgst_pers[$i];
		 $sgst_per = $sgst_pers[$i];
		 $igst_per = $igst_pers[$i]; 
		 $vat_per = 0;
		 $sgst = $sgsts[$i];
		 $igst = $igsts[$i];
		 $vat = 0;
		 $cgst = $cgsts[$i];

		 $insert .= " ( $company_id, ''$date'', 0, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name''), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_InsPurchaseVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 0, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'PU', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @CREATEDBY = '$user_name', @month  = '$month'"); 

	if($result){
			redirect(site_url() . '/entry/manage_purchase_voucher/');
		}
	}


	public function test(){
		$date1 = '23-11-2017';
		$date =  date("m-d-Y",strtotime($date1));
		echo $date;
	}

	public function sales_order_report() {
 		if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);			
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = 'SO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id;
			$this->data['date'] = $purchase_order[0]['LEDGER_ID'];
			foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @ID = '$item_id' ");
				$tax = $query_cmp->result('array');
				$string = 'DOMESTIC';
				if (strpos($leg,$string) !== false) {
		    		$purchase_order[$key]['sgst'] = $tax[0]['SGST'];
					$purchase_order[$key]['igst'] = 0;
					$purchase_order[$key]['cgst'] = $tax[0]['CGST'];
				}else{
					$purchase_order[$key]['sgst'] = 0;
					$purchase_order[$key]['igst'] = $tax[0]['IGST'];
					$purchase_order[$key]['cgst'] = 0;
				}
			}
			
		 
		$this->data['purchase_order_detail'] = $purchase_order;
		
		}else{
			$this->data['purchase_order_detail'] =  array();
			$this->data['account_id'] = '';
			$this->data['ledger_id'] = '';
			$this->data['purchase_id'] = '';
		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 1");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("EXEC	[dbo].[usp_GetNEWSerialNo] @Company_ID = 1, @VoucherType = 'SA', @Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('sales_order_report',$this->data);
	}

	public function sales_order_report_det() {
		if($this->input->post()){
		$po_id = $this->input->post('cmb_so_number');
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = 'SO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT'];
			$this->data['date'] = $purchase_order[0]['DATE'];
			$this->data['validtity'] = $purchase_order[0]['VALIDITY'];

			$this->data['purchase_id'] = $po_id;
			$this->data['ledger'] = $purchase_order[0]['LEDGER'];
			
			$this->data['so_number'] = $purchase_order[0]['PREFIX'].'00000'.$purchase_order[0]['VOUCHER_ID'];
		 
		$this->data['purchase_order_detail'] = $purchase_order;
	}
	$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 1");
		$this->data['purchase_order'] = $query_po->result_array();
		$this->load->view('sales_order_report',$this->data);
	}

	public function purchase_order_report() {
 		if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);			
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = 'PO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id;
			$this->data['date'] = $purchase_order[0]['LEDGER_ID'];
			foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @ID = '$item_id' ");
				$tax = $query_cmp->result('array');
				$string = 'DOMESTIC';
				if (strpos($leg,$string) !== false) {
		    		$purchase_order[$key]['sgst'] = $tax[0]['SGST'];
					$purchase_order[$key]['igst'] = 0;
					$purchase_order[$key]['cgst'] = $tax[0]['CGST'];
				}else{
					$purchase_order[$key]['sgst'] = 0;
					$purchase_order[$key]['igst'] = $tax[0]['IGST'];
					$purchase_order[$key]['cgst'] = 0;
				}
			}
			
		 
		$this->data['purchase_order_detail'] = $purchase_order;
		
		}else{
			$this->data['purchase_order_detail'] =  array();
			$this->data['account_id'] = '';
			$this->data['ledger_id'] = '';
			$this->data['purchase_id'] = '';
		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("EXEC	[dbo].[usp_GetNEWSerialNo] @Company_ID = 1, @VoucherType = 'SA', @Financialyear_id = 6");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('purchase_order_report',$this->data);
	}

	public function purchase_order_report_det() {
		if($this->input->post()){
		$po_id = $this->input->post('cmb_so_number');
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = 'PO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT'];
			$this->data['date'] = $purchase_order[0]['DATE'];
			$this->data['validtity'] = $purchase_order[0]['VALIDITY'];

			$this->data['purchase_id'] = $po_id;
			$this->data['ledger'] = $purchase_order[0]['LEDGER'];
			
			$this->data['so_number'] = $purchase_order[0]['PREFIX'].'00000'.$purchase_order[0]['VOUCHER_ID'];
		 
		$this->data['purchase_order_detail'] = $purchase_order;
	}
	$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		$this->load->view('purchase_order_report',$this->data);
	}

	public function manage_purchase_order(){

		$query_po = $this->db->query("SELECT PREFIX,SUM(AMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from Orders WHERE COMPANY_ID = 1 AND FINANCIALYEAR_ID = 6 AND ISSALESORDER =0 AND PREFIX = 'PO' group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE");
		$this->data['purchase_order'] = $query_po->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_purchase_order',$this->data);	

	}


	public function manage_sales_order(){

		$query_po = $this->db->query("SELECT PREFIX,SUM(AMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from Orders WHERE COMPANY_ID = 1 AND FINANCIALYEAR_ID = 6 AND ISSALESORDER =1 AND PREFIX = 'SO' group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE");
		$this->data['purchase_order'] = $query_po->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_sales_order',$this->data);	

	}

	public function manage_purchase_voucher(){

		$query_po = $this->db->query("SELECT PREFIX,SUM(AMOUNT)-SUM(DISCOUNT)+SUM(SGSTAMOUNT)+SUM(CGSTAMOUNT)+SUM(IGSTAMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from InvoiceTran WHERE COMPANY_ID = 1 AND FINANCIALYEAR_ID = 6 AND ISSALES =0 group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE");
		$this->data['purchase_voucher'] = $query_po->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_purchase_voucher',$this->data);	

	}

	public function manage_sales_voucher(){

		$query_po = $this->db->query("SELECT PREFIX,SUM(AMOUNT)-SUM(DISCOUNT)+SUM(IGSTAMOUNT)+SUM(CGSTAMOUNT)+SUM(SGSTAMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from InvoiceTran WHERE COMPANY_ID = 1 AND FINANCIALYEAR_ID = 6 AND ISSALES =1 group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE");
		$this->data['sales_voucher'] = $query_po->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_sales_voucher',$this->data);	

	}
	

public function edit_purchase_voucher() {
			$voucher_id = $this->uri->segment(3);	
			$prefix = $this->uri->segment(4);		
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetInvoiceTran] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];			
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
		 
		$this->data['purchase_order_detail'] = $purchase_order;	
		
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'PU', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'PU', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('edit_purchase_voucher',$this->data);
	}

	public function update_purchasevoucher() {

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = 1, @VoucherType = 'PU'");
		$gst = $query_sn->result_array();

		foreach($gst as $val) {
			if($val['GSTAccount'] == 'OUTPUT CGST'){
				$cgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'OUTPUT SGST'){
				$sgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'OUTPUT IGST'){
				$igst_id = $val['GSTAccount_ID'];
			}
		}

		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));
		$month =  date("m",strtotime($date1));
		$credit_period = $this->input->post('txt_validity');
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_so_number');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		
		$validity = $this->input->post('txt_validity');
		$user_name = $this->user_name;

		$total_discount = $this->input->post('txt_total_discount');
		$total_sgst= $this->input->post('txt_total_sgst');
		$total_cgst= $this->input->post('txt_total_cgst');
		$total_igst= $this->input->post('txt_total_igst');
		$final_total= $this->input->post('txt_final_total');

		$item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];
		$amounts = $_POST['txt_amount'];
		$discounts = $_POST['txt_discount']; 		
		$sgsts = $_POST['txt_sgst'];
		$sgst_pers = $_POST['txt_sgst_per'];
		$cgsts = $_POST['txt_cgst'];
		$cgst_pers = $_POST['txt_cgst_per']; 		
		$igsts = $_POST['txt_igst'];
		$igst_pers = $_POST['txt_igst_per'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;

		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];
		

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $rate = $rates[$i];
		 $unit_id = $unit[$i];		 
		 $amount = $amounts[$i];
		 $discount = $discounts[$i];
		 $cgst_per =$cgst_pers[$i];
		 $sgst_per = $sgst_pers[$i];
		 $igst_per = $igst_pers[$i]; 
		 $vat_per = 0;
		 $sgst = $sgsts[$i];
		 $igst = $igsts[$i];
		 $vat = 0;
		 $cgst = $cgsts[$i];

		 $insert .= " ( $company_id, ''$date'', 0, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name''), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_UpdPurchaseVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 0, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'PU', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @MODIFIEDBY = '$user_name', @month  = '$month', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'"); 

	if($result){
			redirect(site_url() . '/entry/manage_purchase_voucher/');
		}
	}

	public function edit_sales_voucher() {
			$voucher_id = $this->uri->segment(3);	
			$prefix = $this->uri->segment(4);		
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetInvoiceTran] @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
			/*foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = 1, @FINANCIALYEAR_ID = 6, @ID = '$item_id' ");
				$tax = $query_cmp->result('array');
				$string = 'DOMESTIC';
				if (strpos($leg,$string) !== false) {
		    		$purchase_order[$key]['sgst'] = $tax[0]['SGST'];
					$purchase_order[$key]['igst'] = 0;
					$purchase_order[$key]['cgst'] = $tax[0]['CGST'];
				}else{
					$purchase_order[$key]['sgst'] = 0;
					$purchase_order[$key]['igst'] = $tax[0]['IGST'];
					$purchase_order[$key]['cgst'] = 0;
				}
			}*/
			
		 
		$this->data['purchase_order_detail'] = $purchase_order;	
		
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = 1, @FINANCIALYEAR_ID = 6");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [Total].[dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID =1, @Vouchertype = 'SA', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = 1");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = 1, @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('edit_sales_voucher',$this->data);
	}

	public function update_salesvoucher() {

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = 1, @VoucherType = 'SA'");
		$gst = $query_sn->result_array();

		foreach($gst as $val) {
			if($val['GSTAccount'] == 'INPUT CGST'){
				$cgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'INPUT SGST'){
				$sgst_id = $val['GSTAccount_ID'];
			}

			if($val['GSTAccount'] == 'INPUT IGST'){
				$igst_id = $val['GSTAccount_ID'];
			}
		}

		$company_id = $this->company_id;
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));
		$month =  date("m",strtotime($date1));
		$credit_period = $this->input->post('txt_validity');
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_so_number');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		
		$validity = 0;
		$user_name = $this->user_name;

		$total_discount = $this->input->post('txt_total_discount');
		$total_sgst= $this->input->post('txt_total_sgst');
		$total_cgst= $this->input->post('txt_total_cgst');
		$total_igst= $this->input->post('txt_total_igst');
		$final_total= $this->input->post('txt_final_total');

		$item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];
		$amounts = $_POST['txt_amount'];
		$discounts = $_POST['txt_discount']; 		
		$sgsts = $_POST['txt_sgst'];
		$sgst_pers = $_POST['txt_sgst_per'];
		$cgsts = $_POST['txt_cgst'];
		$cgst_pers = $_POST['txt_cgst_per']; 		
		$igsts = $_POST['txt_igst'];
		$igst_pers = $_POST['txt_igst_per'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;

		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];
		

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $rate = $rates[$i];
		 $unit_id = $unit[$i];		 
		 $amount = $amounts[$i];
		 $discount = $discounts[$i];
		 $cgst_per =$cgst_pers[$i];
		 $sgst_per = $sgst_pers[$i];
		 $igst_per = $igst_pers[$i]; 
		 $vat_per = 0;
		 $sgst = $sgsts[$i];
		 $igst = $igsts[$i];
		 $vat = 0;
		 $cgst = $cgsts[$i];

		 $insert .= " ( $company_id, ''$date'', 1, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name''), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_UpdSalesVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 1, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'SA', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @MODIFIEDBY = '$user_name', @month  = '$month', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'"); 

	if($result){
			redirect(site_url() . 'entry/manage_sales_voucher/');
		}
	}




}
