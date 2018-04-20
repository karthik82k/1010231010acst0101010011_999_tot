<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mis_report extends CI_Controller {	

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

	public function cancel_sales_register() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		
		$query_trial = $this->db->query("EXEC [dbo].[usp_GetInactiveInvoiceRegister] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @GROUP_ID = NULL");
		$this->data['trail_balance'] = $query_trial->result_array();
		$this->load->view('cancel_sales_register',$this->data);	
	}

	public function sales_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$sales_register_report_det = array();
		$this->data['sales_register_report'] =  array();
		$sales_master = array();
		$sales_total = array();
		$month = '';
		$export_col = '';
		$sez_col = '';
		$zero_col = '';
		if($this->uri->segment(3)){
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_sales = $this->db->query("EXEC [dbo].[usp_GetSalesRegisterReport] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['sales_register_report'] = $sales_register_report = $query_sales->result_array();
		foreach ($sales_register_report as $val) { 

				$prefix = $val['PREFIX'];
				if($prefix != 'Add Debit Note' && $prefix != 'Add Receipt' && $prefix != 'Less Credit Note'){
					$sales_master[$val['PREFIX']][$val['VOUCHER_ID']] = array('DATE' => $val['DATE'], 'PREFIX' => $val['PREFIX'], 'VOUCHER_ID' => $val['VOUCHER_ID'], 'TINNO' => $val['TINNO'], 'ACCOUNT' => $val['ACCOUNT'], 'REFNUM' => $val['REFNUM']);

				$sales_total[$val['PREFIX']][$val['VOUCHER_ID']][$val['GSTPERCENT']] =  array('SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);
				
				$sales_register_report_det[$val['DATE']][$val['PREFIX']][$val['REFNUM']][$val['VOUCHER_ID']][$val['TINNO']][$val['ACCOUNT']][$val['ACCOUNT']][$val['GSTPERCENT']] = array('LEDGER' => $val['LEDGER'],'SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'GSTPERCENT' => $val['GSTPERCENT'], 'LEDGER' => $val['LEDGER'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);

				}
			
			$type = $val['LEDGER'];

			if(stristr($type, "EXPORT") != '' ){
              $export_col = 'y';
            }

            if(stristr($type, "SEZ") != '' ){
              $sez_col = 'y';
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_col =  'y';
            }
			
		}
		

	}

	$this->data['sales_register_report_det'] = $sales_register_report_det;
	$this->data['sales_master'] = $sales_master;
	$this->data['sales_total'] = $sales_total;
	
		$this->data['export_col'] = $export_col;
		$this->data['sez_col'] = $sez_col;
		$this->data['zero_col'] = $zero_col;

		$query_month = $this->db->query("SELECT *  FROM [dbo].[months]");
		$this->data['month_list'] = $query_month->result_array();
		$this->data['month'] = $month;

		$this->load->view('sales_register_report',$this->data);
	}

	public function purchase_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['purchase_register_report']  = array();		
		$purchase_register_report_det = array();
		$purchase_master = array();
		$purchase_total = array();
		$month = '';
		$export_col = '';
		$sez_col = '';
		$zero_col = '';
		if($this->uri->segment(3)){
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_purchase = $this->db->query("EXEC [dbo].[usp_GetPurchaseRegisterReport] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['purchase_register_report'] = $purchase_register_report = $query_purchase->result_array();
		foreach ($purchase_register_report as $val) {

			$prefix = $val['PREFIX'];
				if($prefix != 'Less Debit Note' && $prefix != 'Less Payment' && $prefix != 'Add Credit Note'){
					$purchase_master[$val['PREFIX']][$val['VOUCHER_ID']] = array('DATE' => $val['DATE'], 'PREFIX' => $val['PREFIX'], 'VOUCHER_ID' => $val['VOUCHER_ID'], 'TINNO' => $val['TINNO'], 'ACCOUNT' => $val['ACCOUNT'], 'REFNUM' => $val['REFNUM']);

				$purchase_total[$val['PREFIX']][$val['VOUCHER_ID']][$val['GSTPERCENT']] =  array('SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);
				
				$purchase_register_report_det[$val['DATE']][$val['PREFIX']][$val['REFNUM']][$val['VOUCHER_ID']][$val['TINNO']][$val['ACCOUNT']][$val['ACCOUNT']][$val['GSTPERCENT']] = array('LEDGER' => $val['LEDGER'],'SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'GSTPERCENT' => $val['GSTPERCENT'], 'LEDGER' => $val['LEDGER'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);

				}

			$type = $val['LEDGER'];

			if(stristr($type, "IMPORT") != '' ){
              $export_col = 'y';
            }

            if(stristr($type, "SEZ") != '' ){
              $sez_col = 'y';
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_col =  'y';
            }
			
		}
	}

	$this->data['purchase_register_report_det'] = $purchase_register_report_det;
	$this->data['purchase_master'] = $purchase_master;
	$this->data['purchase_total'] = $purchase_total;

		$query_month = $this->db->query("SELECT *  FROM [dbo].[months]");
		$this->data['month_list'] = $query_month->result_array();
		$this->data['month'] = $month;
		$this->data['export_col'] = $export_col;
		$this->data['sez_col'] = $sez_col;
		$this->data['zero_col'] = $zero_col;

		$this->load->view('purchase_register_report',$this->data);
	}

	public function debitnote_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['purchase_register_report']  = array();		
		$purchase_register_report_det = array();
		$purchase_master = array();
		$purchase_total = array();
		$month = '';
		$export_col = '';
		$sez_col = '';
		$zero_col = '';
		if($this->uri->segment(3)){
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_purchase = $this->db->query("EXEC [dbo].[usp_GetDebitNoteRegisterReport_2] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['purchase_register_report'] = $purchase_register_report = $query_purchase->result_array();
		foreach ($purchase_register_report as $val) {
				
					$purchase_master[$val['PREFIX']][$val['VOUCHER_ID']] = array('DATE' => $val['DATE'], 'PREFIX' => $val['PREFIX'], 'VOUCHER_ID' => $val['VOUCHER_ID'], 'TINNO' => $val['TINNO'], 'ACCOUNT' => $val['ACCOUNT'], 'REFNUM' => $val['REFNUM'],'BILLNO' => $val['BILLNO'],'BILLDATE' => $val['BILLDATE']);

				$purchase_total[$val['PREFIX']][$val['VOUCHER_ID']][$val['GSTPERCENT']] =  array('SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);
				
				$purchase_register_report_det[$val['DATE']][$val['PREFIX']][$val['REFNUM']][$val['VOUCHER_ID']][$val['TINNO']][$val['ACCOUNT']][$val['ACCOUNT']][$val['GSTPERCENT']] = array('LEDGER' => $val['LEDGER'],'SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'GSTPERCENT' => $val['GSTPERCENT'], 'LEDGER' => $val['LEDGER'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);

				

			$type = $val['LEDGER'];

			if(stristr($type, "IMPORT") != '' || stristr($type, "EXPORT") != '' ){
              $export_col = 'y';
            }

            if(stristr($type, "SEZ") != '' ){
              $sez_col = 'y';
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_col =  'y';
            }
			
		}
	}

	$this->data['purchase_register_report_det'] = $purchase_register_report_det;
	$this->data['purchase_master'] = $purchase_master;
	$this->data['purchase_total'] = $purchase_total;

		$query_month = $this->db->query("SELECT *  FROM [dbo].[months]");
		$this->data['month_list'] = $query_month->result_array();
		$this->data['month'] = $month;
		$this->data['export_col'] = $export_col;
		$this->data['sez_col'] = $sez_col;
		$this->data['zero_col'] = $zero_col;
		$this->load->view('debitnote_register_report',$this->data);
	}
	
	public function creditnote_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['purchase_register_report']  = array();		
		$purchase_register_report_det = array();
		$purchase_master = array();
		$purchase_total = array();
		$month = '';
		$export_col = '';
		$sez_col = '';
		$zero_col = '';
		if($this->uri->segment(3)){
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_purchase = $this->db->query("EXEC [dbo].[usp_GetCreditNoteRegisterReport_2] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['purchase_register_report'] = $purchase_register_report = $query_purchase->result_array();
		foreach ($purchase_register_report as $val) {
				
					$purchase_master[$val['PREFIX']][$val['VOUCHER_ID']] = array('DATE' => $val['DATE'], 'PREFIX' => $val['PREFIX'], 'VOUCHER_ID' => $val['VOUCHER_ID'], 'TINNO' => $val['TINNO'], 'ACCOUNT' => $val['ACCOUNT'], 'REFNUM' => $val['REFNUM'],'BILLNO' => $val['BILLNO'],'BILLDATE' => $val['BILLDATE']);

				$purchase_total[$val['PREFIX']][$val['VOUCHER_ID']][$val['GSTPERCENT']] =  array('SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);
				
				$purchase_register_report_det[$val['DATE']][$val['PREFIX']][$val['REFNUM']][$val['VOUCHER_ID']][$val['TINNO']][$val['ACCOUNT']][$val['ACCOUNT']][$val['GSTPERCENT']] = array('LEDGER' => $val['LEDGER'],'SGSTAMOUNT' => $val['SGSTAMOUNT'], 'CGSTAMOUNT' => $val['CGSTAMOUNT'],'IGSTAMOUNT' => $val['IGSTAMOUNT'],'GSTPERCENT' => $val['GSTPERCENT'], 'LEDGER' => $val['LEDGER'],'AMOUNT' => $val['AMOUNT'],'DISCOUNT' => $val['DISCOUNT']);

				

			$type = $val['LEDGER'];

			if(stristr($type, "IMPORT") != '' || stristr($type, "EXPORT") != '' ){
              $export_col = 'y';
            }

            if(stristr($type, "SEZ") != '' ){
              $sez_col = 'y';
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_col =  'y';
            }
			
		}
	}

	$this->data['purchase_register_report_det'] = $purchase_register_report_det;
	$this->data['purchase_master'] = $purchase_master;
	$this->data['purchase_total'] = $purchase_total;

		$query_month = $this->db->query("SELECT *  FROM [dbo].[months]");
		$this->data['month_list'] = $query_month->result_array();
		$this->data['month'] = $month;
		$this->data['export_col'] = $export_col;
		$this->data['sez_col'] = $sez_col;
		$this->data['zero_col'] = $zero_col;

		$this->load->view('credit_register_report',$this->data);
	}


	public function cancel_sales_report() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['cancel_sales_report'] = array();
		$this->data['cancel_sales_report_det'] = array();
		
		if($this->uri->segment(3)){
			$vouhcer_id  = $this->uri->segment(3);
				$pre = $this->input->get('pre');
				$prefix = urldecode($pre);
					$query_sales_pre = $this->db->query("EXEC [dbo].[usp_GetInactiveInvoiceRegister]  @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @PREFIX  = '$prefix', @VOUCHER_ID = '$vouhcer_id' ");		
					$this->data['cancel_sales_report_det'] = $cancel_sales_report_det = $query_sales_pre->result_array();
					$this->data['ACCOUNT'] = $cancel_sales_report_det[0]['ACCOUNT'];
					$this->data['LEDGER'] = $cancel_sales_report_det[0]['LEDGER'];
					$this->data['type_sales'] = $cancel_sales_report_det[0]['LEDGER'];					
					$this->data['date'] = date("d-m-Y",strtotime($cancel_sales_report_det[0]['DATE']));
					$this->data['prefix'] = $cancel_sales_report_det[0]['PREFIX'];
					$this->data['sn'] = $cancel_sales_report_det[0]['V_ID'];
					$this->data['bill_no'] = $cancel_sales_report_det[0]['REFNUM'];	
			
				}else{

		$query_cancel = $this->db->query("exec [dbo].[usp_GetInactiveInvoiceRegister] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id'");
		$this->data['cancel_sales_report'] = $query_cancel->result_array();
	}

		$this->load->view('cancel_invoice',$this->data);
	}

	public function gstn_filling() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$month = '';
		$export_col = '';
		$sez_col = '';
		$zero_col = '';
		$this->data['gstr1b'] = array();
		$this->data['gstr3b'] = array();
		$this->data['month'] = $month;
		if($this->uri->segment(3)){
		$month = $this->data['month'] = $this->uri->segment(3);
		$query_gstr1 = $this->db->query("EXEC [dbo].[usp_GetGSTR1] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['gstr1b'] = $gstr1_report = $query_gstr1->result_array();

		$query_gstr3 = $this->db->query("EXEC [dbo].[usp_GetGSTR3B] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$this->data['gstr3b'] = $gstr3_report = $query_gstr3->result_array();
		foreach ($gstr3_report as $val) {

			

			$type = $val['LEDGER'];

			if(stristr($type, "IMPORT") != '' || stristr($type, "EXPORT") != '' ){
              $export_col = 'y';
            }

            if(stristr($type, "SEZ") != '' ){
              $sez_col = 'y';
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_col =  'y';
            }
			
		}
	}
		$this->data['export_col'] = $export_col;
		$this->data['sez_col'] = $sez_col;
		$this->data['zero_col'] = $zero_col;

		$query_month = $this->db->query("SELECT *  FROM [dbo].[months]");
		$this->data['month_list'] = $query_month->result_array();
		$this->load->view('gstn_filling',$this->data);
	}


	public function payable_billwise() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['payable'] =  array();
		$this->data['account_id'] =  '';

		if($this->input->post())
		{
			$days = $this->input->post('txt_days');
			$account_id = $this->input->post('cmb_account');
			if($days == ''){
				$days = 0;
			}

			$query_payable = $this->db->query("EXEC [usp_GetAgeingReport] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ACCOUNT_ID = '$account_id', @ISSALES = 0, @AGEDAYS = $days");
			$this->data['payable'] = $query_payable->result_array();


			$query_account = $this->db->query("exec [dbo].[usp_GetAccount] @COMPANY_ID = '$this->company_id', @FINANCIALYEAR_ID  = '$finance_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL, @ledgeraccountid  = Null");
			$this->data['account'] = $query_account->result_array();

			$this->data['account_id'] =  $account_id;
		}
		
	

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'PB', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$this->load->view('payment_billwise',$this->data);

	}

	public function recievable_billwise() {
		

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['payable'] =  array();
		$this->data['account_id'] =  '';

		if($this->input->post())
		{
			$days = $this->input->post('txt_days');
			$account_id = $this->input->post('cmb_account');
			if($days == ''){
				$days = 0;
			}

			$query_payable = $this->db->query("EXEC [usp_GetAgeingReport] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ACCOUNT_ID = '$account_id', @ISSALES = 1, @AGEDAYS = $days");
			$this->data['payable'] = $query_payable->result_array();


			$query_account = $this->db->query("exec [dbo].[usp_GetAccount] @COMPANY_ID = '$this->company_id', @FINANCIALYEAR_ID  = '$finance_id', @ID = '$account_id', @Vouchertype = NULL, @ColumnType = NULL, @ledgeraccountid  = Null");
			$this->data['account'] = $query_account->result_array();

			$this->data['account_id'] =  $account_id;
		}
		
	

		$query_ledger = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @Vouchertype = 'RB', @ColumnType = 'Account'");
		$this->data['ledger'] = $query_ledger->result_array();

		$this->load->view('receivable_billwise',$this->data);

	}

	public function export_excel_gstr() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		//$month = 'April';
		$month = $this->data['month'] = $this->uri->segment(3);

		$query_gstr1 = $this->db->query("EXEC [dbo].[usp_GetGSTR1] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$gstr1_report = $query_gstr1->result_array();

		$query_gstr3 = $this->db->query("EXEC [dbo].[usp_GetGSTR3B] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH  = '$month' ");	
	
		$gstr3_report = $query_gstr3->result_array();
		$this->load->library('excel');
    	
    	$i = 0;
    	
    	$this->excel->setActiveSheetIndex($i);
    	$date_of_report = date('d-M-Y');
    	$start_of_report = date('d-M-Y');
    	$sharedStyle1 = new PHPExcel_Style();
    	$sharedStyle2 = new PHPExcel_Style();

    	$sharedStyle1->applyFromArray(
    			array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
    					'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
    					'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    					'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)),
    			)
    	);
    	
    	// name the worksheet    	
    	$title_set = "GSTN_FILLING";
    	$this->excel->getActiveSheet()->setTitle($title_set);
    	//set cell A1 content with some text
    	$cell_value1 = "GSTN FILLING";
    	$this->excel->getActiveSheet()->setCellValue('A1', $cell_value1);
    	//change the font size
    	$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(13);
    	$this->excel->getActiveSheet()->mergeCells('A1:K1');
    	$this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>  array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	
    	$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    	$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    	$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    	$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
    	$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    	$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    	$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    	$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    	$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    	$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    	$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    	
    	$this->excel->getActiveSheet()->mergeCells('D2:E2');
    	$this->excel->getActiveSheet()->mergeCells('F2:H2');
    	$this->excel->getActiveSheet()->mergeCells('I2:K2');

    	$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
    	$this->excel->getActiveSheet()->getStyle('A2')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	$this->excel->getActiveSheet()->getStyle('B2')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	$this->excel->getActiveSheet()->getStyle('C2')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	$this->excel->getActiveSheet()->getStyle('D2:E2')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	$this->excel->getActiveSheet()->getStyle('F2:H2')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	$this->excel->getActiveSheet()->getStyle('I2:K2')->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
    	

    	$this->excel->getActiveSheet()->SetCellValue('A2', 'GSTR-3B');
    	$this->excel->getActiveSheet()->SetCellValue('B2', 'TAX');
    	$this->excel->getActiveSheet()->SetCellValue('C2', '');
    	$this->excel->getActiveSheet()->SetCellValue('D2', 'TAXABLE');
    	$this->excel->getActiveSheet()->SetCellValue('F2', 'TAX');
    	$this->excel->getActiveSheet()->SetCellValue('I2', 'NON TAXABLE');

    	$this->excel->getActiveSheet()->getStyle('A3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('B3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('C3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('D3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('E3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('F3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('G3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('H3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('I3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('J3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('K3')->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->SetCellValue('A3', '');
	   	$this->excel->getActiveSheet()->SetCellValue('B3', 'RATE %');
    	$this->excel->getActiveSheet()->SetCellValue('C3', 'TOTAL VALUE');
    	$this->excel->getActiveSheet()->SetCellValue('D3', 'DOMESTIC');
    	$this->excel->getActiveSheet()->SetCellValue('E3', 'INTERSTATE');
    	$this->excel->getActiveSheet()->SetCellValue('F3', 'SGST');
    	$this->excel->getActiveSheet()->SetCellValue('G3', 'CGST');
    	$this->excel->getActiveSheet()->SetCellValue('H3', 'IGST');
    	$this->excel->getActiveSheet()->SetCellValue('I3', 'EXPORT');
    	$this->excel->getActiveSheet()->SetCellValue('J3', 'SEZ');
    	$this->excel->getActiveSheet()->SetCellValue('K3', 'ZERO');
    	
		
		$k = 4;
		foreach ($gstr3_report as  $value) {



    	$this->excel->getActiveSheet()->getStyle('A'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('B'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('C'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('D'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('E'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('F'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('G'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('H'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('I'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('J'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('K'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
			 $interstate_amount = 0;
              $domestic_amount = 0;
              $export_amount = 0;
              $sez_amount = 0;
              $zero_amount = 0;
               $ledger = $value['LEDGER'];
             $tax_rate = $value['CGSTPERCENT'] + $value['CGSTPERCENT'] + $value['IGSTPERCENT'];
            $total_amount = $value['AMOUNT'] + $value['SGSTAMOUNT'] + $value['CGSTAMOUNT'] + $value['IGSTAMOUNT'];
            $type = $value['SGSTPERCENT'] + $value['CGSTPERCENT'];
            if($type > 0 ){
              $domestic_amount = abs($value['AMOUNT']) - abs($value['DISCOUNT']);
            }elseif($value['IGSTPERCENT'] > 0) {
              $interstate_amount = abs($value['AMOUNT']) -  abs($value['DISCOUNT']);
            }elseif($tax_rate == 0){

               if(stristr($ledger, "EXPORT") != '' || stristr($ledger, "IMPORT") != '' ){
              $export_amount = $value['AMOUNT'];
            }

            if(stristr($ledger, "SEZ") != '' ){
              $sez_amount = $value['AMOUNT'];
            }

            if(stristr($type, "ZERO") != '' ){
              $zero_amount = $value['AMOUNT'];
            }
            }

			$this->excel->getActiveSheet()->SetCellValue('A'.$k, $value['LEDGER']);
		   	$this->excel->getActiveSheet()->SetCellValue('B'.$k, $tax_rate);
	    	$this->excel->getActiveSheet()->SetCellValue('C'.$k, number_format(abs($total_amount),2));
	    	$this->excel->getActiveSheet()->SetCellValue('D'.$k, number_format($domestic_amount,2));
	    	$this->excel->getActiveSheet()->SetCellValue('E'.$k, number_format($interstate_amount,2));
	    	$this->excel->getActiveSheet()->SetCellValue('F'.$k, number_format(abs($value['SGSTAMOUNT']),2));
	    	$this->excel->getActiveSheet()->SetCellValue('G'.$k, number_format(abs($value['CGSTAMOUNT']),2));
	    	$this->excel->getActiveSheet()->SetCellValue('H'.$k, number_format(abs($value['IGSTAMOUNT']),2));
	    	$this->excel->getActiveSheet()->SetCellValue('I'.$k, number_format($export_amount,2));
	    	$this->excel->getActiveSheet()->SetCellValue('J'.$k, number_format($sez_amount,2));
	    	$this->excel->getActiveSheet()->SetCellValue('K'.$k, number_format($zero_amount,2));
		$k = $k+1;
		}

$k = $k+1;
$this->excel->getActiveSheet()->getStyle('A'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
$this->excel->getActiveSheet()->getStyle('B'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
$this->excel->getActiveSheet()->getStyle('C'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
$this->excel->getActiveSheet()->getStyle('D'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
$this->excel->getActiveSheet()->getStyle('E'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
$this->excel->getActiveSheet()->getStyle('F'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
$this->excel->getActiveSheet()->getStyle('G'.$k)->applyFromArray(
    			array('font' => array('bold' => true),
    					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);


			$this->excel->getActiveSheet()->SetCellValue('A'.$k, 'GSTR-1');
		   	$this->excel->getActiveSheet()->SetCellValue('B'.$k, 'HSN CODE');
	    	$this->excel->getActiveSheet()->SetCellValue('C'.$k, 'DESCRIPTION');
	    	$this->excel->getActiveSheet()->SetCellValue('D'.$k, 'UOM');
	    	$this->excel->getActiveSheet()->SetCellValue('E'.$k, 'TOTAL QUANTITY');
	    	$this->excel->getActiveSheet()->SetCellValue('F'.$k, 'TOTAL VALUE');
	    	$this->excel->getActiveSheet()->SetCellValue('G'.$k, 'TOTAL TAXABLE VALUE');

	   $k = $k+1; 	
foreach ($gstr1_report as  $key) {
	$this->excel->getActiveSheet()->getStyle('A'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('B'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('C'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('D'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('E'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('F'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('G'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);
	 $total_amount = $key['AMOUNT'] + $key['CENTRAL_TAX'] + $key['STATE_TAX'] + $key['INTEGRATED_TAX'];
	$this->excel->getActiveSheet()->SetCellValue('A'.$k,  $key['SALETYPE']);
		   	$this->excel->getActiveSheet()->SetCellValue('B'.$k, $key['HSNCODE']);
	    	$this->excel->getActiveSheet()->SetCellValue('C'.$k, $key['DESCRIPTION']);
	    	$this->excel->getActiveSheet()->SetCellValue('D'.$k, $key['UNIT']);
	    	$this->excel->getActiveSheet()->SetCellValue('E'.$k, $key['TOTALQUANTITY']);
	    	$this->excel->getActiveSheet()->SetCellValue('F'.$k, number_format($total_amount,2));
	    	$this->excel->getActiveSheet()->SetCellValue('G'.$k, number_format($key['AMOUNT'],2));
	    	$k = $k+1;

}
$this->excel->getActiveSheet()->getStyle('A'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('B'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('C'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    					
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('D'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    					
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('E'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    					
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('F'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    					
    			)
    	);

    	$this->excel->getActiveSheet()->getStyle('G'.$k)->applyFromArray(
    			array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
    					'borders' =>array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
    							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    					)
    					
    			)
    	);
    	$filename = "gstn_filling" . $date_of_report . "_" . strtotime(date('Y-m-d H:i:s')) . ".xlsx";
		ob_clean();
 		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename);
 		header('Cache-Control: max-age=0');
    	
    	//		PHPExcel_Calculation::getInstance()->clearCalculationCache();
    	//		PHPExcel_Calculation::getInstance()->setCalculationCacheEnabled(FALSE);
    	//		$objReader->load($filename);
    	//		$objWriter = new PHPExcel_Writer_Excel2007();
    	//		save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    	// 		if you want to save it as .XLSX Excel 2007 format
    	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    	// force user to download the Excel file without writing it to server's HD
    	// save to path
    	//$objWriter->save('tmp/'.$filename);
    	//$objWriter->save('php://output');
    	
    	//echo $filename;

    	$objWriter->save('php://output');
    	$excelOutput = ob_get_clean();
    	unset($spreadsheet);
    	$genfile = 'data/' . $filename;    	
    	
	}


	public function stock_journal_checklist() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$stock_journal = array();
		$stock_journal_details = array();
		$stock_journal_prefix = array();
		
		if($this->uri->segment(3)){
			$month = $this->data['month'] = $this->uri->segment(3);
			if($this->uri->segment(4)){
				$voucher_id = $this->uri->segment(4);
				$pre = $this->input->get('pre');
				$prefix = urldecode($pre);
				$query_stock_journal_pre = $this->db->query("EXEC [dbo].[usp_GetStockJournalCheckList] 	@Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH = '$month', @PREFIX  = '$prefix' , @VOUCHER_ID = '$voucher_id' ");	
	
				$stock_journal_prefix = $query_stock_journal_pre->result_array();
				$this->data['voucher_pre'] = $stock_journal_prefix[0]['PREFIX'].'00000'.$stock_journal_prefix[0]['VOUCHER_ID'];
				$this->data['bill_no'] = $stock_journal_prefix[0]['BILL_NO'];
				$this->data['date'] = date("d-m-Y",strtotime($stock_journal_prefix[0]['DATE']));
				$this->data['narration'] = $stock_journal_prefix[0]['NARRATION'];
				
			}
			else{
				$query_stock_journal_det = $this->db->query("EXEC [dbo].[usp_GetStockJournalCheckList] 	@Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
				$stock_journal_details = $query_stock_journal_det->result_array();

			}
			
		}else{
		$query_stock_journal = $this->db->query("EXEC [dbo].[usp_GetStockJournalCheckList] 	@Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
			$stock_journal = $query_stock_journal->result_array();
		}

		$this->data['stock_journal'] = $stock_journal;
		$this->data['stock_journal_details'] = $stock_journal_details;
		$this->data['stock_journal_prefix'] = $stock_journal_prefix;
		$this->load->view('stock_journal_checklist',$this->data);
	}


	public function journal_checklist() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$stock_journal = array();
		$stock_journal_details = array();
		$stock_journal_prefix = array();
		
		if($this->uri->segment(3)){
			$month = $this->data['month'] = $this->uri->segment(3);
			if($this->uri->segment(4)){
				$voucher_id = $this->uri->segment(4);
				$pre = $this->input->get('pre');
				$prefix = urldecode($pre);
				$query_stock_journal_pre = $this->db->query("EXEC [dbo].[usp_GetJournalCheckList] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH = '$month', @PREFIX  = '$prefix' , @VOUCHER_ID = '$voucher_id' ");	
	
				$stock_journal_prefix = $query_stock_journal_pre->result_array();
				$this->data['voucher_pre'] = $stock_journal_prefix[0]['PREFIX'].$stock_journal_prefix[0]['V_ID'];
				$this->data['bill_no'] = $stock_journal_prefix[0]['REFNUM'];
				$this->data['date'] = date("d-m-Y",strtotime($stock_journal_prefix[0]['DATE']));
				$this->data['narration'] = $stock_journal_prefix[0]['NARRATION'];
				
			}
			else{
				$query_stock_journal_det = $this->db->query("EXEC [dbo].[usp_GetJournalCheckList] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
				$stock_journal_details = $query_stock_journal_det->result_array();

			}
			
		}else{
		$query_stock_journal = $this->db->query("EXEC [dbo].[usp_GetJournalCheckList] @Company_Id = '$company_id', @FinancialYear_Id = '$finance_id', @MONTH = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");	
	
			$stock_journal = $query_stock_journal->result_array();
		}

		$this->data['stock_journal'] = $stock_journal;
		$this->data['stock_journal_details'] = $stock_journal_details;
		$this->data['stock_journal_prefix'] = $stock_journal_prefix;
		$this->load->view('journal_checklist',$this->data);
	}


}
