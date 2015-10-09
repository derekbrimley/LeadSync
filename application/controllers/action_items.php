<?php
	
class Action_Items extends MY_Controller {
	
	function index()
	{
		$where = null;
		$where = '1 = 1';
		$action_items = db_select_action_items($where);
		
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
		$data['title'] = 'Action Items';
		$data['transfer_count'] = $transfer_count;
		$data['call_count'] = $call_count;
		$data['action_items'] = $action_items;
		$this->load->view('action_items_view',$data);
	}
	function get_action_item($id)
	{
		$action_item = db_select_action_item($id);
		return $action_item;
	}
	function get_action_items()
	{
		$lead = $_POST["lead_filter"];
		$due_date_after = $_POST["due_date_after_filter"];
		$due_date_before = $_POST["due_date_before_filter"];
		$availability_date_after = $_POST["availability_date_after_filter"];
		$availability_date_before = $_POST["availability_date_before_filter"];
		$lead_type = $_POST["lead_type_filter"];
		$lead_status = $_POST["action_lead_status_filter"];
		
		$where = null;
		
		if(!empty($lead))
		{
			$names = explode(" ",$lead);
			$where = $where." AND lead.first_name = '".$names[0]."' AND lead.last_name = '".$names[1]."'";
		}
		if(!empty($due_date_after))
		{
			$where = $where." AND due_date >'".date("Y-m-d G:i:s",strtotime($due_date_after))."'";
		}
		if(!empty($due_date_before))
		{
			$where = $where." AND due_date <'".date("Y-m-d G:i:s",strtotime($due_date_before)+60*60*24)."'";
		}
		if(!empty($availability_date_after))
		{
			$where = $where." AND availability_date >'".date("Y-m-d G:i:s",strtotime($availability_date_after))."'";
		}
		if(!empty($availability_date_before))
		{
			$where = $where." AND availability_date <'".date("Y-m-d G:i:s",strtotime($availability_date_before)+60*60*24)."'";
		}
		if(!empty($lead_type))
		{
			$where = $where." AND why_called_in = '".$lead_type."'";
		}
		if(!empty($lead_status))
		{
			if($lead_status=="Show Active")
			{
				$where = $where." AND (lead_status IS NULL OR lead_status = 'Call Back' OR lead_status = 'Book Travel' OR lead_status = 'In Route')";
			}
			else
			{
				$where = $where." AND lead_status = '".$lead_status."'";
			}
		}
		
		$where = substr($where,4);
		//echo $where;
		$action_items = db_select_action_items($where,"due_date");
		
		$count = count($action_items);
		
		$data['action_items'] = $action_items;
		$data['count'] = $count;
		return $data;
	}
	function update_action_item_report()
	{
		$data = $this->get_action_items();
		$this->load->view("action_items/action_items_report",$data);
	}
	function update_action_item_count()
	{
		$data = $this->get_action_items();
		echo $data["count"];
	}
	function update_action_item()
	{
		$action_item_id = $_POST['id'];
		
		$where = null;
		$where['id'] = $action_item_id;
		
		$action_item = db_select_action_item($where);
		
		$data['action_item'] = $action_item;
		
		$this->load->view("action_items/action_items_row.php",$data);
	}
}
