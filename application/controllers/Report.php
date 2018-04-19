<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {	

	function __construct() {
        
        parent::__construct();
        date_default_timezone_set( 'Asia/Kolkata' );
        $this->company_id = $this->session->userdata('company_id');
        $this->finance_id = $this->session->userdata('financial_id');
        $this->user_name = $this->session->userdata('user_name');
        $this->start_dt = $this->session->userdata('financial_from');
        $this->end_dt = $this->session->userdata('financial_to');
        $this->db_name = $this->session->userdata('db_name');
        $this->connectapi->cons($this->db_name);
        $this->load->model('admin_model');
        if (!$this->session->userdata('is_logged_in'))
      redirect(site_url());
    }

	public function index() {

		
	}

	public function trail_balance() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		if($this->uri->segment(3)){
			$group_id = $this->uri->segment(3);
			$query_trial = $this->db->query("EXEC [dbo].[usp_GetTrialbalance] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @GROUP_ID = '$group_id'");
		}else{
			$query_trial = $this->db->query("EXEC [dbo].[usp_GetTrialbalance] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @GROUP_ID = NULL");
		}
		
		
		$this->data['trail_balance'] = $query_trial->result_array();
		$this->load->view('trail_balance',$this->data);	
	}

	public function general_ledger() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['ledger_report']  = array();
		$this->data['ledger_report_details']  = array();
		$ledger_id = '';
		
		if($this->uri->segment(3)){
			$ledger_id = $this->uri->segment(3);
			if($this->uri->segment(4)){
				$month = $this->uri->segment(4);
				$year = $this->uri->segment(5);
				$query_ledger_report = $this->db->query("EXEC usp_getLedger @AccountId = '$ledger_id', @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @MonthId  = '$month', @yearId  = '$year'");
				$this->data['ledger_report_details']  = $query_ledger_report->result_array();
			}else{
				$query_ledger_report = $this->db->query("EXEC usp_getLedger @AccountId = '$ledger_id', @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
				$this->data['ledger_report']  = $ledger_report = $query_ledger_report->result_array();

				$this->data['opening_bal']  = $ledger_report[0]['OPENINGBALANCE'].' '.$ledger_report[0]['OPACCOUNTTYPE'];
			}
			

		}
		$query_leger = $this->db->query("Exec [dbo].[usp_getAccount_Ledger] @COMPANY_ID ='$company_id',@FinancialYear_Id ='$finance_id'");
		$this->data['ledger'] = $query_leger->result_array();
		$this->data['ledger_id'] = $ledger_id;
		$this->load->view('general_ledger',$this->data);	
	}

	public function cash_book() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$cash_book_details = array();
		$cash_book = array();
		$cash_book_month = array();
		if($this->uri->segment(3)){
			$account_id = $this->uri->segment(3);
			if($this->uri->segment(4)){
			$month = $this->uri->segment(4);
			$query_cash = $this->db->query("EXEC [dbo].[usp_GetCashBook] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Month = '$month', @AccountID = '$account_id'");
		$cash_book_month = $query_cash->result_array();
				$close = $this->input->get('close');
				$opening_bal = urldecode($close);
				$this->data['opening_bal']  = $opening_bal;
		}else{
			$query_cash = $this->db->query("EXEC [dbo].[usp_GetCashBook] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Month = NULL, @AccountID = '$account_id'");
		$cash_book_details = $query_cash->result_array();
					$close = $this->input->get('close');
					$opening_bal = urldecode($close);
					$this->data['opening_bal']  = $opening_bal;
		}
		
		$this->data['ACCOUNT_ID'] = $this->uri->segment(3);
		}else{			
			$query_cash = $this->db->query("EXEC [dbo].[usp_GetCashBook] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Month = NULL, @AccountID = NULL");
			$cash_book = $query_cash->result_array();	
			//$this->data['opening_bal']  = $cash_book[0]['OPENING'];
		}
		$this->data['cash_book_details'] = $cash_book_details;
		$this->data['cash_book'] = $cash_book;
		$this->data['cash_book_month'] = $cash_book_month;
		
		$this->load->view('cash_book',$this->data);	
	}

	public function bank_book() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$bank_book_details = array();
		$bank_book = array();
		$bank_book_month = array();
		if($this->uri->segment(3)){
			$account_id = $this->uri->segment(3);
			if($this->uri->segment(4)){
			$month = $this->uri->segment(4);
			$query_bank = $this->db->query("EXEC [dbo].[usp_GetBankBook] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Month = '$month', @AccountID = '$account_id'");		
			$bank_book_month = $query_bank->result_array();
			$close = $this->input->get('close');
				$opening_bal = urldecode($close);
				$this->data['opening_bal']  = $opening_bal;
			}else{
				$query_bank = $this->db->query("EXEC [dbo].[usp_GetBankBook] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Month = NULL, @AccountID = '$account_id'");		
			$bank_book_details = $query_bank->result_array();
			$close = $this->input->get('close');
					$opening_bal = urldecode($close);
					$this->data['opening_bal']  = $opening_bal;

			}
			$this->data['ACCOUNT_ID'] = $this->uri->segment(3);
		}else{
			$query_bank = $this->db->query("EXEC [dbo].[usp_GetBankBook] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @Month = NULL, @AccountID = NULL");		
			$bank_book = $query_bank->result_array();
			$this->data['opening_bal']  = $bank_book[0]['OPENING'];
		}		
		
		$this->data['bank_book_details'] = $bank_book_details;
		$this->data['bank_book_month'] = $bank_book_month;		
		$this->data['bank_book'] = $bank_book;
		$this->load->view('bank_book',$this->data);	
	}

	public function profit_n_loss() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$query_profit = $this->db->query("EXEC [dbo].[usp_getPandL]  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");		
			$this->data['profit_loss'] = $query_profit->result_array();
		$this->load->view('profit_n_loss',$this->data);
	}

	public function sales_register() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$sales_month = array();
		$sales_register_details = array();
		$sales_register_month = array();
		$sales_register_prefix = array();
		
		if($this->uri->segment(3)){
			$month = $this->data['month'] = $this->uri->segment(3);
			if($this->uri->segment(4)){
			$account_id = $this->uri->segment(4);
				if(isset($_GET['pre'])){
					$pre = $this->input->get('pre');
					$prefix = urldecode($pre);
					$vouhcer_id = $this->input->get('vouhcer_id');
					$query_sales_pre = $this->db->query("EXEC [dbo].[usp_GetSalesRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = '$prefix', @VOUCHER_ID = '$vouhcer_id' ");		
					$sales_register_prefix = $query_sales_pre->result_array();
					$this->data['ACCOUNT'] = $sales_register_prefix[0]['ACCOUNT'];
					$this->data['LEDGER'] = $sales_register_prefix[0]['LEDGER'];
					$this->data['type_sales'] = $sales_register_prefix[0]['LEDGER'];					
					$this->data['date'] = date("d-m-Y",strtotime($sales_register_prefix[0]['DATE']));
					$this->data['prefix'] = $sales_register_prefix[0]['PREFIX'];
					$this->data['sn'] = $sales_register_prefix[0]['V_ID'];
					$this->data['bill_no'] = $sales_register_prefix[0]['REFNUM'];	
			
				}else{
					$query_sales_month = $this->db->query("EXEC [dbo].[usp_GetSalesRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
					$sales_register_month = $query_sales_month->result_array();

				}
			}else{
			
			$query_sales_det = $this->db->query("EXEC [dbo].[usp_GetSalesRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
			$sales_register_details = $query_sales_det->result_array();
		}
			
			
		}else{
		$query_sales = $this->db->query("EXEC [dbo].[usp_GetSalesRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
			$sales_month = $query_sales->result_array();
		}

		$this->data['sales_month'] = $sales_month;
		$this->data['sales_register_details'] = $sales_register_details;
		$this->data['sales_register_month'] = $sales_register_month;
		$this->data['sales_register_prefix'] = $sales_register_prefix;
		$this->load->view('sales_register',$this->data);
	}

	public function sales_register_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_sales = $this->db->query("EXEC [dbo].[usp_GetSalesRegisterReport] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['sales_register_report'] = $query_sales->result_array();
		$this->load->view('sales_register_report',$this->data);
	}

	public function purchase_register() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$purchase_month = array();
		$purchase_register_details = array();
		$purchase_register_month = array();
		$purchase_register_prefix = array();
		
		if($this->uri->segment(3)){
			$month = $this->data['month'] = $this->uri->segment(3);
			if($this->uri->segment(4)){
			$account_id = $this->uri->segment(4);
				if(isset($_GET['pre'])){
					$pre = $this->input->get('pre');
					$prefix = urldecode($pre);
					$vouhcer_id = $this->input->get('vouhcer_id');
					$query_sales_pre = $this->db->query("EXEC [dbo].[usp_GetPurchaseRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = '$prefix', @VOUCHER_ID = '$vouhcer_id' ");		
					$purchase_register_prefix = $query_sales_pre->result_array();
					$this->data['ACCOUNT'] = $purchase_register_prefix[0]['ACCOUNT'];
					$this->data['LEDGER'] = $purchase_register_prefix[0]['LEDGER'];
					$this->data['type_sales'] = $purchase_register_prefix[0]['LEDGER'];					
					$this->data['date'] = date("d-m-Y",strtotime($purchase_register_prefix[0]['DATE']));
					$this->data['prefix'] = $purchase_register_prefix[0]['PREFIX'];
					$this->data['sn'] = $purchase_register_prefix[0]['V_ID'];
					$this->data['bill_no'] = $purchase_register_prefix[0]['REFNUM'];	
			
				}else{
					$query_sales_month = $this->db->query("EXEC [dbo].[usp_GetPurchaseRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
					$purchase_register_month = $query_sales_month->result_array();

				}
			}else{
			
			$query_sales_det = $this->db->query("EXEC [dbo].[usp_GetPurchaseRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
			$purchase_register_details = $query_sales_det->result_array();
		}
			
			
		}else{
		$query_sales = $this->db->query("EXEC [dbo].[usp_GetPurchaseRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
			$purchase_month = $query_sales->result_array();
		}

		$this->data['purchase_month'] = $purchase_month;
		$this->data['purchase_register_details'] = $purchase_register_details;
		$this->data['purchase_register_month'] = $purchase_register_month;
		$this->data['purchase_register_prefix'] = $purchase_register_prefix;			
		$this->load->view('purchase_register',$this->data);
	}

	public function purchase_register_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_purchase = $this->db->query("EXEC [dbo].[usp_GetPurchaseRegisterReport] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['purchase_register_report'] = $query_purchase->result_array();
		$this->load->view('purchase_register_report',$this->data);
	}
	
	public function balance_sheet() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$query_balance = $this->db->query("EXEC [dbo].[usp_getBalanceSheet]  @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id'");		
			$this->data['balance_sheet'] = $query_balance->result_array();
		$this->load->view('balance_sheet',$this->data);
	}

	public function credit_note_register() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$sales_month = array();
		$sales_register_details = array();
		$sales_register_month = array();
		$sales_register_prefix = array();
		
		if($this->uri->segment(3)){
			$month = $this->data['month'] = $this->uri->segment(3);
			if($this->uri->segment(4)){
			$account_id = $this->uri->segment(4);
				if(isset($_GET['pre'])){
					$pre = $this->input->get('pre');
					$prefix = urldecode($pre);
					$vouhcer_id = $this->input->get('vouhcer_id');
					$query_sales_pre = $this->db->query("EXEC [dbo].[usp_GetCreditNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = '$prefix', @VOUCHER_ID = '$vouhcer_id' ");		
					$sales_register_prefix = $query_sales_pre->result_array();
					$this->data['ACCOUNT'] = $sales_register_prefix[0]['ACCOUNT'];
					$this->data['LEDGER'] = $sales_register_prefix[0]['LEDGER'];
					$this->data['type_sales'] = $sales_register_prefix[0]['LEDGER'];					
					$this->data['date'] = date("d-m-Y",strtotime($sales_register_prefix[0]['DATE']));
					$this->data['prefix'] = $sales_register_prefix[0]['PREFIX'];
					$this->data['sn'] = $sales_register_prefix[0]['V_ID'];
					$this->data['bill_no'] = $sales_register_prefix[0]['REFNUM'];	
			
				}else{
					$query_sales_month = $this->db->query("EXEC [dbo].[usp_GetCreditNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
					$sales_register_month = $query_sales_month->result_array();

				}
			}else{
			
			$query_sales_det = $this->db->query("EXEC [dbo].[usp_GetCreditNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
			$sales_register_details = $query_sales_det->result_array();
		}
			
			
		}else{
		$query_sales = $this->db->query("EXEC [dbo].[usp_GetCreditNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
			$sales_month = $query_sales->result_array();
		}

		$this->data['sales_month'] = $sales_month;
		$this->data['sales_register_details'] = $sales_register_details;
		$this->data['sales_register_month'] = $sales_register_month;
		$this->data['sales_register_prefix'] = $sales_register_prefix;
		$this->load->view('credit_note_register',$this->data);
	}

	public function debit_note_register() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$sales_month = array();
		$sales_register_details = array();
		$sales_register_month = array();
		$sales_register_prefix = array();
		
		if($this->uri->segment(3)){
			$month = $this->data['month'] = $this->uri->segment(3);
			if($this->uri->segment(4)){
			$account_id = $this->uri->segment(4);
				if(isset($_GET['pre'])){
					$pre = $this->input->get('pre');
					$prefix = urldecode($pre);
					$vouhcer_id = $this->input->get('vouhcer_id');
					$query_sales_pre = $this->db->query("EXEC [dbo].[usp_GetDebitNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = '$prefix', @VOUCHER_ID = '$vouhcer_id' ");		
					$sales_register_prefix = $query_sales_pre->result_array();
					$this->data['ACCOUNT'] = $sales_register_prefix[0]['ACCOUNT'];
					$this->data['LEDGER'] = $sales_register_prefix[0]['LEDGER'];
					$this->data['type_sales'] = $sales_register_prefix[0]['LEDGER'];					
					$this->data['date'] = date("d-m-Y",strtotime($sales_register_prefix[0]['DATE']));
					$this->data['prefix'] = $sales_register_prefix[0]['PREFIX'];
					$this->data['sn'] = $sales_register_prefix[0]['V_ID'];
					$this->data['bill_no'] = $sales_register_prefix[0]['REFNUM'];	
			
				}else{
					$query_sales_month = $this->db->query("EXEC [dbo].[usp_GetDebitNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = '$account_id', @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
					$sales_register_month = $query_sales_month->result_array();

				}
			}else{
			
			$query_sales_det = $this->db->query("EXEC [dbo].[usp_GetDebitNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");		
			$sales_register_details = $query_sales_det->result_array();
		}
			
			
		}else{
		$query_sales = $this->db->query("EXEC [dbo].[usp_GetDebitNoteRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @ACCOUNT_ID = NULL, @MONTH  = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
			$sales_month = $query_sales->result_array();
		}

		$this->data['sales_month'] = $sales_month;
		$this->data['sales_register_details'] = $sales_register_details;
		$this->data['sales_register_month'] = $sales_register_month;
		$this->data['sales_register_prefix'] = $sales_register_prefix;
		$this->load->view('debit_note_register',$this->data);
	}

	public function current_liability(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'CL', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['current_liability'] = $current_liability = $query_current_lia->result_array();

		$this->load->view('current_liability',$this->data);

	}

	public function loan_advance(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'LA', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['loan_advance'] = $loan_advance = $query_current_lia->result_array();

		$this->load->view('loan_advance',$this->data);

	}

	public function loan_advance_asset(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'LAD', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['loan_advance_asset'] = $loan_advance_asset = $query_current_lia->result_array();

		$this->load->view('loan_advance_asset',$this->data);

	}

	public function fixed_asset(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'FA', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['fixed_asset'] = $fixed_asset = $query_current_lia->result_array();

		$this->load->view('fixed_asset',$this->data);

	}

	public function investments_deposit(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'ID', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['investments_deposit'] = $investments_deposit = $query_current_lia->result_array();

		$this->load->view('investments_deposit',$this->data);

	}

	public function secured_loan(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'SL', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['secured_loan'] = $secured_loan = $query_current_lia->result_array();

		$this->load->view('secured_loan',$this->data);

	}

	public function un_secured_loan(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'UL', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['un_secured_loan'] = $un_secured_loan = $query_current_lia->result_array();

		$this->load->view('un_secured_loan',$this->data);

	}


	public function current_asset(){

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_current_lia = $this->db->query("EXEC [dbo].[usp_getBalacesheetDetails] @type = 'CA', @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id' ");		
		$this->data['current_asset'] = $fixed_asset = $query_current_lia->result_array();

		$this->load->view('current_asset',$this->data);

	}

	/*$months = array();
		$query_fin = $this->db->query("EXEC [dbo].[usp_GetCompanyFinancialYear] @FINANCIALYEAR_ID = ".$finance_id.", @COMPANY_ID = ".$company_id);
				 	$financial_year = $query_fin->result_array();
				 	if(!empty($financial_year)){
				 		$financial_select_year = $financial_year[0]['FINANCIALYEAR'];
				 	$financial_from = date("Y-m-d",strtotime($financial_year[0]['STARTDATE']));
				 	$financial_to = date("Y-m-d",strtotime($financial_year[0]['ENDDATE']));
				 }
  	$from = $financial_from;
	$to = $financial_to;
	$i = date("Ym", strtotime($a));
	while($i <= date("Ym", strtotime($from))){
    
    $$months[] = (date("FY", strtotime($i."01")));
    //echo substr($i, 4, 2);
    if(substr($i, 4, 2) == "12")
        $i = (date("Y", strtotime($i."01")) + 1)."01";
    else
        $i++;

	}*/

}
