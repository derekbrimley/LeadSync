<?php
class Calls extends MY_Controller {
	
	function index()
	{
		$where = null;
		$where = "1 = 1";
		$users = db_select_users($where);
		
		$user_options = array();
		$user_options[''] = "All";
		foreach($users as $user)
		{
			$user_options[$user['id']] = $user['first_name']." ".$user['last_name'];
		}
		
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
		$data['users'] = $user_options;
		$data['title'] = 'Calls';
		
		$this->load->view('calls_view',$data);
	}
	
	function calls_report()
	{
		$data = $this->get_calls();
	
		// $data['calls'] = $calls;
		$this->load->view('calls/calls_report',$data);
	}
	
	function get_calls()
	{
		$search_filter = $_POST['search_filter'];
		$contact_after_date_filter = $_POST['contact_after_date_filter'];
		$contact_before_date_filter = $_POST['contact_before_date_filter'];
		$answered_by_filter = $_POST['answered_by_filter'];
		$call_source_filter = $_POST['call_source_filter'];
		$outcome_filter = $_POST['outcome_filter'];
		$transferred_to_filter = $_POST['transferred_to_filter'];
		
		$where = '';
		if(!empty($search_filter))
		{
			// echo $search_filter;
			$characters = array("-","(",")");
			$stripped_search_filter = str_replace($characters,"",$search_filter);
			//echo $stripped_search_filter;
			$terms = explode(" ",$stripped_search_filter);
			if(count($terms) > 1)
			{
				$where = $where." AND (phone_number LIKE '%".$terms[0]."%' OR phone_number LIKE '%".$terms[0]."%' OR phone_number LIKE '%".$terms[1]."%' OR phone_number LIKE '%".$terms[1]."%')";
			}
			else
			{
				$where = $where." AND (phone_number LIKE '%".$stripped_search_filter."%' OR phone_number LIKE '%".$stripped_search_filter."%' OR phone_number LIKE '%".$stripped_search_filter."%')";
			}
			
		}
		else
		{
			if(!empty($contact_after_date_filter))
			{
				$after_contact_datetime = date("Y-m-d G:i:s",strtotime($contact_after_date_filter));
				$where = $where." AND call_time > '".$after_contact_datetime."'";
			}
			if(!empty($contact_before_date_filter))
			{
				$before_contact_datetime = date("Y-m-d G:i:s",strtotime($contact_before_date_filter)+60*60*24);
				$where = $where." AND call_time < '".$before_contact_datetime."'";
			}
			if(!empty($call_source_filter))
			{
				$where = $where." AND source = '".$call_source_filter."'";
			}
			if(!empty($answered_by_filter))
			{
				$where = $where." AND user_id = '".$answered_by_filter."'";
			}
			if(!empty($outcome_filter))
			{
				$where = $where." AND outcome = '".$outcome_filter."'";
			}
			if(!empty($transferred_to_filter))
			{
				$where = $where." AND transfer_user_id = '".$transferred_to_filter."'";
			}
		}
		
		$where = substr($where,4);
		// echo "WHERE ".$where;
		
		$calls = db_select_calls($where,"call_time DESC");
		// print_r($calls);
		$count = count($calls);
		
		//echo $count;
		$data['count'] = $count;
		$data['calls'] = $calls;
		return $data;
	}
	
	function refresh_top_bar()
	{
		$data = $this->get_calls();
		
		// $leads = db_select_leads($where);
		
		// $count = count($leads);
		//echo $count;
		$count = $data['count'];
		echo $count;
	}
	
	
	
	
}