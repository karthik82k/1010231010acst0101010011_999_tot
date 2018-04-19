<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function get_country($country_id) {
		$query = $this->db->query('EXEC	[dbo].[usp_GetCountry]	@ID ='.$country_id);
		return $query->result_array();
	}

	public function get_company_type($id) {
		$query_company = $this->db->query('EXEC [dbo].[usp_GetCompanyType] @ID ='.$id);
		return $query_company->result_array();
	}
	
	public function get_company_sub_type($id) {
		$query_company_sub = $this->db->query('EXEC [dbo].[usp_GetCompanysubType] @ID ='.$id);
		return $query_company_sub->result_array();
	}

	public function get_financial_year() {
		$query_financial_year = $this->db->query("EXEC [dbo].[usp_GetFinancialYear] @FINANCIALYEAR =''");
		return $query_financial_year->result_array();
	}
	
	public function get_currency(){
		$query_currency = $this->db->query("EXEC [dbo].[usp_Getcurrency] @ID = 0");
		return $query_currency->result_array();
	}

	public function get_state($country_id){
		$query_state = $this->db->query("EXEC [dbo].[usp_GetState] @ID = null, @COUNTRY_ID = ". $country_id);
		return $query_state->result_array();
	}
	
	public function get_district($state_id){
		$query_distict = $this->db->query("EXEC [dbo].[usp_GetDistrict] @ID = 0, @STATE_ID = ". $state_id);
		return $query_distict->result_array();
	}

	public function check_username($username){

	}	

}