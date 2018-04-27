<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry extends CI_Controller {	

	function __construct() {
        
        parent::__construct();
        date_default_timezone_set( 'Asia/Kolkata' );
        $this->company_id = $this->session->userdata('company_id');
        $this->finance_id = $this->session->userdata('financial_id');
        $this->user_name = $this->session->userdata('user_name');
        $this->load->model('admin_model');
        $this->db_name = $this->session->userdata('db_name');
        $this->connectapi->cons($this->db_name);
        if (!$this->session->userdata('is_logged_in'))
      redirect(site_url());
    }

	public function index() {

		$this->load->view('sales_voucher');
	}

	public function manage_debitnote(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;   	

        $query_dn = $this->db->query("EXEC [dbo].[usp_Get_manage_Debitnote] @COMPANY_ID	= '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = 'DN', @ISACTIVE = 1");
		$this->data['debit_note'] = $query_dn->result_array();

		$this->load->view('manage_debitnote',$this->data);	

	}

	public function debit_note() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'DN', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'DN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'DN' and Financialyear_id = '$finance_id'");
	$this->data['sn'] = $query_sn->result_array();

		$this->load->view('debit_note',$this->data);
	}

	public function add_debitnote() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'DN'");
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
		if($bill_no == ''){
			$bill_no = NULL;
		}
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
		$reference_date = $this->input->post('txt_reference_date');
		$reference_date =  date("m-d-Y",strtotime($reference_date));
		$refnum = $this->input->post('txt_reference_no');
		if($refnum == ''){
			$refnum = NULL;
		}
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$item = $_POST['cmb_item_name'];
		$qty = $_POST['txt_qty'];
		$unit_price = $_POST['txt_unit_price'];
		$discount = $_POST['txt_discount'];
		$total = $_POST['txt_total'];
		$unit_ids = $_POST['cmb_unit'];
		$k = count($item) - 1;
		$insert = 'insert into AllAccountTran([COMPANY_ID],[BILLDATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[DATE]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit = $unit_price[$i];
		 $discounts = $discount[$i];
		 $totals = $total[$i];
		 $unit_id = $unit_ids[$i];
		 $insert .= " ($company_id, ''$date'', $Financialyear_id, ''$voucher_type'', @voucherno, ''$bill_no'', $dr_account_id, $cr_account_id, $totals, $items, $qtys, $unit_id, $unit, $discounts, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id,''$refnum'',''$reference_date''), ";
		}
		
		$insert = rtrim($insert,", ");
		$insert = $insert."; ";			
		
		$result = $this->db->query("EXEC [dbo].[usp_InsDebitNote] @AllAccounttran = '$insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @prefix = '$voucher_type', @Financialyear_id = $Financialyear_id, @TotalAMOUNT = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT = $igst_amount, @CREATEDBY = '$user_name', @NARRATION = '$narration'  ");
		if($result){
			redirect(site_url() . '/entry/manage_debitnote/?msg=1');
		}

	}
	
	public function edit_debitnote() {

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
			$voucher_id = $this->uri->segment(3);	
			$prefix = $this->uri->segment(4);		
			//$query_dn_det = $this->db->query("SELECT *, REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from AllAccountTran WHERE COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX ='$prefix' AND VOUCHER_ID = '$voucher_id'");

			$query_dn_det = $this->db->query("EXEC [dbo].[usp_GetDebitnote] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");

			 $debit_note = $query_dn_det->result_array();
			$this->data['account_id'] = $accound_id = $debit_note[0]['DR_ACCOUNT_ID'];
			$this->data['ledger_id'] = $ledgeraccountid = $debit_note[0]['CR_ACCOUNT_ID'];
			$this->data['ledger'] = $ledger = $debit_note[0]['LEDGER'];
			$this->data['date'] = date("d-m-Y",strtotime($debit_note[0]['DATE']));
			$this->data['prefix'] = $debit_note[0]['PREFIX'];
			$this->data['sno'] = $debit_note[0]['V_ID'];
		 	$this->data['cgst'] = round($debit_note[0]['CGSTPERCENT'],2);
		 	$this->data['sgst'] = round($debit_note[0]['SGSTPERCENT'],2);
		 	$this->data['igst'] = round($debit_note[0]['IGSTPERCENT'],2);
		 	$this->data['cgst_amount'] = round($debit_note[0]['CGSTAMOUNT'],2);
		 	$this->data['sgst_amount'] = round($debit_note[0]['SGSTAMOUNT'],2);
		 	$this->data['igst_amount'] = round($debit_note[0]['IGSTAMOUNT'],2);
		 	$this->data['narration'] = $debit_note[0]['NARRATION'];
		 	$this->data['tax_id'] = $debit_note[0]['companytax_id'];
		 	$this->data['bill_no'] = $debit_note[0]['REFNUM'];
		 	$this->data['ref_date'] = date("d-m-Y",strtotime($debit_note[0]['BILLDATE']));
		 	$this->data['ref_no'] = $debit_note[0]['BILLNO'];
		 	$this->data['sn_id'] = $debit_note[0]['VOUCHER_ID'];
		$this->data['debit_note_detail'] = $debit_note;	
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Vouchertype = NULL, @ColumnType = NULL, @ledgeraccountid = '$ledgeraccountid'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'DN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'DN' and Financialyear_id = '$finance_id'");
	$this->data['sn'] = $query_sn->result_array();


	$string1 = 'SALES';
	
	if (strpos($ledger,$string1) !== false) {
		$sales = 1;
	}else{
		$sales = 0;
	}
	
	$query_bill = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$accound_id', @ISSALES = '$sales'");
	$this->data['bill'] = $query_bill->result_array();
		$this->load->view('edit_debitnote',$this->data);
	}

	public function update_debitnote() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'DN'");
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
		if($bill_no == ''){
			$bill_no = NULL;
		}
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
		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$reference_date = $this->input->post('txt_reference_date');
		$reference_date =  date("m-d-Y",strtotime($reference_date));
		$refnum = $this->input->post('txt_reference_no');
		if($refnum == ''){
			$refnum = NULL;
		}
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$item = $_POST['cmb_item_name'];
		$qty = $_POST['txt_qty'];
		$unit_price = $_POST['txt_unit_price'];
		$discount = $_POST['txt_discount'];
		$total = $_POST['txt_total'];
		$unit_ids = $_POST['cmb_unit'];
		$k = count($item) - 1;
		$insert = 'insert into AllAccountTran([COMPANY_ID],[BILLDATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[DATE]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit = $unit_price[$i];
		 $discounts = $discount[$i];
		 $totals = $total[$i];
		 $unit_id = $unit_ids[$i];
		 $insert .= " ($company_id, ''$date'', $Financialyear_id, ''$voucher_type'', @voucherno, ''$bill_no'', $dr_account_id, $cr_account_id, $totals, $items, $qtys, $unit_id, $unit, $discounts, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id,''$refnum'',''$reference_date''), ";
		}
		
		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		
		
		$result = $this->db->query("EXEC [dbo].[usp_UpdDebitNote] @AllAccounttran = '$insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @prefix = '$voucher_type', @VOUCHER_ID = $voucher_id,@Financialyear_id = $Financialyear_id, @GRANDTOTALAMOUNT = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT = $igst_amount, @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address', @NARRATION = '$narration'");
		if($result){
			redirect(site_url() . '/entry/manage_debitnote/?msg=1');
		}

	}
	

	public function manage_creditnote(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_dn = $this->db->query("EXEC [dbo].[usp_Get_manage_Creditnote] @COMPANY_ID	= '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = 'CN', @ISACTIVE = 1");
		
		$this->data['debit_note'] = $query_dn->result_array();

		$this->load->view('manage_creditnote',$this->data);	

	}


	public function credit_note() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CN', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();


		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'CN' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();

		$this->load->view('credit_note',$this->data);
	}	
	

	public function get_tax() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$tax_type = $this->input->get('tax');
		$leg = $this->input->get('leg');
		
		$query_cmp = $this->db->query("EXEC [dbo].[usp_GetCompanyTax]  @COMPANY_ID = '$company_id', @ISACTIVE = 1, @TAX_ID = '$tax_type', @LEDGERTYPE = '$leg' ");
		$tax = $query_cmp->result('array');
		$tax_val = array('sgst' => $tax[0]['SGST'],'igst' => $tax[0]['IGST'], 'cgst' => $tax[0]['CGST'] );
		echo json_encode($tax_val, JSON_FORCE_OBJECT);
		
	}

	public function get_rate() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$item_id = $this->input->get('item_id');

		$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ID = '$item_id', @ISEXPORT = NULL");
		$rate = $query_cmp->result('array');
		$rate_val = array('rate' => $rate[0]['SRATE'],'unit_id' => $rate[0]['UNIT_ID'], 'hsn_code' => $rate[0]['HSNCODE'], 'tax_id' => $rate[0]['CompanyTax_ID'],'stock' => $rate[0]['STOCK'] );
		echo json_encode($rate_val, JSON_FORCE_OBJECT);
		
	}

	public function add_creditnote() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'CN'");
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
		if($bill_no == ''){
			$bill_no = NULL;
		}
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
		$reference_date = $this->input->post('txt_reference_date');
		$reference_date =  date("m-d-Y",strtotime($reference_date));
		$refnum = $this->input->post('txt_reference_no');
		if($refnum == ''){
			$refnum = NULL;
		}
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$item = $_POST['cmb_item_name'];
		$qty = $_POST['txt_qty'];
		$unit_price = $_POST['txt_unit_price'];
		$discount = $_POST['txt_discount'];
		$total = $_POST['txt_total'];
		$unit_ids = $_POST['cmb_unit'];
		$k = count($item) - 1;
		$insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[BILLDATE]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit = $unit_price[$i];
		 $discounts = $discount[$i];
		 $totals = $total[$i];
		 $unit_id = $unit_ids[$i];
		 $insert .= " ($company_id, ''$reference_date'', $Financialyear_id, ''$voucher_type'', @voucherno, ''$bill_no'', $dr_account_id, $cr_account_id, $totals, $items, $qtys, $unit_id, $unit, $discounts, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id,''$refnum'',''$date''), ";
		}
		
		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		
		
		$result = $this->db->query("EXEC [dbo].[usp_InsCreditNote] @AllAccounttran = '$insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @prefix = '$voucher_type', @Financialyear_id = $Financialyear_id, @TotalAMOUNT = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT = $igst_amount, @CREATEDBY = '$user_name', @NARRATION = '$narration'  ");

		if($result){
			redirect(site_url() . '/entry/manage_creditnote/?msg=1');
		}
		
	}

	public function edit_creditnote() {
			$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
			$voucher_id = $this->uri->segment(3);	
			$prefix = $this->uri->segment(4);		
			//$query_dn_det = $this->db->query("SELECT *, REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from AllAccountTran WHERE COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX ='$prefix' AND VOUCHER_ID = '$voucher_id'");

			$query_dn_det = $this->db->query("EXEC [dbo].[usp_GetCreditnote] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $debit_note = $query_dn_det->result_array();
			$this->data['ledger_id'] = $ledgeraccountid = $debit_note[0]['DR_ACCOUNT_ID'];
			$this->data['ledger'] = $ledger = $debit_note[0]['LEDGER'];
			$this->data['account_id'] = $accound_id = $debit_note[0]['CR_ACCOUNT_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($debit_note[0]['DATE']));
			$this->data['prefix'] = $debit_note[0]['PREFIX'];
			$this->data['sno'] = $debit_note[0]['V_ID'];
		 	$this->data['cgst'] = round($debit_note[0]['CGSTPERCENT'],2);
		 	$this->data['sgst'] = round($debit_note[0]['SGSTPERCENT'],2);
		 	$this->data['igst'] = round($debit_note[0]['IGSTPERCENT'],2);
		 	$this->data['cgst_amount'] = round($debit_note[0]['CGSTAMOUNT'],2);
		 	$this->data['sgst_amount'] = round($debit_note[0]['SGSTAMOUNT'],2);
		 	$this->data['igst_amount'] = round($debit_note[0]['IGSTAMOUNT'],2);
		 	$this->data['narration'] = $debit_note[0]['NARRATION'];
		 	$this->data['tax_id'] = $debit_note[0]['companytax_id'];
		 	$this->data['bill_no'] = $debit_note[0]['REFNUM'];
		 	$this->data['ref_date'] = date("d-m-Y",strtotime($debit_note[0]['BILLDATE']));
		 	$this->data['ref_no'] = $debit_note[0]['BILLNO'];
		 	$this->data['sn_id'] = $debit_note[0]['VOUCHER_ID'];
		$this->data['credit_note_detail'] = $debit_note;	
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Vouchertype = NULL, @ColumnType = NULL, @ledgeraccountid = '$ledgeraccountid'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();


		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'CN' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
	$this->data['sn'] = $query_sn->result_array();

	$string1 = 'SALES';
	
	if (strpos($ledger,$string1) !== false) {
		$sales = 1;
	}else{
		$sales = 0;
	}
	
	$query_bill = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$accound_id', @ISSALES = '$sales'");
	$this->data['bill'] = $query_bill->result_array();

		$this->load->view('edit_creditnote',$this->data);
	}

	public function update_creditnote() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'CN'");
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
		if($bill_no == ''){
			$bill_no = NULL;
		}
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
		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];
		$reference_date = $this->input->post('txt_reference_date');
		$reference_date =  date("m-d-Y",strtotime($reference_date));
		$refnum = $this->input->post('txt_reference_no');
		if($refnum == ''){
			$refnum = NULL;
		}
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$item = $_POST['cmb_item_name'];
		$qty = $_POST['txt_qty'];
		$unit_price = $_POST['txt_unit_price'];
		$discount = $_POST['txt_discount'];
		$total = $_POST['txt_total'];
		$unit_ids = $_POST['cmb_unit'];
		$k = count($item) - 1;
		$insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[BILLDATE]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit = $unit_price[$i];
		 $discounts = $discount[$i];
		 $totals = $total[$i];
		 $unit_id = $unit_ids[$i];
		 $insert .= " ($company_id, ''$reference_date'', $Financialyear_id, ''$voucher_type'', @voucherno, ''$bill_no'', $dr_account_id, $cr_account_id, $totals, $items, $qtys, $unit_id, $unit, $discounts, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id,''$refnum'',''$date''), ";
		}
		
		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		
		
		$result = $this->db->query("EXEC [dbo].[usp_UpdCreditNote] @AllAccounttran = '$insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @prefix = '$voucher_type', @VOUCHER_ID = $voucher_id,@Financialyear_id = $Financialyear_id, @GRANDTOTALAMOUNT = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT = $igst_amount, @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address', @NARRATION = '$narration'  ");

		if($result){
			redirect(site_url() . '/entry/manage_creditnote/?msg=1');
		}
		
	}
	

	public function sales_quotation() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'SO', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'SO', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'SO' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->load->view('sales_quotation',$this->data);

 	}


	public function edit_salesquotation() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $pre = $this->input->get('pre');
		$prefix = urldecode($pre);
        $voucher_id = $this->uri->segment(3);
        $order_type =  $this->uri->segment(4);
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'SO', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'SO', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'SO' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_po = $this->db->query("EXEC [dbo].[usp_GetOrders_quotation] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISSALESORDER = NULL, @ISACTIVE = 1");
		$this->data['purchase_order_det'] = $purchase_order_det = $query_po->result_array();

		$this->data['prefix_voucher_id'] = $purchase_order_det[0]['V_ID'];
		$this->data['prefix_voucher_type'] = $purchase_order_det[0]['PREFIX'];
		$this->data['quote_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['DATE']));
		$this->data['validity'] = $purchase_order_det[0]['VALIDITY'];
		$this->data['qt_ref_no'] = $purchase_order_det[0]['QUOTATION_REFERENCE'];
		$this->data['qt_ref_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['QUOTATION_REFERENCE_DATE']));
		$this->data['account_id'] = $account_id = $purchase_order_det[0]['ACCOUNT_ID'];
		$this->data['ledger_id'] = $purchase_order_det[0]['LEDGER_ID'];
		$this->data['termsnconditions'] = $purchase_order_det[0]['TermsandConditions'];

		$this->load->view('edit_salesquotation',$this->data);

 	}


 	public function add_salesquotation() {

 		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
 		
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
		if($validity == ''){
			$validity = 0;
		}
		$user_name = $this->user_name;
		$final_amount = $this->input->post('txt_final_total');
		$ref_num = $this->input->post('txt_ref_num');
		if($ref_num == ''){
			$ref_num = NULL;
		}
		$ref_date1 = $this->input->post('txt_ref_date');
		$reference_date =  date("m-d-Y",strtotime($ref_date1));
		$terms = $this->input->post('txt_terms');
		$terms = str_replace("'","''",$terms);

 		$item = $_POST['cmb_item_name']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];		
		$total = $_POST['txt_total'];
		$tax = $_POST['txt_tax'];

		$k = count($item) - 1;
		

 		$insert = 'insert into [dbo].[Orders] ([DATE], [COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [VALIDITY], [ISSALESORDER], [ACCOUNT_ID], [LEDGER_ID], [ITEM_ID], [UNIT_ID], [RATE], [QTY], [AMOUNT], [CREATEDBY], [CREATEDON],[TAXAMOUNT] ) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit_ids = $unit_id[$i];
		 $rate = $rates[$i];
		 $amount = $total[$i];
		 $taxs = $tax[$i];

		 $insert .= " (''$date'', $company_id, $Financialyear_id,''$voucher_type'', @voucherno, $validity, NULL, $account_id, $ledger_id, $items, $unit_ids, $rate, $qtys, $amount, ''$user_name'', getdate(), $taxs), ";
		}

		$insert = rtrim($insert,", ");

 		$result = $this->db->query("EXEC[dbo].[usp_InsOrders] @Orders = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id,@QUOTATION_REFERENCE = '$ref_num', @QUOTATION_REFERENCE_DATE = '$reference_date', @TermsandConditions = '$terms' ");
 		

 		if($result){
			redirect(site_url() . '/entry/manage_sales_quotation/?msg=1');
		}
 	}

 	public function update_salesquotation() {

 		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
 		
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
		if($validity == ''){
			$validity = 0;
		}
		$user_name = $this->user_name;
		$final_amount = $this->input->post('txt_final_total');
		$ref_num = $this->input->post('txt_ref_num');
		if($ref_num == ''){
			$ref_num = NULL;
		}
		$ref_date1 = $this->input->post('txt_ref_date');
		$reference_date =  date("m-d-Y",strtotime($ref_date1));
		$terms = $this->input->post('txt_terms');
		$terms = str_replace("'","''",$terms);
		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];

 		$item = $_POST['cmb_item_name']; 		
		$rate = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];		
		$total = $_POST['txt_total'];
		$tax = $_POST['txt_tax'];

		$k = count($item) - 1;
		

 		$insert = 'insert into [dbo].[Orders] ([DATE], [COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [VALIDITY], [ISSALESORDER], [ACCOUNT_ID], [LEDGER_ID], [ITEM_ID], [UNIT_ID], [RATE], [QTY], [AMOUNT], [CREATEDBY], [CREATEDON],[TAXAMOUNT] ) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit_ids = $unit_id[$i];
		 $rates = $rate[$i];
		 $amount = $total[$i];
		 $taxs = $tax[$i];

		 $insert .= " (''$date'', $company_id, $Financialyear_id,''$voucher_type'', @voucherno, $validity, NULL, $account_id, $ledger_id, $items, $unit_ids, $rates, $qtys, $amount, ''$user_name'', getdate(), $taxs), ";
		}

		$insert = rtrim($insert,", ");


 		$result = $this->db->query("EXEC[dbo].[usp_UpdOrders] @Orders = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id,@QUOTATION_REFERENCE = '$ref_num', @QUOTATION_REFERENCE_DATE = '$reference_date', @TermsandConditions = '$terms',@machineName = '$machine_name' , @ipaddress = '$ip_address' ");
 		

 		if($result){
			redirect(site_url() . '/entry/manage_sales_quotation/?msg=1');
		}
 	}

 	public function delete_sales_quotation(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelOrders] @Company_ID = '$company_id', @Financialyear_id = '$finance_id', @prefix = '$prefix', @VOUCHER_ID= '$voucher_id', @USERID = '$user_name', @machinename = '$machine_name', @ipaddress = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_sales_quotation/');
		}

	}

 	public function purchase_order() {

 		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
 		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'PO', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'PO', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'PO' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->load->view('purchase_order',$this->data);
	}

	public function edit_purchase_order() {

 		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $pre = $this->input->get('pre');
		$prefix = urldecode($pre);
        $voucher_id = $this->uri->segment(3);
        $order_type =  $this->uri->segment(4);
 		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();		

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'PO' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_po = $this->db->query("EXEC	[dbo].[usp_GetOrders_quotation] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISSALESORDER = '$order_type', @ISACTIVE = 1");
		$this->data['purchase_order_det'] = $purchase_order_det = $query_po->result_array();

		$this->data['prefix_voucher_id'] = $purchase_order_det[0]['V_ID'];
		$this->data['prefix_voucher_type'] = $purchase_order_det[0]['PREFIX'];
		$this->data['po_no'] = $purchase_order_det[0]['PURCHASE_ORDER_NO']; 
		$this->data['po_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['DATE']));
		$this->data['credit_period'] = $purchase_order_det[0]['VALIDITY'];
		$this->data['qt_ref_no'] = $purchase_order_det[0]['QUOTATION_REFERENCE'];
		$this->data['qt_ref_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['QUOTATION_REFERENCE_DATE']));
		$this->data['account_id'] = $account_id = $purchase_order_det[0]['ACCOUNT_ID'];
		$this->data['ledger_id'] = $purchase_order_det[0]['LEDGER_ID'];
		$this->data['vendor_code'] = $purchase_order_det[0]['VENDOR_CODE'];
		$this->data['project_ref'] = $purchase_order_det[0]['PROJECT_REFERNCE'];
		$this->data['test_certificate'] = $purchase_order_det[0]['TEST_CERTIFICATE'];
		$this->data['mode_of_transport'] = $purchase_order_det[0]['TRANSPORTATION'];
		$this->data['shipping_id'] = $purchase_order_det[0]['PARTYADDRESS_ID'];
		$this->data['order_type'] = $order_type = $purchase_order_det[0]['ISSALESORDER'];
		$this->data['termsnconditions'] = $purchase_order_det[0]['TermsandConditions'];
		$this->data['delivery_from'] = date("d-m-Y",strtotime($purchase_order_det[0]['DELIVERY_FROM']));
		$this->data['delivery_to'] =  date("d-m-Y",strtotime($purchase_order_det[0]['DELIVERY_TO']));

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress]  @ACCOUNT_ID  = '$account_id', @COMPANY_ID = '$company_id', @ISACTIVE = 1");
		$this->data['party_address'] = $query_address->result_array();

		if($order_type == 0){
		$voucher_type = 'PO';
		}else{
			$voucher_type = 'SO';
		}

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = '$voucher_type', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = '$voucher_type', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$this->load->view('edit_purchase_order',$this->data);
	}

	public function add_purchaseorder() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
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
		if($validity == ''){
			$validity = 0;
		}
		$user_name = $this->user_name;
		$final_amount = $this->input->post('txt_final_total');	
		$sales_type = $this->input->post('chk_type');
		$bill_no = $this->input->post('txt_bill_no');
		$reference_date1 = $this->input->post('txt_reference_date');	
		$reference_date =  date("m-d-Y",strtotime($reference_date1));
		$ref_num = $this->input->post('txt_ref_num');
		$vendor_code = $this->input->post('txt_vendor_code');
		$project_ref = $this->input->post('txt_project_ref');
		$test_certificate = $this->input->post('txt_test_certificate');
		$transport = $this->input->post('txt_transport');
		$jurisdiction = $this->input->post('txt_jurisdiction');
		$shipping_id = $this->input->post('cmb_shipping');
		$delivery_from_date1 = $this->input->post('txt_delivery_from_date');
		$delivery_from_date =  date("m-d-Y",strtotime($delivery_from_date1));
		$delivery_to_date1 = $this->input->post('txt_delivery_to_date');
		$delivery_to_date =  date("m-d-Y",strtotime($delivery_to_date1));

		
		$terms = $this->input->post('txt_terms');
		$terms = str_replace("'","''",$terms);

 		$item = $_POST['cmb_item_name']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];		
		$total = $_POST['txt_total'];

		$k = count($item) - 1;
		

 		$insert = 'insert into [dbo].[Orders] ([DATE], [COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [VALIDITY], [ISSALESORDER], [ACCOUNT_ID], [LEDGER_ID], [ITEM_ID], [UNIT_ID], [RATE], [QTY], [AMOUNT], [CREATEDBY], [CREATEDON] ) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit_id = $unit_ids[$i];
		 $rate = $rates[$i];
		 $amount = $total[$i];

		 $insert .= " (''$date'', $company_id, $Financialyear_id,''$voucher_type'', @voucherno, $validity, $sales_type, $account_id, $ledger_id, $items, $unit_id, $rate, $qtys, $amount, ''$user_name'', getdate() ), ";
		}

		$insert = rtrim($insert,", ");
		
		
 		$result = $this->db->query("EXEC [dbo].[usp_InsOrders] @ORDERS = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id, @PURCHASE_ORDER_NO  = '$bill_no', @QUOTATION_REFERENCE = '$ref_num', @QUOTATION_REFERENCE_DATE = '$reference_date', @PROJECT_REFERNCE  = '$project_ref', @VENDOR_CODE  = '$vendor_code', @CREDIT_PERIOD = $validity, @DELIVERY_FROM = '$delivery_from_date', @DELIVERY_TO  = '$delivery_to_date', @TEST_CERTIFICATE  = '$test_certificate', @PARTYADDRESS_ID  = '$shipping_id', @TRANSPORTATION  = '$transport', @ORDER_JURISDICTION  = '$jurisdiction', @USERID  = '$user_name', @TermsandConditions = '$terms'");
 		if($result){
			redirect(site_url() . '/entry/manage_purchase_order/?msg=1');
		}

 	}

 	public function update_purchaseorder() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
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
		if($validity == ''){
			$validity = 0;
		}
		$user_name = $this->user_name;
		$final_amount = $this->input->post('txt_final_total');	
		$sales_type = $this->input->post('chk_type');
		$bill_no = $this->input->post('txt_bill_no');
		$reference_date1 = $this->input->post('txt_reference_date');	
		$reference_date =  date("m-d-Y",strtotime($reference_date1));
		$ref_num = $this->input->post('txt_ref_num');
		$vendor_code = $this->input->post('txt_vendor_code');
		$project_ref = $this->input->post('txt_project_ref');
		$test_certificate = $this->input->post('txt_test_certificate');
		$transport = $this->input->post('txt_transport');
		$jurisdiction = $this->input->post('txt_jurisdiction');
		$shipping_id = $this->input->post('cmb_shipping');
		$delivery_from_date1 = $this->input->post('txt_delivery_from_date');
		$delivery_from_date =  date("m-d-Y",strtotime($delivery_from_date1));
		$delivery_to_date1 = $this->input->post('txt_delivery_to_date');
		$delivery_to_date =  date("m-d-Y",strtotime($delivery_to_date1));
		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];
		
		$terms = $this->input->post('txt_terms');
		$terms = str_replace("'","''",$terms);

 		$item = $_POST['cmb_item_name']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];		
		$total = $_POST['txt_total'];

		$k = count($item) - 1;
		

 		$insert = 'insert into [dbo].[Orders] ([DATE], [COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [VALIDITY], [ISSALESORDER], [ACCOUNT_ID], [LEDGER_ID], [ITEM_ID], [UNIT_ID], [RATE], [QTY], [AMOUNT], [CREATEDBY], [CREATEDON] ) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $unit_id = $unit_ids[$i];
		 $rate = $rates[$i];
		 $amount = $total[$i];

		 $insert .= " (''$date'', $company_id, $Financialyear_id,''$voucher_type'', @voucherno, $validity, $sales_type, $account_id, $ledger_id, $items, $unit_id, $rate, $qtys, $amount, ''$user_name'', getdate() ), ";
		}
		
		$insert = rtrim($insert,", ");
		
 		$result = $this->db->query("EXEC [dbo].[usp_UpdOrders] @ORDERS = '$insert', @COMPANY_ID = $company_id, @FINANCIALYEAR_ID = $Financialyear_id, @PREFIX = '$voucher_type', @VOUCHER_ID = $voucher_id, @PURCHASE_ORDER_NO  = '$bill_no', @QUOTATION_REFERENCE = '$ref_num', @QUOTATION_REFERENCE_DATE = '$reference_date', @PROJECT_REFERNCE  = '$project_ref', @VENDOR_CODE  = '$vendor_code', @CREDIT_PERIOD = $validity, @DELIVERY_FROM = '$delivery_from_date', @DELIVERY_TO  = '$delivery_to_date', @TEST_CERTIFICATE  = '$test_certificate', @PARTYADDRESS_ID  = '$shipping_id', @TRANSPORTATION  = '$transport', @ORDER_JURISDICTION  = '$jurisdiction', @USERID  = '$user_name', @TermsandConditions = '$terms',@machineName = '$machine_name' , @ipaddress = '$ip_address' ");
 		if($result){
			redirect(site_url() . '/entry/manage_purchase_order/?msg=1');
		}

 	}

 	public function delete_purchase_order(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelOrders] @Company_ID = '$company_id', @Financialyear_id = '$finance_id', @prefix = '$prefix', @VOUCHER_ID= '$voucher_id', @USERID = '$user_name', @machinename = '$machine_name', @ipaddress = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_purchase_order/');
		}

	}

 	public function sales_voucher() {
 		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
 		if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);
			$voucher_type = $this->input->get('v_type');			
			$this->data['v_type'] = trim($voucher_type);		
			$sn = $this->input->get('sn');
			$this->data['ser_num'] = $sn;		
			
			$this->data['bill']  = $this->input->get('bill');
				
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = 'PO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1, @ISSALESORDER = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $account_id=   $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id;
			$this->data['date'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['validity']  = $purchase_order[0]['VALIDITY'];
			foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ID = '$item_id' ");
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

		$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 1, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();
		$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		$this->data['ac_cur_val'] =  $ac_cur[0]['CURRENTBALANCE'];
		
		}else{
			$this->data['purchase_order_detail'] =  array();
			
			$this->data['ledger_id'] = '';
			if(isset($_GET)){
				$acc = $this->input->get('acc');
			$this->data['account_id'] = $account_id = $acc;
				$voucher_type = $this->input->get('v_type');
				$this->data['v_type'] = trim($voucher_type);		
				$sn = $this->input->get('sn');
				$this->data['ser_num'] = $sn;		
				$this->data['validity']  = 0;
				$this->data['bill']  = $this->input->get('bill');
				$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',	@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 1, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();
		
			$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		if(!empty($ac_cur)){
			$this->data['ac_cur_val'] =  $ac_cur[0]['CURRENTBALANCE'];
		}else{
			$this->data['ac_cur_val'] =  '';
		}
			}else{
				$this->data['v_type'] = '';
				$this->data['ser_num'] = '';		
				$this->data['validity']  = 0;
				$this->data['ac_cur_val'] =  '';
				$this->data['purchase_order'] = '';
			}
			$this->data['purchase_id'] = '';
			
			
			
		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();

		
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'SA', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'SA', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_transport = $this->db->query("EXEC [dbo].[usp_GetModeofTransport]");
		$this->data['transport_mode'] = $query_transport->result_array();

		$query_sn = $this->db->query("Select prefix from SerialNo where Company_ID = '$company_id' and  VoucherType = 'SA' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$query_bill = $this->db->query("Select * from SerialNo where Company_ID = '$company_id' and  VoucherType = 'LS' and Financialyear_id = '$finance_id' ");
		$this->data['last_bill'] = $last_bill = $query_bill->result_array();

		$this->data['bill']  = $last_bill[0]['SerialNo'] + 1;

		$query = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, @COMPANY_ID = '$company_id'");
		$this->data['account_group'] = $query->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		
		$this->load->view('sales_voucher',$this->data);
	}

	public function check_sales_bill() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $bill_no = $this->input->get('bill_no');
        $query_bill = $this->db->query("select * from dbo.[InvoiceTran] where REFNUM = '". $bill_no."' AND COMPANY_ID ='$company_id' AND ISSALES = '1' AND FINANCIALYEAR_ID = '$finance_id' and ISACTIVE = 1 and IsCancelled = 0 ");
		$billno = $query_bill->num_rows();
		if($billno != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}
	}

	public function add_salesvoucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'SA'");
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
		if($credit_period == ''){
		$credit_period = 0;	
		}
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_so_number');
		$shipping = $this->input->post('cmb_shipping');
		if($cmb_so_number == '' ){
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
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}		
		if($shipping == ''){			
				$shipping = 0;			
		}
		$transport_mode = $this->input->post('txt_transport_mode');
		if($transport_mode == ''){
			$transport_mode = NULL;
		}
		$vechicle_no = $this->input->post('txt_vechicle_no');
		if($vechicle_no == ''){
			$vechicle_no = NULL;
		}
		$date_of_supply1 = $this->input->post('txt_date_of_supply');
		$date_of_supply =  date("m-d-Y",strtotime($date_of_supply1));

		$round_off =  $this->input->post('txt_round_off');

		$dc_no =  $this->input->post('txt_dc_no');
		if($dc_no == ''){
			$dc_no = NULL;
		}
		$po_manual_no =  $this->input->post('txt_po_manual');
		if($po_manual_no == '' || $cmb_so_number != 0 ){
			$po_manual_no = NULL;
		}

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY],[REFNUM],[shipping_partyAddress_ID],[MODEOFTRANSPORT],[VEHICLEREGNO],[DATEOFSUPPLY],[ROUNDOFF], [DeliveryChalanNo], [DirectPONo]) VALUES ';

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

		 $insert .= " ( $company_id, ''$date'', 1, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name'', @refnum, $shipping, ''$transport_mode'', ''$vechicle_no'',''$date_of_supply'', $round_off, ''$dc_no'', ''$po_manual_no'' ), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";

		$result = $this->db->query("EXEC [dbo].[usp_InsSalesVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 1, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'SA', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @CREATEDBY = '$user_name', @month  = '$month', @REFNUM = '$bill_no', @ROUNDOFF = $round_off"); 
		if($result){
			redirect(site_url() . '/entry/manage_sales_voucher/?msg=1');
		}

	}

	public function get_item_tax() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$item_id = $this->input->get('item_id');
		$leg = $this->input->get('leg');
		$export = 0;
		$string1 = 'EXPORT';
		if (strpos($leg,$string1) !== false) {
			$export = 1;
		}
		$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ID = '$item_id',@ISEXPORT = '$export'");
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

		$tax_val = array( 'unit_id' => $tax[0]['UNIT_ID'], 'rate' => $tax[0]['SRATE'], 'sgst' => $sgst,'igst' => $igst, 'cgst' => $cgst, 'stock' => $tax[0]['STOCK'], 'tax_id' => $tax[0]['CompanyTax_ID']  );
		echo json_encode($tax_val, JSON_FORCE_OBJECT);
		
	}

	public function new_sn() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$type = $this->input->get('type');
		
		$query_sn = $this->db->query("EXEC	[dbo].[usp_GetNEWSerialNo] @Company_ID = '$company_id', @prefix = '$type', @Financialyear_id = '$finance_id'");
		$sn = $query_sn->result_array();

		$sn_val = array('sn' => $sn[0]['SN'], );
		echo json_encode($sn_val, JSON_FORCE_OBJECT);
		
	}

public function get_sales_order() {
	$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $account_id = $this->input->get('account_id');	
		$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$orders = $query_po->result_array();
		echo json_encode($orders, JSON_FORCE_OBJECT);
}

public function get_sales_order_sales() {
	$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $account_id = $this->input->get('account_id');	
	$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 1, @ACCOUNTID = ' $account_id'");
		$orders = $query_po->result_array();
		echo json_encode($orders, JSON_FORCE_OBJECT);
}

public function get_account() {
	$company_id = $this->company_id;
	$val = $this->input->get('val');
	if($val == 'purchase'){
		$voucher_type = 'PO';
	}else{
		$voucher_type = 'SO';
	}
	$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = '$voucher_type', @ColumnType = 'Account'");
		$account = $query_acc->result_array();
		echo json_encode($account, JSON_FORCE_OBJECT);
}

public function get_ledger() {
	$company_id = $this->company_id;
	$val = $this->input->get('val');
	if($val == 'purchase'){
		$voucher_type = 'PO';
	}else{
		$voucher_type = 'SO';
	}
	$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = '$voucher_type', @ColumnType = 'Ledger'");
		$account = $query_acc->result_array();
		echo json_encode($account, JSON_FORCE_OBJECT);
}

public function get_current_account() {

	$company_id = $this->company_id;
    $account_id = $this->input->get('account_id');	
	$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		$ac_cur_val = array('curr' => $ac_cur[0]['CURRENTBALANCE'], );
		echo json_encode($ac_cur_val, JSON_FORCE_OBJECT);
}

	public function purchase_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);
			$pur_id = explode('-', $po_id);
			$po_pre = $pur_id[0];
			$po_pre_id = $pur_id[1];
			$voucher_type = $this->input->get('v_type');
			$this->data['v_type'] = trim($voucher_type);		
			$sn = $this->input->get('sn');
			$this->data['ser_num'] = $sn;
			$this->data['bill']  = $this->input->get('bill');			
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$po_pre', @VOUCHER_ID = '$po_pre_id', @ISACTIVE = 1, @ISSALESORDER = 0, @PURCHASE_ORDER_VOUCHER_ID = '$po_pre_id'");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $account_id = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id;
			$this->data['validity']  = $purchase_order[0]['VALIDITY'];
			$this->data['grn_no'] =  $purchase_order[0]['GRNNO'];
			foreach ($purchase_order as $key => $value) {
				$item_id = $value['ITEM_ID'];
				$leg = $value['LEDGER'];
				$query_cmp = $this->db->query("EXEC [dbo].[usp_GetItem]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ID = '$item_id' ");
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
		$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();
		$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		$this->data['ac_cur_val'] =  $ac_cur[0]['CURRENTBALANCE'];
		}else{
			$this->data['purchase_order_detail'] =  array();
			$this->data['account_id'] = $account_id = $this->input->get('acc');
			$this->data['ledger_id'] = '';
			$this->data['purchase_id'] = '';
			$this->data['grn_no'] =  '';
			if($this->input->get()){
				$voucher_type = $this->input->get('v_type');
				$this->data['v_type'] = trim($voucher_type);		
				$sn = $this->input->get('sn');
				$this->data['ser_num'] = $sn;		
				$this->data['validity']  = $this->input->get('val');
				$this->data['bill']  = $this->input->get('bill');
				$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();	
				$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		if(!empty($ac_cur)){
			$this->data['ac_cur_val'] =  $ac_cur[0]['CURRENTBALANCE'];
		}else{
			$this->data['ac_cur_val'] =  '';
		}			

			}else{
				$this->data['v_type'] = '';
				$this->data['ser_num'] = '';		
				$this->data['validity']  = 0;
				$this->data['bill']  = '';
				$this->data['purchase_order'] = '';
				$this->data['ac_cur_val'] =  '';				
			}

		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();		
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'PU', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'PU', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix from SerialNo where Company_ID = '$company_id' and  VoucherType = 'PU' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, @COMPANY_ID = '$company_id'");
		$this->data['account_group'] = $query->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('purchase_voucher',$this->data);
	}

	public function check_purchase_bill() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $bill_no = $this->input->get('bill_no');
        $query_bill = $this->db->query("select * from dbo.[InvoiceTran] where REFNUM = '". $bill_no."' AND COMPANY_ID ='$company_id' AND ISSALES = '0' AND FINANCIALYEAR_ID = '$finance_id' ");
		$billno = $query_bill->num_rows();
		if($billno != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}
	}
	public function add_purchasevoucher() {

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'PU'");
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
		if($credit_period == ''){
		$credit_period = 0;	
		}
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$account_id = $this->input->post('cmb_account_group');
		$ledger_id= $this->input->post('cmb_ledger');
		$voucher_id = $this->input->post('txt_serial_no');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$po_id = $this->input->post('cmb_so_number');
		$pur_id = explode('-', $po_id);
		$po_pre = $pur_id[0];
		$cmb_so_number = $pur_id[1];		
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
			$po_pre = NULL;
		}

		//$shipping = $this->input->post('cmb_shipping');
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
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
		$round_off =  $this->input->post('txt_round_off');
		

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY],[REFNUM],[PURCHASEORDER_PREFIX],[ROUNDOFF]) VALUES ';

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

		 $insert .= " ( $company_id, ''$date'', 0, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name'',''$bill_no'',''$po_pre'', $round_off), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_InsPurchaseVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 0, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'PU', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @CREATEDBY = '$user_name', @month  = '$month', @ROUNDOFF = $round_off"); 
		

	if($result){
			redirect(site_url() . '/entry/manage_purchase_voucher/?msg=1');
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

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 1");
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
	$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 1");
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

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
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
	$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = 1 and FINANCIALYEAR_ID = 6 and ISSALESORDER = 0");
		$this->data['purchase_order'] = $query_po->result_array();
		$this->load->view('purchase_order_report',$this->data);
	}

	public function manage_purchase_order(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_po = $this->db->query("SELECT o.PREFIX,SUM(o.AMOUNT) as Total,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,REPLICATE('0',6-LEN(RTRIM(o.VOUCHER_ID))) + RTRIM(o.VOUCHER_ID) as V_ID,ORR.PURCHASE_ORDER_NO,o.ISSALESORDER  from Orders o Left Outer Join ORDERREFERENCE ORR with (nolock) on o.PREFIX=orr.PREFIX and o.VOUCHER_ID=orr.VOUCHER_ID and orr.FINANCIALYEAR_ID='$finance_id'   and ORR.company_id= '$company_id' WHERE o.COMPANY_ID = '$company_id' AND o.FINANCIALYEAR_ID = '$finance_id' AND o.PREFIX = 'PO' group by o.PREFIX,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,ORR.PURCHASE_ORDER_NO,o.ISSALESORDER ");
		$this->data['purchase_order'] = $query_po->result_array();

	

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id'");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_purchase_order',$this->data);	

	}


	public function manage_sales_quotation(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_po = $this->db->query("SELECT o.PREFIX,SUM(o.AMOUNT) as Total,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,REPLICATE('0',6-LEN(RTRIM(o.VOUCHER_ID))) + RTRIM(o.VOUCHER_ID) as V_ID,ORR.QUOTATION_REFERENCE from Orders o Left Outer Join ORDERREFERENCE ORR with (nolock) on o.PREFIX=orr.PREFIX and o.VOUCHER_ID=orr.VOUCHER_ID and orr.FINANCIALYEAR_ID='$finance_id' and ORR.company_id= '$company_id' WHERE o.COMPANY_ID = '$company_id' AND o.FINANCIALYEAR_ID = '$finance_id' AND o.ISSALESORDER IS NULL AND o.PREFIX = 'SO' group by o.PREFIX,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,ORR.QUOTATION_REFERENCE");

		$this->data['purchase_order'] = $query_po->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id'");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_sales_quotation',$this->data);	

	}

	public function manage_purchase_voucher(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_po = $this->db->query("EXEC [dbo].[usp_Get_manage_InvoiceTran]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id',	@ISACTIVE = 1,@ISSALES = 0");
		
		$this->data['purchase_voucher'] = $query_po->result_array();


		$this->load->view('manage_purchase_voucher',$this->data);	

	}

	public function manage_sales_voucher(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_po = $this->db->query("EXEC [dbo].[usp_Get_manage_InvoiceTran]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id',	@ISACTIVE = 1,@ISSALES = 1");
        $this->data['sales_voucher'] = $query_po->result_array();
        
		$this->load->view('manage_sales_voucher',$this->data);	

	}
	

public function edit_purchase_voucher() {
	$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
			$voucher_id = $this->uri->segment(3);	
			$pre = $this->input->get('pre');
			$prefix = urldecode($pre);		
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetInvoiceTran] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $account_id = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];			
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];
		 $this->data['PartyAddress_ID'] =  $purchase_order[0]['shipping_partyAddress_ID'];
		$this->data['purchase_order_detail'] = $purchase_order;	
		$this->data['purchase_id_prefix'] = $purchase_order[0]['PURCHASEORDER_PREFIX'];
		$this->data['round_off'] =  $purchase_order[0]['ROUNDOFF'];
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		$this->data['ac_cur_val'] =  $ac_cur[0]['CURRENTBALANCE'];
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'PU', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'PU', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress]  @ACCOUNT_ID  = '$account_id', @COMPANY_ID = '$company_id'");
		$this->data['party_address'] = $query_address->result_array();

		$query = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, @COMPANY_ID = '$company_id'");
		$this->data['account_group'] = $query->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('edit_purchase_voucher',$this->data);
	}

	public function update_purchasevoucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'PU'");
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
		$po_id = $this->input->post('cmb_so_number');
		$pur_id = explode('-', $po_id);
		$po_pre = $pur_id[0];
		$cmb_so_number = $pur_id[1];		
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
			$po_pre = NULL;
		}
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
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
		$round_off =  $this->input->post('txt_round_off');
		

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY],[REFNUM],[PURCHASEORDER_PREFIX],[ROUNDOFF]) VALUES ';

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

		 $insert .= " ( $company_id, ''$date'', 0, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name'',''$bill_no'', ''$po_pre'', $round_off), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_UpdPurchaseVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 0, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'PU', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @MODIFIEDBY = '$user_name', @month  = '$month', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address',@ROUNDOFF = $round_off"); 

	if($result){
			redirect(site_url() . '/entry/manage_purchase_voucher/?msg=1');
		}
	}

	public function edit_sales_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
			$voucher_id = $this->uri->segment(3);	
			$pre = $this->input->get('pre');
			$prefix = urldecode($pre);
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetInvoiceTran] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $account_id = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];
			$this->data['PartyAddress_ID'] =  $purchase_order[0]['shipping_partyAddress_ID'];
			if($purchase_order[0]['DATEOFSUPPLY'] != '1970-01-01'){
				$this->data['date_of_supply'] = date("d-m-Y",strtotime($purchase_order[0]['DATEOFSUPPLY']));
			}else{
				$this->data['date_of_supply'] =  '';	
			}
			 			
			$this->data['mode_of_transport'] =  $purchase_order[0]['MODEOFTRANSPORT'];
			$this->data['vehicle_no'] =  $purchase_order[0]['VEHICLEREGNO'];
			$this->data['round_off'] =  $purchase_order[0]['ROUNDOFF'];
			$this->data['dc_no'] =  $purchase_order[0]['DeliveryChalanNo'];
			$this->data['po_manual'] =  $purchase_order[0]['DirectPONo'];
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
		
		$query_current = $this->db->query("EXEC	[dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL");
		$ac_cur = $query_current->result_array();
		$this->data['ac_cur_val'] =  $ac_cur[0]['CURRENTBALANCE'];

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("EXEC [dbo].[usp_GetValidOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 1, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'SA', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'SA', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress]  @ACCOUNT_ID  = '$account_id', @COMPANY_ID = '$company_id', @ISACTIVE = 1");
		$this->data['party_address'] = $query_address->result_array();

		$query_transport = $this->db->query("EXEC [dbo].[usp_GetModeofTransport]");
		$this->data['transport_mode'] = $query_transport->result_array();

		$query = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, @COMPANY_ID = '$company_id'");
		$this->data['account_group'] = $query->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$this->load->view('edit_sales_voucher',$this->data);
	}

	public function update_salesvoucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'SA'");
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
		$shipping = $this->input->post('cmb_shipping');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		if($shipping == ''){
			
				$shipping = 0;	
			
		}
		$validity = 0;
		$user_name = $this->user_name;

		$total_discount = $this->input->post('txt_total_discount');
		$total_sgst= $this->input->post('txt_total_sgst');
		$total_cgst= $this->input->post('txt_total_cgst');
		$total_igst= $this->input->post('txt_total_igst');
		$final_total= $this->input->post('txt_final_total');
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}

		$transport_mode = $this->input->post('txt_transport_mode');
		if($transport_mode == ''){
			$transport_mode = NULL;
		}
		$vechicle_no = $this->input->post('txt_vechicle_no');
		if($vechicle_no == ''){
			$vechicle_no = NULL;
		}
		$date_of_supply1 = $this->input->post('txt_date_of_supply');
		$date_of_supply =  date("m-d-Y",strtotime($date_of_supply1));

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
		
		$round_off =  $this->input->post('txt_round_off');

		$dc_no =  $this->input->post('txt_dc_no');
		if($dc_no == ''){
			$dc_no = NULL;
		}
		$po_manual_no =  $this->input->post('txt_po_manual');
		if($po_manual_no == '' || $cmb_so_number != 0 ){
			$po_manual_no = NULL;
		}

 	$insert = 'insert into [dbo].[InvoiceTran] ([COMPANY_ID], [DATE], [ISSALES], [FINANCIALYEAR_ID], [ACCOUNT_ID], [LEDGER_ID], [PREFIX], [VOUCHER_ID], [PURCHASEORDER_ID], [ITEM_ID], [QUANTITY], [RATE], [UNIT_ID], [AMOUNT], [DISCOUNT], [CGSTPERCENT], [SGSTPERCENT], [IGSTPERCENT], [VATPERCENT], [CGSTAMOUNT], [SGSTAMOUNT], [IGSTAMOUNT], [VATAMOUNT], [CREDITPERIOD], [CREATEDON], [CREATEDBY],[REFNUM],[shipping_partyAddress_ID],[MODEOFTRANSPORT],[VEHICLEREGNO],[DATEOFSUPPLY],[ROUNDOFF], [DeliveryChalanNo], [DirectPONo]) VALUES ';

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

		 $insert .= " ( $company_id, ''$date'', 1, $Financialyear_id, $account_id, $ledger_id, ''$voucher_type'', @voucherno, $cmb_so_number,  $items, $qtys, $rate, $unit_id, $amount, $discount, $cgst_per, $sgst_per, $igst_per, $vat_per, $cgst, $sgst, $igst, $vat, $credit_period, getdate(), ''$user_name'',''$bill_no'', $shipping, ''$transport_mode'', ''$vechicle_no'',''$date_of_supply'', $round_off, ''$dc_no'', ''$po_manual_no'' ), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_UpdSalesVoucher] @Invoicetran = '$insert', @COMPANY_ID  = '$company_id',  @DATE = '$date', @ISSALES = 1, @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = '$account_id', @LEDGERACCOUNT_ID = '$ledger_id', @IGSTACCOUNT_ID = '$igst_id', @SGSTACCOUNT_ID = '$sgst_id', @CGSTACCOUNT_ID = '$cgst_id', @PREFIX = '$voucher_type', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'SA', @DISCOUNT = '$total_discount', @CGSTAMOUNT = '$total_cgst', @SGSTAMOUNT = '$total_sgst', @IGSTAMOUNT = '$total_igst', @GRANDTOTALAMOUNT = '$final_total', @CREDITPERIOD = '$credit_period', @MODIFIEDBY = '$user_name', @month  = '$month', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address', @ROUNDOFF = $round_off"); 

	if($result){
			redirect(site_url() . 'entry/manage_sales_voucher/?msg=1');
		}
	}

	public function print_purchase_order() {


		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $pre = $this->input->get('pre');
		$prefix = urldecode($pre);
        $voucher_id = $this->uri->segment(3);
        $order_type =  $this->uri->segment(4);
        $query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();

 		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();		

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'PO' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_po = $this->db->query("EXEC	[dbo].[usp_GetOrders_quotation] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISSALESORDER = '$order_type', @ISACTIVE = 1");
		$this->data['purchase_order_det'] = $purchase_order_det = $query_po->result_array();

		$this->data['prefix_voucher_id'] = $purchase_order_det[0]['V_ID'];
		$this->data['prefix_voucher_type'] = $purchase_order_det[0]['PREFIX'];
		$this->data['po_no'] = $purchase_order_det[0]['PURCHASE_ORDER_NO']; 
		$this->data['po_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['DATE']));
		$this->data['credit_period'] = $purchase_order_det[0]['VALIDITY'];
		//$this->data['qt_ref_no'] = $purchase_order_det[0]['QUOTATION_REFERENCE'];
		//$this->data['qt_ref_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['QUOTATION_REFERENCE_DATE']));
		$this->data['account'] = $purchase_order_det[0]['ACCOUNT'];
		$this->data['account_id'] = $account_id = $purchase_order_det[0]['ACCOUNT_ID'];
		$this->data['ledger_id'] = $purchase_order_det[0]['LEDGER_ID'];
		$this->data['vendor_code'] = $purchase_order_det[0]['VENDOR_CODE'];
		$this->data['project_ref'] = $purchase_order_det[0]['PROJECT_REFERNCE'];
		$this->data['test_certificate'] = $purchase_order_det[0]['TEST_CERTIFICATE'];
		$this->data['mode_of_transport'] = $purchase_order_det[0]['TRANSPORTATION'];
		$this->data['shipping_id'] = $shipping_id = $purchase_order_det[0]['PARTYADDRESS_ID'];
		$this->data['order_type'] = $order_type = $purchase_order_det[0]['ISSALESORDER'];
		$this->data['termsnconditions'] = $purchase_order_det[0]['TermsandConditions'];
		$this->data['delivery_from'] = date("d-m-Y",strtotime($purchase_order_det[0]['DELIVERY_FROM']));
		$this->data['delivery_to'] =  date("d-m-Y",strtotime($purchase_order_det[0]['DELIVERY_TO']));
		$this->data['tinno'] = $purchase_order_det[0]['TINNO'];

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress]  @ACCOUNT_ID  = '$account_id', @COMPANY_ID = '$company_id', @ISACTIVE = 1");
		$this->data['party_address'] = $query_address->result_array();

		if($order_type == 0){
		$voucher_type = 'PO';
		}else{
			$voucher_type = 'SO';
		}

		$query_sales_total = $this->db->query("SELECT o.PREFIX,SUM(o.AMOUNT) as Total,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,REPLICATE('0',6-LEN(RTRIM(o.VOUCHER_ID))) + RTRIM(o.VOUCHER_ID) as V_ID,ORR.PURCHASE_ORDER_NO,o.ISSALESORDER  from Orders o Left Outer Join ORDERREFERENCE ORR with (nolock) on o.PREFIX=orr.PREFIX and o.VOUCHER_ID=orr.VOUCHER_ID and orr.FINANCIALYEAR_ID='$finance_id'   and ORR.company_id= '$company_id' WHERE o.COMPANY_ID = '$company_id' AND o.FINANCIALYEAR_ID = '$finance_id' AND o.PREFIX = 'PO' AND o.VOUCHER_ID = '$voucher_id' AND o.ISSALESORDER = '$order_type' group by o.PREFIX,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,ORR.PURCHASE_ORDER_NO,o.ISSALESORDER ");
		$total = $query_sales_total->result_array();
		$final_word= $total[0]['Total'];
		 $final_words  = $this->getIndianCurrency($final_word);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);
		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1, @ISACTIVE = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode'], 'STATETINPREFIX' => $address[0]['STATETINPREFIX'], 'GST' => $address[0]['GSTNO']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		$this->data['party_address'] = $party_address;
		$query_shipping = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ID = '$shipping_id'");
		$this->data['shipping_address'] = $shipping_address = $query_shipping->result_array();
		if(!empty($shipping_address)){			
			$party_shipping_address =  array('ADDRESS1' => $shipping_address[0]['ADDRESS1'], 'ADDRESS2' => $shipping_address[0]['ADDRESS2'],'DISTRICT' => $shipping_address[0]['DISTRICT'],'PINCODE' => $shipping_address[0]['PINCODE'], 'STATE' => $shipping_address[0]['STATE'], 'STATETINPREFIX' => $shipping_address[0]['STATETINPREFIX'], 'GST' => $shipping_address[0]['GSTNO']);
		}else{
			$party_shipping_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		if($shipping_id == 0){
			$this->data['party_shipping_address'] = $party_address;
		}else{
			$this->data['party_shipping_address'] = $party_shipping_address;
		}
		

		$this->load->view('print_purchase_order',$this->data);
	}

	public function print_delivery_challan() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	

			
			
			$voucher_id = $this->uri->segment(3);	
			$pre = $this->input->get('pre');
			$prefix = urldecode($pre);		
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetDeliveryChalan] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$account_id = $this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ACCOUNT'] = $purchase_order[0]['ACCOUNT'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['purchase_order_prefix'] = $po_prefix = $purchase_order[0]['PURCHASEORDER_PREFIX'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];			
			$this->data['tinno'] = $purchase_order[0]['TINNO'];
			$this->data['sno'] = $purchase_order[0]['V_ID'];
			$this->data['type_sales'] = $purchase_order[0]['LEDGER'];
			$shipping_id = $purchase_order[0]['shipping_partyAddress_ID'];
			if($purchase_order[0]['DATEOFSUPPLY'] != '1970-01-01'){
				$this->data['date_of_supply'] = date("d-m-Y",strtotime($purchase_order[0]['DATEOFSUPPLY']));
			}else{
				$this->data['date_of_supply'] = '';	
			}
		 	
		 	$this->data['transport_mode'] = $purchase_order[0]['MODEOFTRANSPORT'];
		 	$this->data['vehicle_no'] = $purchase_order[0]['VEHICLEREGNO'];
		 	$this->data['po_ref_no'] =  $purchase_order[0]['PURCHASEORDER_NO'];
		 	$this->data['dc_no'] =  $purchase_order[0]['DeliveryChalanNo'];
		    $this->data['purchase_order_detail'] = $purchase_order;	

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1, @ISACTIVE = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode'], 'STATETINPREFIX' => $address[0]['STATETINPREFIX'], 'GST' => $address[0]['GSTNO']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		$this->data['party_address'] = $party_address;
		$query_shipping = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ID = '$shipping_id'");
		$this->data['shipping_address'] = $shipping_address = $query_shipping->result_array();
		if(!empty($shipping_address)){			
			$party_shipping_address =  array('ADDRESS1' => $shipping_address[0]['ADDRESS1'], 'ADDRESS2' => $shipping_address[0]['ADDRESS2'],'DISTRICT' => $shipping_address[0]['DISTRICT'],'PINCODE' => $shipping_address[0]['PINCODE'], 'STATE' => $shipping_address[0]['STATE'], 'STATETINPREFIX' => $shipping_address[0]['STATETINPREFIX'], 'GST' => $shipping_address[0]['GSTNO']);
		}else{
			$party_shipping_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		if($shipping_id == 0){
			$this->data['party_shipping_address'] = $party_address;
		}else{
			$this->data['party_shipping_address'] = $party_shipping_address;
		}

		$this->load->view('print_delivery_challan',$this->data);
	}

	public function print_invoice() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	

			
			
			$voucher_id = $this->uri->segment(3);	
			$pre = $this->input->get('pre');
			$prefix = urldecode($pre);		
			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetInvoiceTran] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$account_id = $this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ACCOUNT'] = $purchase_order[0]['ACCOUNT'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $po_id = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['purchase_order_prefix'] = $po_prefix = $purchase_order[0]['PURCHASEORDER_PREFIX'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];			
			$this->data['tinno'] = $purchase_order[0]['TINNO'];
			$this->data['sno'] = $purchase_order[0]['V_ID'];
			$this->data['type_sales'] = $purchase_order[0]['LEDGER'];
			$shipping_id = $purchase_order[0]['shipping_partyAddress_ID'];
			if($purchase_order[0]['DATEOFSUPPLY'] != '1970-01-01'){
				$this->data['date_of_supply'] = date("d-m-Y",strtotime($purchase_order[0]['DATEOFSUPPLY']));
			}else{
				$this->data['date_of_supply'] = '';	
			}
		 	
		 	$this->data['transport_mode'] = $purchase_order[0]['MODEOFTRANSPORT'];
		 	$this->data['vehicle_no'] = $purchase_order[0]['VEHICLEREGNO'];
		 	$this->data['reverse_charge'] = $purchase_order[0]['REVERSE_CHARGES_APPLICABLE'];
		 	$this->data['round_off'] =  $purchase_order[0]['ROUNDOFF'];
		 	$this->data['po_ref_no'] =  $purchase_order[0]['PURCHASEORDER_NO'];
		 	$this->data['dc_no'] =  $purchase_order[0]['DeliveryChalanNo'];
		$this->data['purchase_order_detail'] = $purchase_order;	

 

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = '$company_id' and FINANCIALYEAR_ID = '$finance_id' and ISSALESORDER = 0 ");
		$this->data['purchase_order'] = $query_po->result_array();

		$query_sales_total = $this->db->query("SELECT PREFIX,SUM(AMOUNT)-SUM(DISCOUNT)+SUM(IGSTAMOUNT)+SUM(CGSTAMOUNT)+SUM(SGSTAMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID,isnull(ROUNDOFF,0) as ROUNDOFF from InvoiceTran WHERE COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND ISSALES =1 AND VOUCHER_ID = '$voucher_id' AND PREFIX='$prefix' group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,ROUNDOFF");
		$total = $query_sales_total->result_array();
		$final_word= $total[0]['Total'] + $total[0]['ROUNDOFF'];
		 $final_words  = $this->getIndianCurrency($final_word);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);
		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1, @ISACTIVE = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode'], 'STATETINPREFIX' => $address[0]['STATETINPREFIX'], 'GST' => $address[0]['GSTNO']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		$this->data['party_address'] = $party_address;
		$query_shipping = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ID = '$shipping_id'");
		$this->data['shipping_address'] = $shipping_address = $query_shipping->result_array();
		if(!empty($shipping_address)){			
			$party_shipping_address =  array('ADDRESS1' => $shipping_address[0]['ADDRESS1'], 'ADDRESS2' => $shipping_address[0]['ADDRESS2'],'DISTRICT' => $shipping_address[0]['DISTRICT'],'PINCODE' => $shipping_address[0]['PINCODE'], 'STATE' => $shipping_address[0]['STATE'], 'STATETINPREFIX' => $shipping_address[0]['STATETINPREFIX'], 'GST' => $shipping_address[0]['GSTNO']);
		}else{
			$party_shipping_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		if($shipping_id == 0){
			$this->data['party_shipping_address'] = $party_address;
		}else{
			$this->data['party_shipping_address'] = $party_shipping_address;
		}
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$this->data['termsnconditions'] = $terms[0]['TermsAndConditions'];
		}else{
			$this->data['termsnconditions'] = '';
		}

		$this->load->view('print_invoice',$this->data);
	}

	public function print_debit_note() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
			$voucher_id = $this->uri->segment(3);	
			$prefix = $this->uri->segment(4);		
			

			$query_dn_det = $this->db->query("EXEC [dbo].[usp_GetDebitnote] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");

			 $debit_note = $query_dn_det->result_array();
			$account_id = $this->data['account_id'] = $debit_note[0]['DR_ACCOUNT_ID'];
			$this->data['ACCOUNT'] = $debit_note[0]['ACCOUNT'];
			$this->data['ledger_id'] = $debit_note[0]['CR_ACCOUNT_ID'];
			$this->data['tinno'] = $debit_note[0]['TINNO'];
			
			$this->data['date'] = date("d-m-Y",strtotime($debit_note[0]['DATE']));
			$this->data['prefix'] = $debit_note[0]['PREFIX'];
			$this->data['sno'] = $debit_note[0]['V_ID'];
			$this->data['sn'] = $debit_note[0]['VOUCHER_ID'];
		 	$this->data['cgst'] = round($debit_note[0]['CGSTPERCENT']);
		 	$this->data['sgst'] = round($debit_note[0]['SGSTPERCENT']);
		 	$this->data['igst'] = round($debit_note[0]['IGSTPERCENT']);
		 	$this->data['cgst_amount'] = round($debit_note[0]['CGSTAMOUNT'],2);
		 	$this->data['sgst_amount'] = round($debit_note[0]['SGSTAMOUNT'],2);
		 	$this->data['igst_amount'] = round($debit_note[0]['IGSTAMOUNT'],2);
		 	$this->data['narration'] = $debit_note[0]['NARRATION'];
		 	$this->data['tax_id'] = $debit_note[0]['companytax_id'];
		 	$this->data['bill_no'] = $debit_note[0]['REFNUM'];
		 	$this->data['ref_num'] = $debit_note[0]['BILLNO'];
		 	$this->data['reverse_charge'] = $debit_note[0]['REVERSE_CHARGES_APPLICABLE'];
		 	$this->data['type_sales'] = $debit_note[0]['LEDGER'];
		$this->data['debit_note_detail'] = $debit_note;	

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode'=>'');	
		}

		$this->data['party_address'] = $party_address;
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$this->data['termsnconditions'] = $terms[0]['TermsAndConditions'];
		}else{
			$this->data['termsnconditions'] = '';
		}
		$query_dn = $this->db->query("SELECT PREFIX,SUM(AMOUNT)+IGSTAMOUNT+CGSTAMOUNT+SGSTAMOUNT as Total from AllAccountTran WHERE COMPANY_ID = '$company_id' AND VOUCHER_ID = '$voucher_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX ='DN'  group by PREFIX,VOUCHER_ID,DR_ACCOUNT_ID,CR_ACCOUNT_ID,DATE,IGSTAMOUNT,CGSTAMOUNT,SGSTAMOUNT");
		$total = $query_dn->result_array();
		$final_word= $total[0]['Total'];
		 $final_words  = $this->getIndianCurrency($final_word);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);

		$this->load->view('print_debit_note',$this->data);

	}


	public function print_credit_note() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
			$voucher_id = $this->uri->segment(3);	
			$prefix = $this->uri->segment(4);		
			

			$query_dn_det = $this->db->query("EXEC [dbo].[usp_GetCreditnote] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");

			 $debit_note = $query_dn_det->result_array();
			$ledger_id = $this->data['ledger_id'] = $debit_note[0]['DR_ACCOUNT_ID'];
			$this->data['ACCOUNT'] = $debit_note[0]['ACCOUNT'];
			$account_id = $this->data['account_id'] = $debit_note[0]['CR_ACCOUNT_ID'];
			$this->data['tinno'] = $debit_note[0]['TINNO'];
			
			$this->data['date'] = date("d-m-Y",strtotime($debit_note[0]['DATE']));
			$this->data['prefix'] = $debit_note[0]['PREFIX'];
			$this->data['sno'] = $debit_note[0]['V_ID'];
			$this->data['sn'] = $debit_note[0]['VOUCHER_ID'];
		 	$this->data['cgst'] = round($debit_note[0]['CGSTPERCENT']);
		 	$this->data['sgst'] = round($debit_note[0]['SGSTPERCENT']);
		 	$this->data['igst'] = round($debit_note[0]['IGSTPERCENT']);
		 	$this->data['cgst_amount'] = round($debit_note[0]['CGSTAMOUNT'],2);
		 	$this->data['sgst_amount'] = round($debit_note[0]['SGSTAMOUNT'],2);
		 	$this->data['igst_amount'] = round($debit_note[0]['IGSTAMOUNT'],2);
		 	$this->data['narration'] = $debit_note[0]['NARRATION'];
		 	$this->data['tax_id'] = $debit_note[0]['companytax_id'];
		 	$this->data['bill_no'] = $debit_note[0]['REFNUM'];		 	
		 	$this->data['ref_num'] = $debit_note[0]['BILLNO'];
		 	$this->data['reverse_charge'] = $debit_note[0]['REVERSE_CHARGES_APPLICABLE'];
		 	$this->data['type_sales'] = $debit_note[0]['LEDGER'];
		$this->data['debit_note_detail'] = $debit_note;	

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '');	
		}
		$this->data['party_address'] = $party_address;
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$this->data['termsnconditions'] = $terms[0]['TermsAndConditions'];
		}else{
			$this->data['termsnconditions'] = '';
		}	
		$query_dn = $this->db->query("SELECT PREFIX,SUM(AMOUNT)+IGSTAMOUNT+CGSTAMOUNT+SGSTAMOUNT as Total from AllAccountTran WHERE COMPANY_ID = '$company_id' AND VOUCHER_ID = '$voucher_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX ='CN' group by PREFIX,VOUCHER_ID,DR_ACCOUNT_ID,CR_ACCOUNT_ID,DATE,IGSTAMOUNT,CGSTAMOUNT,SGSTAMOUNT");
		$total = $query_dn->result_array();
		$final_word= $total[0]['Total'];
		 $final_words  = $this->getIndianCurrency($final_word);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);

		$this->load->view('print_credit_note',$this->data);

	}

	
public function getIndianCurrency($number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');

    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
   // $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
     $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' ' : '';
     //$paise = ($decimal) ? "." . ($words[$decimal]) . ' Paise' : '';
    return ($Rupees ? $Rupees : '') . $paise ;
}

public function get_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$payment_type = $this->input->get('payment_type');
		$query_sn = $this->db->query("Select prefix from SerialNo where Company_ID = '$company_id' and  VoucherType = '$payment_type' and Financialyear_id = '$finance_id'");
		
		$voucher_type = $query_sn->result_array();

		//$query_acc = $this->db->query("exec [usp_GetAccount] @company_id='$company_id', @id=NULL, @VoucherType='$payment_type',@ColumnType='Account'");
		//$account = $query_acc->result_array();
		$query_ledger = $this->db->query("exec [usp_GetAccount] @company_id='$company_id', @id=NULL, @VoucherType='$payment_type', @ColumnType='Ledger'");
		$ledger = $query_ledger->result_array();

		$array_val = array('voucher_type' => $voucher_type, 'ledger_name' => $ledger );

		echo json_encode($array_val, JSON_FORCE_OBJECT);

}

public function payment_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();


		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CP', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$this->load->view('payment_voucher',$this->data);
	}

	public function add_payment_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'PM'");
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

		

		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$dr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		$tin_no = $this->input->post('txt_tin');
		if($tin_no == ''){
			$tin_no = NULL;
		}
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$cr_account = $_POST['cmb_ledger'];
		$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');
		
		$total = $_POST['txt_total'];
		$k = count($cr_account) - 1;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax],[CUSTOMERTAXNO]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $cr_account_id = $cr_account[$i];
		 $totals = $total[$i];
		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $totals, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, ''$tin_no''), ";
		}
		
	

		if($sgst_amount != 0)
		{
			
 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $sgst_id, $dr_account_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $dr_account_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $igst_id, $dr_account_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $igst_id, $dr_account_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cgst_id, $dr_account_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		   $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cgst_id, $dr_account_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
		
		$result = $this->db->query("EXEC [dbo].[usp_InsPayment]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @Prefix = '$prefix', @Financialyear_id = $Financialyear_id, @TotalAmount = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $dr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT  = $igst_amount, @CREATEDBY = '$user_name', @NARRATION = '$narration'");

		
		if($result){
			redirect(site_url() . '/entry/manage_payment_voucher/');
		}

	}


	public function payment_voucher_billwise() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();


		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'BP', @ColumnType = 'Account', @isbillwise = 1");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$this->load->view('payment_voucher_billwise',$this->data);
	}

	public function add_payment_voucher_billwise(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] @COMPANY_ID = '$company_id', @VoucherType = 'PM'");
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

		

		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$dr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		/*$tin_no = $this->input->post('txt_tin');
		if($tin_no == ''){
			$tin_no = NULL;
		} */
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$cr_account = $this->input->post('cmb_ledger');
		/*$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		*/
		$tax_id = 0;
		$sgst = 0;
		$igst = 0;
		$cgst = 0;
		$sgst_amount = 0;
		$igst_amount = 0;
		$cgst_amount = 0;
		$final_amount = $this->input->post('txt_final_total');
		
		$total = $_POST['txt_total'];
		$invoice_amount = $_POST['cmb_invoice'];
		$amount = $this->input->post('txt_amount');
		$k = count($invoice_amount) - 1;
		$cr_account_id = $cr_account;
		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[BILLDATE],[INVOICEHEADER_ID],[INVOICEHEADER_AMOUNT]) VALUES ';

		

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax],[CUSTOMERTAXNO]) VALUES ';

		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, NULL), ";
		

		for ($i=0; $i <= $k ; $i++) { 
		$invoice_amounts = $invoice_amount[$i];
		 $totals = $total[$i];
		 $invoice =explode("~",$invoice_amounts);
		 $bill_no_header = $invoice[0];
		 $bill_amount_header = $invoice[1];
		 $bill_date_header = $invoice[2];
		 $header_id = $invoice[3];
		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, $bill_no_header, ''$bill_date_header'', $header_id, $bill_amount_header), ";

		 
		}
		
	/*

		if($sgst_amount != 0)
		{
			
 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $sgst_id, $dr_account_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $dr_account_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $igst_id, $dr_account_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $igst_id, $dr_account_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cgst_id, $dr_account_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		   $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cgst_id, $dr_account_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}
*/
		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";

			$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
		
		$result = $this->db->query("EXEC [dbo].[usp_InsPayment]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @Prefix = '$prefix', @Financialyear_id = $Financialyear_id, @TotalAmount = $final_amount, @ACCOUNT_ID = $dr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $dr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT  = $igst_amount, @CREATEDBY = '$user_name', @NARRATION = '$narration'");

		
		if($result){
			redirect(site_url() . '/entry/manage_payment_voucher_billwise/');
		}

	}

	public function receipt_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CR', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$this->load->view('recipet_voucher',$this->data);
	}

	public function receipt_voucher_billwise() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CR', @ColumnType = 'Account', @isbillwise = 1");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$this->load->view('recipet_voucher_billwise',$this->data);
	}

	

public function add_receipt_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'RP'");
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
		
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$cr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$dr_account = $_POST['cmb_ledger'];
		$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}

		$tin_no = $this->input->post('txt_tin');
		if($tin_no == ''){
			$tin_no = NULL;
		}

		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');
		
		$total = $_POST['txt_total'];
		$k = count($dr_account) - 1;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax],[CUSTOMERTAXNO]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $dr_account_id = $dr_account[$i];
		 $totals = $total[$i];

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $totals, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, ''$tin_no''), ";
		}
		

		if($sgst_amount != 0)
		{		 

 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $sgst_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		/* $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $cr_account_id, $sgst_amount, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, getdate(), ''$user_name'', 1, $tax_id), ";*/

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $sgst_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $igst_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $igst_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $cgst_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $cgst_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
			
		
$result = $this->db->query("EXEC [dbo].[usp_InsReceipt]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @Prefix = '$prefix', @Financialyear_id = $Financialyear_id, @TotalAmount = $final_amount, @ACCOUNT_ID = $cr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT  = $igst_amount, @CREATEDBY = '$user_name', @NARRATION = '$narration'");

		if($result){
			redirect(site_url() . '/entry/manage_receipt_voucher/');
		}

	}

public function add_receipt_voucher_billwise(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'RP'");
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
		
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$cr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$dr_account = $this->input->post('cmb_ledger');
		/*$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}

		$tin_no = $this->input->post('txt_tin');
		if($tin_no == ''){
			$tin_no = NULL;
		}

		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');*/
		$tax_id = 0;
		$sgst = 0;
		$igst = 0;
		$cgst = 0;
		$sgst_amount = 0;
		$igst_amount = 0;
		$cgst_amount = 0;
		$dr_account_id = $dr_account;
		
		$total = $_POST['txt_total'];
		$invoice_amount = $_POST['cmb_invoice'];
		$amount = $this->input->post('txt_amount');
		$k = count($invoice_amount) - 1;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[BILLDATE],[INVOICEHEADER_ID],[INVOICEHEADER_AMOUNT]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax],[CUSTOMERTAXNO]) VALUES ';

		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, NULL), ";
		
		for ($i=0; $i <= $k ; $i++) { 
			$invoice_amounts = $invoice_amount[$i];
		 $dr_account_id = $dr_account;
		 $totals = $total[$i];
		 $invoice =explode("~",$invoice_amounts);
		 $bill_no_header = $invoice[0];
		 $bill_amount_header = $invoice[1];
		 $bill_date_header = $invoice[2];
		 $header_id = $invoice[3];

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, $bill_no_header, ''$bill_date_header'', $header_id, $bill_amount_header), ";
		 
		}
		
		/*
		if($sgst_amount != 0)
		{		 

 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $sgst_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $cr_account_id, $sgst_amount, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, getdate(), ''$user_name'', 1, $tax_id), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $sgst_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $igst_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $igst_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $cgst_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $cgst_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		} */

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
		
$result = $this->db->query("EXEC [dbo].[usp_InsReceipt]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date',  @VoucherType = '$voucher_type', @Prefix = '$prefix', @Financialyear_id = $Financialyear_id, @TotalAmount = $final_amount, @ACCOUNT_ID = $cr_account_id, @month = '$month', @YEAR = '$year', @LEDGERACCOUNT_ID = $cr_account_id, @SGSTACCOUNT_ID = $sgst_id, @CGSTACCOUNT_ID = $cgst_id, @IGSTACCOUNT_ID = $igst_id, @SGSTAMOUNT = $sgst_amount, @CGSTAMOUNT = $cgst_amount, @IGSTAMOUNT  = $igst_amount, @CREATEDBY = '$user_name', @NARRATION = '$narration'");

		if($result){
			redirect(site_url() . '/entry/manage_receipt_voucher_billwise/');
		}

	}

	public function manage_journal_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_jv = $this->db->query("EXEC [dbo].[usp_GetManageJournal] @COMPANY_ID	 =  '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX =NULL , @VOUCHER_ID  = NULL");
		
		$this->data['journal_voucher'] = $query_jv->result_array();

		$this->load->view('manage_journal_voucher',$this->data);	
	}

	public function journal_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();


		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'JE', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'JE' and Financialyear_id = '$finance_id'");
	$this->data['sn'] = $query_sn->result_array();

		$this->load->view('journal_voucher',$this->data);
	}

	public function add_journalvoucher() {

		$company_id = $this->company_id;		
        $Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$cmb_ledger = $_POST['cmb_ledger'];
		$types = $_POST['cmb_type'];
		$total = $_POST['amount'];
		$k = count($total) - 1;
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_id = $this->input->post('txt_serial_no');
		$refnum = $this->input->post('txt_bill_no');
		if($refnum == ''){
			$refnum = NULL;
		}
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));	

		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$cr_account_id = $this->input->post('cmb_account_group');
		
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		
		$credit_total =  $this->input->post('txt_credit_total');
    	$debit_total =  $this->input->post('txt_debit_total');

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id]) VALUES ';		
		
		for ($i=0; $i <= $k ; $i++) { 
			$account_id = $cmb_ledger[$i];
			$type = $types[$i];
			if($type == 'credit'){
				$dr_account_id = 0;
				$cr_account_id = $account_id;
			}else{
				$cr_account_id = 0;
				$dr_account_id = $account_id;
			}		 
		 	$totals = $total[$i];
		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $dr_account_id, $cr_account_id, $totals, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, getdate(), ''$user_name'', 1, 0), ";
		}
		
		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";

				
		$result = $this->db->query("EXEC [dbo].[usp_InsJournal] @AllAccounttran = '$allaccount_insert', @Company_ID = $company_id, @date = '$date', @VoucherType = '$prefix', @prefix = '$prefix', @Financialyear_id = $Financialyear_id, @TotalDebitAmount = $debit_total, @TotalCreditAmount = $credit_total, @month = '$month', @YEAR = '$year', @CREATEDBY = '$user_name', @NARRATION = '$narration'"); 
		if($result){
			redirect(site_url() . '/entry/manage_journal_voucher/');
		}
	}


	public function edit_journal_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$voucher_id = $this->uri->segment(3);
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);			

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();


		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'JE', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'JE' and Financialyear_id = '$finance_id'");
	$this->data['sn'] = $query_sn->result_array();

	$query_jv = $this->db->query("EXEC [dbo].[usp_GetJournal] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id' ");
	
	$this->data['jv_details'] = $jv_details = $query_jv->result_array();

	$this->data['refnum'] = $jv_details[0]['REFNUM'];
	$this->data['narration'] = $jv_details[0]['NARRATION'];
	$this->data['date'] = date("d-m-Y",strtotime($jv_details[0]['DATE']));
	$this->data['ref_serial_no'] = $jv_details[0]['V_ID'];
	$this->data['ref_prefix'] = $jv_details[0]['PREFIX'];


		$this->load->view('edit_journal_voucher',$this->data);
	}

	public function update_journalvoucher() {

		$company_id = $this->company_id;		
        $Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$cmb_ledger = $_POST['cmb_ledger'];
		$types = $_POST['cmb_type'];
		$total = $_POST['amount'];
		$k = count($total) - 1;
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_id = $this->input->post('txt_serial_no');
		$refnum = $this->input->post('txt_bill_no');
		if($refnum == ''){
			$refnum = NULL;
		}
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));	

		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$cr_account_id = $this->input->post('cmb_account_group');
		
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		
		$credit_total =  $this->input->post('txt_credit_total');
    	$debit_total =  $this->input->post('txt_debit_total');
		 $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];	

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id]) VALUES ';		
		
		for ($i=0; $i <= $k ; $i++) { 
			$account_id = $cmb_ledger[$i];
			$type = $types[$i];
			if($type == 'credit'){
				$dr_account_id = 0;
				$cr_account_id = $account_id;
			}else{
				$cr_account_id = 0;
				$dr_account_id = $account_id;
			}		 
		 	$totals = $total[$i];
		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $dr_account_id, $cr_account_id, $totals, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, getdate(), ''$user_name'', 1, 0), ";
		}
		
		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
				
		$result = $this->db->query("EXEC [dbo].[usp_UpdJournal] @AllAccounttran = '$allaccount_insert', @Company_ID = $company_id,  @VoucherType = '$prefix', @prefix = '$prefix', @Financialyear_id = $Financialyear_id, @CREATEDBY = '$user_name', @VOUCHERNO = '$voucher_id', @machinename =  '$machine_name', @ipaddress = '$ip_address', @NARRATION = '$narration'"); 
		if($result){
			redirect(site_url() . '/entry/manage_journal_voucher/');
		}
	}

	public function delete_journal_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelJournal] @Company_ID = '$company_id', @VoucherType = 'JE', @prefix = '$prefix', @Financialyear_id = '$finance_id', @CREATEDBY = '$user_name', @VOUCHERNO = '$voucher_id', @machinename = '$machine_name', @ipaddress = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_journal_voucher/');
		}

	}

	public function stock_journal() {
		
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'DN', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'DN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'SJ' and Financialyear_id = '$finance_id'");
	$this->data['sn'] = $query_sn->result_array();

		$this->load->view('stock_journal',$this->data);
	}

	public function add_stock_journal() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $user_name = $this->user_name;	
        $prefix = $this->input->post('cmb_voucher_type'); 
        $voucher_id = $this->input->post('txt_serial_no'); 
        $bill_no = $this->input->post('txt_bill_no'); 
        $dates = $this->input->post('txt_date');
        $date = date("m-d-Y",strtotime($dates));
        $narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		if($narration == ''){
			$narration == NULL;	
		}
        $item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit'];
		$total = $_POST['txt_outward'];
		$types = $_POST['cmb_type'];
		$k = count($total) - 1;

		$ledger_insert = 'insert into StockJournal ([COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [BILL_NO], [DATE], [ITEM_ID], [UNIT_ID], [INQTY], [OUTQTY], [NARRATION], [ISACTIVE]) VALUES ';		
		
	for ($i=0; $i <= $k ; $i++) { 
			$items = $item[$i];
			$type = $types[$i];
			$totals = $total[$i];
			$units = $unit[$i];
			if($type == 'inward'){
				$inqty = $totals;
				$outqty = 0;
			}else{
				$inqty = 0;
				$outqty = $totals;
			}		 
		 	
		 $ledger_insert .= " ($company_id, $finance_id, ''$prefix'', @voucherno, $bill_no,''$date'', $items, $units, $inqty, $outqty, ''$narration'', 1), ";
		}
		
		$ledger_insert = rtrim($ledger_insert,", ");
		$ledger_insert = $ledger_insert."; ";

		
		$result = $this->db->query("EXEC [dbo].[usp_InsStockJournal] @StockJournal = '$ledger_insert', @Company_ID = '$company_id', @date = '$date', @prefix = '$prefix', @Financialyear_id = '$finance_id', @CREATEDBY = '$user_name'");
  	if($result){
			redirect(site_url() . '/entry/manage_stock_journal/');
		}

		
	}

	public function edit_stock_journal() {
		
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		$voucher_id = $this->uri->segment(3);
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);	

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'DN', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'DN', @ColumnType = 'Ledger'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();

		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'SJ' and Financialyear_id = '$finance_id'");
	$this->data['sn'] = $query_sn->result_array();

	$query_jv = $this->db->query("EXEC [dbo].[usp_GetstockJournal] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id' ");
	$this->data['jv_details'] = $jv_details = $query_jv->result_array();
	

	$this->data['bill_no'] = $jv_details[0]['BILL_NO'];
	$this->data['narration'] = $jv_details[0]['NARRATION'];
	$this->data['date'] = date("d-m-Y",strtotime($jv_details[0]['DATE']));
	$this->data['ref_serial_no'] = $jv_details[0]['VOUCHER_ID'];
	$this->data['ref_prefix'] = $jv_details[0]['PREFIX'];

		$this->load->view('edit_stock_journal',$this->data);
	}

	public function update_stock_journal() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $user_name = $this->user_name;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];
        $prefix = $this->input->post('cmb_voucher_type'); 
        $voucher_id = $this->input->post('txt_serial_no'); 
        $bill_no = $this->input->post('txt_bill_no'); 
        $dates = $this->input->post('txt_date');
        $date = date("m-d-Y",strtotime($dates));
        $narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		if($narration == ''){
			$narration == NULL;	
		}
        $item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit'];
		$total = $_POST['txt_outward'];
		$types = $_POST['cmb_type'];
		$k = count($total) - 1;

		$ledger_insert = 'insert into StockJournal ([COMPANY_ID], [FINANCIALYEAR_ID], [PREFIX], [VOUCHER_ID], [BILL_NO], [DATE], [ITEM_ID], [UNIT_ID], [INQTY], [OUTQTY], [NARRATION], [ISACTIVE]) VALUES ';		
		
	for ($i=0; $i <= $k ; $i++) { 
			$items = $item[$i];
			$type = $types[$i];
			$totals = $total[$i];
			$units = $unit[$i];
			if($type == 'inward'){
				$inqty = $totals;
				$outqty = 0;
			}else{
				$inqty = 0;
				$outqty = $totals;
			}		 
		 	
		 $ledger_insert .= " ($company_id, $finance_id, ''$prefix'', @voucherno, $bill_no,''$date'', $items, $units, $inqty, $outqty, ''$narration'', 1), ";
		}
		
		$ledger_insert = rtrim($ledger_insert,", ");
		$ledger_insert = $ledger_insert."; ";

		
		$result = $this->db->query("EXEC [dbo].[usp_UpdStockJournal] @StockJournal = '$ledger_insert', @Company_ID = '$company_id', @date = '$date', @prefix = '$prefix', @voucher_id = $voucher_id, @Financialyear_id = '$finance_id', @Modifiedby = '$user_name', @machinename = '$machine_name', @ipaddress = '$ip_address' ");
  	if($result){
			redirect(site_url() . '/entry/manage_stock_journal/');
		}

		
	}

	public function manage_stock_journal(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_jv = $this->db->query("select PREFIX, DATE, BILL_NO, VOUCHER_ID,SUM(INQTY) as IN_QTY,SUM(OUTQTY) as OUT_QTY,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from StockJournal where COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX = 'SJ' AND ISACTIVE = 1 group by PREFIX, DATE, BILL_NO, VOUCHER_ID");
		
		$this->data['stock_journal'] = $query_jv->result_array();

		$this->load->view('manage_stock_journal',$this->data);	
	}

	public function delete_stock_journal(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelStockJournal] @Company_ID = '$company_id', @prefix = '$prefix', @voucher_id = '$voucher_id', @Financialyear_id = '$finance_id', @Modifiedby = '$user_name', @machinename = '$machine_name', @ipaddress = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_stock_journal/');
		}

	}

	public function get_shipping() {
		$account_id = $this->input->get('account_id');		
		$company_id = $this->company_id;		
		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress]  @ACCOUNT_ID  = '$account_id', @COMPANY_ID = '$company_id', @ISACTIVE = 1");
		$party_address = $query_address->result_array();
		echo json_encode($party_address, JSON_FORCE_OBJECT);
	}
	public function get_billing() {
		$account_id = $this->input->get('account_id');		
		$company_id = $this->company_id;		
		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress]  @ACCOUNT_ID  = '$account_id', @COMPANY_ID = '$company_id',@ISBILLING = 1");
		$party_address = $query_address->result_array();
		echo json_encode($party_address, JSON_FORCE_OBJECT);
	}

	public function delete_sales_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelSalesVoucher] @COMPANY_ID = '$company_id', @ISSALES = '1', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'SA', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_sales_voucher/?msg=3');
		}

	}

	public function cancel_sales_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_CancelSalesVoucher] @COMPANY_ID = '$company_id', @ISSALES = '1', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'SA', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_sales_voucher/?msg=3');
		}

	}

	public function delete_purchase_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelPurchaseVoucher] @COMPANY_ID = '$company_id', @ISSALES = '0', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'PU', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_purchase_voucher/');
		}

	}

	public function add_print_data(){
		$voucher_id = $this->input->get('voucher_id');	
		$voucher_type = $this->input->get('voucher_type');		
		$prefix = $this->input->get('prefix');	
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		
		$result = $this->db->query("EXEC [dbo].[usp_InsPrinteddata] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @VOUCHERTYPE = '$voucher_type', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @MACHINENAME = '$machine_name', @CREATEDBY = '$user_name'");

	}

	public function delete_debit_note(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelDebitNote] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'DN', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_debitnote/');
		}

	}

	public function delete_credit_note(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);

		$result = $this->db->query("EXEC [dbo].[usp_DelCreditNote] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @VOUCHERTYPE = 'CN', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_creditnote/');
		}

	}



public function moneyFormatIndia($num){
    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i < sizeof($expunit);  $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}

public function export_bill(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_export = $this->db->query("EXEC [dbo].[usp_Get_manage_EcportInvoiceTran]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id',	@ISACTIVE = 1,@ISSALES = 1");

		$this->data['export_bill'] = $query_export->result_array();

		$this->load->view('export_bill',$this->data);	

	}

	public function export_invoice() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
			$voucher_id = $this->uri->segment(3);	
			$pre = $this->input->get('pre');
			$prefix = urldecode($pre);
			$query_export_det = $this->db->query("select top 1 * from [dbo].[EXPORTBILL] where COMPANY_ID = '$company_id' and FINANCIALYEAR_ID = '$finance_id' and PREFIX ='$prefix' and VOUCHER_ID = '$voucher_id'");
			
			$export_det = $query_export_det->result_array();
			if(!empty($export_det )){
				$prefix = $export_det[0]['PREFIX'];
				$voucher_id  = $export_det[0]['VOUCHER_ID'];
				$conversion = $export_det[0]['CONVERTION_RATE'];
				$this->export_invoice_print($prefix,$voucher_id,$conversion);
			}else{
				$query_po_det = $this->db->query("EXEC [dbo].[usp_GetInvoiceTran] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];
			
			
		 
		$this->data['purchase_order_detail'] = $purchase_order;	

		$this->data['currency'] = $this->admin_model->get_currency();
		

		$this->load->view('export_bill_print_screen',$this->data);
		}
			
	}

	public function export_invoice_print($prefix,$voucher_id,$conversion){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();

		$query_po_det = $this->db->query("EXEC [dbo].[usp_GetExportInvoiceTran] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @CONVERTION_RATE  = '$conversion', @ISSALES = 1,@ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$account_id = $this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ACCOUNT'] = $purchase_order[0]['ACCOUNT'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];			
			$this->data['tinno'] = $purchase_order[0]['TINNO'];
			$this->data['sno'] = $purchase_order[0]['V_ID'];
			$this->data['type_sales'] = $purchase_order[0]['LEDGER'];
			$shipping_id = $purchase_order[0]['shipping_partyAddress_ID'];
		 	$this->data['date_of_supply'] = date("d-m-Y",strtotime($purchase_order[0]['DATEOFSUPPLY']));
		 	$this->data['transport_mode'] = $purchase_order[0]['MODEOFTRANSPORT'];
		 	$this->data['vehicle_no'] = $purchase_order[0]['VEHICLEREGNO'];
		 	$this->data['reverse_charge'] = $purchase_order[0]['REVERSE_CHARGES_APPLICABLE'];
		 	$this->data['currency'] = $purchase_order[0]['Export_currency'];
		 	$this->data['po_ref_no'] =  $purchase_order[0]['PURCHASEORDER_NO'];
		 	$this->data['dc_no'] =  $purchase_order[0]['DeliveryChalanNo'];
		$this->data['purchase_order_detail'] = $purchase_order;	
		
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = '$company_id' and FINANCIALYEAR_ID = '$finance_id' and ISSALESORDER = 0 ");
		$this->data['purchase_order'] = $query_po->result_array();
 	
			
			 
		$query_sales_total = $this->db->query("SELECT PREFIX,SUM(AMOUNT)-SUM(DISCOUNT)+SUM(IGSTAMOUNT)+SUM(CGSTAMOUNT)+SUM(SGSTAMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from InvoiceTran WHERE COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND ISSALES =1 AND VOUCHER_ID = '$voucher_id' AND PREFIX='$prefix'  group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE");
		$total = $query_sales_total->result_array();
		$final_word = $total[0]['Total'];
		$conversion = $conversion;
		$final_word_num = $final_word / $conversion;
		//$final_word_nums = number_format($final_word_num,2);
		 $final_words  = $this->getIndianCurrency($final_word_num);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);
		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '');	
		}

		$this->data['party_address'] = $party_address;
		$query_shipping = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ID = '$shipping_id'");
		$this->data['shipping_address'] = $shipping_address = $query_shipping->result_array();
		if(!empty($shipping_address)){			
			$party_shipping_address =  array('ADDRESS1' => $shipping_address[0]['ADDRESS1'], 'ADDRESS2' => $shipping_address[0]['ADDRESS2'],'DISTRICT' => $shipping_address[0]['DISTRICT'],'PINCODE' => $shipping_address[0]['PINCODE'], 'STATE' => $shipping_address[0]['STATE']);
		}else{
			$party_shipping_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '');	
		}

		if($shipping_id == 0){
			$this->data['party_shipping_address'] = $party_address;
		}else{
			$this->data['party_shipping_address'] = $party_shipping_address;
		}
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$this->data['termsnconditions'] = $terms[0]['TermsAndConditions'];
		}else{
			$this->data['termsnconditions'] = '';
		}
		
		
		$this->load->view('export_invoice',$this->data);
	}

	public function print_export_invoice() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	
		$voucher_id = $this->input->post('txt_serial_no');
		$bill_no = $this->input->post('txt_bill_no');
		$amount = $this->input->post('txt_amount');
		$prefix = $this->input->post('cmb_voucher_type');
		$conversion = $this->input->post('txt_conversion');
		$currency_id = $this->input->post('cmb_currency');
		$user_name = $this->user_name;	
		$result = $this->db->query("EXEC [dbo].[usp_InsEXPORTBILL] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @CURRENCY_ID = '$currency_id', @CONVERTION_RATE = '$conversion', @AMOUNT = '$amount', @CREATEDBY = '$user_name'");
			
			if($result){

			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetExportInvoiceTran] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @CONVERTION_RATE  = '$conversion', @ISSALES = 1,@ISACTIVE = 1");
			 $purchase_order = $query_po_det->result_array();
			$account_id = $this->data['account_id'] = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['ACCOUNT'] = $purchase_order[0]['ACCOUNT'];
			$this->data['ledger_id'] = $purchase_order[0]['LEDGER_ID'];
			$this->data['purchase_id'] = $purchase_order[0]['PURCHASEORDER_ID'];
			$this->data['date'] = date("d-m-Y",strtotime($purchase_order[0]['DATE']));
			$this->data['prefix'] = $purchase_order[0]['PREFIX'];
			$this->data['sn'] = $purchase_order[0]['VOUCHER_ID'];
			$this->data['credit_period'] = $purchase_order[0]['CREDITPERIOD'];
			$this->data['bill_no'] = $purchase_order[0]['REFNUM'];			
			$this->data['tinno'] = $purchase_order[0]['TINNO'];
			$this->data['sno'] = $purchase_order[0]['V_ID'];
			$this->data['type_sales'] = $purchase_order[0]['LEDGER'];
			$shipping_id = $purchase_order[0]['shipping_partyAddress_ID'];
		 	$this->data['date_of_supply'] = date("d-m-Y",strtotime($purchase_order[0]['DATEOFSUPPLY']));
		 	$this->data['transport_mode'] = $purchase_order[0]['MODEOFTRANSPORT'];
		 	$this->data['vehicle_no'] = $purchase_order[0]['VEHICLEREGNO'];
		 	$this->data['reverse_charge'] = $purchase_order[0]['REVERSE_CHARGES_APPLICABLE'];
		 	$this->data['currency'] = $purchase_order[0]['Export_currency'];
		$this->data['purchase_order_detail'] = $purchase_order;	
		
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();

		$query_po = $this->db->query("SELECT Distinct[PREFIX],[VOUCHER_ID],REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as id FROM [dbo].[Orders] where COMPANY_ID = '$company_id' and FINANCIALYEAR_ID = '$finance_id' and ISSALESORDER = 0 ");
		$this->data['purchase_order'] = $query_po->result_array();
 	
			
			 
		$query_sales_total = $this->db->query("SELECT PREFIX,SUM(AMOUNT)-SUM(DISCOUNT)+SUM(IGSTAMOUNT)+SUM(CGSTAMOUNT)+SUM(SGSTAMOUNT) as Total,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE,REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID from InvoiceTran WHERE COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND ISSALES =1 AND VOUCHER_ID = '$voucher_id' AND PREFIX='$prefix'  group by PREFIX,VOUCHER_ID,ACCOUNT_ID,LEDGER_ID,DATE");
		$total = $query_sales_total->result_array();
		$final_word = $total[0]['Total'];
		$conversion = $conversion;
		$final_word_num = $final_word / $conversion;
		//$final_word_nums = number_format($final_word_num,2);
		 $final_words  = $this->getIndianCurrency($final_word_num);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);
		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1, @ISACTIVE = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '');	
		}

		$this->data['party_address'] = $party_address;
		$query_shipping = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ID = '$shipping_id'");
		$this->data['shipping_address'] = $shipping_address = $query_shipping->result_array();
		if(!empty($shipping_address)){			
			$party_shipping_address =  array('ADDRESS1' => $shipping_address[0]['ADDRESS1'], 'ADDRESS2' => $shipping_address[0]['ADDRESS2'],'DISTRICT' => $shipping_address[0]['DISTRICT'],'PINCODE' => $shipping_address[0]['PINCODE'], 'STATE' => $shipping_address[0]['STATE']);
		}else{
			$party_shipping_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '');	
		}

		if($shipping_id == 0){
			$this->data['party_shipping_address'] = $party_address;
		}else{
			$this->data['party_shipping_address'] = $party_shipping_address;
		}
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$this->data['termsnconditions'] = $terms[0]['TermsAndConditions'];
		}else{
			$this->data['termsnconditions'] = '';
		}
		
		}
		$this->load->view('export_invoice',$this->data);
	}


	public function manage_receipt_voucher(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
		$query_recipet = $this->db->query("SELECT PREFIX, SUM(AMOUNT) as Total, VOUCHER_ID, DATE, REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID FROM AllAccountTran WHERE COMPANY_ID= '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX IN ('BR','CR') AND ISACTIVE = 1  AND ISNULL(INVOICEHEADER_ID,0)<=0 group by PREFIX,VOUCHER_ID,DATE");
		$this->data['receipt_voucher'] = $query_recipet->result_array();
		
		$query_recipet_cnt = $this->db->query("select COUNT(DR_ACCOUNT_ID) as cnt,VOUCHER_ID,PREFIX from AllAccountTran WHERE COMPANY_ID= '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX IN ('BR','CR') AND ISACTIVE = 1 and CR_ACCOUNT_ID NOT IN (select  ID from Account where COMPANY_ID='$company_id' and group_id = 'I005')AND ISNULL(INVOICEHEADER_ID,0)<=0 group by VOUCHER_ID,PREFIX");
    $receipt_cont = $query_recipet_cnt->result_array();
    $recp_cont = array( );
    foreach ($receipt_cont as  $value) {
      $prefix_id =  $value['PREFIX'].$value['VOUCHER_ID'];
      $recp_cont[$prefix_id] = $value['cnt'];
      
    }

    $this->data['recp_cont'] = $recp_cont;

		$this->load->view('manage_receipt_voucher',$this->data);	

	}

	public function manage_receipt_voucher_billwise(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
		$query_recipet = $this->db->query("SELECT PREFIX, SUM(AMOUNT) as Total, VOUCHER_ID, DATE, REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID FROM AllAccountTran WHERE COMPANY_ID= '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX IN ('BR','CR') AND ISACTIVE = 1  AND ISNULL(INVOICEHEADER_ID,0) > 0 group by PREFIX,VOUCHER_ID,DATE");
		$this->data['receipt_voucher'] = $query_recipet->result_array();
		

		$this->load->view('manage_receipt_voucher_billwise',$this->data);	

	}

	public function edit_receipt_voucher(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);	
		$query_reciept = $this->db->query("EXEC	[dbo].[usp_GetReceipt] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1, @FromDate = NULL, @ToDate = NULL");
		
		$this->data['reciept_details'] = $reciept_details = $query_reciept->result_array();
		
		$this->data['voucher_id'] = $reciept_details[0]['V_ID'];
		$this->data['prefix'] = $prefix = $reciept_details[0]['PREFIX'];
		$this->data['ref_num'] = $reciept_details[0]['REFNUM'];
		$this->data['ref_date'] = date("d-m-Y",strtotime($reciept_details[0]['DATE']));
		$this->data['tax_id'] = $reciept_details[0]['companytax_id'];
		$this->data['igst_per'] = $reciept_details[0]['IGSTPERCENT'];
		$this->data['sgst_per'] = $reciept_details[0]['SGSTPERCENT'];
		$this->data['cgst_per'] = $reciept_details[0]['CGSTPERCENT'];
		$this->data['igst_amount'] = $reciept_details[0]['IGSTAMOUNT'];
		$this->data['sgst_amount'] = $reciept_details[0]['SGSTAMOUNT'];
		$this->data['cgst_amount'] = $reciept_details[0]['CGSTAMOUNT'];
		$this->data['narration'] = $reciept_details[0]['NARRATION'];
		$this->data['ledger_id'] = $reciept_details[0]['DR_ACCOUNT_ID'];
		$this->data['tinno'] = $reciept_details[0]['CUSTOMERTAXNO'];
		$this->data['sn_id'] = $reciept_details[0]['VOUCHER_ID'];
		$query_voucher = $this->db->query("Select VoucherType from SerialNo where  Company_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND Prefix = '$prefix'");
		$voucher_type_id = $query_voucher->result_array();
		$this->data['voucher_type_id'] = $voucher_type_id = $voucher_type_id[0]['VoucherType'];

		$query_ledger = $this->db->query("exec [usp_GetAccount] @company_id='$company_id', @id=NULL, @VoucherType='$voucher_type_id', @ColumnType='Ledger'");
		$this->data['account'] = $query_ledger->result_array();

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CR', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();		

		$this->load->view('edit_recipet_voucher',$this->data);
	}

public function edit_receipt_voucher_billwise(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);	
		$voucher_amount = 0;
		$query_reciept = $this->db->query("EXEC	[dbo].[usp_GetReceipt_Billwise] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1, @FromDate = NULL, @ToDate = NULL");
		$this->data['reciept_details'] = $reciept_details = $query_reciept->result_array();
		foreach ($reciept_details as $key ) {
			if($key['isTax'] == 0){
				$voucher_amount = $voucher_amount + $key['AMOUNT'];
			}
			
		}
		$this->data['voucher_amount'] = $voucher_amount;
		$this->data['voucher_id'] = $reciept_details[0]['V_ID'];
		$this->data['prefix'] = $prefix = $reciept_details[0]['PREFIX'];
		$this->data['ref_num'] = $reciept_details[0]['REFNUM'];
		$this->data['ref_date'] = date("d-m-Y",strtotime($reciept_details[0]['DATE']));
		$this->data['tax_id'] = $reciept_details[0]['companytax_id'];
		$this->data['igst_per'] = $reciept_details[0]['IGSTPERCENT'];
		$this->data['sgst_per'] = $reciept_details[0]['SGSTPERCENT'];
		$this->data['cgst_per'] = $reciept_details[0]['CGSTPERCENT'];
		$this->data['igst_amount'] = $reciept_details[0]['IGSTAMOUNT'];
		$this->data['sgst_amount'] = $reciept_details[0]['SGSTAMOUNT'];
		$this->data['cgst_amount'] = $reciept_details[0]['CGSTAMOUNT'];
		$this->data['narration'] = $reciept_details[0]['NARRATION'];
		$this->data['ledger_id'] = $reciept_details[0]['DR_ACCOUNT_ID'];
		$this->data['account_id'] = $account_id = $reciept_details[0]['CR_ACCOUNT_ID'];
		$this->data['tinno'] = $reciept_details[0]['CUSTOMERTAXNO'];
		$this->data['sn_id'] = $reciept_details[0]['VOUCHER_ID'];
		$query_voucher = $this->db->query("Select VoucherType from SerialNo where  Company_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND Prefix = '$prefix'");
		$voucher_type_id = $query_voucher->result_array();
		$this->data['voucher_type_id'] = $voucher_type_id = $voucher_type_id[0]['VoucherType'];

		$query_ledger = $this->db->query("exec [usp_GetAccount] @company_id='$company_id', @id=NULL, @VoucherType='$voucher_type_id', @ColumnType='Ledger'");
		$this->data['account'] = $query_ledger->result_array();

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CR', @ColumnType = 'Account', @isbillwise = 1");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();	

		$query_acc = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$account_id', @ISSALES = NULL,  @receiptorpayment=1, @transaction = 'U'");
		$this->data['bill_wise'] = $bill_wise = $query_acc->result_array();	

		$this->load->view('edit_recipet_voucher_billwise',$this->data);
	}
	public function update_receipt_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];	
        $query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'RP'");
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
		
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$cr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$dr_account = $_POST['cmb_ledger'];
		$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}

		$tin_no = $this->input->post('txt_tin');
		if($tin_no == ''){
			$tin_no = NULL;
		}
		
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');
		
		$total = $_POST['txt_total'];
		$k = count($dr_account) - 1;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax], [CUSTOMERTAXNO]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $dr_account_id = $dr_account[$i];
		 $totals = $total[$i];

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $totals, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, ''$tin_no''), ";
		}
		

		if($sgst_amount != 0)
		{		 

 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $sgst_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		/* $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $cr_account_id, $sgst_amount, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, getdate(), ''$user_name'', 1, $tax_id), ";*/

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $sgst_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $igst_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $igst_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $cgst_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $cgst_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
			
		
$result = $this->db->query("EXEC [dbo].[usp_UpdReceipt]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date', @prefix = '$prefix', @VOUCHERNO = '$voucher_id',  @Financialyear_id = $Financialyear_id, @ACCOUNT_ID = $cr_account_id, @CREATEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address' ");

		if($result){
			redirect(site_url() . '/entry/manage_receipt_voucher/');
		}

	}

	public function update_receipt_voucher_billwise(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];	
        $query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'RP'");
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
		
		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$cr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		
		
		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$dr_account = $this->input->post('cmb_ledger');
		/*$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}

		$tin_no = $this->input->post('txt_tin');
		if($tin_no == ''){
			$tin_no = NULL;
		}
		
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');*/
		
		$tax_id = 0;
		$sgst = 0;
		$igst = 0;
		$cgst = 0;
		$sgst_amount = 0;
		$igst_amount = 0;
		$cgst_amount = 0;

		 $dr_account_id = $dr_account;
		
		$total = $_POST['txt_total'];
		$invoice_amount = $_POST['cmb_invoice'];
		$amount = $this->input->post('txt_amount');
		$k = count($invoice_amount) - 1;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[BILLDATE],[INVOICEHEADER_ID],[INVOICEHEADER_AMOUNT]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax], [CUSTOMERTAXNO]) VALUES ';

		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, NULL), ";
		
		for ($i=0; $i <= $k ; $i++) { 
		$invoice_amounts = $invoice_amount[$i];
		 $dr_account_id = $dr_account;
		 $totals = $total[$i];
		 $invoice =explode("~",$invoice_amounts);
		 $bill_no_header = $invoice[0];
		 $bill_amount_header = $invoice[1];
		 $bill_date_header = $invoice[2];
		 $header_id = $invoice[3];

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, $bill_no_header, ''$bill_date_header'', $header_id, $bill_amount_header), ";

		 
		}
		
/* 
		if($sgst_amount != 0)
		{		 

 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $sgst_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		$allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $cr_account_id, $sgst_amount, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, getdate(), ''$user_name'', 1, $tax_id), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $sgst_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $igst_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $igst_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $cgst_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $cgst_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}*/

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
			
		
$result = $this->db->query("EXEC [dbo].[usp_UpdReceipt]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date', @prefix = '$prefix', @VOUCHERNO = '$voucher_id',  @Financialyear_id = $Financialyear_id, @ACCOUNT_ID = $cr_account_id, @CREATEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address' ");

		if($result){
			redirect(site_url() . '/entry/manage_receipt_voucher_billwise/');
		}

	}

	public function manage_payment_voucher(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
		$query_recipet = $this->db->query("SELECT PREFIX, SUM(AMOUNT) as Total, VOUCHER_ID, DATE, REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID FROM AllAccountTran WHERE COMPANY_ID= '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX IN ('BP','CP') AND ISACTIVE = 1 AND ISNULL(INVOICEHEADER_ID,0)<=0  group by PREFIX,VOUCHER_ID,DATE");
		$this->data['receipt_voucher'] = $query_recipet->result_array();
		$query_recipet_cnt = $this->db->query("select COUNT(DR_ACCOUNT_ID) as cnt,VOUCHER_ID,PREFIX from AllAccountTran WHERE COMPANY_ID= '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX IN ('BP','CP') AND ISACTIVE = 1 and DR_ACCOUNT_ID NOT IN (select  ID from Account where COMPANY_ID= '$company_id' and group_id = 'E007')AND ISNULL(INVOICEHEADER_ID,0)<=0 group by VOUCHER_ID,PREFIX");
		$receipt_cont = $query_recipet_cnt->result_array();
		$recp_cont = array( );
		foreach ($receipt_cont as  $value) {
			$prefix_id =  $value['PREFIX'].$value['VOUCHER_ID'];
			$recp_cont[$prefix_id] = $value['cnt'];
			
		}
		
		$this->data['recp_cont'] = $recp_cont;

		$this->load->view('manage_payment_voucher',$this->data);	

	}

	public function manage_payment_voucher_billwise(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
		
		$query_recipet = $this->db->query("SELECT PREFIX, SUM(AMOUNT) as Total, VOUCHER_ID, DATE, REPLICATE('0',6-LEN(RTRIM(VOUCHER_ID))) + RTRIM(VOUCHER_ID) as V_ID FROM AllAccountTran WHERE COMPANY_ID= '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND PREFIX IN ('BP','CP') AND ISACTIVE = 1 AND ISNULL(INVOICEHEADER_ID,0) > 0 group by PREFIX,VOUCHER_ID,DATE");
		$this->data['receipt_voucher'] = $query_recipet->result_array();
		

		$this->load->view('manage_payment_voucher_billwise',$this->data);	

	}
	public function edit_payment_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);	
		$query_reciept = $this->db->query("EXEC	[dbo].[usp_Getpayment] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = $finance_id, @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1, @FromDate = NULL, @ToDate = NULL");
		$this->data['reciept_details'] = $reciept_details = $query_reciept->result_array();

		
		$this->data['voucher_id'] = $reciept_details[0]['V_ID'];
		$this->data['prefix'] = $prefix = $reciept_details[0]['PREFIX'];
		$this->data['ref_num'] = $reciept_details[0]['REFNUM'];
		$this->data['ref_date'] = date("d-m-Y",strtotime($reciept_details[0]['DATE']));
		$this->data['tax_id'] = $reciept_details[0]['companytax_id'];
		$this->data['igst_per'] = $reciept_details[0]['IGSTPERCENT'];
		$this->data['sgst_per'] = $reciept_details[0]['SGSTPERCENT'];
		$this->data['cgst_per'] = $reciept_details[0]['CGSTPERCENT'];
		$this->data['igst_amount'] = $reciept_details[0]['IGSTAMOUNT'];
		$this->data['sgst_amount'] = $reciept_details[0]['SGSTAMOUNT'];
		$this->data['cgst_amount'] = $reciept_details[0]['CGSTAMOUNT'];
		$this->data['narration'] = $reciept_details[0]['NARRATION'];
		$this->data['ledger_id'] = $reciept_details[0]['CR_ACCOUNT_ID'];
		$this->data['tinno'] = $reciept_details[0]['CUSTOMERTAXNO'];
		$this->data['sn_id'] = $reciept_details[0]['VOUCHER_ID'];
		$query_voucher = $this->db->query("Select VoucherType from SerialNo where  Company_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND Prefix = '$prefix'");
		$voucher_type_id = $query_voucher->result_array();
		$this->data['voucher_type_id'] = $voucher_type_id = $voucher_type_id[0]['VoucherType'];
		$query_ledger = $this->db->query("exec [usp_GetAccount] @company_id='$company_id', @id=NULL, @VoucherType='$voucher_type_id', @ColumnType='Ledger'");
		$this->data['account'] = $query_ledger->result_array();
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();


		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'CP', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$this->load->view('edit_payment_voucher',$this->data);
	}

	public function edit_payment_voucher_billwise() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$voucher_amount = 0;
		$prefix = urldecode($pre);	
		$query_reciept = $this->db->query("EXEC	[dbo].[usp_Getpayment_Billwise] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = $finance_id, @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISACTIVE = 1, @FromDate = NULL, @ToDate = NULL");
		$this->data['reciept_details'] = $reciept_details = $query_reciept->result_array();
		foreach ($reciept_details as $key ) {
			if($key['isTax'] == 0){
				$voucher_amount = $voucher_amount + $key['AMOUNT'];
			}
			
		}
		$this->data['voucher_amount'] = $voucher_amount;
		$this->data['voucher_id'] = $reciept_details[0]['V_ID'];
		$this->data['prefix'] = $prefix = $reciept_details[0]['PREFIX'];
		$this->data['ref_num'] = $reciept_details[0]['REFNUM'];
		$this->data['ref_date'] = date("d-m-Y",strtotime($reciept_details[0]['DATE']));
		$this->data['tax_id'] = $reciept_details[0]['companytax_id'];
		$this->data['igst_per'] = $reciept_details[0]['IGSTPERCENT'];
		$this->data['sgst_per'] = $reciept_details[0]['SGSTPERCENT'];
		$this->data['cgst_per'] = $reciept_details[0]['CGSTPERCENT'];
		$this->data['igst_amount'] = $reciept_details[0]['IGSTAMOUNT'];
		$this->data['sgst_amount'] = $reciept_details[0]['SGSTAMOUNT'];
		$this->data['cgst_amount'] = $reciept_details[0]['CGSTAMOUNT'];
		$this->data['narration'] = $reciept_details[0]['NARRATION'];
		$this->data['ledger_id'] = $reciept_details[0]['CR_ACCOUNT_ID'];
		$this->data['account_id'] = $accound_id = $reciept_details[0]['DR_ACCOUNT_ID'];
		$this->data['tinno'] = $reciept_details[0]['CUSTOMERTAXNO'];
		$this->data['sn_id'] = $reciept_details[0]['VOUCHER_ID'];
		$query_voucher = $this->db->query("Select VoucherType from SerialNo where  Company_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' AND Prefix = '$prefix'");
		$voucher_type_id = $query_voucher->result_array();
		$this->data['voucher_type_id'] = $voucher_type_id = $voucher_type_id[0]['VoucherType'];
		$query_ledger = $this->db->query("exec [usp_GetAccount] @company_id='$company_id', @id=NULL, @VoucherType='$voucher_type_id', @ColumnType='Ledger'");
		$this->data['account'] = $query_ledger->result_array();
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' ");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();


		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'BP', @ColumnType = 'Account', @isbillwise = 1");
		$this->data['ledger'] = $query_ledger->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();

		$query_acc = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$accound_id', @ISSALES = NULL,  @receiptorpayment=1, @transaction = 'U'");
		$this->data['bill_wise'] = $bill_wise = $query_acc->result_array();
		

		$this->load->view('edit_payment_voucher_billwise',$this->data);
	}

	public function update_payment_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
         $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];	

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'PM'");
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

		

		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$dr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		
		$tin_no = $this->input->post('txt_tin');
	    if($tin_no == ''){
	      $tin_no = NULL;
	    }

		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$cr_account = $_POST['cmb_ledger'];
		$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		$final_amount = $this->input->post('txt_final_total');
		
		$total = $_POST['txt_total'];
		$k = count($cr_account) - 1;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax],[CUSTOMERTAXNO]) VALUES ';
		
		for ($i=0; $i <= $k ; $i++) { 
		 $cr_account_id = $cr_account[$i];
		 $totals = $total[$i];
		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $totals, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, ''$tin_no''), ";
		}
		
		

		if($sgst_amount != 0)
		{
			
 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $sgst_id, $dr_account_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $dr_account_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $igst_id, $dr_account_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $igst_id, $dr_account_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cgst_id, $dr_account_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		   $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cgst_id, $dr_account_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id), ";
		}

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
		
		$result = $this->db->query("EXEC [dbo].[usp_UpdPayment]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date', @prefix = '$prefix', @VOUCHERNO = '$voucher_id', @Financialyear_id = $Financialyear_id, @ACCOUNT_ID = $dr_account_id, @CREATEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'  ");

		
		if($result){
			redirect(site_url() . '/entry/manage_payment_voucher/');
		}

	}

	public function update_payment_voucher_billwise(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
         $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];	

		$query_sn = $this->db->query("EXEC [dbo].[usp_GetGSTAccount] 	@COMPANY_ID = '$company_id', @VoucherType = 'PM'");
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

		

		$date1 = $this->input->post('txt_date');
		$date =  date("m-d-Y",strtotime($date1));		
		$bill_no = $this->input->post('txt_bill_no');
		if($bill_no == ''){
			$bill_no = NULL;
		}
		$prefix = $this->input->post('cmb_voucher_type'); 
		$prefix = trim($prefix);
		$voucher_type = $this->input->post('cmb_payment_type'); 
		$month =  date("m",strtotime($date1));
		$year =  date("Y",strtotime($date1));		
		$dr_account_id = $this->input->post('cmb_account_group');
		
		$voucher_id = $this->input->post('txt_serial_no');
		
		$final_amount = $this->input->post('txt_final_total');
		
		/*$tin_no = $this->input->post('txt_tin');
	    if($tin_no == ''){
	      $tin_no = NULL;
	    }*/

		$refnum = $bill_no;
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$user_name = $this->user_name;
		$cr_account = $this->input->post('cmb_ledger');
		/*$tax_id = $this->input->post('cmb_tax');
		if($tax_id == ''){
			$tax_id = 0;	
		}
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$cgst = $this->input->post('txt_cgst');
		$sgst_amount = $this->input->post('txt_total_sgst');
		$igst_amount = $this->input->post('txt_total_igst');
		$cgst_amount = $this->input->post('txt_total_cgst');
		*/
		$final_amount = $this->input->post('txt_final_total');
		
		$tax_id = 0;	
		$sgst = 0;
		$igst = 0;
		$cgst = 0;
		$sgst_amount = 0;
		$igst_amount = 0;
		$cgst_amount = 0;
		//$final_amount = 0;
		$total = $_POST['txt_total'];
		$invoice_amount = $_POST['cmb_invoice'];
		$amount = $this->input->post('txt_amount');
		$k = count($invoice_amount) - 1;
		$cr_account_id = $cr_account;

		$allaccount_insert = 'insert into AllAccountTran([COMPANY_ID],[DATE],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[REFNUM],[DR_ACCOUNT_ID],[CR_ACCOUNT_ID],[AMOUNT],[ITEM_ID],[QUANTITY],[UNIT_ID],[RATE],[DISCOUNT],[CGSTPERCENT],[SGSTPERCENT],[IGSTPERCENT],[VATPERCENT],[CGSTAMOUNT],[SGSTAMOUNT],[IGSTAMOUNT],[VATAMOUNT],[CREATEDON],[CREATEDBY],[ISACTIVE],[companytax_id],[BILLNO],[BILLDATE],[INVOICEHEADER_ID],[INVOICEHEADER_AMOUNT]) VALUES ';

		$account_insert = 'insert into AccountTran([COMPANY_ID], [DATE], [FINANCIALYEAR_ID], [VOUCHER_ID], [REFNUM], [VOUCHERTYPE], [DR_ACCOUNT_ID], [CR_ACCOUNT_ID], [AMOUNT], [NARRATION], [PREFIX], [TAXPERCENT], [CREDITPERIOD], [PARENT_ID], [ITEM_ID], [CREATEDON], [CREATEDBY], [IsTax],[CUSTOMERTAXNO]) VALUES ';

		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cr_account_id, $dr_account_id, $amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 0, NULL), ";
		
		for ($i=0; $i <= $k ; $i++) { 
		$invoice_amounts = $invoice_amount[$i];
		 $totals = $total[$i];
		 $invoice =explode("~",$invoice_amounts);
		 $bill_no_header = $invoice[0];
		 $bill_amount_header = $invoice[1];
		 $bill_date_header = $invoice[2];
		 $header_id = $invoice[3];
		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cr_account_id, $dr_account_id, $totals, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, $bill_no_header, ''$bill_date_header'', $header_id, $bill_amount_header), ";
		 
		}
		
		
/*
		if($sgst_amount != 0)
		{
			
 		$account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $sgst_id, $dr_account_id, $sgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

 		 $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $sgst_id, $dr_account_id, $sgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";

		
		}

		if($igst_amount != 0)
		{
		 $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $igst_id, $dr_account_id, $igst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		  $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $igst_id, $dr_account_id, $igst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}

		if($cgst_amount != 0)
		{
		  $account_insert .= " ($company_id, ''$date'', $Financialyear_id, @voucherno, ''$refnum'',''$voucher_type'', $cgst_id, $dr_account_id, $cgst_amount, ''$narration'', ''$prefix'', 0, 0, NULL, 0, getdate(), ''$user_name'', 1, ''$tin_no''), ";

		   $allaccount_insert .= " ($company_id, ''$date'', $Financialyear_id, ''$prefix'', @voucherno, ''$refnum'', $cgst_id, $dr_account_id, $cgst_amount, 0, 0, 0, 0, 0, $cgst, $sgst, $igst, 0, $cgst_amount, $sgst_amount, $igst_amount, 0, getdate(), ''$user_name'', 1, $tax_id, 0, NULL, 0, 0), ";
		}*/

		$account_insert = rtrim($account_insert,", ");
		$account_insert = $account_insert."; ";	

		$allaccount_insert = rtrim($allaccount_insert,", ");
		$allaccount_insert = $allaccount_insert."; ";
		
		$result = $this->db->query("EXEC [dbo].[usp_UpdPayment]  @AllAccounttran = '$allaccount_insert', @Accounttran = ' $account_insert', @Company_ID = $company_id, @date = '$date', @prefix = '$prefix', @VOUCHERNO = '$voucher_id', @Financialyear_id = $Financialyear_id, @ACCOUNT_ID = $dr_account_id, @CREATEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'  ");

		
		if($result){
			redirect(site_url() . '/entry/manage_payment_voucher_billwise/');
		}

	}

	public function delete_payment_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);
		$date =  date("m-d-Y");

		$result = $this->db->query("EXEC [dbo].[usp_DelReceipt] @COMPANY_ID = '$company_id', @date = '$date', @prefix = '$prefix',  @VOUCHERNO = '$voucher_id', @Financialyear_id = '$finance_id', @CREATEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_payment_voucher/?msg=3');
		}

	}

	public function delete_receipt_voucher(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);
		$date =  date("m-d-Y");
 
		$result = $this->db->query("EXEC [dbo].[usp_DelReceipt] @COMPANY_ID = '$company_id', @date = '$date', @prefix = '$prefix',  @VOUCHERNO = '$voucher_id', @Financialyear_id = '$finance_id', @CREATEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/entry/manage_receipt_voucher/?msg=3');
		}

	}

	public function get_debit_account() {
	$company_id = $this->company_id;
	$ledgeraccountid = $this->input->get('leg_id');
	$finance_id = $this->finance_id;

	$query_acc = $this->db->query("EXEC [dbo].[usp_GetAccount] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Vouchertype = NULL, @ColumnType = NULL, @ledgeraccountid = '$ledgeraccountid'");
		$account = $query_acc->result_array();
		echo json_encode($account, JSON_FORCE_OBJECT);
	}

	public function get_invoice() {
	$company_id = $this->company_id;
	$leg = $this->input->get('leg');
	$accound_id = $this->input->get('accound_id');
	$finance_id = $this->finance_id;
	$sales = 0;
	$string1 = 'SALES';
	
	if (strpos($leg,$string1) !== false) {
		$sales = 1;
	}

	$query_acc = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$accound_id', @ISSALES = '$sales'");
		$account = $query_acc->result_array();

		echo json_encode($account, JSON_FORCE_OBJECT);
	}

	public function get_invoice_payment() {
	$company_id = $this->company_id;
	$accound_id = $this->input->get('accound_id');
	$finance_id = $this->finance_id;

	//$query_acc = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$accound_id', @ISSALES = '$sales'");

$query_acc = $this->db->query("EXEC [dbo].[usp_GetInvoiceHeader]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id' , @ACCOUNT_ID = '$accound_id', @ISSALES = NULL,  @receiptorpayment=1, @transaction = 'A'");


		$account = $query_acc->result_array();

		echo json_encode($account, JSON_FORCE_OBJECT);
	}


	public function print_payment_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);	
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	

		 $query_payment = $this->db->query("EXEC [dbo].[usp_GetpaymentPRINT] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID  = '$voucher_id', @ISACTIVE = 1");
		 $this->data['payment_print'] = $payment_print = $query_payment->result_array();
		 // echo "<pre>";
		 // print_r( $payment_print);
		 // exit;
		
		 $this->data['account'] = $payment_print[0]['ACCOUNT'];
		  $this->data['ledger'] = $payment_print[0]['LEDGER'];
		$this->data['date'] = $payment_print[0]['DATE'];
		 $this->data['ref_no'] = $payment_print[0]['PREFIX'].$payment_print[0]['REFNUM'];
		  $this->data['prefix'] = $payment_print[0]['PREFIX'];
		  $this->data['sn'] = $payment_print[0]['VOUCHER_ID'];
		 $this->data['total_amount'] = $final_word_num = $payment_print[0]['AMOUNT'];
		 $this->data['NARRATION']  = $payment_print[0]['NARRATION'];

		$this->data['igst'] = $payment_print[0]['IGSTPERCENT'];
		$this->data['cgst'] = $payment_print[0]['CGSTPERCENT'];
		$this->data['sgst'] = $payment_print[0]['SGSTPERCENT'];

		$this->data['tax'] = $payment_print[0]['companytax_id'];
		$this->data['tax_amount'] = $tax_amount = $payment_print[0]['CGSTAMOUNT']+$payment_print[0]['SGSTAMOUNT']+$payment_print[0]['IGSTAMOUNT'];

		 if($payment_print[0]['PREFIX'] == 'BP'){
		 	$this->data['type'] = 'BANK';
		 }else{
		 	$this->data['type'] = 'CASH';
		 }
		 $final_word_nums = $final_word_num + $tax_amount;
		 $final_words  = $this->getIndianCurrency($final_word_nums);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);

		$this->load->view('print_payment_voucher',$this->data);
	}

	public function print_receipt_voucher() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $voucher_id = $this->uri->segment(3);	
		$pre = $this->input->get('pre');
		$prefix = urldecode($pre);	
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	

		 $query_payment = $this->db->query("EXEC [dbo].[usp_GetReceiptPrint] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID  = '$voucher_id', @ISACTIVE = 1");
		 $this->data['payment_print'] = $payment_print = $query_payment->result_array();
		 // echo "<pre>";
		 // print_r( $payment_print);
		 // exit;
		$this->data['NARRATION']  = $payment_print[0]['NARRATION'];
		 $this->data['account'] = $payment_print[0]['ACCOUNT'];
		  $this->data['ledger'] = $payment_print[0]['LEDGER'];
		$this->data['date'] = $payment_print[0]['DATE'];
		 $this->data['ref_no'] = $payment_print[0]['PREFIX'].$payment_print[0]['REFNUM'];
		 $this->data['total_amount'] = $final_word_num = $payment_print[0]['AMOUNT'];
		 $this->data['prefix'] = $payment_print[0]['PREFIX'];
		  $this->data['sn'] = $payment_print[0]['VOUCHER_ID'];

		 $this->data['igst'] = $payment_print[0]['IGSTPERCENT'];
		$this->data['cgst'] = $payment_print[0]['CGSTPERCENT'];
		$this->data['sgst'] = $payment_print[0]['SGSTPERCENT'];

		$this->data['tax'] = $payment_print[0]['companytax_id'];

		$this->data['tax_amount'] = $tax_amount = $payment_print[0]['CGSTAMOUNT']+$payment_print[0]['SGSTAMOUNT']+$payment_print[0]['IGSTAMOUNT'];
		 if($payment_print[0]['PREFIX'] == 'BR'){
		 	$this->data['type'] = 'BANK';
		 }else{
		 	$this->data['type'] = 'CASH';
		 }
		 $final_word_nums = $final_word_num + $tax_amount;
		 $final_words  = $this->getIndianCurrency($final_word_nums);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);
			

		$this->load->view('print_receipt_voucher',$this->data);
	}


	public function print_proforma_invoice() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
		$query_company = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = '$company_id'");
		$this->data['company_data'] = $query_company->result_array();	

			
			
			$voucher_id = $this->uri->segment(3);	
			$pre = $this->input->get('pre');
			$prefix = urldecode($pre);		
			$query_po = $this->db->query("EXEC [dbo].[usp_GetOrders_quotation] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = '$prefix', @VOUCHER_ID = '$voucher_id', @ISSALESORDER = NULL, @ISACTIVE = 1");
		$this->data['purchase_order_det'] = $purchase_order_det = $query_po->result_array();

		$this->data['prefix_voucher_id'] = $purchase_order_det[0]['V_ID'];
		$this->data['prefix_voucher_type'] = $purchase_order_det[0]['PREFIX'];
		$this->data['quote_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['DATE']));
		$this->data['validity'] = $purchase_order_det[0]['VALIDITY'];
		$this->data['qt_ref_no'] = $purchase_order_det[0]['QUOTATION_REFERENCE'];
		$this->data['qt_ref_date'] = date("d-m-Y",strtotime($purchase_order_det[0]['QUOTATION_REFERENCE_DATE']));
		$this->data['account_id'] = $account_id = $purchase_order_det[0]['ACCOUNT_ID'];
		$this->data['ledger_id'] = $purchase_order_det[0]['LEDGER_ID'];
		$this->data['account'] = $purchase_order_det[0]['ACCOUNT'];
		$this->data['type_sales'] = $type_sales = $purchase_order_det[0]['LEDGER'];
		$this->data['termsnconditions'] = $purchase_order_det[0]['TermsandConditions'];

		$this->data['tinno'] = $purchase_order_det[0]['TINNO'];
		   $tax_amount = 0;
           $total_tax_amount = 0;
           $amount_with_tax = 0;
           $total_amount_with_tax = 0;
           $sgst_amount_final = 0;
           $cgst_amount_final = 0;
           $igst_amount_final = 0;
		foreach ($purchase_order_det as  $value) {

	 		$amount = $value['AMOUNT'];
            if($type_sales == 'DOMESTIC SALES'){
              $sgst_per = $value['SGST'];
              $cgst_per = $value['CGST'];          
              $igst_per = 0;
             }else{
              $sgst_per = 0;
              $cgst_per =0;          
              $igst_per = $value['IGST'];
            }             
              
          
          	$sgst_amount = $amount * ($sgst_per / 100);
          	$cgst_amount = $amount * ($cgst_per / 100);
          	$igst_amount = $amount * ($igst_per / 100);
           	$sgst_amount_final = $sgst_amount_final + $sgst_amount;
           	$cgst_amount_final = $cgst_amount_final + $cgst_amount;
           	$igst_amount_final = $igst_amount_final + $igst_amount;
          	$tax_amount = round($sgst_amount,2) + round($cgst_amount,2) + round($igst_amount,2);
          	$total_tax_amount = $tax_amount + $total_tax_amount;
          	$amount_with_tax = $amount + $tax_amount;
          	$total_amount_with_tax = $total_amount_with_tax + $amount_with_tax;
	
		}
 


 		$final_words  = $this->getIndianCurrency($total_amount_with_tax);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);

		
/*

		$query_sales_total = $this->db->query("SELECT o.PREFIX,SUM(o.AMOUNT) as Total,SUM(o.SGST) as SGST,,SUM(o.CGST) as CGST,SUM(o.IGST) as IGST,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,REPLICATE('0',6-LEN(RTRIM(o.VOUCHER_ID))) + RTRIM(o.VOUCHER_ID) as V_ID,ORR.QUOTATION_REFERENCE from Orders o Left Outer Join ORDERREFERENCE ORR with (nolock) on o.PREFIX=orr.PREFIX and o.VOUCHER_ID=orr.VOUCHER_ID and orr.FINANCIALYEAR_ID='$finance_id' and ORR.company_id= '$company_id' 		
			JOIN Item It  with (nolock) ON O.ITEM_ID=It.ID
			join GroupItem G WITH (nolock)on  It.GROUP_ID=G.ID 
			JOIN COMPANYTAX CT WITH (NOLOCK) ON G.CompanyTax_ID=CT.ID WHERE o.COMPANY_ID = '$company_id' AND o.FINANCIALYEAR_ID = '$finance_id' AND o.ISSALESORDER IS NULL AND o.PREFIX = 'SO'  and o.voucher_id = '$voucher_id' group by o.PREFIX,o.VOUCHER_ID,o.ACCOUNT_ID,o.LEDGER_ID,o.DATE,ORR.QUOTATION_REFERENCE");
		$total = $query_sales_total->result_array();
		$final_word= $total[0]['Total'];
		 $final_words  = $this->getIndianCurrency($final_word);
		 $this->data['final_word'] = str_replace('.',' and ', $final_words);*/

		$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1, @ISACTIVE = 1");
		$this->data['address'] = $address = $query_address->result_array();
		if(!empty($address)){			
			$party_address =  array('ADDRESS1' => $address[0]['ADDRESS1'], 'ADDRESS2' => $address[0]['ADDRESS2'],'DISTRICT' => $address[0]['DISTRICT'],'PINCODE' => $address[0]['PINCODE'], 'STATE' => $address[0]['STATE'], 'vendorcode' => $address[0]['VenderCode'], 'STATETINPREFIX' => $address[0]['STATETINPREFIX'], 'GST' => $address[0]['GSTNO']);
		}else{
			$party_address =  array('ADDRESS1' => 'Not Available', 'ADDRESS2' => '','DISTRICT' => '','PINCODE' => '', 'STATE' => '', 'vendorcode' => '', 'STATETINPREFIX' => '', 'GST' => '');	
		}

		$this->data['party_address'] = $party_address;
		

		$this->load->view('print_proforma_invoice',$this->data);
	}
}
