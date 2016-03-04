<?php

class Leads extends MY_Controller {
	
	function index()
	{
		$this->time_out();
		date_default_timezone_set('America/Denver');
		$current_date = date("Y-m-d",strtotime('today'));
		
		$where = null;
		$where = '1 = 1';
		$leads = db_select_leads($where);
		
		$role = $this->session->userdata('role');
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
		
		$total_transfer_sql = "SELECT COUNT(id) AS total_transfer_count FROM `lead` WHERE application_datetime > '".$current_date."' AND submitted_to IS NOT NULL";
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
		$data['title'] = 'Leads';
		$data['leads'] = $leads;
		
		if($role == "admin" || $role == "manager" || $role == "recruiter" || $role == "caller")
		{
			$this->load->view('leads_view',$data);
		}
	}
	
	//ADD COMPLETION DATE TO ACTION ITEM
	function complete_action_item()
	{
		date_default_timezone_set('America/Denver');
		$current_datetime = date("Y-m-d H:i:s");
		
		$action_item_id = $_POST['id'];
		
		$set = array();
		$set['completion_date'] = $current_datetime;
		
		$where = null;
		$where['id'] = $action_item_id;
		
		db_update_action_item($set,$where);
		
		
	}
	
	//CREATE NEW ACTION ITEM
	function create_action_item()
	{
		$lead_id = $_POST['id'];
		if($_POST['due_date']===NULL)
		{
			$due_date = NULL;
		}
		else
		{
			$due_date = date('Y-m-d', strtotime($_POST['due_date']));
		}
		
		$note = $_POST['action_item_note'];
		
		$action_item = array();
		$action_item['lead_id'] = $lead_id;
		$action_item['due_date'] = $due_date;
		$action_item['note'] = $note;
		
		db_insert_action_item($action_item);
		//echo "action item added";
	}
	
	function create_note()
	{
		$lead_id = $_POST['id'];
		$text = $_POST['note'];
		
		$initials = substr($this->session->userdata('first_name'),0,1).substr($this->session->userdata('last_name'),0,1);
        date_default_timezone_set('America/Denver');
        $date_text = date("m/d/y H:i");
        
        $full_note = $date_text." - ".$initials." | ".$text."\n\n";
        
        $where['id'] = $lead_id;
        $lead = db_select_lead($where);
        
        $update_lead["applicant_status_log"] = $full_note.$lead["applicant_status_log"];
        db_update_lead($update_lead,$where);
	}
	
	function get_daily_stats()
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
		
		$transfer_sql = "SELECT COUNT(id) AS transfer_count FROM `lead` WHERE application_datetime > '".$current_date."' AND user_submitted = '".$username."' AND submitted_to IS NOT NULL";
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
		
		$total_transfer_sql = "SELECT COUNT(id) AS total_transfer_count FROM `lead` WHERE application_datetime > '".$current_date."' AND submitted_to IS NOT NULL";
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
		$this->load->view("daily_stats_div",$data);
	}
	
	//GET LEADS FOR LEAD REPORT
	function get_leads()
	{
		$search_filter = $_POST['search_filter'];
		$contact_after_date_filter = $_POST['contact_after_date_filter'];
		$contact_before_date_filter = $_POST['contact_before_date_filter'];
		$lead_type_filter = $_POST['lead_type_filter'];
		$lead_status_filter = $_POST['lead_status_filter'];
		$lead_source_filter = $_POST['lead_source_filter'];
		$submitted_to_filter = $_POST['submitted_to_filter'];
		$availability_after_date_filter = $_POST['availability_after_date_filter'];
		$availability_before_date_filter = $_POST['availability_before_date_filter'];
		$action_after_date_filter = $_POST['action_after_date_filter'];
		$action_before_date_filter = $_POST['action_before_date_filter'];
		$sort_filter = $_POST['sort_filter'];
		
		//CREATE WHERE CLAUSE BASED ON FILTERS
		$where = '';
		$having = '';
		$limit = 'all';
		
		//SEARCH FILTER
		if (!empty($search_filter))
		{
			// echo $search_filter;
			$characters = array("-","(",")");
			$stripped_search_filter = str_replace($characters,"",$search_filter);
			//echo $stripped_search_filter;
			$terms = explode(" ",$stripped_search_filter);
			if(count($terms) > 1)
			{
				$where = $where." AND (first_name LIKE '%".$terms[0]."%' OR last_name LIKE '%".$terms[0]."%' OR first_name LIKE '%".$terms[1]."%' OR last_name LIKE '%".$terms[1]."%')";
			}
			else
			{
				$where = $where." AND (first_name LIKE '%".$stripped_search_filter."%' OR last_name LIKE '%".$stripped_search_filter."%' OR phone_number LIKE '%".$stripped_search_filter."%')";
			}
		}
		else
		{
			
			//LEAD STATUS FILTER
			if(!empty($lead_status_filter))
			{
				if($lead_status_filter=="Show Active")
				{
					$where = $where." AND (lead_status IS NULL OR lead_status = 'Call Back' OR lead_status = 'Book Travel' OR lead_status = 'In Route')";
				}
				else
				{
					$where = $where." AND lead_status = '".$lead_status_filter."'";
				}
			}
			
			//START CONTACT DATE FILTER
			if(!empty($contact_after_date_filter))
			{
				$after_contact_datetime = date("Y-m-d",strtotime($contact_after_date_filter));
				$where = $where." AND application_datetime > '".$after_contact_datetime."'";
			}
			
			//END CONTACT DATE FILTER
			if(!empty($contact_before_date_filter))
			{
				$before_contact_datetime = date("Y-m-d",strtotime($contact_before_date_filter)+60*60*24);
				$where = $where." AND application_datetime < '".$before_contact_datetime."'";
			}
			
			//LEAD TYPE FILTER
			if(!empty($lead_type_filter))
			{
				$where = $where." AND why_called_in = '".$lead_type_filter."'";
			}
			
			//LEAD SOURCE FILTER
			if(!empty($lead_source_filter)){
				if($lead_source_filter=="Craigslist"){
					$where = $where." AND lead_source_id = 1";
				}else if($lead_source_filter=="BackPage"){
					$where = $where." AND lead_source_id = 2";
				}else if($lead_source_filter=="Rhino"){
					$where = $where." AND lead_source_id = 3";
				}else if($lead_source_filter=="Google/websearch"){
					$where = $where." AND lead_source_id = 5";
				}else if($lead_source_filter=="Indeed"){
					$where = $where." AND lead_source_id = 6";
				}else if($lead_source_filter=="Other"){
					$where = $where." AND lead_source_id = 4";
				}
			}
			
			//SUBMITTED TO FILTER
			if(!empty($submitted_to_filter))
			{
				$where = $where." AND submitted_to = '".$submitted_to_filter."'";
			}
			
			//START AVAILABILITY DATE FILTER
			if(!empty($availability_after_date_filter))
			{
				$after_availability_datetime = date("Y-m-d G:i:s",strtotime($availability_after_date_filter));
				$where = $where." AND availability_date > '".$after_availability_datetime."'";
			}
			
			//END AVAILABILITY DATE FILTER
			if(!empty($availability_before_date_filter))
			{
				$before_availability_datetime = date("Y-m-d G:i:s",strtotime($availability_before_date_filter)+60*60*24);
				$where = $where." AND availability_date < '".$before_availability_datetime."'";
			}
			
			
			//START ACTION DATE FILTER
			if(!empty($action_after_date_filter))
			{
				$after_action_datetime = date("Y-m-d",strtotime($action_after_date_filter));
				$having = $having." AND action_item_due_date > '".$after_action_datetime."'";
			}
			
			// END ACTION DATE FILTER
			if(!empty($action_before_date_filter))
			{
				$before_action_datetime = date("Y-m-d",strtotime($action_before_date_filter)+60*60*24);
				$having = $having." AND action_item_due_date < '".$before_action_datetime."'";
			}
			if(!empty($having))
			{
				$having = substr($having,4);
				$having = "HAVING ".$having;
			}
			else
			{
				$limit = "100";
			}
		}
		if($sort_filter=="Contact Date")
		{
			$sort = "application_datetime DESC";
		}
		else
		{
			$sort = "case when action_item_due_date is null then 1 else 0 end, action_item_due_date ASC";
		}
		
		$where = substr($where,4);
		$where = $where."".$having;
		//echo "WHERE ".$where;
		
		
		$leads = db_select_leads($where,$sort,$limit);
		
		$count = count($leads);
		
		//echo $count;
		$data['count'] = $count;
		$data['leads'] = $leads;
		return $data;
	}
	
	//GET LEADS FOR COUNT
	function get_leads_count()
	{
		$search_filter = $_POST['search_filter'];
		$contact_after_date_filter = $_POST['contact_after_date_filter'];
		$contact_before_date_filter = $_POST['contact_before_date_filter'];
		$lead_type_filter = $_POST['lead_type_filter'];
		$lead_status_filter = $_POST['lead_status_filter'];
		$lead_source_filter = $_POST['lead_source_filter'];
		$submitted_to_filter = $_POST['submitted_to_filter'];
		$availability_after_date_filter = $_POST['availability_after_date_filter'];
		$availability_before_date_filter = $_POST['availability_before_date_filter'];
		$action_after_date_filter = $_POST['action_after_date_filter'];
		$action_before_date_filter = $_POST['action_before_date_filter'];
		$sort_filter = $_POST['sort_filter'];
		
		//CREATE WHERE CLAUSE BASED ON FILTERS
		$where = '';
		$having = '';
		
		//SEARCH FILTER
		if (!empty($search_filter))
		{
			// echo $search_filter;
			$characters = array("-","(",")");
			$stripped_search_filter = str_replace($characters,"",$search_filter);
			//echo $stripped_search_filter;
			$terms = explode(" ",$stripped_search_filter);
			if(count($terms) > 1)
			{
				$where = $where." AND (first_name LIKE '%".$terms[0]."%' OR last_name LIKE '%".$terms[0]."%' OR first_name LIKE '%".$terms[1]."%' OR last_name LIKE '%".$terms[1]."%')";
			}
			else
			{
				$where = $where." AND (first_name LIKE '%".$stripped_search_filter."%' OR last_name LIKE '%".$stripped_search_filter."%' OR phone_number LIKE '%".$stripped_search_filter."%')";
			}
		}
		else
		{
			
			//LEAD STATUS FILTER
			if(!empty($lead_status_filter))
			{
				if($lead_status_filter=="Show Active")
				{
					$where = $where." AND (lead_status IS NULL OR lead_status = 'Call Back' OR lead_status = 'Book Travel' OR lead_status = 'In Route')";
				}
				else
				{
					$where = $where." AND lead_status = '".$lead_status_filter."'";
				}
			}
			
			//START CONTACT DATE FILTER
			if(!empty($contact_after_date_filter))
			{
				$after_contact_datetime = date("Y-m-d",strtotime($contact_after_date_filter));
				$where = $where." AND application_datetime > '".$after_contact_datetime."'";
			}
			
			//END CONTACT DATE FILTER
			if(!empty($contact_before_date_filter))
			{
				$before_contact_datetime = date("Y-m-d",strtotime($contact_before_date_filter)+60*60*24);
				$where = $where." AND application_datetime < '".$before_contact_datetime."'";
			}
			
			//LEAD TYPE FILTER
			if(!empty($lead_type_filter))
			{
				$where = $where." AND why_called_in = '".$lead_type_filter."'";
			}
			
			//LEAD SOURCE FILTER
			if(!empty($lead_source_filter))
			{
				if($lead_source_filter=="Craigslist")
				{
					$where = $where." AND lead_source_id = 1";
				}
				else if($lead_source_filter=="BackPage")
				{
					$where = $where." AND lead_source_id = 2";
				}
				else if($lead_source_filter=="Rhino")
				{
					$where = $where." AND lead_source_id = 3";
				}
				else if($lead_source_filter=="Other")
				{
					$where = $where." AND lead_source_id = 4";
				}
			}
			
			//SUBMITTED TO FILTER
			if(!empty($submitted_to_filter))
			{
				$where = $where." AND submitted_to = '".$submitted_to_filter."'";
			}
			
			//START AVAILABILITY DATE FILTER
			if(!empty($availability_after_date_filter))
			{
				$after_availability_datetime = date("Y-m-d G:i:s",strtotime($availability_after_date_filter));
				$where = $where." AND availability_date > '".$after_availability_datetime."'";
			}
			
			//END AVAILABILITY DATE FILTER
			if(!empty($availability_before_date_filter))
			{
				$before_availability_datetime = date("Y-m-d G:i:s",strtotime($availability_before_date_filter)+60*60*24);
				$where = $where." AND availability_date < '".$before_availability_datetime."'";
			}
			
			
			//START ACTION DATE FILTER
			if(!empty($action_after_date_filter))
			{
				$after_action_datetime = date("Y-m-d",strtotime($action_after_date_filter));
				$having = $having." AND action_item_due_date > '".$after_action_datetime."'";
			}
			
			// END ACTION DATE FILTER
			if(!empty($action_before_date_filter))
			{
				$before_action_datetime = date("Y-m-d",strtotime($action_before_date_filter)+60*60*24);
				$having = $having." AND action_item_due_date < '".$before_action_datetime."'";
			}
			if(!empty($having))
			{
				$having = substr($having,4);
				$having = "HAVING ".$having;
			}
		}
		if($sort_filter=="Contact Date")
		{
			$sort = "application_datetime DESC";
		}
		else
		{
			$sort = "case when action_item_due_date is null then 1 else 0 end, action_item_due_date ASC";
		}
		
		$where = substr($where,4);
		$where = $where."".$having;
		//echo "WHERE ".$where;
		
		
		$leads = db_select_leads($where,$sort);
		
		$count = count($leads);
		
		//echo $count;
		$data['count'] = $count;
		$data['leads'] = $leads;
		return $data;
	}
	
	//GET NOTES FOR SPECIFIED LEAD
    function get_notes($lead_id)
    {
        $where = null;
        $where['id'] = $lead_id;
        $lead = db_select_lead($where);
        //echo $lead['id'];
        $data['lead'] = $lead;
        $this->load->view('leads/lead_notes_div',$data);
    }//end get_notes
	
	//LOADS LEAD REPORT BASED OFF OF FILTER INPUTS
	function leads_report()
	{
		$data = $this->get_leads();
		
		// $leads = db_select_leads($where,"action_item_due_date");
		
		// $count = count($leads);
		$this->load->view('leads/leads_report',$data);
	}
	
	//LOAD DETAILS FOR SPECIFIED LEAD
	function load_lead_details()
	{
		$id = $_POST['id'];

		// GET LEAD FOR SPECIFIED ID
		$where = null;
		$where['id'] = $id;
		// echo $id;
		$lead = db_select_lead($where);
		
		// GET ACTION ITEMS FOR SPECIFIED LEAD
		$where = null;
		$where['lead_id'] = $id;
		$action_items = db_select_action_items($where,"due_date DESC");
		
		//GET RECRUITERS
		$where = null;
		$where['role'] = "recruiter";
		$recruiters = db_select_users($where);
		
		//print_r($recruiters);
		
		$recruiter_options = array();
		$recruiter_options[] = "Select";
		foreach($recruiters as $recruiter)
		{
			$recruiter_options[$recruiter['id']] = $recruiter['first_name']." ".$recruiter['last_name'];
		}
		
		//GET CREDIT OPTIONS
		$credit_options = array();
		$credit_options[] = "0";
		for($i=400;$i<=1000;$i+=20)
		{
			$credit_options[$i] = $i;
		}
		
		$data['credit_options'] = $credit_options;
		$data['recruiter_options'] = $recruiter_options;
		$data['action_items'] = $action_items;
		$data['lead'] = $lead;
		$this->load->view('leads/lead_row_details.php',$data);
	}

	//LOAD LEAD DETAILS INTO ROW
	function load_lead_row()
	{
		$id = $_POST['id'];
		
		//GET SPECIFIED LEAD
		$where = null;
		$where['id'] = $id;
		$lead = db_select_lead($where);
		
		$data['lead'] = $lead;
		$this->load->view('leads/lead_row.php',$data);
	}
	
	//TEMPORARY PAGE TO ADD TO MARKET RELATION TABLE
	function market_relation()
	{
		$where = null;
		$where = "1 = 1";
		$markets = db_select_markets($where,"name ASC");
		
		$market_options = array();
		$market_options[] = "Select";
		foreach($markets as $market)
		{
			$market_options[$market['id']] = $market['name'];
		}
		
		$data['market_options'] = $market_options;
		$data['title'] = 'Market Relation';
		$this->load->view('market_relation_view',$data);
		
		//OLD CODE TO ADD MARKETS TO DB
		// $url = 'http://www.craigslist.org/about/sites';
		// $input = file_get_contents($url);
		// $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
		// if(preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)) {
			
			// $i = 0;
			
			// foreach($matches as $match)
			// {
				// if($i>=7 AND $i<=423)
				// {
					// $market = null;
					// $market['name'] = $match[3];
					// $market['link'] = $match[2];
					
					// db_insert_market($market);
					
				// }
				// $i++;
			// }
		// }
		
	}
	
	//TEMPORARY FUNCTION TO ADD TO MARKET RELATION TABLE
	function market_relation_form()
	{
		$primary_market_id = $_POST['primary_market_name'];
		$related_market_id = $_POST['related_market_name'];
		
		$market_relation = null;
		$market_relation['market_id'] = $primary_market_id;
		$market_relation['related_market_id'] = $related_market_id;
		
		db_insert_market_relationship($market_relation);
		header("Location: ".base_url('/index.php/leads/market_relation'));
		die();
	}
	
	function get_cl_link()
	{
		$market_id = $_POST['id'];
		
		$where = null;
		$where['id'] = $market_id;
		$market = db_select_market($where);
		
		$link = "<a target='_blank'href=".$market['link'].">Click to go to market page</a>";
		
		echo $link;
	}
	
	function get_related_markets()
	{
		$market_id = $_POST['id'];
		
		$where = null;
		$where['market_id'] = $market_id;
		$market_relationships = db_select_market_relationships($where);
		
		$related_markets = array();
		if(!empty($market_relationships))
		{
			foreach($market_relationships as $market_relationships)
			{
				$where = null;
				$where['id'] = $market_relationships['related_market_id'];
				$market = db_select_market($where);
				
				$related_markets[] = $market['name'];
			}
		}
		
		$data['related_markets'] = $related_markets;
		$this->load->view('market_relation/related_markets_div',$data);
	}
	
	//MAIN NEXTGEN PAGE
	function nextgen()
	{
		
		$data['title'] = 'NextGen Marketing';
		$this->load->view('nextgen_view',$data);
	}
	
	//REFRESH COUNT IN THE TOP BAR
	function refresh_top_bar()
	{
		$data = $this->get_leads_count();
		
		// $leads = db_select_leads($where);
		
		// $count = count($leads);
		//echo $count;
		$count = $data['count'];
		echo $count;
	}
	
	//SAVE NOTE
    function save_note()
    {
        $lead_id = $_POST["lead_id"];
        
        $text = $_POST["new_note"];
        $initials = substr($this->session->userdata('first_name'),0,1).substr($this->session->userdata('last_name'),0,1);
        date_default_timezone_set('America/Denver');
        $date_text = date("m/d/y H:i");
        
        $full_note = $date_text." - ".$initials." | ".$text."\n\n";
        
        $where['id'] = $lead_id;
        $lead = db_select_lead($where);
        
        $update_lead["applicant_status_log"] = $full_note.$lead["applicant_status_log"];
        db_update_lead($update_lead,$where);
        
		// echo $full_note;
        $this->get_notes($lead_id);
        
        // echo $update_load["settlement_notes"];
    }
	
	//UPDATE ACTION ITEMS
	function update_action_items()
	{
		$action_item_id = $_POST['action_item_id'];
		$lead_id = $_POST['lead_id_input'];
		
		$set = array();
		$set['note'] = $_POST["action_item_note_input"];
		$set['due_date'] = date("Y-m-d G:i:s",strtotime($_POST['due_date_input']));
		$set['completion_date'] = null;
		
		$where = null;
		$where['id'] = $action_item_id;
		db_update_action_item($set,$where);
	}
	
	//UPDATE DETAILS FOR GIVEN LEAD
	function update_lead_details()
	{
		$id = $_POST['id_input'];
		$first_name = $_POST['first_name_input'];
		$last_name = $_POST['last_name_input'];
		$address = $_POST['address_input'];
		$city = $_POST['city_input'];
		$state = $_POST['state_input'];
		$zip_code = $_POST['zip_code_input'];
		$phone_number = $_POST['phone_number_input'];
		if(empty($phone_number))
		{
			
		}
		else
		{
			$stripped_phone_number = preg_replace("/[^0-9]/",'', $phone_number);
		}
		$email = $_POST['email_input'];
		if(empty($_POST["availability_date_input"]))
		{
			$availability_date = null;
		}
		else
		{
			$availability_date = date("Y-m-d G:i:s",strtotime($_POST["availability_date_input"]));
		}
		$number_of_tickets = $_POST['number_of_tickets_input'];
		$number_of_accidents = $_POST['number_of_accidents_input'];
		$credit_score = $_POST['credit_score_input'];
		$dob = $_POST['dob_input'];
		$license_number = $_POST['license_number_input'];
		$license_state = $_POST['license_state_input'];
		$ok_w_teams = $_POST['ok_w_teams_input'];
		$ok_w_otr = $_POST['ok_w_otr_input'];
		$call_type = $_POST['call_type_input'];
		$lead_type = $_POST['lead_type_input'];
		$lead_source = $_POST['lead_source_input'];
		$submitted_to = $_POST['submitted_to_input'];
		$lead_status = $_POST['lead_status_input'];
		$assigned_recruiter_id = $_POST['assigned_recruiter'];
		$cdl = $_POST['cdl_input'];
		
		$where = null;
		$where['id'] = $id;
		
		$set = array();
		$set['first_name'] = $first_name;
		$set['last_name'] = $last_name;
		$set['current_address'] = $address;
		$set['current_city'] = $city;
		$set['current_state'] = $state;
		$set['current_zip_code'] = $zip_code;
		$set['phone_number'] = $stripped_phone_number;
		$set['email'] = $email;
		$set['availability_date'] = $availability_date;
		$set['number_of_tickets'] = $number_of_tickets;
		$set['number_of_accidents'] = $number_of_accidents;
		$set['credit_score'] = $credit_score;
		$set['dob'] = $dob;
		$set['current_license_number'] = $license_number;
		$set['current_license_state'] = $license_state;
		$set['drive_team'] = $ok_w_teams;
		$set['drive_otr'] = $ok_w_otr;
		$set['inbound_or_outbound'] = $call_type;
		$set['lead_source_id'] = $lead_source;
		$set['why_called_in'] = $lead_type;
		$set['submitted_to'] = $submitted_to;
		$set['assigned_recruiter_id'] = $assigned_recruiter_id;
		$set['lead_status'] = $lead_status;
		$set['cdl'] = $cdl;
		
		db_update_lead($set,$where);
		
	}
	
}