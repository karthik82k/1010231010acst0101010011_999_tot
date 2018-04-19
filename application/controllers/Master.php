<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {	

	function __construct() {
        
        parent::__construct();
         $this->user_name = $this->session->userdata('user_name');
         $this->company_id = $this->session->userdata('company_id');
         $this->user_id = $this->session->userdata('user_id');
         $this->finance_id = $this->session->userdata('financial_id');        
        $this->load->model('admin_model');
        $this->db_name = $this->session->userdata('db_name');
        $this->connectapi->cons($this->db_name);
        if (!$this->session->userdata('is_logged_in'))
            redirect(site_url());
    }

	public function index() {

		$this->load->view('create_tax');		
	}

	public function create_tax() {		

		$this->load->view('create_tax');
	}

	public function edit_tax() {	

		$tax_id = $this->uri->segment(3);
	  	$query_tax = $this->db->query("SELECT * FROM dbo.[COMPANYTAX] where ID =  $tax_id ");
		$this->data['tax_data_list'] = $query_tax->result('array');

		$this->load->view('edit_tax',$this->data);
	}

	public function manage_tax() {	
		$company_id = $this->company_id;
		$query_company_list = $this->db->query("select * from dbo.[COMPANYTAX] where COMPANY_ID = '$company_id'");
		$this->data['company_tax'] = $query_company_list->result_array();		
		$this->load->view('manage_tax',$this->data);
	}

	public function tax_list() {

		$page = $this->input->post('page');
        $rp = $this->input->post('rp');
        $sortname = $this->input->post('sortname');
        $sortorder = $this->input->post('sortorder');

        if (!$sortname)
            $sortname = 'name';
        if (!$sortorder)
            $sortorder = 'ASC';
		 $sort = "ORDER BY $sortname $sortorder";
        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 10;
        $start = (($page - 1) * $rp);

         $query_cmp = $this->db->query("SELECT * FROM (
             SELECT ROW_NUMBER() OVER(ORDER BY ID) AS NUMBER,
                    * FROM dbo.[COMPANYTAX]
               ) AS TBL
WHERE NUMBER BETWEEN 0 AND 10
ORDER BY ID");
$this->data['company_data_list'] =$query_cmp->result('array');
      //  SET @PageNumber = 2
//SET @RowspPage = 5
/*SELECT * FROM (
             SELECT ROW_NUMBER() OVER(ORDER BY ID) AS NUMBER,
                    ID, NAME, DT_CREATE FROM Company
               ) AS TBL
WHERE NUMBER BETWEEN (($page - 1) * $page + 1) AND ($page * $rp)
ORDER BY ID*/        



        $query = $this->db->query("SELECT COUNT(*) as cnt FROM dbo.[COMPANYTAX] ");
        $this->data['company_data_cnt'] = $query->result('array');

        $this->data['page'] = $page;
        $this->data['total'] = $this->data['company_data_cnt'][0]['cnt'];
        

		$this->load->view('taxGridList', $this->data);
	}

	public function add_tax() {
		$company_id = $this->company_id;
		$tax_type = $this->input->post('txt_tax_type');
		$cgst = $this->input->post('txt_cgst');
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$export = $this->input->post('txt_export');
		$others = $this->input->post('txt_others');
		$username = $this->user_name;

		$result_com = $this->db->query("EXEC [dbo].[usp_InsCompanyTax] @COMPANY_ID = '$company_id', @TAXTYPE = '$tax_type', @CGST = '$cgst', @SGST = '$sgst', @IGST = '$igst', @EXPORT = '$export', @OTHERS = '$others', @CREATEDBY = '$username', @UPDATEDBY = '$username' ");
		if($result_com) {
			redirect(site_url() . '/master/manage_tax/');
		}

	}

	public function edit_company() {
	 	$company_id = $this->uri->segment(3);
	  	$query_cmp = $this->db->query("SELECT * FROM dbo.Company where ID =  $company_id ");
		$this->data['company_data_list'] = $company_list = $query_cmp->result('array');
		$this->data['country'] = $this->admin_model->get_country(0);
		
		$this->data['company_type'] = $this->admin_model->get_company_type(0);
		
		$this->data['financial_year'] = $this->admin_model->get_financial_year();
		
		$this->data['currency'] = $this->admin_model->get_currency();

		$country = $company_list[0]['COUNTRY_ID'];		
		$this->data['state'] = $this->admin_model->get_state($country);

		$state = $company_list[0]['STATE_ID'];		
		$this->data['distict']= $this->admin_model->get_district($state);

		$this->load->view('edit_company',$this->data);

	}

	public function get_state() {

		$country = $this->input->get('country');		
		$state = $this->admin_model->get_state($country);
		echo json_encode($state, JSON_FORCE_OBJECT);

	}	 

	 public function get_district() {

		$state = $this->input->get('state');		
		$distict = $this->admin_model->get_district($state);
		echo json_encode($distict, JSON_FORCE_OBJECT);

	}

	public function check_username() {

		$username = $this->input->get('username');
		$query_user = $this->db->query("select * from dbo.[User] where username = '". $username."'");
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}

	public function update_tax() {
		
		$tax_type = $this->input->post('txt_tax_type');
		$cgst = $this->input->post('txt_cgst');
		$sgst = $this->input->post('txt_sgst');
		$igst = $this->input->post('txt_igst');
		$export = $this->input->post('txt_export');
		$others = $this->input->post('txt_others');
		$tax_id = $this->input->post('tax_id');
		$username = $this->user_name;

		$result_com = $this->db->query("Update dbo.[COMPANYTAX] set TAXTYPE = '$tax_type', CGST = '$cgst', SGST = '$sgst', IGST = '$igst', EXPORT = '$export', OTHERS = '$others', UPDATEDBY = '$username' where ID = '$tax_id' ");
		if($result_com) {
			redirect(site_url() . '/master/manage_tax/');
		}
		

	}

	public function manage_user() {	
		$company_id = $this->company_id;
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$role = $query_role->result('array');
		foreach ($role as $data_r) {
        	$role_data[$data_r['ID']] = $data_r['NAME'];
        }
        $this->data['role_data'] = $role_data;
		$query_company_list = $this->db->query("select * from dbo.[User] where COMPANY_ID = '$company_id' ");
		$this->data['user_list'] = $query_company_list->result_array();		
		$this->load->view('manage_user',$this->data);
	}

	public function create_user() {
		
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE where ID != 1");
		$this->data['role_data_list'] = $query_role->result('array');
		$this->load->view('create_user',$this->data);
	}

	public function add_user() {
		 $company_id = $this->company_id;
		 $role_id = $this->input->post('cmb_role');
		 $first_name = $this->input->post('txt_first_name');
		 $middle_name = $this->input->post('txt_middle_name');
		 $last_name = $this->input->post('txt_last_name');
		 $username = $this->input->post('txt_username');
		 $moblie_no = $this->input->post('txt_mobile');
		 $email_id = $this->input->post('txt_email');
		 $displayname = $this->input->post('txt_display_name');
		 $password = $this->input->post('txt_password');
		 $mobile_pin = $this->input->post('txt_mobile_pin');
		 $question = $this->input->post('txt_question');
		 $answer = $this->input->post('txt_answer');

		

		$result = $this->db->query("DECLARE	@return int EXEC  @return = [dbo].[usp_InsUser] @COMPANY_ID = '$company_id', @ROLE_ID  = '$role_id', @FIRSTNAME = '$first_name', @MIDDLENAME = '$middle_name', @LASTNAME = '$last_name', @MOBILENO = '$moblie_no', @EMAIL = '$email_id', @USERNAME = '$username', @DISPLAYNAME = '$displayname'  SELECT @return as ID");
			$company = $result->result_array();
			$user_id_ins = $company[0]['ID'];

			  
			$result_member =  $this->db->query("EXEC [usp_InsMembership] @COMPANY_ID ='$company_id', @USER_ID = '$user_id_ins', @PASSWORD = '$password', @MOBILEPIN = '$mobile_pin', @EMAIL = '$email_id', @PASSWORDQUESTION = '$question', @PASSWORDANSWER = '$answer' ");

			redirect(site_url() . '/master/manage_user/');

	}

	public function update_user() {
		 $company_id = $this->company_id;
		 $role_id = $this->input->post('cmb_role');
		 $first_name = $this->input->post('txt_first_name');
		 $middle_name = $this->input->post('txt_middle_name');
		 $last_name = $this->input->post('txt_last_name');
		 $username = $this->input->post('txt_username');
		 $moblie_no = $this->input->post('txt_mobile');
		 $email_id = $this->input->post('txt_email');
		 $displayname = $this->input->post('txt_display_name');
		 $mobile_pin = $this->input->post('txt_mobile_pin');
		 $question = $this->input->post('txt_question');
		 $answer = $this->input->post('txt_answer');
		 $user_id = $this->input->post('user_id');
		 $member_id = $this->input->post('member_id');		

		$result_user = $this->db->query("Update dbo.[User] set DISPLAYNAME = '$displayname', FIRSTNAME = '$first_name', LASTNAME = '$last_name', MIDDLENAME = '$middle_name', ROLE_ID = '$role_id',  COMPANY_ID = '$company_id', USERNAME = '$username', EMAIL = '$email_id', MOBILENO = '$moblie_no'  where ID = '$user_id' ");
		if($result_user){
			$result_member =  $this->db->query("Update dbo.[Membership] set COMPANY_ID ='$company_id', USER_ID = '$user_id',  MOBILEPIN = '$mobile_pin', EMAIL = '$email_id', PASSWORDQUESTION = '$question', PASSWORDANSWER = '$answer' where ID = '$member_id' ");

			redirect(site_url() . '/master/manage_user/');
		}	  

	}

public function update_account() {
		$config['upload_path'] = './assets/photo/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '700';
        $this->load->library('upload');
        $this->upload->initialize($config);
		 $company_id = $this->company_id;
		 $first_name = $this->input->post('txt_first_name');
		 $middle_name = $this->input->post('txt_middle_name');
		 $last_name = $this->input->post('txt_last_name');
		 $username = $this->input->post('txt_username');
		 $moblie_no = $this->input->post('txt_mobile');
		 $email_id = $this->input->post('txt_email');
		 $displayname = $this->input->post('txt_display_name');
		 $mobile_pin = $this->input->post('txt_mobile_pin');
		 $question = $this->input->post('txt_question');
		 $answer = $this->input->post('txt_answer');
		 $user_id = $this->input->post('user_id');
		 $member_id = $this->input->post('member_id');		

		$result_user = $this->db->query("Update dbo.[User] set DISPLAYNAME = '$displayname', FIRSTNAME = '$first_name', LASTNAME = '$last_name', MIDDLENAME = '$middle_name',  COMPANY_ID = '$company_id', USERNAME = '$username', EMAIL = '$email_id', MOBILENO = '$moblie_no'  where ID = '$user_id' ");
		if($result_user){
			$result_member =  $this->db->query("Update dbo.[Membership] set COMPANY_ID ='$company_id', USER_ID = '$user_id',  MOBILEPIN = '$mobile_pin', EMAIL = '$email_id', PASSWORDQUESTION = '$question', PASSWORDANSWER = '$answer' where ID = '$member_id' ");

			if (!empty($_FILES['logo_upload']['name'])) {//only if something exits to upload
            if ($this->upload->do_upload('logo_upload')) {//only if something is uploaded
                $img_data = $this->upload->data();
                 if ($img_data['is_image']) {//only if the uploaded file is image
                    rename($img_data['full_path'], $img_data['file_path'] .'logo_'.$company_id. $img_data['file_ext']);
                    $img_data['file_name'] = 'logo_'.$company_id. $img_data['file_ext'];
                    //	$this->load->library('image_lib');
                    unset($config);
                    $file_name = $img_data['file_name'];
               }
               $this->db->query("Update Company set Logo= '".$file_name."' where ID = '$company_id'");
            }
        }

			redirect(site_url() . '/master/my_account/'.$user_id);
		}	  

	}
	

	public function edit_user() {
		$user_id = $this->uri->segment(3);
		$query_user = $this->db->query("select u.ID as user_id ,m.ID as mem_id, m.COMPANY_ID, u.DISPLAYNAME,u.FIRSTNAME,u.LASTNAME,u.MIDDLENAME,u.ROLE_ID,m.PASSWORDQUESTION,m.PASSWORDANSWER,m.MOBILEPIN,u.USERNAME,u.EMAIL,u.MOBILENO from dbo.[User] as u, dbo.Membership as m where u.ID = m.USER_ID and u.ID = '". $user_id."'");
		$this->data['user_data'] = $query_user->result('array');
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$this->data['role_data_list'] = $query_role->result('array');
		$this->load->view('edit_user',$this->data);
	}

	public function my_account() {
		$user_id = $this->user_id;
		$query_user = $this->db->query("select u.ID as user_id ,m.ID as mem_id, m.COMPANY_ID, u.DISPLAYNAME,u.FIRSTNAME,u.LASTNAME,u.MIDDLENAME,u.ROLE_ID,m.PASSWORDQUESTION,m.PASSWORDANSWER,m.MOBILEPIN,u.USERNAME,u.EMAIL,u.MOBILENO from dbo.[User] as u, dbo.Membership as m where u.ID = m.USER_ID and u.ID = '". $user_id."'");
		$this->data['user_data'] = $query_user->result('array');
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$this->data['role_data_list'] = $query_role->result('array');
		$this->load->view('my_account',$this->data);
	}


	

	public function user_list() {
		$company_id = $this->company_id;
		$page = $this->input->post('page');
        $rp = $this->input->post('rp');
        $sortname = $this->input->post('sortname');
        $sortorder = $this->input->post('sortorder');

        if (!$sortname)
            $sortname = 'name';
        if (!$sortorder)
            $sortorder = 'ASC';
		 $sort = "ORDER BY $sortname $sortorder";
        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 10;
        $start = (($page - 1) * $rp);

         $query_cmp = $this->db->query("SELECT * FROM (
             SELECT ROW_NUMBER() OVER(ORDER BY ID) AS NUMBER,
                    * FROM dbo.[User] where COMPANY_ID = '$company_id'
               ) AS TBL
WHERE NUMBER BETWEEN 0 AND 10
ORDER BY ID");
$this->data['company_data_list'] =$query_cmp->result('array');
      //  SET @PageNumber = 2
//SET @RowspPage = 5
/*SELECT * FROM (
             SELECT ROW_NUMBER() OVER(ORDER BY ID) AS NUMBER,
                    ID, NAME, DT_CREATE FROM Company
               ) AS TBL
WHERE NUMBER BETWEEN (($page - 1) * $page + 1) AND ($page * $rp)
ORDER BY ID*/
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$role = $query_role->result('array');
		foreach ($role as $data_r) {
        	$role_data[$data_r['ID']] = $data_r['NAME'];
        }
        $this->data['role_data'] = $role_data;

        $query = $this->db->query("SELECT COUNT(*) as cnt FROM dbo.[User] where COMPANY_ID = '$company_id' ");
        $this->data['company_data_cnt'] = $query->result('array');

        $this->data['page'] = $page;
        $this->data['total'] = $this->data['company_data_cnt'][0]['cnt'];
        

		$this->load->view('userGridList_company', $this->data);
	}

	public function change_password() {

		$this->load->view('change_password');
	}
	
	public function update_password() {
		$company_id = $this->company_id;
		$user_id = $this->user_id;
		$password_old = $this->input->post('txt_password');
		$password_new = $this->input->post('txt_new_password');

		$result_member =  $this->db->query("EXEC [dbo].[usp_UpdPasword] @COMPANY_ID = '$company_id', @USER_ID = '$user_id', @NEW_PASSWORD = '$password_new', @OLD_PASSWORD  = '$password_old', @PASSWORD_QUESTION  = NULL, @PASSWORD_ANSWER = NULL");

		redirect(site_url() . '/master/change_password/');
	}

	public function add_serial() {
		$query_voucher = $this->db->query("select * from [dbo].[VoucherType]");
		$this->data['voucher'] = $query_voucher->result_array();
		$this->load->view('add_serial',$this->data);

 	}

 	public function create_serial() {
			$prefix = $this->input->post('txt_prefix');
			$type = $this->input->post('cmb_type');
			$finance_id = $this->finance_id;
			$company_id = $this->company_id;
			$user_name = $this->user_name;
			$result = $this->db->query("EXEC [dbo].[usp_InsSerialPrefix]  @company_id = '$company_id', @VoucherType = '$type', @prefix = '$prefix', @financialyear_id = '$finance_id', @createdby = '$user_name' "); 
			if($result){
			redirect(site_url() . 'master/manage_serial/');
		}
	}


	public function manage_serial() {
		$company_id = $this->company_id;
		$finance_id = $this->finance_id;

		$query_item = $this->db->query("select * from [dbo].[SerialNo] where COMPANY_ID = '$company_id' and FINANCIALYEAR_ID = '$finance_id'");
		$this->data['prefix'] = $query_item->result_array();
		$query_tax = $this->db->query("select * from [dbo].[VoucherType]");
		$tax = $query_tax->result_array();
		foreach ($tax as $data) {
			$v_type = trim($data['VoucherType']);
		    $tax_name[$v_type] = $data['Description'];
		}
		$this->data['voucher_type']  = $tax_name;
		$query_finance = $this->db->query("EXEC [dbo].[usp_GetCompanyFinancialYear] @FINANCIALYEAR_ID = null, @COMPANY_ID = null");
		$finance = $query_finance->result_array();		
		
		foreach ($finance as $data) {
		    $financial_year[$data['FINANCIALYEAR_ID']] = $data['FINANCIALYEAR'];
		}
		$this->data['financial_year']  = $financial_year;
		
		$this->load->view('manage_serial',$this->data);

	}

	public function prefix() {
		$company_id = $this->company_id;
		$prefix = $this->input->get('prefix');
		$query_user = $this->db->query("select * from dbo.[SerialNo] where PREFIX LIKE '%". $prefix."%' AND Company_ID = '$company_id' ");
		
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}
	
	public function create_party() {
		$company_id = $this->company_id;
		$query_account = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'ADDRESS', @ColumnType = 'Account'");
		$this->data['account'] = $query_account->result_array();
		$this->data['country'] = $this->admin_model->get_country(0);
		$this->load->view('add_party',$this->data);
	}

	public function add_party() {

		$company_id = $this->company_id;
		$user_name = $this->user_name;
		$account_id = $this->input->post('cmb_account');
		$address1 = $this->input->post('txt_address1');
		$address1 = str_replace("'","''",$address1);
		$address2 = $this->input->post('txt_address2');
		$address2 = str_replace("'","''",$address2);
		$country_id = $this->input->post('cmb_country');
		$state_id = $this->input->post('cmb_state');
		$district_id = $this->input->post('cmb_district');
		$pincode = $this->input->post('txt_pin_code');
		$billing = $this->input->post('chk_billing');
		$vendor_code = $this->input->post('txt_vender_code');
		$gst_no = $this->input->post('txt_gst_no');
		$result = $this->db->query("EXEC [dbo].[usp_InsPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ADDRESS1 = '$address1', @ADDRESS2 = '$address2', @PINCODE = '$pincode', @DISTRICT_ID = '$district_id', @STATE_ID = '$state_id', @COUNTRY_ID = '$country_id', @ISBILLING = $billing, @CREATEDBY = '$user_name', @MODIFIEDBY = '$user_name', @VenderCode = '$vendor_code', @GSTNO = '$gst_no' ");
		if($result){
			redirect(site_url() . 'master/manage_party/');
		}

	}

	public function edit_party() {
		$company_id = $this->company_id;
		$party_id = $this->uri->segment(3);
		$query_party = $this->db->query("select * from PartyAddress where ID = '$party_id'");
		$this->data['party_address'] = $party_address = $query_party->result_array();
		$query_account = $this->db->query("Exec usp_GetAccount @COMPANY_ID = '$company_id', @Vouchertype = 'ADDRESS', @ColumnType = 'Account'");
		$this->data['account'] = $query_account->result_array();

		$this->data['country'] = $this->admin_model->get_country(0);
		$country = $party_address[0]['COUNTRY_ID'];		
		$this->data['state'] = $this->admin_model->get_state($country);

		$state = $party_address[0]['STATE_ID'];		
		$this->data['distict']= $this->admin_model->get_district($state);
		$this->load->view('edit_party',$this->data);

	}

	public function update_party() {

		$company_id = $this->company_id;
		$user_name = $this->user_name;
		$account_id = $this->input->post('cmb_account');
		$address1 = $this->input->post('txt_address1');
		$address1 = str_replace("'","''",$address1);
		$address2 = $this->input->post('txt_address2');
		$address2 = str_replace("'","''",$address2);
		$country_id = $this->input->post('cmb_country');
		$state_id = $this->input->post('cmb_state');
		$district_id = $this->input->post('cmb_district');
		$pincode = $this->input->post('txt_pin_code');
		$billing = $this->input->post('chk_billing');
		$vendor_code = $this->input->post('txt_vender_code');
		
		$active = $this->input->post('chk_active');
		if($active == '') {
			$active = 0;
		}		

		$party_id = $this->input->post('party_id');
		$gst_no = $this->input->post('txt_gst_no');
		
		$result = $this->db->query("EXEC [dbo].[usp_UpdPartyAddress]	@ID = '$party_id' , @COMPANY_ID = '$company_id', @ACCOUNT_ID = '$account_id', @ADDRESS1 = '$address1', @ADDRESS2 = '$address2', @PINCODE = '$pincode', @DISTRICT_ID = '$district_id', @STATE_ID = '$state_id', @COUNTRY_ID = '$country_id', @ISBILLING = $billing, @MODIFIEDBY = '$user_name', @ISACTIVE= '$active', @VenderCode = '$vendor_code', @GSTNO = '$gst_no' ");

		if($result){
			redirect(site_url() . 'master/manage_party/');
		}
	}

	public function manage_party() {
		$company_id = $this->company_id;
		$query_party = $this->db->query("EXEC [dbo].[usp_GetPartyAddress] @COMPANY_ID = '$company_id', @ACCOUNT_ID = NULL, @DISTRICT_ID = NULL, @STATE_ID  = NULL, @COUNTRY_ID  = NULL, @ISBILLING  = NULL");
		$this->data['party_address'] = $query_party->result_array();

	$this->load->view('manage_party',$this->data);
	}

	public function add_terms_condition() {
		$company_id = $this->company_id;
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$this->data['termsnconditions'] = $terms[0]['TermsAndConditions'];
		}else{
			$this->data['termsnconditions'] = '';
		}
		$this->load->view('add_termsncondition',$this->data);

 	}

 	public function create_terms_condition() {
 		$termsncond = $this->input->post('txt_narration');
		$termsncond = str_replace("'","''",$termsncond);
		$company_id = $this->company_id;
		$user_name = $this->user_name;
		$query_terms = $this->db->query("EXEC [dbo].[usp_GetTermsAndConditions] @COMPANY_ID = '$company_id'");
		$terms = $query_terms->result_array();
		if(!empty($terms)){
			$result = $this->db->query("EXEC [dbo].[usp_updTermsAndConditions]  @COMPANY_ID = '$company_id', @Termsandconditions = '$termsncond',	@UPDATEDBY = '$user_name' ");
		}else{
			$result = $this->db->query("EXEC [dbo].[usp_InsTermsAndConditions]  @COMPANY_ID = '$company_id', @Termsandconditions = '$termsncond',	@CREATEDBY = '$user_name' ");	
		}
		if($result){
			redirect(site_url() . 'master/add_terms_condition/');
		}
 	}

}
