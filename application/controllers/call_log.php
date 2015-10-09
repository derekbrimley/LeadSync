<?php
class Call_Log extends MY_Controller {
	
	function index()
	{
		$data['title'] = 'Upload Call Log';
		$this->load->view('upload_call_log_view',$data);
	}
	
	function upload_call_log()
	{
		$config = null;
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '1000';
		
		$this->load->library('upload', $config);
		
		//IF ERRORS
		if ( ! $this->upload->do_upload())
		{
			echo $this->upload->display_errors();
		}
		else //SUCCESS
		{
			$file = $this->upload->data();
			$file_name = $file["file_name"];
			
			//PARSE THROUGH CSV FILE
			
			$csv_doc = fopen("./uploads/$file_name", "r");
			$row_number = 1;
			//FOREACH ROW
			while (($row = fgetcsv($csv_doc)) !== false) 
			{
				echo "<br>";
				// echo $row_number;
				if($row_number > 1)
				{
					$column = 1;
					//FOR EACH CELL
					foreach ($row as $cell) 
					{
						if($column == 1)
						{
							$type = htmlspecialchars($cell);
						}
						else if($column == 2)
						{
							$phone_number = htmlspecialchars($cell);
						}
						else if($column == 3)
						{
							$name = htmlspecialchars($cell);
						}
						elseif($column == 4)
						{
							$date = htmlspecialchars($cell);
						}
						elseif($column == 5)
						{
							$time = htmlspecialchars($cell);
						}
						elseif($column == 6)
						{
							$action = htmlspecialchars($cell);
						}
						elseif($column == 7)
						{
							$action_result = htmlspecialchars($cell);
						}
						elseif($column == 8)
						{
							$result_description = htmlspecialchars($cell);
						}
						elseif($column == 9)
						{
							$duration = htmlspecialchars($cell);
						}
						
						$column++;
					}//END COLUMN
					
					//GET FORMATTED ADDRESS FROM GOOGLE API
					
					//BUILD TRUCK_STOP ARRAY
					$new_call = null;
					$new_call["type"] = $type;
					$new_call["phone_number"] = $phone_number;
					$new_call["name"] = $name;
					$new_call["call_time"] = substr($date,4).' '.$time;
					$new_call["action"] = $action;
					$new_call["action_result"] = $action_result;
					$new_call["result_description"] = $result_description;
					$new_call["duration"] = $duration;

					db_insert_call($new_call);
					
				}//END ROW
				$row_number++;
				
			}
			fclose($csv_doc);

			
		}
	}
	

}