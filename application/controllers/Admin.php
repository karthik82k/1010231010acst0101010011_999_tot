<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {	

	function __construct() {
        
        parent::__construct();
        date_default_timezone_set( 'Asia/Kolkata' );
        $this->load->library('email');        
        $this->db_name = $this->session->userdata('db_name');
        $this->connectapi->cons($this->db_name);
        $this->load->model('admin_model');
        if (!$this->session->userdata('is_logged_in'))
            redirect(site_url());
    }

	public function index() {

		$this->load->view('create_company');		
	}

	public function create_company() {	

		$this->data['country'] = $this->admin_model->get_country(0);
		
		$this->data['company_type'] = $this->admin_model->get_company_type(0);

		$this->data['sub_company_type'] = $this->admin_model->get_company_sub_type(0);
		
		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();
		
		$this->data['currency'] = $this->admin_model->get_currency();

		$this->load->view('create_company',$this->data);
	}

	public function company_registration() {

		//$config['upload_path'] = './assets/photo/';
       // $config['allowed_types'] = '*';
        //$config['max_size'] = '700';
        //$this->load->library('upload');
        //$this->upload->initialize($config);        
		$company_name = strtoupper($this->input->post('txt_company_name'));
		$company_name = str_replace("'","''",$company_name);
		$addres1 = $this->input->post('txt_address1');
		$addres1 = str_replace("'","''",$addres1);
		$address2 = $this->input->post('txt_address2');
		$address2 = str_replace("'","''",$address2);		
		$phone = $this->input->post('txt_phone');
		$mobile = $this->input->post('txt_mobile');
		$owner_name = $this->input->post('txt_name');
		$email_id = $this->input->post('txt_emailid');
		$country_id = $this->input->post('cmb_country');
		$city = $this->input->post('txt_city');
		$state_id = $this->input->post('cmb_state');
		$district_id = $this->input->post('cmb_district');
		$tin_no = strtoupper($this->input->post('txt_tin'));
		$pin_code = $this->input->post('txt_pin_code');
		$company_type = $this->input->post('cmb_comp_type');		
		$company_sub_type = $this->input->post('cmb_comp_sub_type');
		$rc = $this->input->post('txt_tax_regime');
		if($this->input->post('chk_gst') == 1){
			$gstin = 1;
		}else{
				$gstin = 0;
		}			
		$vat_code = strtoupper($this->input->post('txt_vat_code'));	
		$financial_year = $this->input->post('cmb_financial');
		$pan_no = strtoupper($this->input->post('txt_pan'));
		$account_type = $this->input->post('cmb_acc_type');
		$currency_type = $this->input->post('cmb_currency');
		$bank = $this->input->post('txt_bank');
		$branch = $this->input->post('txt_branch');
		$ifsc = $this->input->post('txt_ifsc');
		$account_no = $this->input->post('txt_account_no');
		$username = $this->input->post('txt_username');
		$jurisdiction = $this->input->post('txt_jurisdiction');
		$jurisdiction = str_replace("'","''",$jurisdiction);
		$tan = strtoupper($this->input->post('txt_tan'));
		$managed_by = $this->input->post('txt_managed_by');

		$result = $this->db->query("DECLARE	@return int EXEC @return = [dbo].[usp_InsCompany] @NAME = '$company_name', @ADDRESS1 = '$addres1', @ADDRESS2 = '$address2', @CITY = '$city', @STATE_ID = '$state_id', @PIN = '$pin_code', @DISTRICT_ID = '$district_id', @COUNTRY_ID = '$country_id', @RC = '$rc', @JURISDICTION = '$jurisdiction', @OWNER = '$owner_name', @OWNER_TELNO = '$phone', @OWNER_MOBILE = '$mobile', @EMAIL = '$email_id', @PANNO = '$pan_no', @TINNO = '$tin_no',	@VATCODE = '$vat_code', @FINANCIALYEAR_ID = '$financial_year', @BANK = '$bank', @BRANCH = '$branch', @IFSC = '$ifsc', @BANKACCNUM = '$account_no', @COMPANYTYPE_ID = '$company_type', @LICENSETYPE = '$account_type', @CURRENCYCODE = '$currency_type', @TAX_ID = '1', @USERID = NULL, @ISACTIVE = 1, @isGST = '$gstin', @COMPANYSUBTYPE_ID = '$company_sub_type', @TAN = '$tan', @MANAGEDBY = '$managed_by' SELECT @return as ID");
		
		if($result) {

			$company = $result->result_array();
			$company_id = $company[0]['ID'];

			$result_user = $this->db->query("DECLARE	@return int EXEC  @return = [dbo].[usp_InsUser] @COMPANY_ID = '$company_id', @ROLE_ID  = '7', @FIRSTNAME = '$owner_name', @MIDDLENAME = NULL, @LASTNAME = NULL, @MOBILENO = '$mobile', @EMAIL = '$email_id', @USERNAME = '$username', @DISPLAYNAME = '$owner_name'  SELECT @return as ID");


			$user = $result_user->result_array();
			$user_id_ins = $user[0]['ID'];

			/*if (!empty($_FILES['logo_upload']['name'])) {//only if something exits to upload
            if ($this->upload->do_upload('logo_upload')) {//only if something is uploaded
                $img_data = $this->upload->data();
                 if ($img_data['is_image']) {//only if the uploaded file is image
                    rename($img_data['full_path'], $img_data['file_path'] .'logo_'.$company_id. $img_data['file_ext']);
                    $img_data['file_name'] = 'logo_'.$company_id. $img_data['file_ext'];
                    //	$this->load->library('image_lib');
                    unset($config);
                    $file_name = $img_data['file_name'];
               }
            }
        }else{
        	$file_name = NULL;
        } */
        $file_name = NULL;
			$this->db->query("Update Company set USERID = '$user_id_ins', Logo= '".$file_name."' where ID = '$company_id'");

			$result_member =  $this->db->query("EXEC [usp_InsMembership] @COMPANY_ID ='$company_id', @USER_ID = '$user_id_ins', @PASSWORD = 'total@123' ");

			//$result_finance =  $this->db->query("EXEC [dbo].[usp_InsCOMPANYFINANCIALYEAR] @FINANCIALYEAR_ID = '$financial_year', @COmpany_ID = '$company_id' ");

		}
		if($result_member) {
		$from_email = "admin@totalaccounting.in";
        $to_email = $email_id;
       
        $this->email->from($from_email, 'System generation mail');
        $this->email->to($to_email);
        $this->email->subject('username and password');
        $message = 'Hi '.$owner_name.',<br />' ;
        $message .= 'Username :- '.$username.' <br />' ;
        $message .= 'Password :- total@123 <br />' ;
        $message .= 'Thanks & Regards,<br />Admin' ;
        $this->email->message($message);
        $this->email->send();

		}
		
        //Send mail
		redirect(site_url() . '/admin/manage_company/');

	}

	public function manage_company() {	
		$query_company_list = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = 0");
		$this->data['company_list'] = $query_company_list->result_array();		
		$this->load->view('manage_company',$this->data);
	}	

	public function edit_company() {
	 	$company_id = $this->uri->segment(3);
	  	$query_cmp = $this->db->query("SELECT C.*,U.USERNAME FROM dbo.Company as C with (nolock) left outer join [User] U  with (nolock) on C.USERID=U.ID where C.ID =  $company_id ");
		$this->data['company_data_list'] = $company_list = $query_cmp->result('array');
		$this->data['country'] = $this->admin_model->get_country(0);
		
		$this->data['company_type'] = $this->admin_model->get_company_type(0);
		$this->data['sub_company_type'] = $this->admin_model->get_company_sub_type(0);
		
		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();
		
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
	public function company_name() {

		$txt_company_name = $this->input->get('company_name');
		$query_user = $this->db->query("select * from dbo.[Company] where NAME = '". $txt_company_name."'");
		$username = $query_user->num_rows();
		if($username != 0) {
			echo 'exists';
		} else {
			echo 'Not exists';	
		}

	}

	public function update_company() {
		//$config['upload_path'] = './assets/photo/';
        //$config['allowed_types'] = '*';
        //$config['max_size'] = '700';
        //$this->load->library('upload');
        //$this->upload->initialize($config); 
		$company_name = $this->input->post('txt_company_name');
		$company_name = str_replace("'","''",$company_name);
		$addres1 = $this->input->post('txt_address1');
		$addres1 = str_replace("'","''",$addres1);
		$address2 = $this->input->post('txt_address2');
		$address2 = str_replace("'","''",$address2);		
		$phone = $this->input->post('txt_phone');
		$mobile = $this->input->post('txt_mobile');
		$owner_name = $this->input->post('txt_name');
		$email_id = $this->input->post('txt_emailid');
		$country_id = $this->input->post('cmb_country');
		$city = $this->input->post('txt_city');
		$state_id = $this->input->post('cmb_state');
		$district_id = $this->input->post('cmb_district');
		$tin_no = $this->input->post('txt_tin');
		$pin_code = $this->input->post('txt_pin_code');
		$company_type = $this->input->post('cmb_comp_type');		
		$company_sub_type = $this->input->post('cmb_comp_sub_type');
		$rc = $this->input->post('txt_tax_regime');
		if($this->input->post('chk_gst') == 1){
			$gstin = 1;
		}else{
				$gstin = 0;
		}

		if($this->input->post('chck_status') == 1){
			$status = 1;
		}else{
				$status = 0;
		}

		if($this->input->post('chck_lock') == 1){
			$lock = 1;
		}else{
				$lock = 0;
		}

		$vat_code = $this->input->post('txt_vat_code');	
		$financial_year = $this->input->post('cmb_financial');
		$pan_no = $this->input->post('txt_pan');
		$account_type = $this->input->post('cmb_acc_type');
		$currency_type = $this->input->post('cmb_currency');
		$bank = $this->input->post('txt_bank');
		$branch = $this->input->post('txt_branch');
		$ifsc = $this->input->post('txt_ifsc');
		$account_no = $this->input->post('txt_account_no');
		
		$jurisdiction = $this->input->post('txt_jurisdiction');
		$jurisdiction = str_replace("'","''",$jurisdiction);
		$id = $this->input->post('company_id');
		$lock_period = $this->input->post('txt_lock_period');
		$company_user_id =  $this->input->post('txt_company_user_id');
		$tan = $this->input->post('txt_tan');
		$managed_by = $this->input->post('txt_managed_by');

			/*if (!empty($_FILES['logo_upload']['name'])) {//only if something exits to upload
            if ($this->upload->do_upload('logo_upload')) {//only if something is uploaded
                $img_data = $this->upload->data();
                 if ($img_data['is_image']) {//only if the uploaded file is image
                    rename($img_data['full_path'], $img_data['file_path'] .'logo_'.$id. $img_data['file_ext']);
                    $img_data['file_name'] = 'logo_'.$id. $img_data['file_ext'];
                    //	$this->load->library('image_lib');
                    unset($config);
                    $file_name = $img_data['file_name'];
               }
            }
        }else{
        	$file_name = NULL;
        } */
 	$file_name = $this->input->post('logo_id');
		$result = $this->db->query("EXEC [dbo].[usp_updCompany] @ID = '$id', @NAME = '$company_name', @ADDRESS1 = '$addres1', @ADDRESS2 = '$address2', @CITY = '$city', @STATE_ID = '$state_id', @PIN = '$pin_code', @DISTRICT_ID = '$district_id', @COUNTRY_ID = '$country_id', @RC = '$rc', @JURISDICTION = '$jurisdiction', @OWNER = '$owner_name', @OWNER_TELNO = '$phone', @OWNER_MOBILE = '$mobile', @EMAIL = '$email_id', @PANNO = '$pan_no', @TINNO = '$tin_no',	@VATCODE = '$vat_code', @FINANCIALYEAR_ID = '$financial_year', @BANK = '$bank', @BRANCH = '$branch', @IFSC = '$ifsc', @BANKACCNUM = '$account_no', @COMPANYTYPE_ID = '$company_type', @LICENSETYPE = '$account_type', @CURRENCYCODE = '$currency_type', @TAX_ID = '1', @USERID = '$company_user_id', @ISACTIVE = '$status', @isGST = '$gstin', @COMPANYSUBTYPE_ID = '$company_sub_type', @Logo = '$file_name', @LockingPeriod = '$lock_period', @IsLocked = '$lock', @TAN = '$tan', @MANAGEDBY = '$managed_by'");

		if($result){
			redirect(site_url() . '/admin/manage_company/');
		}		

	}

	public function create_user() {
		$query_cmp = $this->db->query("SELECT * FROM dbo.Company");
		$this->data['company_data_list'] = $query_cmp->result('array');
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$this->data['role_data_list'] = $query_role->result('array');
		$this->load->view('create_user',$this->data);
	}

	public function add_user() {
		 $company_id = $this->input->post('cmb_company');
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

		$result_user = $this->db->query("DECLARE	@return int EXEC  @return = [dbo].[usp_InsUser] @COMPANY_ID = '$company_id', @ROLE_ID  = '$role_id', @FIRSTNAME = '$first_name', @MIDDLENAME = '$middle_name', @LASTNAME = '$last_name', @MOBILENO = '$moblie_no', @EMAIL = '$email_id', @USERNAME = '$username', @DISPLAYNAME = '$displayname'  SELECT @return as ID");


			$user = $result_user->result_array();
			$user_id_ins = $user[0]['ID'];
			  
			$result_member =  $this->db->query("EXEC [usp_InsMembership] @COMPANY_ID ='$company_id', @USER_ID = '$user_id_ins', @PASSWORD = '$password', @MOBILEPIN = '$mobile_pin', @EMAIL = '$email_id', @PASSWORDQUESTION = '$question', @PASSWORDANSWER = '$answer' ");

			redirect(site_url() . '/admin/create_user/');

	}

	public function update_user() {
		 $company_id = $this->input->post('cmb_company');
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

			redirect(site_url() . '/admin/create_user/');
		}	  

	}

	public function edit_user() {
		$user_id = $this->uri->segment(3);
		$query_user = $this->db->query("select u.ID as user_id ,m.ID as mem_id, m.COMPANY_ID, u.DISPLAYNAME,u.FIRSTNAME,u.LASTNAME,u.MIDDLENAME,u.ROLE_ID,m.PASSWORDQUESTION,m.PASSWORDANSWER,m.MOBILEPIN,u.USERNAME,u.EMAIL,u.MOBILENO from dbo.[User] as u, dbo.Membership as m where u.ID = m.USER_ID and u.ID = '". $user_id."'");
		$this->data['user_data'] = $query_user->result('array');
		$query_cmp = $this->db->query("SELECT * FROM dbo.Company");
		$this->data['company_data_list'] = $query_cmp->result('array');
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$this->data['role_data_list'] = $query_role->result('array');
		$this->load->view('edit_user',$this->data);
	}


	public function manage_user() {	
		$query_company_list = $this->db->query("select * from dbo.[User] ");
		$this->data['company_list'] = $query_company_list->result_array();		
		$this->load->view('manage_user',$this->data);
	}

	public function user_list() {

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
                    * FROM dbo.[User] 
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

       	$query_company = $this->db->query("SELECT * FROM dbo.Company");
       	$company = $query_company->result('array');
       	foreach ($company as $data_d) {
        	$company_data[$data_d['ID']] = $data_d['NAME'];
        }
        $this->data['company_data'] = $company_data;
		
		$query_role = $this->db->query("SELECT * FROM dbo.ROLE");
		$role = $query_role->result('array');
		foreach ($role as $data_r) {
        	$role_data[$data_r['ID']] = $data_r['NAME'];
        }
        $this->data['role_data'] = $role_data;

        $query = $this->db->query("SELECT COUNT(*) as cnt FROM dbo.[User] ");
        $this->data['company_data_cnt'] = $query->result('array');

        $this->data['page'] = $page;
        $this->data['total'] = $this->data['company_data_cnt'][0]['cnt'];
        

		$this->load->view('userGridList', $this->data);
	}


	public function manage_announcements() {	
		$query_announce_list = $this->db->query("EXEC [dbo].[usp_GetAnnouncements] @ID = NULL, @COMPANY_ID = 0 ");
		$this->data['announcement_list'] = $query_announce_list->result_array();		
		$this->load->view('manage_announcements',$this->data);
	}


	public function create_announcements() {
		$query_cmp = $this->db->query("SELECT * FROM dbo.Company");
		$this->data['company_data_list'] = $query_cmp->result('array');
		$this->load->view('create_announcement',$this->data);
	}

	public function add_announcement() {
		 $Company = $this->input->post('cmb_company');	
		 $start_date = $this->input->post('txt_date');	
		 $start_date =  date("m-d-Y",strtotime($start_date));
		 $end_date = $this->input->post('txt_end_date');
		 $end_date =  date("m-d-Y",strtotime($end_date));
		 $announcement = $this->input->post('txt_announcement');
		 $announcement = str_replace("'","''",$announcement);		

		$result = $this->db->query("EXEC dbo.[usp_InsAnnouncements]  @COMPANY_ID = $Company, @STARTDATE  = '$start_date', @ENDDATE  = '$end_date', @Announcement = '$announcement', @CREATEDBY = 'admin' ");
		if($result){			

			redirect(site_url() . '/admin/manage_announcements/');
		}	
	}

 public function edit_announcement() {
 		$id = $this->uri->segment(3);
 		$query_announce = $this->db->query("EXEC [dbo].[usp_GetAnnouncements] @ID = $id, @COMPANY_ID = 0");
		$this->data['announcement'] = $query_announce->result_array();	
		$query_cmp = $this->db->query("SELECT * FROM dbo.Company");
		$this->data['company_data_list'] = $query_cmp->result('array');
		$this->load->view('edit_announcement',$this->data);
	}

	public function update_announcement() {
 		$Company = $this->input->post('cmb_company');	
		 $start_date = $this->input->post('txt_date');	
		 $start_date =  date("m-d-Y",strtotime($start_date));
		 $end_date = $this->input->post('txt_end_date');
		 $end_date =  date("m-d-Y",strtotime($end_date));
		 $announcement = $this->input->post('txt_announcement');
		 $announcement = str_replace("'","''",$announcement);	
		 $announcement_id = $this->input->post('announcement_id');	
		 $machine_name =  getenv('COMPUTERNAME');
		 $ip_address =  $_SERVER['SERVER_ADDR'];		

		$result = $this->db->query("EXEC dbo.[usp_UpdAnnouncements] @ID = $announcement_id, @COMPANY_ID = $Company, @STARTDATE  = '$start_date', @ENDDATE  = '$end_date', @Announcement = '$announcement', @UPDATEDBY = 'admin', @MACHINENAME = '$machine_name', @IPADDRESS = '$ip_address' ");
		if($result){			

			redirect(site_url() . '/admin/manage_announcements/');
		}	
	}

	

	

}
