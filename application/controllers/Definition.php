<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Definition extends CI_Controller {	

	function __construct() {
        
        parent::__construct();
        
        $this->company_id = $this->session->userdata('company_id');
       $this->finance_id = $this->session->userdata('financial_id');
        $this->user_name = $this->session->userdata('user_name');
        $this->db_name = $this->session->userdata('db_name');
        $this->connectapi->cons($this->db_name);        
        $this->load->model('admin_model');
         if (!$this->session->userdata('is_logged_in'))
            redirect(site_url());
    }

	public function index() {

		$this->load->view('add_accounts');
	}
	

	public function verify() {

		$this->load->view('verify');
	}

	public function add_accounts($type=NULL) {

		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();
		$this->data['country'] = $this->admin_model->get_country(0);
		$this->data['page'] = $type;
		$query = $this->db->query("EXEC [dbo].[usp_GetCompanyGroup] @Company_ID = ".$this->company_id);
		$this->data['account_group'] = $query->result_array();

		$this->load->view('add_accounts',$this->data);
	}

	public function create_accounts() {
		
		$financial_id = $this->input->post('cmb_financial_year');
		$account_name = strtoupper($this->input->post('txt_account_name'));
		$account_name = str_replace("'","''",$account_name);
		$account_type = $this->input->post('rtn_credit');
		$group_id = $this->input->post('cmb_group');
		$tin_no = $this->input->post('txt_tin_num');
		$opening_balance = $this->input->post('txt_opening_bal');
		$reverse_charge = $this->input->post('cmb_reverse_charge');
		$billwise = $this->input->post('cmb_billwise');
		$bill_of_sales = 0;
		$company_id = $this->company_id;
		$user_name = $this->user_name;		
		/*$address1 = $this->input->post('txt_address1');
		$address1 = str_replace("'","''",$address1);
		$address2 = $this->input->post('txt_address2');
		$address2 = str_replace("'","''",$address2);
		$country_id = $this->input->post('cmb_country');
		$state_id = $this->input->post('cmb_state');
		$district_id = $this->input->post('cmb_district');
		$pincode = $this->input->post('txt_pin_code');
		$billing = 1; */
		$page = $this->input->post('txt_page');
		$result = $this->db->query("DECLARE	@return int EXEC @return = [dbo].[usp_InsAccount] @COMPANY_ID = '$company_id', @ACCOUNTDESC = '$account_name', @ACCOUNTTYPE = '$account_type', @GROUP_ID = '$group_id', @TINNO = '$tin_no', @OPBALANCE = '$opening_balance', @BILLOFSALE_ID = '$bill_of_sales', @CREATEDBY = 'total', @Isactive = '1', @UPDATEDBY  = '', @FINANCIALYEAR_ID = '$financial_id',@REVERSE_CHARGES_APPLICABLE = '$reverse_charge', @MAINTAIN_BILLWISE_DETAILS = '$billwise' SELECT @return as ID");
		if($result){
			$account = $result->result_array();
			$account_id = $account[0]['ID'];
			/*$this->db->query("EXEC [dbo].[usp_InsPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ADDRESS1 = '$address1', @ADDRESS2 = '$address2', @PINCODE = '$pincode', @DISTRICT_ID = '$district_id', @STATE_ID = '$state_id', @COUNTRY_ID = '$country_id', @ISBILLING = $billing, @CREATEDBY = '$user_name', @MODIFIEDBY = '$user_name'");*/
			
			if($page == 'sales'){
			redirect(site_url() . '/entry/sales_voucher/');
			}else{
			redirect(site_url() . '/definition/manage_account/');	
			}
		}
	}

	public function manage_account() {
		$company_id = $this->company_id;	
		$query_company_list = $this->db->query("EXEC [dbo].[usp_GetAccount]	@COMPANY_ID = '$company_id', @ID = NULL");
		$this->data['account_list'] = $query_company_list->result_array();	
		$query = $this->db->query("EXEC [dbo].[usp_GetCompanyFinancialYear] @FINANCIALYEAR_ID = null, @COMPANY_ID = null");
		$finance = $query->result_array();
		foreach ($finance as $data) {
		    $financial_year[$data['FINANCIALYEAR_ID']] = $data['FINANCIALYEAR'];
		}
		$this->data['financial_year']  = $financial_year;

		$query = $this->db->query("EXEC [dbo].[usp_GetCompanyGroup] @Company_ID = ".$this->company_id);
		$account_group = $query->result_array();
		foreach ($account_group as $data) {
			$account_group_list[$data['groupid']] = $data['groupname'];
		}
		$this->data['account_group_list'] = $account_group_list;
			
		$this->load->view('manage_account',$this->data);
	}

	public function edit_accounts() {
		$company_id = $this->company_id;	
		$account_id = $this->uri->segment(3);

		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();

		$query = $this->db->query("EXEC [dbo].[usp_GetCompanyGroup] @Company_ID = ".$this->company_id);
		$this->data['account_group'] = $query->result_array();

		$query_company_list = $this->db->query("EXEC [dbo].[usp_GetAccount]	@COMPANY_ID = '$company_id', @ID = '$account_id'");
		$this->data['account_list'] = $account_list =  $query_company_list->result_array();
		$group_id = $account_list[0]['GROUP_ID'];
		$query_group_st = $this->db->query("select groupid from [dbo].[Group_Company] where[groupname] in ('SALES','PURCHASE','EXPENDITURE INDIRECT','EXPENDITURE DIRECT','DIRECT INCOME','INDIRECT INCOME','INCOME DIRECT','INCOME INDIRECT') and groupid = '$group_id'");
		 $groups_status = $query_group_st->result_array();
		 if(empty($groups_status)){
             $groups_hide = 'false';
         }else{
         	 $groups_hide = 'true';
         }
		 $this->data['groups_hide'] = $groups_hide;
		 $query_group_reg = $this->db->query("select groupid from [dbo].[Group_Company] where[groupname] in ('SUNDRY DEBTORS','SUNDRY CREDITORS') and groupid = '$group_id'");
		 $groups_reg = $query_group_reg->result_array();
		 if(empty($groups_reg)){
             $reg_hide = 'true';
         }else{
         	 $reg_hide = 'false';
         }
		 $this->data['reg_hide'] = $reg_hide;
	/*	$query_party = $this->db->query("select * from PartyAddress where ACCOUNT_ID = '$account_id' and ISBILLING = '1'");
		$this->data['party_address'] = $party_address = $query_party->result_array();
		

		$this->data['country'] = $this->admin_model->get_country(0);
		if(!empty($party_address)){
			$country = $party_address[0]['COUNTRY_ID'];		
			$this->data['state'] = $this->admin_model->get_state($country);
			$state = $party_address[0]['STATE_ID'];		
			$this->data['distict']= $this->admin_model->get_district($state);
			$this->data['country_id']  = $party_address[0]['COUNTRY_ID'];
			$this->data['state_id']  = $party_address[0]['STATE_ID'];	
			$this->data['district_id']  = $party_address[0]['DISTRICT_ID'];
			$this->data['pincode'] =  $party_address[0]['PINCODE'];	
			$this->data['address1']  = $party_address[0]['ADDRESS1'];	
			$this->data['address2'] =  $party_address[0]['ADDRESS2'];	
	}else{
		$this->data['country_id']  = '';
		$this->data['state']  = '';
		$this->data['state_id']  = '';
		$this->data['district_id']  = '';
		$this->data['distict'] =  '';
		$this->data['pincode'] =  '';
		$this->data['address1']  = '';
		$this->data['address2'] =  '';
	}
	*/	

		$this->load->view('edit_accounts',$this->data);
	}

	public function update_accounts() {
		$financial_id = $this->input->post('cmb_financial_year');
		$account_name = strtoupper($this->input->post('txt_account_name'));
		$account_name = str_replace("'","''",$account_name);
		$account_type = $this->input->post('rtn_credit');
		$group_id = $this->input->post('cmb_group');
		$tin_no = $this->input->post('txt_tin_num');
		$opening_balance = $this->input->post('txt_opening_bal');
		$bill_of_sales = 0;
		$account_id = $this->input->post('account_id');
		$company_id = $this->input->post('company_id');
		$user_name = $this->user_name;
		$machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];
		$reverse_charge = $this->input->post('cmb_reverse_charge');
		$billwise = $this->input->post('cmb_billwise');

	/*	$address1 = $this->input->post('txt_address1');
		$address1 = str_replace("'","''",$address1);
		$address2 = $this->input->post('txt_address2');
		$address2 = str_replace("'","''",$address2);
		$country_id = $this->input->post('cmb_country');
		$state_id = $this->input->post('cmb_state');
		$district_id = $this->input->post('cmb_district');
		$pincode = $this->input->post('txt_pin_code');
		$billing = 1;*/

		/*$query_address = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ISBILLING = 1");
		$address = $query_address->result_array();*/

		$result = $this->db->query("EXEC [dbo].[usp_UpdAccount] @Account_ID = '$account_id',  @COMPANY_ID = '$company_id', @ACCOUNTDESC = '$account_name', @ACCOUNTTYPE = '$account_type', @GROUP_ID = '$group_id', @TINNO = '$tin_no', @OPBALANCE = '$opening_balance', @BILLOFSALE_ID = '$bill_of_sales', @UPDATEDBY = '$user_name', @Isactive = '1', @FINANCIALYEAR_ID = '$financial_id', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address',@REVERSE_CHARGES_APPLICABLE = '$reverse_charge', @MAINTAIN_BILLWISE_DETAILS = '$billwise' ");
		if($result){
			/*if(!empty($address)){
				$party_id = $address[0]['PartyAddress_ID'];
				
				$result = $this->db->query("EXEC[dbo].[usp_UpdPartyAddress]	@ID = '$party_id' , @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ADDRESS1 = '$address1', @ADDRESS2 = '$address2', @PINCODE = '$pincode', @DISTRICT_ID = '$district_id', @STATE_ID = '$state_id', @COUNTRY_ID = '$country_id', @ISBILLING = $billing, @MODIFIEDBY = '$user_name', @ISACTIVE= '1' ");

			}else{
				$this->db->query("EXEC [dbo].[usp_InsPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ADDRESS1 = '$address1', @ADDRESS2 = '$address2', @PINCODE = '$pincode', @DISTRICT_ID = '$district_id', @STATE_ID = '$state_id', @COUNTRY_ID = '$country_id', @ISBILLING = $billing, @CREATEDBY = '$user_name', @MODIFIEDBY = '$user_name'");	
			} */
			
			redirect(site_url() . '/definition/manage_account/');
		}

	}

	public function transfer_account() {
		
		$company_id = $this->company_id;		
		$query = $this->db->query("EXEC [dbo].[usp_GetCompanyGroup] @Company_ID = ".$this->company_id);
		$this->data['account_group'] = $query->result_array();

		$this->load->view('transfer_account',$this->data);
	}

	public function get_accountname(){
		$company_id = $this->company_id;	
		$group_id = $this->input->get('group');
		$query = $this->db->query("EXEC [dbo].[usp_GetGroupAccount]  @GROUP_ID = '$group_id', @Company_ID = '$company_id' ");
		$account_name = $query->result_array();
		echo json_encode($account_name, JSON_FORCE_OBJECT);

	}

	public function add_transfer_account(){
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;
		$user_name = $this->user_name;
		$from_account = $this->input->post('cmb_account_from');
		$to_account = $this->input->post('txt_opening_bal');
		$group_id = $this->input->post('cmb_account_to');

		$result = $this->db->query("EXEC [dbo].[usp_UpdTransferAccount]  @FromAccount_ID = '$from_account', @ToAccount_ID = '$to_account', @Group_ID = '$group_id', @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id',  @USERID = '$user_name'");
		 
		if($result){
			redirect(site_url() . '/definition/transfer_account/');
		}
		

	}

	public function delete_account(){

		$company_id = $this->company_id;		
        $finance_id = $this->finance_id;	
        $machine_name =  getenv('COMPUTERNAME');
		$ip_address =  $_SERVER['SERVER_ADDR'];		
		$user_name = $this->user_name;	
		$account_id = $this->uri->segment(3);

		$result = $this->db->query("EXEC [dbo].[usp_DelAccount] @COMPANY_ID = '$company_id', @FINANCIALYEAR_ID = '$finance_id', @ACCOUNT_ID = '$account_id', @MODIFIEDBY = '$user_name', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address'");
		echo $result;
		exit;
		if($result){
			redirect(site_url() . '/definition/manage_account/');
		}

	}

	public function create_accounts_pop() {
		
		$company_id = $this->company_id;
		$financial_id = $finance_id = $this->finance_id;
		$account_name = strtoupper($this->input->post('txt_account_name'));
		$account_name = str_replace("'","''",$account_name);
		$account_type = $this->input->post('rtn_credit');
		$type = $this->input->post('type');
		if($type == 'sales'){
			$group_name = 'SUNDRY DEBTORS';
		}else{
			$group_name = 'SUNDRY CREDITORS';	
		}

		$query = $this->db->query("select distinct groupID,groupname from Group_Company with (nolock) where Company_id = '$company_id' and groupname = '$group_name' order by groupname");
		$account_group = $query->result_array();


		$group_id = $account_group[0]['groupID'];
		$tin_no = $this->input->post('txt_tin_num');

		$opening_balance = $this->input->post('txt_opening_bal');
		$reverse_charge = $this->input->post('cmb_reverse_charge');
		$billwise = $this->input->post('cmb_billwise');
		$bill_of_sales = 0;
		$user_name = $this->user_name;		

		$result = $this->db->query("DECLARE	@return int EXEC @return = [dbo].[usp_InsAccount] @COMPANY_ID = '$company_id', @ACCOUNTDESC = '$account_name', @ACCOUNTTYPE = '$account_type', @GROUP_ID = '$group_id', @TINNO = '$tin_no', @OPBALANCE = '$opening_balance', @BILLOFSALE_ID = '$bill_of_sales', @CREATEDBY = 'total', @Isactive = '1', @UPDATEDBY  = '', @FINANCIALYEAR_ID = '$financial_id',@REVERSE_CHARGES_APPLICABLE = '$reverse_charge', @MAINTAIN_BILLWISE_DETAILS = '$billwise' SELECT @return as ID");
		if($result){
			$account = $result->result_array();
			$account_id = $account[0]['ID'];

			$query_acc = $this->db->query("Exec usp_GetAccount @COMPANY_ID ='$company_id', @FINANCIALYEAR_ID  = '$financial_id', @ID = '$account_id'");
		$account = $query_acc->result_array();
		echo json_encode($account, JSON_FORCE_OBJECT);
			
			
			
		}
	}
	
}
