<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function get_random_string($length, $valid_chars = "abcdefghijklmnopqrstuvwxyz0123456789")
	{
		// start with an empty random string
		$random_string = "";

		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick-1];

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

		// return our finished random string
		return $random_string;
	}
		
	//STORE FILE TO FTP SERVER - INPUT NAME (POST NAME), GIVEN PATH, OFFICE_PERMISSION, AND DRIVER_PERMISSION - RETURNS SECURE_FILE
	function store_secure_ftp_file($input_name,$name,$type,$title,$category,$local_path,$server_path,$permission)
	{
		$CI =& get_instance();
		
		echo '<br>'/ENVIRONMENT;
		
		//DEFAULT TITLE
		if(empty($title))
		{
			$title = "File";
		}
		
		//GET EXTENTION
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$name_wo_ext = substr($name,0,strrpos($name,"."));
		$clean_name = preg_replace("/[^A-Za-z0-9 ]/", '', $name_wo_ext);
		$name = $clean_name.".".$ext;
		//error_log("clean_name ".$clean_name." ext ".$ext." | LINE ".__LINE__." ".__FILE__);
		
		//SET SERVER PATH ACCORDING TO ENVIRONMENT
		$server_path = $server_path.ENVIRONMENT."/";
		
		$file_guid = get_random_string(5);
	
		//$secure_file = null;
		$secure_file['name'] = $name;
		$secure_file['type'] = $type;
		$secure_file['title'] = $title;
		$secure_file['category'] = $category;
		$secure_file['file_guid'] = $file_guid;
		$secure_file['server_path'] = $server_path;
		$secure_file['permission'] = $permission;
		db_insert_secure_file($secure_file);
		
		//GET NEWLY INSERTED SECURE FILE
		$where = null;
		$where["file_guid"] = $file_guid;
		$where["name"] = $name;
		$new_secure_file = db_select_secure_file($where);
		
		//UPDATE GUID WITH ID APPENDED TO BEGINNING
		$update_file = null;
		$update_file["file_guid"] = $new_secure_file["id"].$file_guid;
		$update_file["name"] = "(".$new_secure_file["id"].")".$name;
		
		$where = null;
		$where["id"] = $new_secure_file["id"];
		db_update_secure_file($update_file,$where);
		
		//SELECT NEWLY UPDATED SECURE FILE
		$where = null;
		$where["id"] = $new_secure_file["id"];
		$new_secure_file = db_select_secure_file($where);
		
		$full_path = $server_path."/".$new_secure_file["name"];
		
		//SET UP CONNECTION TO FTP SERVER
		$CI->load->library('ftp');
		$config['hostname'] = 'sheep.arvixe.com';
		$config['username'] = 'nextgen';
		$config['password'] = 'retret13';
		$config['debug']	= TRUE;
		$CI->ftp->connect($config);
				
		error_log("Local path: ".$local_path." Full Path: ".$full_path." | LINE ".__LINE__." ".__FILE__);
		//GETS FILES TO SECURE FTP LOCATION DIFFERENTLY BASED ON ENVIRONMENT
		if(ENVIRONMENT == 'development')
		{
			//UPLOAD FILE TO FTP SERVER
			$CI->ftp->upload($local_path,$full_path, 'auto', 0775);
		}
		else if(ENVIRONMENT == 'production')
		{
			//LOAD UPLOAD LIBRARY
			$config = null;
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['remove_spaces'] = TRUE;
			//$config['max_size']	= '1000';
			$CI->load->library('upload', $config);
			
			//UPLOAD FILE TO PUBLIC UPLOADS FOLDER AND CHECK FOR ERRORS
			if ( ! $CI->upload->do_upload($input_name))
			{
				echo $CI->upload->display_errors($input_name);
			}
			else //IF UPLOAD WAS A SUCCESS
			{
				//GET FILE DATA
				$file = $CI->upload->data($input_name);
				//echo $file["full_path"];
			
				//MOVE FILE FROM PUBLIC UPLOADS FOLDER TO SECURE LOCATION
				$CI->ftp->move('/public_html/uploads'.'/'.$file["file_name"], $full_path);
				
				//echo '<br>success';
			}
		}
		return $new_secure_file;
	}
	
	//GET FILE FROM FTP SERVER - GIVEN FILE GUID, RETURNS READ_FILE HEADERS TO CLIENT BROWSER
	function get_secure_ftp_file($file_guid)
	{
		$CI =& get_instance();
	
		//SET UP CONNECTION TO FTP SERVER
		$CI->load->library('ftp');
		
		$config['hostname'] = 'sheep.arvixe.com';
		$config['username'] = 'nextgen';
		$config['password'] = 'retret13';
		$config['debug']	= TRUE;
		
		$CI->ftp->connect($config);
		
		//GET LIST OF FILE IN THE PUBLIC FOLDER
		$public_folder = "/public_html_adsync/temp_files_for_download/".ENVIRONMENT;
		$files = $CI->ftp->list_files($public_folder);
		
		//MOVE ANY LEFT OVER FILES IN THE PUBLIC FOLDER BACK TO THE PRIVATE FOLDER
		foreach($files as $file)
		{
			//echo $file."<br>";
			//GET SECURE FILE
			$this_file = null;
			$where = null;
			$where["name"] = $file;
			$this_file = db_select_secure_file($where);
			
			if(!empty($this_file))
			{
				$full_path = $this_file["server_path"].$this_file["name"];
		
				//MOVE FILE BACK FROM PUBLICLY ACCESSIBLE FOLDER TO NON ACCESSABLE FOLDER
				$CI->ftp->move('/public_html_adsync/temp_files_for_download'.'/'.ENVIRONMENT.'/'.$this_file["name"], $full_path);
				//echo "moved<br>";
			}
		}
		
		//GET SECURE_FILE FROM DB
		$where = null;
		$where["file_guid"] = $file_guid;
		$secure_file = db_select_secure_file($where);
		
		
		if(user_has_file_access($secure_file))
		{
			$full_path = $secure_file["server_path"].$secure_file["name"];
			// echo $full_path;
			
			
			//***** EVEN IN THE DEV ENVIRONMENT THIS ACTUALLY USES THE LIVE SERVER TO UPLOAD AND DOWNLOAD FILES
			
			//MOVE FILE FROM NON ACCESSIBLE FOLDER TO PUBLICLY ACCESSABLE FOLDER
			$CI->ftp->move($full_path, '/public_html_adsync/temp_files_for_download'.'/'.ENVIRONMENT.'/'.$secure_file["name"]);
			//$CI->ftp->move('/public_html/temp_files_for_download/temp_tester.pdf', '/edocuments/tester.pdf');
			
			//GET FILE EXTENSION
			$ext_pos = strpos($secure_file["name"],".");
			$len = strlen($secure_file["name"]);
			$ext = substr($secure_file["name"],($ext_pos-$len));
		
			$url = 'http://adsync.nextgenmarketingsolutions.com/temp_files_for_download'.'/'.ENVIRONMENT.'/'.$secure_file["name"];
			
			header("Content-Description: File Transfer");
			header('Content-type: '.$secure_file["type"]);
			header('Content-Disposition: inline; filename="'.$secure_file["title"].$ext.'"');
			header("Cache-Control: no-cache, must-revalidate");
			readfile($url);
			
			//header("Location: ".'http://fleetsmarts.net/temp_files_for_download'.'/'.ENVIRONMENT.'/'.$secure_file["name"]);
			//MOVE FILE BACK FROM PUBLICLY ACCESSIBLE FOLDER TO NON ACCESSABLE FOLDER
			$CI->ftp->move('/public_html_adsync/temp_files_for_download'.'/'.ENVIRONMENT.'/'.$secure_file["name"], $full_path);
			
			$CI->ftp->close();
		}
		else
		{
			echo "You do not have file access!";
		}
	}
	
	//DETERMINE IS USER HAS FILE ACCESS
	function user_has_file_access($secure_file)
	{
		$CI =& get_instance();
		
		//GET USER AND ROLE
		$user_id = $CI->session->userdata('user_id');
		$role = $CI->session->userdata('role');
		
		if($role == 'Client'){
			if($secure_file["permission"] == 'None')
			{
				return false;
			}
			else if($secure_file["permission"] == 'All')
			{
				return true;
			}
			else if($secure_file["permission"] == 'Client')
			{
				return true;
			}
		}
		else{
			return true;
		}
		
	}	
	
	//LOAD SUCCESS VIEW	
	function load_upload_success_view()
	{
		$CI =& get_instance();
		
		$data["title"] = "Upload Success";
		$CI->load->view('upload_success_view',$data);
	}