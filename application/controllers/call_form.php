<?php

class Call_Form extends MY_Controller {
	
	function index()
	{
		
		$where = null;
		$where['role'] = "recruiter";
		$recruiters = db_select_users($where);
		
		$recruiter_options = array();
		$recruiter_options[''] = "Select";
		$recruiter_options['No one answered'] = "No one answered";
		foreach($recruiters as $recruiter)
		{
			$recruiter_options[$recruiter['id']] = $recruiter['first_name']." ".$recruiter['last_name'];
		}
		
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
		
		$data['min_age_setting_school'] = $min_age_setting_school;
		$data['max_age_setting_school'] = $max_age_setting_school;
		$data['total_call_count'] = $total_call_count;
		$data['total_transfer_count'] = $total_transfer_count;
		$data['transfer_count'] = $transfer_count;
		$data['call_count'] = $call_count;
		$data['location_setting'] = $location_setting;
		$data['credit_setting'] = $credit_setting;
		$data['drivers_needed_setting'] = $drivers_needed_setting;
		$data['max_tickets_setting'] = $max_tickets_setting;
		$data['min_age_setting_cdl'] = $min_age_setting_cdl;
		$data['max_age_setting_cdl'] = $max_age_setting_cdl;
		$data['recruiter_options'] = $recruiter_options;
		$data['title'] = 'Call Form';
		$this->load->view('call_form_view',$data);
	}
	
	function create_lead()
	{
		//SET TIMEZONE
		date_default_timezone_set('America/Denver');
		$current_datetime = date("Y-m-d H:i:s");
		
		$lead_id = $_POST['input_id'];
		$type_of_call = $_POST["inbound_or_outbound_question"];
		$reason_for_call = $_POST["reason_for_call_question"];
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$phone_number = $_POST["phone_number"];
		$lead_source_id = $_POST["source_of_call_question"];
		$user_submitted = $this->session->userdata('username');
		
		$lead = array();
		$lead["application_datetime"] = $current_datetime;
		$lead["inbound_or_outbound"] = $type_of_call;
		$lead["why_called_in"] = $reason_for_call;
		$lead["first_name"] = $first_name;
		$lead["last_name"] = $last_name;
		$lead["phone_number"] = $phone_number;
		$lead['lead_source_id'] = $lead_source_id;
		$lead["lead_status"] = "Call Back";
		$lead["user_submitted"] = $user_submitted;
		
		if(empty($lead_id)){
			$where = null;
			
			$where["first_name"] = $first_name;
			$where["last_name"] = $last_name;
			$where["phone_number"] = $phone_number;
			
			$duplicate_lead = db_select_lead($where);
			
			if(empty($duplicate_lead)){
				db_insert_lead($lead);
				
				$new_lead = db_select_lead($lead);
				
				$where = null;
				$where['username'] = $this->session->userdata('username');
				$user = db_select_user($where);
				$user_id = $user['id'];
				
				$call = array();
				$call["lead_id"] = $new_lead['id'];
				$call["user_id"] = $user_id;
				$call["call_time"] = $current_datetime;
				$call["phone_number"] = $phone_number;
				$call["source"] = $lead_source_id;
				$call["outcome"] = "Other";
				
				db_insert_call($call);
				
				date_default_timezone_set('America/Denver');
				$date_text = date("m/d/y H:i");
				
				$notes = "Lead generated by ".$this->session->userdata('first_name');
				$full_note = $date_text." - | ".$notes."\n\n";
				
				$update_lead["applicant_status_log"] = $full_note.$new_lead["applicant_status_log"];
				db_update_lead($update_lead,$lead);
				
				echo $new_lead['id'];
				
				
			}
			else{
				echo "Lead already in the system!";
			}
		}else{
			$where = null;
			$where['id'] = $lead_id;
			
			$set = array();
			$set["inbound_or_outbound"] = $type_of_call;
			$set["why_called_in"] = $reason_for_call;
			$set["first_name"] = $first_name;
			$set["last_name"] = $last_name;
			$set["phone_number"] = $phone_number;
			$set["lead_status"] = "Call Back";
			
			db_update_lead($set,$where);
			echo $lead_id;
		}
	}
	
	function create_partial_lead(){
		//SET TIMEZONE
		date_default_timezone_set('America/Denver');
		$current_datetime = date("Y-m-d H:i:s");
		
		$type_of_call = $_POST["inbound_or_outbound_question"];
		$reason_for_call = $_POST["reason_for_call_question"];
		$phone_number = $_POST["phone_number"];
		$call_source = $_POST["source_of_call_question"];
		$user_submitted = $this->session->userdata('username');
		
		$lead = array();
		$lead["application_datetime"] = $current_datetime;
		$lead["inbound_or_outbound"] = $type_of_call;
		$lead["why_called_in"] = $reason_for_call;
		$lead["phone_number"] = $phone_number;
		$lead['lead_source_id'] = $call_source;
		$lead['lead_status'] = "Not Interested";
		$lead["user_submitted"] = $user_submitted;
		
		db_insert_lead($lead);
		$new_lead = db_select_lead($lead);
		
		echo $new_lead['id'];
	}
	
	function update_lead()
	{
		//SET TIMEZONE
		date_default_timezone_set('America/Denver');
		$current_datetime = date("Y-m-d H:i:s");
		
		$transferred_to = $_POST['transferred_to'];
		
		if($transferred_to=="No one answered"||$transferred_to=="")
		{
			
		}
		else
		{
			$recruiter_id = $transferred_to;
			$where = null;
			$where['id'] = $recruiter_id;
			// $where['id'] = '1';
			$recruiter = db_select_user($where);
			$transferred_to = $recruiter['first_name']." ".$recruiter['last_name'];
		}
		
		$id = $_POST["input_id"];
		$type_of_call = $_POST["inbound_or_outbound_question"];
		$reason_for_call = $_POST["reason_for_call_question"];
		if($reason_for_call=="School")
		{
			$cdl = "No";
		}
		else if($reason_for_call=="Class A")
		{
			$cdl = "Yes";
		}
		else
		{
			$cdl = "";
		}
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$phone_number = $_POST["phone_number"];
		$address = $_POST["address"];
		$city = $_POST["city"];
		$state = $_POST["state"];
		$zip_code = $_POST['zip_code'];
		$age = $_POST["age_question"];
		$birth_month = $_POST["birth_month"];
		$birth_day = $_POST["birth_day"];
		$birth_year = $_POST["birth_year"];
		$number_of_tickets = $_POST["number_of_tickets_question"];
		$ticket_explanation = $_POST["ticket_explanation_question"];
		$number_of_accidents = $_POST["number_of_accidents_question"];
		$accident_explanation = $_POST["accident_explanation_question"];
		$license_number = $_POST["license_number_question"];
		$license_state = $_POST["license_state_question"];
		$team_driving = $_POST["team_driving_question"];
		$otr_6 = $_POST["otr_6_question"];
		$submitted_to = $_POST["submitted_to"];
		$call_source = $_POST["source_of_call_question"];
		if(empty($_POST["availability_date_question"]))
		{
			$availability_date = null;
		}
		else
		{
			$availability_date = date("Y-m-d G:i:s",strtotime($_POST["availability_date_question"]));
		}
		$user_submitted = $this->session->userdata('username');
		$notes = $_POST["notes_on_lead_input"];
		$credit_score = $_POST['credit_score_input'];
		
		$lead = array();
		$lead["application_datetime"] = $current_datetime;
		$lead["inbound_or_outbound"] = $type_of_call;
		$lead["why_called_in"] = $reason_for_call;
		$lead["first_name"] = ucfirst($first_name);
		$lead["last_name"] = ucfirst($last_name);
		$lead["phone_number"] = $phone_number;
		$lead["current_address"] = $address;
		$lead["current_city"] = ucwords($city);
		$lead["current_state"] = $state;
		$lead["current_zip_code"] = $zip_code;
		$lead["age"] = $age;
		$lead["dob"] = date("Y-m-d G:i:s",strtotime($birth_month." ".$birth_day.", ".$birth_year));
		$lead["number_of_tickets"] = $number_of_tickets;
		$lead["ticket_details"] = $ticket_explanation;
		$lead["number_of_accidents"] = $number_of_accidents;
		$lead["accident_details"] = $accident_explanation;
		$lead["current_license_number"] = $license_number;
		$lead["current_license_state"] = $license_state;
		$lead["drive_team"] = $team_driving;
		$lead["drive_otr"] = $otr_6;
		$lead["submitted_to"] = $submitted_to;
		if($submitted_to=="Knight or CRST Dedicated" || $submitted_to=="Get Trucker Jobs School" || $submitted_to=="Get Trucker Jobs CDL")
		{
			$lead["lead_status"] = "Sold";
		}
		else if($submitted_to=="Non-Recruiting Call")
		{
			$lead["lead_status"] = "Not Interested";
		}
		else if($submitted_to=="Lobos CDL" || $submitted_to=="Lobos School" || $submitted_to=="No one answered" || $submitted_to=="Other")
		{
			$lead["lead_status"] = "Call Back";
		}
		$lead["transferred_to"] = $transferred_to;
		$lead['assigned_recruiter_id'] = $recruiter_id;
		$lead["user_submitted"] = $user_submitted;
		$lead["lead_source_id"] = $call_source;
		$lead["availability_date"] = $availability_date;
		$lead["cdl"] = $cdl;
		$lead['credit_score']= $credit_score;
		
		$where = null;
		$where['id'] = $id;
		
		db_update_lead($lead,$where);
		
		$note_lead = db_select_lead($where);
		
		$initials = substr($this->session->userdata('first_name'),0,1).substr($this->session->userdata('last_name'),0,1);
        date_default_timezone_set('America/Denver');
        $date_text = date("m/d/y H:i");
        
        $full_note = $date_text." - ".$initials." | ".$notes."\n\n";
        
		$submitted_note = $date_text." - ".$initials." | "."Lead submitted to ".$submitted_to."\n\n";
		
		$transferred_note = $date_text." - ".$initials." | "."Call transferred to ".$transferred_to."\n\n";
		
		$set = null;
		$set["applicant_status_log"] = $full_note.$transferred_note.$submitted_note.$note_lead["applicant_status_log"];
		db_update_lead($set,$where);
		
		$where = null;
		$where['lead_id'] = $id;
		
		$call = array();
		$call['transfer_user_id'] = $recruiter_id;
		$call['source'] = $call_source;
		$call['outcome'] = $submitted_to;
		$call['notes'] = $full_note.$transferred_note.$submitted_note.$note_lead["applicant_status_log"];;
		
		db_update_call($call,$where);
		
		if($transferred_to=="No one answered")
		{
			$action_item = array();
			$action_item['lead_id'] = $id;
			$action_item['due_date'] = $current_datetime;
			$action_item['note'] = "Unsuccessful call transfer. Call back as soon as possible.";
			
			db_insert_action_item($action_item);
		}
		
		redirect(base_url("index.php/call_form/"));
		
	}
	
	
}