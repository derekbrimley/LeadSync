<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//TEMPLATE: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT TEMPLATE
	function db_insert_template($template)
	{
		db_insert_table("template",$template);
	
	}//END db_insert_template	

	//SELECT TEMPLATES (many)
	function db_select_templates($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_template($where,$order_by,$limit,"many");
		
	}//end db_select_templates() many	

	//SELECT TEMPLATE (one)
	function db_select_template($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." template.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." template.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." template.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT 
				template.id as id,
				template.recorder_id as recorder_id,
				person.first_name as first_name ,
				template.load_id as load_id,
				`load`.customer_load_number,
				template.truck_id as truck_id ,
				truck.truck_number,
				template.trailer_id as trailer_id ,
				trailer.trailer_number,
				miles_type,
				template.main_driver_id as main_driver_id ,
				main_driver.client_nickname as main_driver_nickname ,
				template.codriver_id as codriver_id ,
				codriver.client_nickname as codriver_nickname ,
				entry_type,
				entry_datetime,
				city,
				state,
				address,
				odometer,
				route,
				miles,
				out_of_route,
				gallons,
				fuel_expense,
				template.mpg AS entry_mpg,
				entry_notes
				FROM `template`
				LEFT JOIN person ON template.recorder_id = person.id 
				LEFT JOIN  `load` ON  `load_id` =  `load`.id
				LEFT JOIN truck ON template.truck_id = truck.id 
				LEFT JOIN trailer ON template.trailer_id = trailer.id 
				LEFT JOIN client as main_driver ON template.main_driver_id = main_driver.id 
				LEFT JOIN client as codriver ON template.codriver_id = codriver.id ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$templates = array();
		foreach ($query->result() as $row)
		{
			$template['id'] = $row->id;
			$template['load_id'] = $row->load_id;
			$template['truck_id'] = $row->truck_id;
			$template['trailer_id'] = $row->trailer_id;
			$template['miles_type'] = $row->miles_type;
			$template['main_driver_id'] = $row->main_driver_id;
			$template['codriver_id'] = $row->codriver_id;
			$template['entry_type'] = $row->entry_type;
			$template['entry_datetime'] = $row->entry_datetime;
			$template['city'] = $row->city;
			$template['state'] = $row->state;
			$template['address'] = $row->address;
			$template['odometer'] = $row->odometer;
			$template['route'] = $row->route;
			$template['miles'] = $row->miles;
			$template['out_of_route'] = $row->out_of_route;
			$template['gallons'] = $row->gallons;
			$template['fuel_expense'] = $row->fuel_expense;
			$template['mpg'] = $row->entry_mpg;
			$template['entry_notes'] = $row->entry_notes;
			
			$recorder["f_name"] = $row->f_name;
			$template["recorder"] = $recorder;
			
			$load["customer_load_number"] = $row->customer_load_number;
			$template["load"] = $load;
			
			$truck["truck_number"] = $row->truck_number;
			$template["truck"] = $truck;
			
			$trailer["trailer_number"] = $row->trailer_number;
			$template["trailer"] = $trailer;
			
			$main_driver["client_nickname"] = $row->main_driver_nickname;
			$template["main_driver"] = $main_driver;
			
			$codriver["client_nickname"] = $row->codriver_nickname;
			$template["codriver"] = $codriver;
			
			$templates[] = $template;
			
		}// end foreach
		
		if (empty($template))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $template;
		}
		else if($many == "many")
		{
			return $templates;
		}
	}//end db_select_template()

	//UPDATE TEMPLATE
	function db_update_template($set,$where)
	{
		db_update_table("template",$set,$where);
		
	}//end update template	
	
	//DELETE TEMPLATE	
	function db_delete_template($where)
	{
		db_delete_from_table("template",$where);
		
	}//end db_delete_template()	
	
	

	
//GENERIC FUNCTIONS TO A HANDLE THE VARIOUS DATABASE FUNCTIONS

	//INSERT TABLE
	function db_insert_table($table,$object)
	{
		$CI =& get_instance();
		$field_names = "";
		$field_values = "";
		foreach($object as $key => $value)
		{
			$field_names = $field_names." `".$key."`,";
			$field_values = $field_values." ?,";
			$values[] = $value;
		}
		//REMOVE REMAINING COMMA AT THE END OF THE STRING
		$field_names = substr($field_names, 0, -1);
		$field_values = substr($field_values, 0, -1);
		
		$sql = "INSERT INTO `$table` (`id`,$field_names) VALUES (NULL,$field_values)";
		$CI->db->query($sql,$values);

	}//END db_insert_template

	//SELECT TABLES (many)  ************* NEEDS TO BE UPDATED EVERY TIME A TABLE IS ADDED TO THE DB ************
	function db_select_tables($table,$where,$order_by = 'id')
	{
		$CI =& get_instance();
		$where_sql = " ";
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." And";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." ".$key." is ?";
				}
				else
				{
					$where_sql = $where_sql." ".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where;
		}
		
		$sql = "SELECT * FROM `$table` WHERE ".$where_sql." ORDER BY ".$order_by;
		$query_table = $CI->db->query($sql,$values);
		
		$object = array();
		$objects = array();
		foreach ($query_table->result() as $row)
		{
			//DEPENDING ON THE TABLE SELECT THE ROWS FROM THAT TABLE
			$object_where['id'] = $row->id;
			if($table == "account")
			{
				$object = db_select_account($object_where);
			}
			else if($table == "account_entry")
			{
				$object = db_select_account_entry($object_where);
			}
			else if($table == "client")
			{
				$object = db_select_client($object_where);
			}
			else if($table == "customer")
			{
				$object = db_select_customer($object_where);
			}
			else if($table == "drop")
			{
				$object = db_select_drop($object_where);
			}
			else if($table == "invoice")
			{
				$object = db_select_invoice($object_where);
			}
			else if($table == "invoice_allocation")
			{
				$object = db_select_invoice_allocation($object_where);
			}
			else if($table == "load")
			{
				$object = db_select_load($object_where);
			}
			else if($table == "load_expense")
			{
				$object = db_select_load_expense($object_where);
			}
			else if($table == "pick")
			{
				$object = db_select_pick($object_where);
			}
			else if($table == "permission")
			{
				$object = db_select_permission($object_where);
			}
			else if($table == "route_request")
			{
				$object = db_select_route_request($object_where);
			}
			else if($table == "settlement_adjustment")
			{
				$object = db_select_settlement_adjustment($object_where);
			}
			else if($table == "settlement_expense")
			{
				$object = db_select_settlement_expense($object_where);
			}
			else if($table == "settlement_profit_split")
			{
				$object = db_select_settlement_profit_split($object_where);
			}
			else if($table == "stop")
			{
				$object = db_select_stop($object_where);
			}
			else if($table == "truck")
			{
				$object = db_select_truck($object_where);
			}
			else if($table == "user_permission")
			{
				$object = db_select_user_permission($object_where);
			}
			else
			{
				echo "You forgot to add this table to the db select tables function";
			}
			
			
			$objects[] = $object;
		}
		
		return $objects;
	}//end db_select_tables() many		

	//UPDATE TABLE
	function db_update_table($table,$set,$where)
	{
		$CI =& get_instance();
		$i = 0;
		$set_sql = " ";
		$values = array();
		foreach($set as $key => $value)
		{
			if ($i > 0)
			{
				$set_sql = $set_sql.", ";
			}
			
			if ($value == null)
			{
				$set_sql = $set_sql." ".$key." = NULL ";
			}
			else
			{
				$set_sql = $set_sql." ".$key." = ?";
				$values[] = $value;
			}
			$i++;
		}
		
		$i = 0;
		$where_sql = " ";
		if(is_array($where))
		{
			$i = 0;
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." And";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." ".$key." is ?";
				}
				else
				{
					$where_sql = $where_sql." ".$key." = ?";
				}
				$values[] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where;
		}
		
		$sql = "UPDATE `$table` SET ".$set_sql." WHERE ".$where_sql;
		//echo $sql;
		//print_r($values);
		$CI->db->query($sql,$values);
	}//end update table

	//DELETE FROM TABLE
	function db_delete_from_table($table,$where)
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = " ";
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." And";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." ".$key." is ?";
				}
				else
				{
					$where_sql = $where_sql." ".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where;
		}
		
		$sql = "DELETE FROM `$table` WHERE ".$where_sql;
		$CI->db->query($sql,$values);
		
	}//end db_delete_from_table()	

	//GET LIST OF DISTINT EXPENSE CATEGORIES
	function get_distinct($column_name,$table_name,$where = null,$order_by = "none")
	{
		if($order_by = "none")
		{
			$order_by = $column_name;
		}
	
		$CI =& get_instance();
		
		$categories = array();
		
		$values = array();
		$where_sql = " ";
		if(!empty($where))
		{
			if(is_array($where))
			{
				$i = 0;
				foreach($where as $key => $value)
				{
					
					if ($i > 0)
					{
						$where_sql = $where_sql." And";
					}
					
					if ($value == null)
					{
						$where_sql = $where_sql." ".$key." is ?";
					}
					else
					{
						$where_sql = $where_sql." ".$key." = ?";
					}
					$values[$i] = $value;
					//echo "value[$i] = $value ";
					$i++;
				}
				
			}
			else
			{
				$where_sql = $where;
			}
			
			$where_sql = " WHERE ".$where_sql;
		}
		
		$sql = "SELECT distinct(".$column_name.") AS column_name FROM `".$table_name."`".$where_sql." ORDER BY ".$order_by;
		//error_log("SQL: ".$sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		foreach ($query->result() as $row)
		{
			$categories[] = $row->column_name;
		}
		
		return $categories;
	}



	
//ACCOUNT_ENTRY: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT ACCOUNT_ENTRY
	function db_insert_account_entry($account_entry)
	{
		db_insert_table("account_entry",$account_entry);
	
	}//END db_insert_account_entry	

	//SELECT ACCOUNT_ENTRYS (many)
	function db_select_account_entrys($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_account_entry($where,$order_by,$limit,"many");
		
	}//end db_select_account_entrys() many	

	//SELECT ACCOUNT_ENTRY (one)
	function db_select_account_entry($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." account_entry.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." account_entry.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." account_entry.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `account_entry` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$account_entrys = array();
		foreach ($query->result() as $row)
		{
			$account_entry['id'] = $row->id;
			$account_entry['user_id'] = $row->user_id;
			$account_entry['post_id'] = $row->post_id;
			$account_entry['description'] = $row->description;
			$account_entry['amount'] = $row->amount;
			$account_entry['datetime'] = $row->datetime;
			$account_entry['payment_screenshot_guid'] = $row->payment_screenshot_guid;
			
			$account_entrys[] = $account_entry;
			
		}// end foreach
		
		if (empty($account_entry))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $account_entry;
		}
		else if($many == "many")
		{
			return $account_entrys;
		}
	}//end db_select_account_entry()

	//UPDATE ACCOUNT_ENTRY
	function db_update_account_entry($set,$where)
	{
		db_update_table("account_entry",$set,$where);
		
	}//end update account_entry	
	
	//DELETE ACCOUNT_ENTRY	
	function db_delete_account_entry($where)
	{
		db_delete_from_table("account_entry",$where);
		
	}//end db_delete_account_entry()	
		
	
	
	

//ACTION_ITEM: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT ACTION_ITEM
	function db_insert_action_item($action_item)
	{
		db_insert_table("action_item",$action_item);
	
	}//END db_insert_action_item	

	//SELECT ACTION_ITEMS (many)
	function db_select_action_items($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_action_item($where,$order_by,$limit,"many");
		
	}//end db_select_action_items() many	

	//SELECT ACTION_ITEM (one)
	function db_select_action_item($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." action_item.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." action_item.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." action_item.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT 
				action_item.id as id,
				action_item.lead_id as lead_id,
				action_item.due_date as due_date,
				action_item.completion_date as completion_date,
				action_item.note as note,
				lead.first_name,
				lead.last_name
				FROM `action_item`
				JOIN `lead` ON action_item.lead_id = lead.id".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$action_items = array();
		foreach ($query->result() as $row)
		{
			$action_item['id'] = $row->id;
			$action_item['lead_id'] = $row->lead_id;
			$action_item['due_date'] = $row->due_date;
			$action_item['completion_date'] = $row->completion_date;
			$action_item['note'] = $row->note;
			
			$action_items[] = $action_item;
			
		}// end foreach
		
		if (empty($action_item))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $action_item;
		}
		else if($many == "many")
		{
			return $action_items;
		}
	}//end db_select_action_item()

	//UPDATE ACTION_ITEM
	function db_update_action_item($set,$where)
	{
		db_update_table("action_item",$set,$where);
		
	}//end update action_item	
	
	//DELETE ACTION_ITEM	
	function db_delete_action_item($where)
	{
		db_delete_from_table("action_item",$where);
		
	}//end db_delete_action_item()	
	
	
//AD_REQUEST: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT AD_REQUEST
	function db_insert_ad_request($ad_request)
	{
		db_insert_table("ad_request",$ad_request);
	
	}//END db_insert_ad_request	

	//SELECT AD_REQUESTS (many)
	function db_select_ad_requests($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_ad_request($where,$order_by,$limit,"many");
		
	}//end db_select_ad_requests() many	

	//SELECT AD_REQUEST (one)
	function db_select_ad_request($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." ad_request.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." ad_request.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." ad_request.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `ad_request` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$ad_requests = array();
		foreach ($query->result() as $row)
		{
			$ad_request['id'] = $row->id;
			$ad_request['client_id'] = $row->client_id;
			$ad_request['market_id'] = $row->market_id;
			$ad_request['category'] = $row->category;
			$ad_request['sub_category'] = $row->sub_category;
			$ad_request['content_desc'] = $row->content_desc;
			$ad_request['price'] = $row->price;
			$ad_request['post_expense'] = $row->post_expense;
			$ad_request['min_count'] = $row->min_count;
			
			$ad_requests[] = $ad_request;
			
		}// end foreach
		
		if (empty($ad_request))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $ad_request;
		}
		else if($many == "many")
		{
			return $ad_requests;
		}
	}//end db_select_ad_request()

	//UPDATE AD_REQUEST
	function db_update_ad_request($set,$where)
	{
		db_update_table("ad_request",$set,$where);
		
	}//end update ad_request	
	
	//DELETE AD_REQUEST	
	function db_delete_ad_request($where)
	{
		db_delete_from_table("ad_request",$where);
		
	}//end db_delete_ad_request()	
		
	
	
//AD_SPOT: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT AD_SPOT
	function db_insert_ad_spot($ad_spot)
	{
		db_insert_table("ad_spot",$ad_spot);
	
	}//END db_insert_ad_spot	

	//SELECT AD_SPOTS (many)
	function db_select_ad_spots($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_ad_spot($where,$order_by,$limit,"many");
		
	}//end db_select_ad_spots() many	

	//SELECT AD_SPOT (one)
	function db_select_ad_spot($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." ad_spot.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." ad_spot.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." ad_spot.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `ad_spot` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$ad_spots = array();
		foreach ($query->result() as $row)
		{
			$ad_spot['id'] = $row->id;
			$ad_spot['ad_request_id'] = $row->ad_request_id;
			$ad_spot['value'] = $row->value;
			$ad_spot['post_datetime'] = $row->post_datetime;
			
			$ad_spots[] = $ad_spot;
			
		}// end foreach
		
		if (empty($ad_spot))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $ad_spot;
		}
		else if($many == "many")
		{
			return $ad_spots;
		}
	}//end db_select_ad_spot()

	//UPDATE AD_SPOT
	function db_update_ad_spot($set,$where)
	{
		db_update_table("ad_spot",$set,$where);
		
	}//end update ad_spot	
	
	//DELETE AD_SPOT	
	function db_delete_ad_spot($where)
	{
		db_delete_from_table("ad_spot",$where);
		
	}//end db_delete_ad_spot()	
		
	
	

//CALL: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT CALL
	function db_insert_call($call)
	{
		db_insert_table("call",$call);
	
	}//END db_insert_call	

	//SELECT CALLS (many)
	function db_select_calls($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_call($where,$order_by,$limit,"many");
		
	}//end db_select_calls() many	

	//SELECT CALL (one)
	function db_select_call($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." call.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." call.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." call.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT *
				FROM `call` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$calls = array();
		foreach ($query->result() as $row)
		{
			$call['id'] = $row->id;
			$call['lead_id'] = $row->lead_id;
			$call['user_id'] = $row->user_id;
			$call['transfer_user_id'] = $row->transfer_user_id;
			$call['call_time'] = $row->call_time;
			$call['phone_number'] = $row->phone_number;
			$call['source'] = $row->source;
			$call['outcome'] = $row->outcome;
			$call['notes'] = $row->notes;
			
			$calls[] = $call;
			
		}// end foreach
		
		if (empty($call))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $call;
		}
		else if($many == "many")
		{
			return $calls;
		}
	}//end db_select_call()

	//UPDATE CALL
	function db_update_call($set,$where)
	{
		db_update_table("call",$set,$where);
		
	}//end update call	
	
	//DELETE CALL	
	function db_delete_call($where)
	{
		db_delete_from_table("call",$where);
		
	}//end db_delete_call()	
	
	
	
	
//CALL_UPLOAD: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT CALL_UPLOAD
	function db_insert_call_upload($call_upload)
	{
		db_insert_table("call_upload",$call_upload);
	
	}//END db_insert_call_upload	

	//SELECT CALL_UPLOADS (many)
	function db_select_call_uploads($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_call_upload($where,$order_by,$limit,"many");
		
	}//end db_select_call_uploads() many	

	//SELECT CALL_UPLOAD (one)
	function db_select_call_upload($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." call_upload.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." call_upload.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." call_upload.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$call_uploads = array();
		foreach ($query->result() as $row)
		{
			$call_upload['id'] = $row->id;
			$call_upload['type'] = $row->type;
			$call_upload['phone_number'] = $row->phone_number;
			$call_upload['name'] = $row->name;
			$call_upload['call_time'] = $row->call_time;
			$call_upload['action'] = $row->action;
			$call_upload['action_result'] = $row->action_result;
			$call_upload['result_description'] = $row->result_description;
			$call_upload['duration'] = $row->duration;
			$call_upload['city'] = $row->city;
			$call_upload['state'] = $row->state;
			$call_upload['address'] = $row->address;
			$call_upload['odometer'] = $row->odometer;
			$call_upload['route'] = $row->route;
			$call_upload['miles'] = $row->miles;
			$call_upload['out_of_route'] = $row->out_of_route;
			$call_upload['gallons'] = $row->gallons;
			$call_upload['fuel_expense'] = $row->fuel_expense;
			$call_upload['mpg'] = $row->entry_mpg;
			$call_upload['entry_notes'] = $row->entry_notes;
			
			$call_uploads[] = $call_upload;
			
		}// end foreach
		
		if (empty($call_upload))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $call_upload;
		}
		else if($many == "many")
		{
			return $call_uploads;
		}
	}//end db_select_call_upload()

	//UPDATE CALL_UPLOAD
	function db_update_call_upload($set,$where)
	{
		db_update_table("call_upload",$set,$where);
		
	}//end update call_upload	
	
	//DELETE CALL_UPLOAD	
	function db_delete_call_upload($where)
	{
		db_delete_from_table("call_upload",$where);
		
	}//end db_delete_call()	

	
	
//CLIENT: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT CLIENT
	function db_insert_client($client)
	{
		db_insert_table("client",$client);
	
	}//END db_insert_client	

	//SELECT CLIENTS (many)
	function db_select_clients($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_client($where,$order_by,$limit,"many");
		
	}//end db_select_clients() many	

	//SELECT CLIENT (one)
	function db_select_client($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." client.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." client.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." client.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `client` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$clients = array();
		foreach ($query->result() as $row)
		{
			$client['id'] = $row->id;
			$client['name'] = $row->name;
			$client['email'] = $row->email;
			$client['phone_number'] = $row->phone_number;
			$client['address'] = $row->address;
			$client['city'] = $row->city;
			$client['state'] = $row->state;
			$client['zip_code'] = $row->zip_code;
			$client['contact_f_name'] = $row->contact_f_name;
			$client['contact_l_name'] = $row->contact_l_name;
			
			$clients[] = $client;
			
		}// end foreach
		
		if (empty($client))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $client;
		}
		else if($many == "many")
		{
			return $clients;
		}
	}//end db_select_client()

	//UPDATE CLIENT
	function db_update_client($set,$where)
	{
		db_update_table("client",$set,$where);
		
	}//end update client	
	
	//DELETE CLIENT	
	function db_delete_client($where)
	{
		db_delete_from_table("client",$where);
		
	}//end db_delete_client()	
		
	
	
	
//CURRENT_FORM: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT CURRENT_FORM
	function db_insert_current_form($current_form)
	{
		db_insert_table("current_form",$current_form);
	
	}//END db_insert_current_form	

	//SELECT CURRENT_FORMS (many)
	function db_select_current_forms($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_current_form($where,$order_by,$limit,"many");
		
	}//end db_select_current_forms() many	

	//SELECT CURRENT_FORM (one)
	function db_select_current_form($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." current_form.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." current_form.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." current_form.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT 
				current_form.id as id,
				current_form.date as date,
				current_form.form_number as form_number,
				FROM 'current_form'".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$current_forms = array();
		foreach ($query->result() as $row)
		{
			$current_form['id'] = $row->id;
			$current_form['date'] = $row->date;
			$current_form['form_number'] = $row->form_number;

			$current_forms[] = $current_form;
			
		}// end foreach
		
		if (empty($current_form))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $current_form;
		}
		else if($many == "many")
		{
			return $current_forms;
		}
	}//end db_select_current_form()

	//UPDATE CURRENT_FORM
	function db_update_current_form($set,$where)
	{
		db_update_table("current_form",$set,$where);
		
	}//end update current_form	
	
	//DELETE CURRENT_FORM	
	function db_delete_current_form($where)
	{
		db_delete_from_table("current_form",$where);
		
	}//end db_delete_current_form()	
	
	
		

//LEAD: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT LEAD
	function db_insert_lead($lead)
	{
		db_insert_table("lead",$lead);
	
	}//END db_insert_lead	

	//SELECT leadS (many)
	function db_select_leads($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_lead($where,$order_by,$limit,"many");
		
	}//end db_select_leads() many	

	//SELECT LEAD (one)
	function db_select_lead($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			//$where_sql = " WHERE ";
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." And";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." lead.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." lead.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." lead.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT 
					lead.id as id,
					lead.client_id as client_id,
					lead.application_datetime,
					lead.lead_status,
					lead.first_name,
					lead.m_name,
					lead.last_name,
					CONCAT(lead.first_name,' ',lead.last_name) as full_name,
					lead.phone_number,
					lead.email,
					lead.dob,
					lead.ssn,
					lead.driving_experience,
					lead.drive_team,
					lead.drive_otr,
					lead.availability_date,
					lead.current_address,
					lead.previous_address_1,
					lead.previous_address_2,
					lead.previous_address_3,
					lead.previous_license_number_1,
					lead.previous_license_state_1,
					lead.previous_license_type_1,
					lead.previous_license_exp_date_1,
					lead.previous_license_number_2,
					lead.previous_license_state_2,
					lead.previous_license_type_2,
					lead.previous_license_exp_date_2,
					lead.previous_license_number_3,
					lead.previous_license_state_3,
					lead.previous_license_type_3,
					lead.previous_license_exp_date_3,
					lead.previous_license_number_4,
					lead.previous_license_state_4,
					lead.previous_license_type_4,
					lead.previous_license_exp_date_4,
					lead.accident_date_1,
					lead.accident_nature_1,
					lead.accident_fatalities_1,
					lead.accident_injuries_1,
					lead.accident_date_2,
					lead.accident_nature_2,
					lead.accident_fatalities_2,
					lead.accident_injuries_2,
					lead.accident_date_3,
					lead.accident_nature_3,
					lead.accident_fatalities_3,
					lead.accident_injuries_3,
					lead.previous_job_employer_name_1,
					lead.previous_job_address_1,
					lead.previous_job_position_1,
					lead.previous_job_start_date_1,
					lead.previous_job_end_date_1,
					lead.previous_job_salary_1,
					lead.previous_job_reason_for_leaving_1,
					lead.previous_job_subject_to_fmcsr_1,
					lead.previous_job_drug_test_1,
					lead.previous_job_employer_name_2,
					lead.previous_job_address_2,
					lead.previous_job_position_2,
					lead.previous_job_start_date_2,
					lead.previous_job_end_date_2,
					lead.previous_job_salary_2,
					lead.previous_job_reason_for_leaving_2,
					lead.previous_job_subject_to_fmcsr_2,
					lead.previous_job_drug_test_2,
					lead.previous_job_employer_name_3,
					lead.previous_job_address_3,
					lead.previous_job_position_3,
					lead.previous_job_start_date_3,
					lead.previous_job_end_date_3,
					lead.previous_job_salary_3,
					lead.previous_job_reason_for_leaving_3,
					lead.previous_job_subject_to_fmcsr_3,
					lead.previous_job_drug_test_3,
					lead.tested_positive_or_refused,
					lead.medical_card_link,
					lead.license_link,
					lead.ss_card_link,
					lead.personal_reference_1,
					lead.personal_reference_relationship_1,
					lead.personal_reference_number_1,
					lead.personal_reference_address_1,
					lead.personal_reference_2,
					lead.personal_reference_relationship_2,
					lead.personal_reference_number_2,
					lead.personal_reference_address_2,
					lead.personal_reference_3,
					lead.personal_reference_relationship_3,
					lead.personal_reference_number_3,
					lead.personal_reference_address_3,
					lead.applicant_status_log,
					lead.inbound_or_outbound,
					lead.why_called_in,
					lead.current_city,
					lead.current_state,
					lead.current_zip_code,
					lead.age,
					lead.number_of_tickets,
					lead.ticket_details,
					lead.number_of_accidents,
					lead.accident_details,
					lead.current_license_number,
					lead.current_license_state,
					lead.submitted_to,
					lead.transferred_to,
					lead.user_submitted,
					lead.lead_source_id,
					lead.lead_source_id,
					lead.cdl,
					lead.assigned_recruiter_id,
					lead.credit_score,
					(SELECT MIN(due_date)
                    from lead l2
                    join action_item a
                    on l2.id = a.lead_id
                    WHERE a.lead_id = lead.id
					AND a.completion_date IS NULL) as action_item_due_date
                    FROM lead".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		// echo $sql;
		$query = $CI->db->query($sql,$values);
		$leads = array();
		foreach ($query->result() as $row)
		{
			$lead['id'] = $row->id;
			$lead['client_id'] = $row->client_id;
			$lead['application_datetime'] = $row->application_datetime;
			$lead['lead_status'] = $row->lead_status;
			$lead['first_name'] = $row->first_name;
			$lead['m_name'] = $row->m_name;
			$lead['last_name'] = $row->last_name;
			$lead['phone_number'] = $row->phone_number;
			$lead['email'] = $row->email;
			$lead['dob'] = $row->dob;
			$lead['ssn'] = $row->ssn;
			$lead['driving_experience'] = $row->driving_experience;
			$lead['drive_team'] = $row->drive_team;
			$lead['drive_otr'] = $row->drive_otr;
			$lead['availability_date'] = $row->availability_date;
			$lead['current_address'] = $row->current_address;
			$lead['previous_address_1'] = $row->previous_address_1;
			$lead['previous_address_2'] = $row->previous_address_2;
			$lead['previous_address_3'] = $row->previous_address_3;
			
			$lead['previous_license_number_1'] = $row->previous_license_number_1;
			$lead['previous_license_state_1'] = $row->previous_license_state_1;
			$lead['previous_license_type_1'] = $row->previous_license_type_1;
			$lead['previous_license_exp_date_1'] = $row->previous_license_exp_date_1;
			
			$lead['previous_license_number_2'] = $row->previous_license_number_2;
			$lead['previous_license_state_2'] = $row->previous_license_state_2;
			$lead['previous_license_type_2'] = $row->previous_license_type_2;
			$lead['previous_license_exp_date_2'] = $row->previous_license_exp_date_2;
			
			$lead['previous_license_number_3'] = $row->previous_license_number_3;
			$lead['previous_license_state_3'] = $row->previous_license_state_3;
			$lead['previous_license_type_3'] = $row->previous_license_type_3;
			$lead['previous_license_exp_date_3'] = $row->previous_license_exp_date_4;
			
			$lead['previous_license_number_4'] = $row->previous_license_number_4;
			$lead['previous_license_state_4'] = $row->previous_license_state_4;
			$lead['previous_license_type_4'] = $row->previous_license_type_4;
			$lead['previous_license_exp_date_4'] = $row->previous_license_exp_date_4;
			
			$lead['accident_date_1'] = $row->accident_date_1;
			$lead['accident_nature_1'] = $row->accident_nature_1;
			$lead['accident_fatalities_1'] = $row->accident_fatalities_1;
			$lead['accident_injuries_1'] = $row->accident_injuries_1;
			
			$lead['accident_date_2'] = $row->accident_date_2;
			$lead['accident_nature_2'] = $row->accident_nature_2;
			$lead['accident_fatalities_2'] = $row->accident_fatalities_2;
			$lead['accident_injuries_2'] = $row->accident_injuries_2;
			
			$lead['accident_date_3'] = $row->accident_date_3;
			$lead['accident_nature_3'] = $row->accident_nature_3;
			$lead['accident_fatalities_3'] = $row->accident_fatalities_3;
			$lead['accident_injuries_3'] = $row->accident_injuries_3;
			
			$lead['previous_job_employer_name_1'] = $row->previous_job_employer_name_1;
			$lead['previous_job_address_1'] = $row->previous_job_address_1;
			$lead['previous_job_position_1'] = $row->previous_job_position_1;
			$lead['previous_job_start_date_1'] = $row->previous_job_start_date_1;
			$lead['previous_job_end_date_1'] = $row->previous_job_end_date_1;
			$lead['previous_job_salary_1'] = $row->previous_job_salary_1;
			$lead['previous_job_reason_for_leaving_1'] = $row->previous_job_reason_for_leaving_1;
			$lead['previous_job_subject_to_fmcsr_1'] = $row->previous_job_subject_to_fmcsr_1;
			$lead['previous_job_drug_test_1'] = $row->previous_job_drug_test_1;
			
			$lead['previous_job_employer_name_2'] = $row->previous_job_employer_name_2;
			$lead['previous_job_address_2'] = $row->previous_job_address_2;
			$lead['previous_job_position_2'] = $row->previous_job_position_2;
			$lead['previous_job_start_date_2'] = $row->previous_job_start_date_2;
			$lead['previous_job_end_date_2'] = $row->previous_job_end_date_2;
			$lead['previous_job_salary_2'] = $row->previous_job_salary_2;
			$lead['previous_job_reason_for_leaving_2'] = $row->previous_job_reason_for_leaving_2;
			$lead['previous_job_subject_to_fmcsr_2'] = $row->previous_job_subject_to_fmcsr_2;
			$lead['previous_job_drug_test_2'] = $row->previous_job_drug_test_2;
			
			$lead['previous_job_employer_name_3'] = $row->previous_job_employer_name_3;
			$lead['previous_job_address_3'] = $row->previous_job_address_3;
			$lead['previous_job_position_3'] = $row->previous_job_position_3;
			$lead['previous_job_start_date_3'] = $row->previous_job_start_date_3;
			$lead['previous_job_end_date_3'] = $row->previous_job_end_date_3;
			$lead['previous_job_salary_3'] = $row->previous_job_salary_3;
			$lead['previous_job_reason_for_leaving_3'] = $row->previous_job_reason_for_leaving_3;
			$lead['previous_job_subject_to_fmcsr_3'] = $row->previous_job_subject_to_fmcsr_3;
			$lead['previous_job_drug_test_3'] = $row->previous_job_drug_test_3;
			
			$lead['tested_positive_or_refused'] = $row->tested_positive_or_refused;
			$lead['medical_card_link'] = $row->medical_card_link;
			$lead['license_link'] = $row->license_link;
			$lead['ss_card_link'] = $row->ss_card_link;
			
			$lead['personal_reference_1'] = $row->personal_reference_1;
			$lead['personal_reference_relationship_1'] = $row->personal_reference_relationship_1;
			$lead['personal_reference_number_1'] = $row->personal_reference_number_1;
			$lead['personal_reference_address_1'] = $row->personal_reference_address_1;
			
			$lead['personal_reference_2'] = $row->personal_reference_2;
			$lead['personal_reference_relationship_2'] = $row->personal_reference_relationship_2;
			$lead['personal_reference_number_2'] = $row->personal_reference_number_2;
			$lead['personal_reference_address_2'] = $row->personal_reference_address_2;
			
			$lead['personal_reference_3'] = $row->personal_reference_3;
			$lead['personal_reference_relationship_3'] = $row->personal_reference_relationship_3;
			$lead['personal_reference_number_3'] = $row->personal_reference_number_3;
			$lead['personal_reference_address_3'] = $row->personal_reference_address_3;
			
			$lead['applicant_status_log'] = $row->applicant_status_log;
			
			$lead['inbound_or_outbound'] = $row->inbound_or_outbound;
			$lead['why_called_in'] = $row->why_called_in;
			$lead['current_city'] = $row->current_city;
			$lead['current_state'] = $row->current_state;
			$lead['current_zip_code'] = $row->current_zip_code;
			$lead['age'] = $row->age;
			$lead['number_of_tickets'] = $row->number_of_tickets;
			$lead['ticket_details'] = $row->ticket_details;
			$lead['number_of_accidents'] = $row->number_of_accidents;
			$lead['accident_details'] = $row->accident_details;
			$lead['current_license_number'] = $row->current_license_number;
			$lead['current_license_state'] = $row->current_license_state;
			$lead['submitted_to'] = $row->submitted_to;
			$lead['transferred_to'] = $row->transferred_to;
			$lead['user_submitted'] = $row->user_submitted;
			$lead['lead_source_id'] = $row->lead_source_id;
			$lead['cdl'] = $row->cdl;
			$lead['assigned_recruiter_id'] = $row->assigned_recruiter_id;
			$lead['credit_score'] = $row->credit_score;
			
			$lead['action_item_due_date'] = $row->action_item_due_date;
			
			$leads[] = $lead;
			
		}// end foreach
		
		if (empty($lead))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $lead;
		}
		else if($many == "many")
		{
			return $leads;
		}
	}//end db_select_lead()

	//UPDATE LEAD
	function db_update_lead($set,$where)
	{
		db_update_table("lead",$set,$where);
		
	}//end update lead	
	
	//DELETE LEAD	
	function db_delete_lead($where)
	{
		db_delete_from_table("lead",$where);
		
	}//end db_delete_lead()

	
	
//LEAD_SOURCE: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT LEAD_SOURCE
	function db_insert_lead_source($lead_source)
	{
		db_insert_table("lead_source",$lead_source);
	
	}//END db_insert_lead_source	

	//SELECT LEAD_SOURCES (many)
	function db_select_lead_sources($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_lead_source($where,$order_by,$limit,"many");
		
	}//end db_select_lead_sources() many	

	//SELECT LEAD_SOURCE (one)
	function db_select_lead_source($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." lead_source.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." lead_source.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." lead_source.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT *
				FROM `lead_source` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$lead_sources = array();
		foreach ($query->result() as $row)
		{
			$lead_source['id'] = $row->id;
			$lead_source['source_name'] = $row->source_name;
			$lead_source['source_description'] = $row->source_description;
			
			$lead_sources[] = $lead_source;
			
		}// end foreach
		
		if (empty($lead_source))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $lead_source;
		}
		else if($many == "many")
		{
			return $lead_sources;
		}
	}//end db_select_lead_source()

	//UPDATE LEAD_SOURCE
	function db_update_lead_source($set,$where)
	{
		db_update_table("lead_source",$set,$where);
		
	}//end update lead_source	
	
	//DELETE LEAD_SOURCE	
	function db_delete_lead_source($where)
	{
		db_delete_from_table("lead_source",$where);
		
	}//end db_delete_lead_source()	
	
	
//MARKET: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT MARKET
	function db_insert_market($market)
	{
		db_insert_table("market",$market);
	
	}//END db_insert_market	

	//SELECT MARKETS (many)
	function db_select_markets($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_market($where,$order_by,$limit,"many");
		
	}//end db_select_markets() many	

	//SELECT MARKET (one)
	function db_select_market($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." market.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." market.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." market.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `market` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$markets = array();
		foreach ($query->result() as $row)
		{
			$market['id'] = $row->id;
			$market['name'] = $row->name;
			$market['state'] = $row->state;
			$market['link'] = $row->link;
			$market['phone_number'] = $row->phone_number;
			
			$markets[] = $market;
			
		}// end foreach
		
		if (empty($market))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $market;
		}
		else if($many == "many")
		{
			return $markets;
		}
	}//end db_select_market()

	//UPDATE MARKET
	function db_update_market($set,$where)
	{
		db_update_table("market",$set,$where);
		
	}//end update market	
	
	//DELETE MARKET	
	function db_delete_market($where)
	{
		db_delete_from_table("market",$where);
		
	}//end db_delete_market()	
	
		

//MARKET_RELATIONSHIP: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT MARKET_RELATIONSHIP
	function db_insert_market_relationship($market_relationship)
	{
		db_insert_table("market_relationship",$market_relationship);
	
	}//END db_insert_market_relationship	

	//SELECT MARKET_RELATIONSHIPS (many)
	function db_select_market_relationships($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_market_relationship($where,$order_by,$limit,"many");
		
	}//end db_select_market_relationships() many	

	//SELECT MARKET_RELATIONSHIP (one)
	function db_select_market_relationship($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." market_relationship.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." market_relationship.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." market_relationship.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT *
				FROM `market_relationship` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$market_relationships = array();
		foreach ($query->result() as $row)
		{
			$market_relationship['id'] = $row->id;
			$market_relationship['market_id'] = $row->market_id;
			$market_relationship['related_market_id'] = $row->related_market_id;
			
			$market_relationships[] = $market_relationship;
			
		}// end foreach
		
		if (empty($market_relationship))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $market_relationship;
		}
		else if($many == "many")
		{
			return $market_relationships;
		}
	}//end db_select_market_relationship()

	//UPDATE MARKET_RELATIONSHIP
	function db_update_market_relationship($set,$where)
	{
		db_update_table("market_relationship",$set,$where);
		
	}//end update market_relationship	
	
	//DELETE MARKET_RELATIONSHIP	
	function db_delete_market_relationship($where)
	{
		db_delete_from_table("market_relationship",$where);
		
	}//end db_delete_market_relationship()	
	
			
		
//POST: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT POST
	function db_insert_post($post)
	{
		db_insert_table("post",$post);
	
	}//END db_insert_post	

	//SELECT POSTS (many)
	function db_select_posts($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_post($where,$order_by,$limit,"many");
		
	}//end db_select_posts() many	

	//SELECT POST (one)
	function db_select_post($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." post.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." post.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." post.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `post` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$posts = array();
		foreach ($query->result() as $row)
		{
			$post['id'] = $row->id;
			$post['ad_request_id'] = $row->ad_request_id;
			$post['result_user_id'] = $row->result_user_id;
			$post['poster_id'] = $row->poster_id;
			$post['post_datetime'] = $row->post_datetime;
			$post['post_exp_datetime'] = $row->post_exp_datetime;
			$post['link'] = $row->link;
			$post['title'] = $row->title;
			$post['content'] = $row->content;
			$post['phone_number'] = $row->phone_number;
			$post['is_renewal'] = $row->is_renewal;
			$post['next_renewal_datetime'] = $row->next_renewal_datetime;
			$post['renewal_datetime'] = $row->renewal_datetime;
			$post['result'] = $row->result;
			$post['result_screen_shot_guid'] = $row->result_screen_shot_guid;
			$post['result_datetime'] = $row->result_datetime;
			$post['amount_due'] = $row->amount_due;
			
			$posts[] = $post;
			
		}// end foreach
		
		if (empty($post))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $post;
		}
		else if($many == "many")
		{
			return $posts;
		}
	}//end db_select_post()

	//UPDATE POST
	function db_update_post($set,$where)
	{
		db_update_table("post",$set,$where);
		
	}//end update post	
	
	//DELETE POST	
	function db_delete_post($where)
	{
		db_delete_from_table("post",$where);
		
	}//end db_delete_post()	
	
	
//SECRET_CODE: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT SECRET_CODE
	function db_insert_secret_code($secret_code)
	{
		db_insert_table("secret_code",$secret_code);
	
	}//END db_insert_secret_code	

	//SELECT SECRET_CODES (many)
	function db_select_secret_codes($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_secret_code($where,$order_by,$limit,"many");
		
	}//end db_select_secret_codes() many	

	//SELECT SECRET_CODE (one)
	function db_select_secret_code($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." secret_code.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." secret_code.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." secret_code.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `secret_code` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$secret_codes = array();
		foreach ($query->result() as $row)
		{
			$secret_code['id'] = $row->id;
			$secret_code['referral_id'] = $row->referral_id;
			$secret_code['secret_code'] = $row->secret_code;
			$secret_code['datetime_created'] = $row->datetime_created;
			$secret_code['datetime_used'] = $row->datetime_used;
			
			$secret_codes[] = $secret_code;
			
		}// end foreach
		
		if (empty($secret_code))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $secret_code;
		}
		else if($many == "many")
		{
			return $secret_codes;
		}
	}//end db_select_secret_code()

	//UPDATE SECRET_CODE
	function db_update_secret_code($set,$where)
	{
		db_update_table("secret_code",$set,$where);
		
	}//end update secret_code	
	
	//DELETE SECRET_CODE	
	function db_delete_secret_code($where)
	{
		db_delete_from_table("secret_code",$where);
		
	}//end db_delete_secret_code()	
		
	
	
	
//SECURE_FILE: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT SECURE_FILE
	function db_insert_secure_file($secure_file)
	{
		db_insert_table("secure_file",$secure_file);
	
	}//END db_insert_secure_file	

	//SELECT SECURE_FILES (many)
	function db_select_secure_files($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_secure_file($where,$order_by,$limit,"many");
		
	}//end db_select_secure_files() many	

	//SELECT SECURE_FILE (one)
	function db_select_secure_file($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." And";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." secure_file.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." secure_file.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." secure_file.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT * FROM `secure_file` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//echo $sql;
		$query = $CI->db->query($sql,$values);
		$secure_files = array();
		foreach ($query->result() as $row)
		{
			$secure_file['id'] = $row->id;
			$secure_file['file_guid'] = $row->file_guid;
			$secure_file['name'] = $row->name;
			$secure_file['type'] = $row->type;
			$secure_file['title'] = $row->title;
			$secure_file['category'] = $row->category;
			$secure_file['server_path'] = $row->server_path;
			$secure_file['permission'] = $row->permission;
			
			$secure_files[] = $secure_file;
			
		}// end foreach
		
		if (empty($secure_file))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $secure_file;
		}
		else if($many == "many")
		{
			return $secure_files;
		}
	}//end db_select_secure_file()

	//UPDATE SECURE_FILE
	function db_update_secure_file($set,$where)
	{
		db_update_table("secure_file",$set,$where);
		
	}//end update secure_file	
	
	//DELETE SECURE_FILE	
	function db_delete_secure_file($where)
	{
		db_delete_from_table("secure_file",$where);
		
	}//end db_delete_secure_file()	
		
	
	
	
	
//SETTING: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT SETTING
	function db_insert_setting($setting)
	{
		db_insert_table("setting",$setting);
	
	}//END db_insert_setting	

	//SELECT SETTINGS (many)
	function db_select_settings($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_setting($where,$order_by,$limit,"many");
		
	}//end db_select_settings() many	

	//SELECT SETTING (one)
	function db_select_setting($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." setting.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." setting.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." setting.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT *
				FROM `setting` ".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$settings = array();
		foreach ($query->result() as $row)
		{
			$setting['id'] = $row->id;
			$setting['tab'] = $row->tab;
			$setting['category'] = $row->category;
			$setting['access_level'] = $row->access_level;
			$setting['setting_name'] = $row->setting_name;
			$setting['setting_value'] = $row->setting_value;
			$setting['setting_notes'] = $row->setting_notes;
			
			$settings[] = $setting;
			
		}// end foreach
		
		if (empty($setting))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $setting;
		}
		else if($many == "many")
		{
			return $settings;
		}
	}//end db_select_setting()

	//UPDATE SETTING
	function db_update_setting($set,$where)
	{
		db_update_table("setting",$set,$where);
		
	}//end update setting	
	
	//DELETE SETTING	
	function db_delete_setting($where)
	{
		db_delete_from_table("setting",$where);
		
	}//end db_delete_setting()	
	
	
	
	
//USER: INSERT, SELECT (many), SELECT (one), UPDATE, DELETE

	//INSERT USER
	function db_insert_user($user)
	{
		db_insert_table("user",$user);
	
	}//END db_insert_user	

	//SELECT USERS (many)
	function db_select_users($where,$order_by = 'id',$limit = 'all')
	{
		return db_select_user($where,$order_by,$limit,"many");
		
	}//end db_select_users() many	

	//SELECT user (one)
	function db_select_user($where,$order_by = 'id',$limit = 'all',$many = 'one')
	{
		$CI =& get_instance();
		$i = 0;
		$where_sql = "";
		if(!empty($where))
		{
			$where_sql = " WHERE ";
		}
		$values = array();
		if(is_array($where))
		{
			$i = 0;
			$values = array();
			foreach($where as $key => $value)
			{
				
				if ($i > 0)
				{
					$where_sql = $where_sql." AND";
				}
				
				if ($value == null)
				{
					$where_sql = $where_sql." user.".$key." is ?";
				}
				else if (substr($value,0,1) == "%" || substr($value,-1) == "%") //IF VALUE START OR ENDS WITH A %
				{
					$where_sql = $where_sql." user.".$key." LIKE ?";
				}
				else
				{
					$where_sql = $where_sql." user.".$key." = ?";
				}
				$values[$i] = $value;
				//echo "value[$i] = $value ";
				$i++;
			}
		}
		else
		{
			$where_sql = $where_sql.$where;
		}
		
		$limit_txt = "";
		if($limit != "all")
		{
			$limit_txt = " LIMIT ".$limit;
		}
		
		$sql = "SELECT *
				FROM `user`".$where_sql." ORDER BY ".$order_by.$limit_txt;
		
		// echo $sql;
		//error_log($sql." | LINE ".__LINE__." ".__FILE__);
		$query = $CI->db->query($sql,$values);
		$users = array();
		
		//$user = null;
		foreach ($query->result() as $row)
		{
			$user["id"] = $row->id;
			$user["first_name"] = $row->first_name;
			$user["last_name"] = $row->last_name;
			$user["username"] = $row->username;
			$user["password"] = $row->password;
			$user["email"] = $row->email;
			$user["role"] = $row->role;
			$user["home_market"] = $row->home_market;
			$user["referral_id"] = $row->referral_id;
			$user["referred_by"] = $row->referred_by;
			$user["date_joined"] = $row->date_joined;
			$user["first_post_datetime"] = $row->first_post_datetime;
			$user["is_active"] = $row->is_active;
			$user["ip_address"] = $row->ip_address;
			$user["geolocation"] = $row->geolocation;
			
			$users[] = $user;
			//echo "hello world ";
		}//end foreach	
		
		
		if (empty($user))
		{
			return null;
		}
		else if($many == 'one')
		{
			return $user;
		}
		else if($many == "many")
		{
			return $users;
		}
	}//end db_select_user()

	//UPDATE USER
	function db_update_user($set,$where)
	{
		db_update_table("user",$set,$where);
		
	}//end update user	
	
	//DELETE USER	
	function db_delete_user($where)
	{
		db_delete_from_table("user",$where);
		
	}//end db_delete_user()	
	
	
	

	
