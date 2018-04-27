<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {	
	function __construct() {
        
        parent::__construct();
        date_default_timezone_set( 'Asia/Kolkata' );
        $this->company_id = $this->session->userdata('company_id');
        $this->financial_year_id = $this->session->userdata('financial_id');
        $this->user_name = $this->session->userdata('user_name');
        $this->load->model('admin_model');
        $this->db_name = $this->session->userdata('db_name');
        $this->connectapi->cons($this->db_name);
        if (!$this->session->userdata('is_logged_in'))
            redirect(site_url());
    }
	
	public function index() {
		$company_id = $this->company_id;
		$query_company_list = $this->db->query("EXEC [dbo].[usp_GetCompany] @ID = ".$company_id);
		$this->data['company_list'] = $query_company_list->result_array();		

		$financial_year_id = $this->financial_year_id;

		$query_pnl = $this->db->query("EXEC [dbo].[usp_getPandLGraph] @Company_Id =  '$company_id', @FinancialYear_Id = '$financial_year_id'");
		$this->data['profit_n_loss'] = $profit_n_loss = $query_pnl->result_array();

<<<<<<< HEAD
=======


>>>>>>> master
		$query_tax = $this->db->query("EXEC [dbo].[usp_getTaxGraph] @Company_Id =  '$company_id', @FinancialYear_Id = '$financial_year_id'");
		$this->data['tax'] = $tax = $query_tax->result_array();

		$query_sales = $this->db->query("EXEC [dbo].[usp_getSalesGraph] @Company_Id =  '$company_id', @FinancialYear_Id = '$financial_year_id'");
		$this->data['sales'] = $sales = $query_sales->result_array();
<<<<<<< HEAD

		$query_announce = $this->db->query("EXEC [dbo].[usp_GetAnnouncements] @COMPANY_ID = $company_id");
		$this->data['announcement'] = $query_announce->result_array();	
=======
>>>>>>> master
		
		$this->load->view('home',$this->data);
	}	

	
}
