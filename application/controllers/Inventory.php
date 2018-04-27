<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {	

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

		$this->load->view('add_item');
	}

	public function add_item($type=NULL) {

		$company_id = $this->company_id;
		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();
		$query = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, @COMPANY_ID = '$company_id'");
		$this->data['account_group'] = $query->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->data['page'] = $type;
		$this->load->view('add_item',$this->data);
	}
	public function manage_item() {
		$company_id = $this->company_id;
		$query_finance = $this->db->query("EXEC [dbo].[usp_GetCompanyFinancialYear] @FINANCIALYEAR_ID = null, @COMPANY_ID = '$company_id'");
		$finance = $query_finance->result_array();		
		
		foreach ($finance as $data) {
		    $financial_year[$data['FINANCIALYEAR_ID']] = $data['FINANCIALYEAR'];
		}
		$this->data['financial_year']  = $financial_year;

		$query = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = ".$this->company_id.", @ID = NULL, @FINANCIALYEAR_ID = NULL");
		$this->data['item_list'] = $query->result_array();

		$this->load->view('manage_item',$this->data);
	}

	public function create_item() {

		$item_name = $this->input->post('txt_item_name');
		$item_name = str_replace("'","''",$item_name);
		$item_code = $this->input->post('txt_item_code');
		$hsn_code = $this->input->post('txt_hsn_code');		
		$opening_stock = $this->input->post('txt_opening_stock');
		$unit_id = $this->input->post('cmb_unit');
		$financial_id = $this->input->post('cmb_financial');
		$opening_value = $this->input->post('txt_opening_value');
		$selling_rate = $this->input->post('txt_selling_rate');
		$group_id = $this->input->post('cmb_group');
		$page = $this->input->post('txt_page');
		$company_id = $this->company_id;
		$user_name = $this->user_name;

		
		$result = $this->db->query("EXEC [dbo].[usp_InsItem] @NAME = '$item_name', @ITEMCODE = '$item_code', @HSNCODE = '$hsn_code',@FINANCIALYEAR_ID = '$financial_id', @UNIT_ID = '$unit_id',  @COMPANY_ID = '$company_id',  @RATE = '', @SRATE = '$selling_rate', @GROUP_ID = '$group_id', @OPSTOCK = '$opening_stock', @OPENINGVALUE = '$opening_value', @ISACTIVE = '1', @CREATEDBY = '$user_name'");
		if($result){
			if($page == 'sales'){
			redirect(site_url() . '/entry/sales_voucher/');
			}else{
			redirect(site_url() . '/inventory/manage_item/');	
			}
			
		}
	}

	public function item_name() {

		$item_name = $this->input->get('item_name');
		$company_id = $this->company_id;
        $finance_id = $this->finance_id;
		$query_user = $this->db->query("select * from dbo.[Item] where NAME = '". $item_name."' AND COMPANY_ID = '". $company_id."'");
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}

	public function item_name_update() {

		$item_name = strtoupper($this->input->get('item_name'));
		$item_id = $this->input->get('item_id');
		$company_id = $this->company_id;
        $finance_id = $this->finance_id;
		$query_user = $this->db->query("select * from dbo.[Item] where NAME = '". $item_name."' AND ID != '$item_id'  AND COMPANY_ID = '". $company_id."' AND FINANCIALYEAR_ID = '". $finance_id."'");
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}
	

	public function edit_item(){
		$item_id = $this->uri->segment(3);
		$company_id = $this->company_id;
		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();
		$query = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, @COMPANY_ID = '$company_id'");
		$this->data['account_group'] = $query->result_array();
		$this->data['account_group'] = $query->result_array();
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$query = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = NULL, @ID = ".$item_id.", @FINANCIALYEAR_ID = NULL");
		$this->data['item_data'] = $query->result_array();
		$this->load->view('edit_item',$this->data);
	}

	public function update_item(){
		$item_id = $this->input->post('item_id');
		$item_name = strtoupper($this->input->post('txt_item_name'));
		$item_name = str_replace("'","''",$item_name);
		$item_code = $this->input->post('txt_item_code');
		$hsn_code = $this->input->post('txt_hsn_code');		
		$opening_stock = $this->input->post('txt_opening_stock');
		$unit_id = $this->input->post('cmb_unit');
		$financial_id = $this->input->post('cmb_financial');
		$opening_value = $this->input->post('txt_opening_value');
		$selling_rate = $this->input->post('txt_selling_rate');
		$group_id = $this->input->post('cmb_group');
		$company_id = $this->company_id;
		$user_name = $this->user_name;
		$result = $this->db->query("EXEC [dbo].[usp_updItem] @ID = '$item_id', @NAME = '$item_name', @ITEMCODE = '$item_code', @HSNCODE = '$hsn_code',@FINANCIALYEAR_ID = '$financial_id', @UNIT_ID = '$unit_id',  @COMPANY_ID = '$company_id', @SRATE = '$selling_rate', @GROUP_ID = '$group_id', @OPSTOCK = '$opening_stock', @OPENINGVALUE = '$opening_value', @ISACTIVE = '1',@STOCK = '$opening_stock'");
		if($result){
			redirect(site_url() . 'inventory/manage_item/');
		}
	}

	public function add_item_group() {
		$company_id = $this->company_id;
		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();
		$this->load->view('add_item_group',$this->data);

 	}


	public function create_item_group() {
			$group_name = $this->input->post('txt_item_name');
			$group_name = str_replace("'","''",$group_name);
			$tax_id = $this->input->post('cmb_tax');
			$company_id = $this->company_id;
			$result = $this->db->query("EXEC [dbo].[usp_InsGroupItem] @GroupName = '$group_name', @COMPANY_ID = '$company_id', @CompanyTax_ID = '$tax_id'");
			if($result){
			redirect(site_url() . 'inventory/manage_item_group/');
		}
	}

	public function edit_group_item() {
		$item_group_id = $this->uri->segment(3);
		$company_id = $this->company_id;
		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();
		$query_item = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = '".$item_group_id."', 	@COMPANY_ID = '$company_id'");
		$this->data['group_item'] = $query_item->result_array();
		$this->load->view('edit_group_item',$this->data);

 	}

 	public function update_item_group() {
			$group_name = $this->input->post('txt_item_name');
			$group_name = str_replace("'","''",$group_name);
			$tax_id = $this->input->post('cmb_tax');
			$company_id = $this->company_id;
			$id = $this->input->post('item_group_id');
			$result = $this->db->query("EXEC [dbo].[usp_UpdGroupItem] @ID = '$id', @GroupName = '$group_name', @COMPANY_ID = '$company_id', @CompanyTax_ID = '$tax_id'");
			if($result){
			redirect(site_url() . 'inventory/manage_item_group/');
		}
	}

	public function manage_item_group() {
		$company_id = $this->company_id;
		//$tax_name = 'array()';
		$query_item = $this->db->query("EXEC [dbo].[usp_getGroupItem] @ID = 0, 	@COMPANY_ID = '$company_id'");
		$this->data['group_item'] = $query_item->result_array();
		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$tax = $query_tax->result_array();
		foreach ($tax as $data) {
		    $tax_name[$data['ID']] = $data['TAXTYPE'];
		}
		$this->data['tax_name']  = $tax_name;
		
		$this->load->view('manage_item_group',$this->data);

	}

	public function item_group_name() {

		$item_group_name = $this->input->get('item_group_name');
		$query_user = $this->db->query("select * from dbo.[GroupItem] where GROUPNAME = '". $item_group_name."'");
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}


	public function item_group_name_update() {

		$item_name = $this->input->get('item_name');
		$item_id = $this->input->get('item_id');
		$query_user = $this->db->query("select * from dbo.[GroupItem] where GROUPNAME = '". $item_name."' AND ID != '$item_id'");
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}

	public function goods_recieved_note() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        if($this->uri->segment(3)){
			$po_id = $this->uri->segment(3);
			$this->data['crn'] = $this->input->get('crn');
			$this->data['crn_date'] = $this->input->get('crndt');
			$this->data['lry_no'] = $this->input->get('lrn');
			$this->data['gate_pass'] = $this->input->get('gtps');
			$this->data['account_id'] = $this->input->get('acc');

			$query_po_det = $this->db->query("EXEC [dbo].[usp_GetOrders] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @PREFIX = 'PO', @VOUCHER_ID = '$po_id', @ISACTIVE = 1, @ISSALESORDER = 0");
			 $purchase_order = $query_po_det->result_array();
			$this->data['account_id'] = $account_id = $purchase_order[0]['ACCOUNT_ID'];
			$this->data['purchase_id'] = $po_id;
			$this->data['date'] = date("m-d-Y",strtotime($purchase_order[0]['DATE']));	
		 
		$this->data['purchase_order_detail'] = $purchase_order;
		$query_po = $this->db->query("EXEC [dbo].[usp_GetGRNOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();
		
		}else{

			$this->data['purchase_order_detail'] =  array();
			$this->data['purchase_id'] = '';
			if($this->input->get()){
				$this->data['crn'] = $this->input->get('crn');
			$this->data['crn_date'] = $this->input->get('crndt');
			$this->data['lry_no'] = $this->input->get('lrn');
			$this->data['gate_pass'] = $this->input->get('gtps');
			$this->data['account_id'] = $account_id =  $this->input->get('acc');
				$query_po = $this->db->query("EXEC [dbo].[usp_GetGRNOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$this->data['purchase_order'] = $query_po->result_array();	
				$this->data['date'] = '';			

			}else{
				$this->data['purchase_order_detail'] =  array();
			$this->data['crn'] = '';
			$this->data['crn_date'] = '';
			$this->data['lry_no'] = '';
			$this->data['gate_pass'] = '';
			$this->data['account_id'] = '';
			$this->data['date'] = '';
			$this->data['purchase_order'] = '';
			}
			
		}
		
		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'GR', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();
		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'GR' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		
		
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->load->view('goods_recieve_note',$this->data);
	}

	public function edit_goods_recieve_note() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

        $grn = $_GET['grn_no'];
        $VOUCHER_grn_ID = $this->uri->segment(3);
        $grn_no = urldecode($grn);
			$query_grn = $this->db->query("EXEC	[dbo].[usp_GetGRN] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @GRNNO = '$grn_no', @PREFIX = 'GR', @VOUCHER_ID = '$VOUCHER_grn_ID', @ISACTIVE = 1");
			$this->data['grn_details'] = $grn_details = $query_grn->result_array();
		
			$this->data['grn_do_bill'] = $grn_details[0]['GRNNO'];
			$this->data['grn_date_time'] = date("d-m-Y H:i",strtotime($grn_details[0]['GRNDATETIME']));
			$this->data['lry_no'] = $grn_details[0]['LRNNO'];
			$this->data['gate_pass'] = $grn_details[0]['GAtEPASSNO'];
			$this->data['account_id'] = $account_id = $grn_details[0]['ACCOUNT_ID'];
			$this->data['po_date'] = date("d-m-Y",strtotime($grn_details[0]['PURCHASE_ORDER_DATE']));
			$this->data['purchase_id'] = $grn_details[0]['PURCHASE_ORDER_VOUCHER_ID'];
			$this->data['narration'] = $grn_details[0]['NARRATION'];
			$this->data['grn_voucherno'] = $grn_details[0]['VOUCHER_ID'];
			$this->data['grn_prefix'] = $grn_details[0]['PREFIX'];

		$query_po = $this->db->query("EXEC [dbo].[usp_GetGRNOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$this->data['orders'] = $query_po->result_array();

		$query_fin = $this->db->query("EXEC	[dbo].[usp_GetCompanyFinancialYear]	@COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['financial_year'] = $query_fin->result_array();
		
		$query = $this->db->query("EXEC [dbo].[usp_GetVoucherType]");
		$this->data['voucher_type'] = $query->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'GR', @ColumnType = 'Account'");
		$this->data['account'] = $query_acc->result_array();

		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id'");
		$this->data['item'] = $query_item->result_array();

		$query_tax = $this->db->query("EXEC [dbo].[usp_GetCompanyTax] @COMPANY_ID = '$company_id', @ISACTIVE=1");
		$this->data['tax'] = $query_tax->result_array();
		$query_sn = $this->db->query("Select prefix,SerialNo+1 as SN from SerialNo where Company_ID = '$company_id' and  VoucherType = 'GR' and Financialyear_id = '$finance_id'");
		$this->data['sn'] = $query_sn->result_array();
		
		
		$query_unit = $this->db->query("EXEC [dbo].[usp_GetUnit] @ID = 0");
		$this->data['unit'] = $query_unit->result_array();
		$this->load->view('edit_goods_recieve_note',$this->data);
	}

	public function add_goods_recieved_note() {

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        
        $crn_no = $this->input->post('txt_crn_no');
		$crn_date_time1 = $this->input->post('txt_crn_date_time');
		$sn = $this->input->post('txt_serial_no');
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$crn_date_time =  date("m-d-Y H:i:s",strtotime($crn_date_time1));
		$date1 = $this->input->post('txt_date');
		$po_date =  date("m-d-Y",strtotime($date1));
		$lrn_no = $this->input->post('txt_lrn_no');
		if($lrn_no == ''){
			$lrn_no = NULL;
		}
		$txt_gate_pass_no = $this->input->post('txt_gate_pass_no');
		if($txt_gate_pass_no == ''){
			$txt_gate_pass_no = NULL;
		}
		$account_id = $this->input->post('cmb_account_group');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_po_number');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		if($narration == ''){
			$narration = NULL;
		}
		
		$user_name = $this->user_name;

		
		$final_total= $this->input->post('txt_final_total');

		$item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;
		

 	$insert = 'insert into [dbo].[GRN] ([COMPANY_ID],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[GRNNO],[GRNDATETIME],[LRNNO],[GAtEPASSNO],[ACCOUNT_ID],[PURCHASE_ORDER_VOUCHER_ID],[PURCHASE_ORDER_DATE],[ITEM_ID],[UNIT_ID],[QTY],[RATE],[AMOUNT],[NARRATION],[CREATEDBY],[CREATEDON],[purchase_order_prefix]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $rate = $rates[$i];
		 $unit_id = $unit[$i];		 
		 $totals = $total[$i];

		 $insert .= " ( $company_id, $Financialyear_id,''$voucher_type'',@voucherno, ''$crn_no'' , ''$crn_date_time'', ''$lrn_no'', ''$txt_gate_pass_no'', $account_id, $cmb_so_number,  ''$po_date'', $items,  $unit_id, $qtys, $rate, $totals, ''$narration'', ''$user_name'', getdate(),''PO''), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_InsGRN] @GRNtran = '$insert', @COMPANY_ID  = '$company_id', @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = $account_id, @PURCHASE_ORDER_ID = $cmb_so_number, @PURCHASE_ORDER_DATE = '$po_date',  @USER = '$user_name', @PREFIX = '$voucher_type',@VOUCHERTYPE = '$voucher_type' ");

	if($result){
			redirect(site_url() . '/inventory/manage_goods_recieved_note/?msg=1');
		}
	}

	public function update_goods_recieved_note() {

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        
        $crn_no = $this->input->post('txt_crn_no');
		$crn_date_time1 = $this->input->post('txt_crn_date_time');
		$sn = $this->input->post('vouceher_no');
		$voucher_type = $this->input->post('cmb_voucher_type');
		$voucher_type = trim($voucher_type);
		$crn_date_time =  date("m-d-Y H:i:s",strtotime($crn_date_time1));
		$date1 = $this->input->post('txt_date');
		$po_date =  date("m-d-Y",strtotime($date1));
		$lrn_no = $this->input->post('txt_lrn_no');
		if($lrn_no == ''){
			$lrn_no = NULL;
		}
		$txt_gate_pass_no = $this->input->post('txt_gate_pass_no');
		if($txt_gate_pass_no == ''){
			$txt_gate_pass_no = NULL;
		}
		$account_id = $this->input->post('cmb_account_group');
		$Financialyear_id = $this->input->post('cmb_finance_year');
		$cmb_so_number = $this->input->post('cmb_po_number');
		if($cmb_so_number == ''){
			$cmb_so_number = 0;
		}
		$narration = $this->input->post('txt_narration');
		$narration = str_replace("'","''",$narration);
		if($narration == ''){
			$narration = NULL;
		}
		
		$user_name = $this->user_name;

		$po_prefix = 'PO';
		$final_total= $this->input->post('txt_final_total');

		$item = $_POST['cmb_item_name'];
		$unit = $_POST['cmb_unit']; 		
		$rates = $_POST['txt_unit_price'];
		$qty = $_POST['txt_qty'];
		$total = $_POST['txt_total'];
		$k = count($item) - 1;
		

 	$insert = 'insert into [dbo].[GRN] ([COMPANY_ID],[FINANCIALYEAR_ID],[PREFIX],[VOUCHER_ID],[GRNNO],[GRNDATETIME],[LRNNO],[GAtEPASSNO],[ACCOUNT_ID],[PURCHASE_ORDER_VOUCHER_ID],[PURCHASE_ORDER_DATE],[ITEM_ID],[UNIT_ID],[QTY],[RATE],[AMOUNT],[NARRATION],[CREATEDBY],[CREATEDON],[purchase_order_prefix]) VALUES ';

		for ($i=0; $i <= $k ; $i++) { 
		 $items = $item[$i];
		 $qtys = $qty[$i];
		 $rate = $rates[$i];
		 $unit_id = $unit[$i];		 
		 $totals = $total[$i];

		 $insert .= " ( $company_id, $Financialyear_id,''$voucher_type'',@voucherno, ''$crn_no'' , ''$crn_date_time'', ''$lrn_no'', ''$txt_gate_pass_no'', $account_id, $cmb_so_number,  ''$po_date'', $items,  $unit_id, $qtys, $rate, $totals, ''$narration'', ''$user_name'', getdate(),''PO''), ";
		}

		$insert = rtrim($insert,", ");
		$insert = $insert."; ";		

		$result = $this->db->query("EXEC [dbo].[usp_UpdGRN] @GRNtran = '$insert', @COMPANY_ID  = '$company_id', @FINANCIALYEAR_ID = '$Financialyear_id', @ACCOUNT_ID = $account_id, @PURCHASE_ORDER_ID = $cmb_so_number, @PURCHASE_ORDER_DATE = '$po_date',  @USER = '$user_name', @PREFIX = '$voucher_type',@VOUCHERTYPE = '$voucher_type',@PURCHASE_ORDER_VOUCHER_ID = '$cmb_so_number', @PURCHASE_ORDER_PREFIX = '$po_prefix', @VOUCHER_ID = $sn ");

	if($result){
			redirect(site_url() . '/inventory/manage_goods_recieved_note/?msg=1');
		}
	}

	public function manage_goods_recieved_note(){
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;

		$query_po = $this->db->query("SELECT GRNNO,SUM(AMOUNT) as Total,ACCOUNT_ID,GRNDATETIME,VOUCHER_ID from GRN WHERE  COMPANY_ID = '$company_id' AND FINANCIALYEAR_ID = '$finance_id' and ISACTIVE=1  group by GRNNO,ACCOUNT_ID,GRNDATETIME,VOUCHER_ID");
		$this->data['purchase_order'] = $query_po->result_array();

		$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id'");
		$accounts = $query_acc->result_array();
		foreach ($accounts as $key) {
			$account_list[$key['ID']] = $key['ACCOUNTDESC'];
		}

		$this->data['account_list'] = $account_list;

		$this->load->view('manage_goods_recieved',$this->data);	

	}

	public function delete_grn(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$pre = $this->input->get('grn_no');
		$grn_no = urldecode($pre);
		$VOUCHER_ID = $this->uri->segment(3);

		$query_grn = $this->db->query("EXEC	[dbo].[usp_GetGRN] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @GRNNO = '$grn_no', @PREFIX = 'GR', @VOUCHER_ID = '$VOUCHER_ID', @ISACTIVE = 1");
			$this->data['grn_details'] = $grn_details = $query_grn->result_array();
		
			
			$account_id = $grn_details[0]['ACCOUNT_ID'];
			$po_date = $grn_details[0]['PURCHASE_ORDER_DATE'];
			$purchase_voucher_id = $grn_details[0]['PURCHASE_ORDER_VOUCHER_ID'];
			$purchase_id = $grn_details[0]['PURCHASE_ORDER_VOUCHER_ID'];
			$purchase_voucher_prefix = $grn_details[0]['purchase_order_prefix'];
			$grn_voucherno = $grn_details[0]['VOUCHER_ID'];
			$grn_prefix = $grn_details[0]['PREFIX'];
	
	
		$result = $this->db->query("EXEC [dbo].[usp_DelGRN] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ACCOUNT_ID =  $account_id, @PURCHASE_ORDER_ID = $purchase_id, @PURCHASE_ORDER_DATE = '$po_date', @USER = '$user_name', @PREFIX = '$grn_prefix', @VOUCHER_ID = $grn_voucherno, @PURCHASE_ORDER_VOUCHER_ID = $purchase_voucher_id, @PURCHASE_ORDER_PREFIX = '$purchase_voucher_prefix', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/inventory/manage_goods_recieved_note/');
		}

	}

	public function check_grn() {
		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $bill_no = $this->input->get('bill_no');
        $query_bill = $this->db->query("select * from dbo.[GRN] where GRNNO = '". $bill_no."' AND COMPANY_ID ='$company_id' AND FINANCIALYEAR_ID = '$finance_id' ");
		$billno = $query_bill->num_rows();
		if($billno != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}
	}

	public function get_sales_grn() {
	$company_id = $this->company_id;		
        $finance_id = $this->finance_id;
        $account_id = $this->input->get('account_id');	
	$query_po = $this->db->query("EXEC [dbo].[usp_GetGRNOrders] @COMPANY_ID = '$company_id',
		@FINANCIALYEAR_ID = '$finance_id', @ISSALESORDER = 0, @ACCOUNTID = ' $account_id'");
		$orders = $query_po->result_array();
		echo json_encode($orders, JSON_FORCE_OBJECT);
}

public function delete_item(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$item_id = $this->uri->segment(3);

		$result = $this->db->query("EXEC [dbo].[usp_DelItem] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ITEM_ID= '$item_id', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		if($result){
			redirect(site_url() . '/inventory/manage_item/');
		}

	}

	public function stock_ledger() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$this->data['ledger_report']  = array();
		$this->data['ledger_report_details']  = array();
		$this->data['ledger_report_prefix']  = array();
		$item_id = '';
		
		if($this->uri->segment(3)){
			$item_id = $this->uri->segment(3);
			if($this->uri->segment(4)){
				$month = $this->data['month'] =  $this->uri->segment(4);
				if(isset($_GET['pre'])){
					$pre = $this->input->get('pre');
					$prefix = urldecode($pre);
					$vouhcer_id = $this->input->get('voucher_id');
					$query_ledger_prefix = $this->db->query("EXEC usp_getStockLedger  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ITEM_ID = '$item_id', @MONTH  = '$month', @PREFIX  = '$prefix', @VOUCHER_ID = '$vouhcer_id'");
					
				$this->data['ledger_report_prefix']  = $ledger_report_prefix = $query_ledger_prefix->result_array();
				

				
				}else{
					$query_ledger_report = $this->db->query("EXEC usp_getStockLedger  @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ITEM_ID = '$item_id', @MONTH  = '$month', @PREFIX  = NULL, @VOUCHER_ID = NULL");
				$this->data['ledger_report_details']  = $query_ledger_report->result_array();
				}
				
			}else{
				$query_ledger_report = $this->db->query("EXEC usp_getStockLedger @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ITEM_ID = '$item_id', @MONTH  = NULL, @PREFIX  = NULL, @VOUCHER_ID = NULL");
				$this->data['ledger_report']  = $query_ledger_report->result_array();
			}
			

		}
		$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id', @FinancialYear_Id ='$finance_id'");
		$this->data['item'] = $query_item->result_array();
		$this->data['item_id'] = $item_id;
		$this->load->view('stock_ledger',$this->data);	
	}


	public function create_item_pop() {

		$item_name = $this->input->post('txt_item_name');
		$item_name = str_replace("'","''",$item_name);
		$item_code = $this->input->post('txt_item_code');
		$hsn_code = $this->input->post('txt_hsn_code');		
		$opening_stock = $this->input->post('txt_opening_stock');
		$unit_id = $this->input->post('cmb_unit');
		$financial_id = $finance_id = $this->finance_id;
		$opening_value = $this->input->post('txt_opening_value');
		$selling_rate = $this->input->post('txt_selling_rate');
		$group_id = $this->input->post('cmb_group');
		$page = $this->input->post('txt_page');
		$company_id = $this->company_id;
		$user_name = $this->user_name;

		
		$result = $this->db->query("DECLARE	@return int EXEC @return = [dbo].[usp_InsItem] @NAME = '$item_name', @ITEMCODE = '$item_code', @HSNCODE = '$hsn_code',@FINANCIALYEAR_ID = '$financial_id', @UNIT_ID = '$unit_id',  @COMPANY_ID = '$company_id',  @RATE = '', @SRATE = '$selling_rate', @GROUP_ID = '$group_id', @OPSTOCK = '$opening_stock', @OPENINGVALUE = '$opening_value', @ISACTIVE = '1', @CREATEDBY = '$user_name'  SELECT @return as ID");
		if($result){			
			$item = $result->result_array();
			$item_id = $item[0]['ID'];

			$query_item = $this->db->query("EXEC [dbo].[usp_GetItem] @COMPANY_ID = '$company_id', @FinancialYear_Id ='$finance_id', @ID = $item_id ");
		$item = $query_item->result_array();
		echo json_encode($item, JSON_FORCE_OBJECT);
		}
	}

<<<<<<< HEAD
	public function inventory_report() {

		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_inventory_report = $this->db->query("EXEC dbo.usp_GetInverntoryReport @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id'");
		$this->data['inventory_report']  = $inventory_report =  $query_inventory_report->result_array();
		//print_r($inventory_report);
		$this->load->view('inventory_report',$this->data);
	}	

=======
>>>>>>> master
}
