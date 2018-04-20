<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {	
	function __construct() {
        
        parent::__construct();
        date_default_timezone_set( 'Asia/Kolkata' );
      //  $this->company_id = $this->session->userdata('company_id');
        //$this->user_name = $this->session->userdata('user_name');
        //$this->load->model('admin_model');
        //if (!$this->session->userdata('is_logged_in'))
          //  redirect(site_url());

    }
	
	public function index() {
		//$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$query_finance = $this->db->query("select * from FinancialYear where ISACTIVE = 1 order by ID desc");
		$this->data['financial_year'] = $query_finance->result_array();
		$this->load->view('login',$this->data);
	}	

	public function forgot_password() {
		$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		$this->data['financial_year'] = $query_finance->result_array();
		$this->load->view('forgot_password',$this->data);
	}	

	public function user_credential() {
		
		$query_finance = $this->db->query("select * from FinancialYear where ISACTIVE = 1 order by ID desc");
		$this->data['financial_year'] = $query_finance->result_array();
		$financial_id = $this->input->post('cmb_finance_year');
		$query_finance_db = $this->db->query("select * from FinancialYear where ISACTIVE = 1 and ID = $financial_id");
		$finance_db = $query_finance_db->result_array();
		$db_name = $finance_db[0]['DBNAME'];
		$this->connectapi->cons($db_name);


	

		//$query_finance = $this->db->query("EXEC	[dbo].[usp_GetFinancialYear] @FINANCIALYEAR = NULL");
		//$this->data['financial_year'] = $query_finance->result_array();
			$user_name = $this->input->post('txt_username');
			$password = $this->input->post('txt_passwords');
			
			$query_user = $this->db->query("select * from dbo.[User] where USERNAME = '". $user_name."'");
			$username = $query_user->result_array();			
			if(!empty($username)){
			$user_id = $username[0]['ID'];
			$name = $username[0]['FIRSTNAME'];
			$company_id = $username[0]['COMPANY_ID'];
			$role_id = $username[0]['ROLE_ID'];
			}else{
				$user_id = '';
			}
			//echo "declare @return int EXEC @return=[dbo].[usp_VarifyPassword] @COMPANY_ID = ".$company_id.", @USER_ID = ".$user_id.", @PASSWORD = '$password' select @return as va";
			if($user_id != ""){
				$query_company = $this->db->query("declare @return int EXEC @return=[dbo].[usp_VarifyPassword] @COMPANY_ID = ".$company_id.", @USER_ID = ".$user_id.", @PASSWORD = '$password' select @return as val");
				$company = $query_company->result_array();
				 $return_val = $company[0]['val'];
				 if($return_val == 1) {	
				 	$query_company_detail = $this->db->query('EXEC [dbo].[usp_GetCompany] @ID ='.$company_id);
				 	$company_detail = $query_company_detail->result_array();
				 	$company_name = $company_detail[0]['NAME'];
				 	$address1 = $company_detail[0]['ADDRESS1'];
				 	$address2 = $company_detail[0]['ADDRESS2'];
				 	$city = $company_detail[0]['CITY'];
				 	$pin = $company_detail[0]['PIN'];
				 	$lock = $company_detail[0]['islocked'];
				 	$logo = $company_detail[0]['Logo']; 

				 	$query_fin = $this->db->query("EXEC [dbo].[usp_GetCompanyFinancialYear] @FINANCIALYEAR_ID = ".$financial_id.", @COMPANY_ID = ".$company_id);
				 	$financial_year = $query_fin->result_array();
				 	if(!empty($financial_year) || $role_id == 1){
				 		$financial_select_year = $financial_year[0]['FINANCIALYEAR'];
				 	$financial_from = date("m-d-Y",strtotime($financial_year[0]['STARTDATE']));
				 	$financial_to = date("m-d-Y",strtotime($financial_year[0]['ENDDATE']));
				 	$financial_id = $financial_year[0]['FINANCIALYEAR_ID'];
				 	$data =  array('user_id'  => $user_id, 'user_name'  => $user_name, 'company_id' => $company_id, 'company_name' => $company_name, 'address1' => $address2, 'address2' => $address2, 'user_role' => $role_id, 'City' => $city, 'pin' => $pin,'is_logged_in' => 'YES', 'name' => $name, 'financial_year' => $financial_select_year,'financial_id' => $financial_id, 'financial_from' => $financial_from,'financial_to' => $financial_to, 'islock' => $lock, 'logo' => $logo, 'db_name' => $db_name );
				 	$this->session->set_userdata($data);
				 	//$this->load->view('home');
				 	if($password == 'total@123'){
				 		redirect(site_url('home'));
				 	}else{
				 		redirect(site_url('home'));
				 	}
				 	
				 }else{
				 	$this->data['alert'] = "Please select correct financial year!!!";
					$this->load->view('login',$this->data);
				 }
				 	
				 }else{
				 	$this->data['alert'] = "Please check your Username and Password!!!";
					$this->load->view('login',$this->data);
				 }
			}else{
				$this->data['alert'] = "Username is not exist!!!";
				$this->load->view('login',$this->data);
			}
	}

	function logout() {
       
            $this->session->sess_destroy();
            redirect(site_url());
    }
}
