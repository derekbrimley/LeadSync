<?php
	
class Settings extends MY_Controller {
	
	function index()
	{
		
		date_default_timezone_set('America/Denver');
		$current_date = date("Y-m-d",strtotime('today'));
		
		$username = $this->session->userdata('username');
		
		$call_sql = "SELECT COUNT(id) AS call_count FROM `lead` WHERE application_datetime > '".$current_date."' AND user_submitted = '".$username."'";
		//echo $call_sql."<br>";
		$query = $this->db->query($call_sql);
		foreach($query->result() as $row)
		{
			$call_count = $row->call_count;
		}
		
		$transfer_sql = "SELECT COUNT(id) AS transfer_count FROM `lead` WHERE application_datetime > '".$current_date."' AND user_submitted = '".$username."' AND transferred_to IS NOT NULL";
		//echo $transfer_sql."<br>";
		$query = $this->db->query($transfer_sql);
		foreach($query->result() as $row)
		{
			$transfer_count = $row->transfer_count;
		}
		
		$total_call_sql = "SELECT COUNT(id) AS total_call_count FROM `lead` WHERE application_datetime > '".$current_date."'";
		//echo $total_call_sql."<br>";
		$query = $this->db->query($total_call_sql);
		foreach($query->result() as $row)
		{
			$total_call_count = $row->total_call_count;
		}
		
		$total_transfer_sql = "SELECT COUNT(id) AS total_transfer_count FROM `lead` WHERE application_datetime > '".$current_date."' AND transferred_to IS NOT NULL";
		//echo $total_transfer_sql."<br>";
		$query = $this->db->query($total_transfer_sql);
		foreach($query->result() as $row)
		{
			$total_transfer_count = $row->total_transfer_count;
		}
		//echo "role: ".$this->session->userdata('role');
		$data['total_call_count'] = $total_call_count;
		$data['total_transfer_count'] = $total_transfer_count;
		$data['transfer_count'] = $transfer_count;
		$data['call_count'] = $call_count;
		$data['title'] = "Settings";
		$this->load->view("settings_view",$data);
	}
	
	function load_settings()
	{
		$where = null;
		$where['id'] = 25;
		$min_age_setting_cdl = db_select_setting($where);
		
		$where = null;
		$where['id'] = 26;
		$max_age_setting_cdl = db_select_setting($where);
		
		$where = null;
		$where['id'] = 27;
		$max_tickets_setting = db_select_setting($where);
		
		$where = null;
		$where['id'] = 28;
		$drivers_needed_setting = db_select_setting($where);
		
		$where = null;
		$where['id'] = 29;
		$credit_setting = db_select_setting($where);
		
		$where = null;
		$where['id'] = 30;
		$location_setting = db_select_setting($where);
		
		$where = null;
		$where['id'] = 31;
		$min_age_setting_school = db_select_setting($where);
		
		$where = null;
		$where['id'] = 32;
		$max_age_setting_school = db_select_setting($where);
		
		$credit_options = array();
		$credit_options[] = "0";
		for($i=400;$i<=1000;$i+=20)
		{
			$credit_options[$i] = $i;
		}
		// print_r($credit_options);
		$data['credit_options'] = $credit_options;
		$data['location_setting'] = $location_setting;
		$data['credit_setting'] = $credit_setting;
		$data['drivers_needed_setting'] = $drivers_needed_setting;
		$data['max_tickets_setting'] = $max_tickets_setting;
		$data['min_age_setting_cdl'] = $min_age_setting_cdl;
		$data['max_age_setting_cdl'] = $max_age_setting_cdl;
		$data['min_age_setting_school'] = $min_age_setting_school;
		$data['max_age_setting_school'] = $max_age_setting_school;
		
		$this->load->view("settings/settings_report",$data);
		
	}
	function update_settings()
	{
		$min_age_cdl_setting = $_POST['min_age_cdl_drowpdown'];
		$max_age_cdl_setting = $_POST['max_age_cdl_drowpdown'];
		$min_age_school_setting = $_POST['min_age_school_drowpdown'];
		$max_age_school_setting = $_POST['max_age_school_drowpdown'];
		$max_tickets_setting = $_POST['max_tickets_drowpdown'];
		$drivers_needed_setting = $_POST['drivers_drowpdown'];
		$credit_setting = $_POST['credit_drowpdown'];
		$location_setting = $_POST['travel_drowpdown'];
		
		
		$min_age_cdl['setting_value'] = $min_age_cdl_setting;
		$where = null;
		$where['id'] = "25";
		db_update_setting($min_age_cdl,$where);
		
		$max_age_cdl['setting_value'] = $max_age_cdl_setting;
		$where = null;
		$where['id'] = "26";
		db_update_setting($max_age_cdl,$where);
		
		$max_tickets['setting_value'] = $max_tickets_setting;
		$where = null;
		$where['id'] = "27";
		db_update_setting($max_tickets,$where);
		
		$drivers_needed['setting_value'] = $drivers_needed_setting;
		$where = null;
		$where['id'] = "28";
		db_update_setting($drivers_needed,$where);
		
		$credit['setting_value'] = $credit_setting;
		$where = null;
		$where['id'] = "29";
		db_update_setting($credit,$where);
		
		$location['setting_value'] = $location_setting;
		$where = null;
		$where['id'] = "30";
		db_update_setting($location,$where);
		
		$min_age_school['setting_value'] = $min_age_school_setting;
		$where = null;
		$where['id'] = "31";
		db_update_setting($min_age_school,$where);
		
		$max_age_school['setting_value'] = $max_age_school_setting;
		$where = null;
		$where['id'] = "32";
		db_update_setting($max_age_school,$where);
		
	}
}