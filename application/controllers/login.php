<?php		

	
class Login extends CI_Controller{

	function index()
	{
		$data['title'] = "Login";
		$this->load->view('leadsync_login_view',$data);
	}
	
	function create_new_user()
	{
		date_default_timezone_set('America/Denver');
		$current_datetime = date("Y-m-d H:i:s");
		$joined_datetime = date("Y-m-d H:i:s",$current_datetime);
		
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$secret_code = $_POST['secret_code'];
		
		if(strpos($email,"@"))
		{
			$email = substr($email, 0, strpos($email,"@"));
		}
		
		$full_email = $email."@gmail.com";
		
		$role = "affiliate";
		$home_market_id = $_POST['market_id'];
		$referred_by = $_POST['referral_id'];
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$geolocation = $latitude.", ".$longitude;
		// echo $ip_address;
		// echo $geolocation;
		
		$where = null;
		$where = "1 = 1";
		$current_users = db_select_users($where);
		
		$ip_list = array();
		$geolocation_list = array();
		$username_list = array();
		$email_list = array();
		foreach($current_users as $current_user)
		{
			$ip_list[] = $current_user['ip_address'];
			$geolocation_list[] = $current_user['geolocation'];
			$username_list[] = $current_user['username'];
			$email_list[] = $current_user['email'];
		}
		
		$where = null;
		$where['datetime_used'] = "NULL";
		$codes = db_select_secret_codes($where);
		print_r($codes);
		$code_list = array();
		foreach($codes as $code)
		{
			$code_list[] = $code['secret_code'];
		}
		
		if(in_array($full_email,$email_list))
		{
			echo "<script>alert('We are sorry. It appears that your email has already been used for an AdSync account. Please try again.');
					window.location.replace('".base_url('/index.php/login/new_user')."');
				</script>";
		}
		else if(in_array($username,$username_list))
		{
			echo "<script>alert('We are sorry. It appears that your username has already been used for an AdSync account. Please try again.');
					window.location.replace('".base_url('/index.php/login/new_user')."');
				</script>";
		}
		else if(in_array($ip_address,$ip_list) || in_array($geolocation,$geolocation_list))
		{
			echo "<script>alert('We are sorry. It appears that your computer has already been used for an AdSync account.');
					window.location.replace('".base_url('/index.php/login/new_user')."');
				</script>";
		}
		else if(!in_array($secret_code,$code_list))
		{
			echo "<script>alert('We are sorry. It appears that the code you entered is incorrect. Please try again.');
					window.location.replace('".base_url('/index.php/login/new_user')."');
				</script>";
		}
		else
		{
			if(!empty($referred_by))
			{
				$where = null;
				$where['referral_id'] = $referred_by;
				$referred_by_user = db_select_user($where);
				$referred_by_user_id = $referred_by_user['id'];
				$new_user['referred_by'] = $referred_by_user_id;
			}
			
			
			$new_user['first_name'] = $first_name;
			$new_user['last_name'] = $last_name;
			$new_user['username'] = $username;
			$new_user['password'] = $password;
			$new_user['email'] = $full_email;
			$new_user['role'] = $role;
			$new_user['home_market'] = $home_market_id;
			$new_user['date_joined'] = $joined_datetime;
			$new_user['is_active'] = "false";
			$new_user['ip_address'] = $ip_address;
			$new_user['geolocation'] = $geolocation;
			
			db_insert_user($new_user);
			
			$user = db_select_user($new_user);
			
			$to = $full_email;
			$subject = "AdSync Confirmation Email";
			$message = "Click here to confirm your new AdSync Account";
			$headers = 'From: admin@nextgenmarketingsolutions.com';
			
			mail($to,$subject,$message,$headers);
			
			$this->session->set_userdata('user_id', $user['id']);
			$this->session->set_userdata('first_name', $user["first_name"]);
			$this->session->set_userdata('last_name', $user["last_name"]);
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('role', $user['role']);
			$this->session->set_userdata('is_active', $user['is_active']);
			$this->session->set_userdata('referral_id', $user['referral_id']);
			
			$where = null;
			$where['secret_code'] = $secret_code;
			
			$set = array();
			$set['datetime_used'] = $current_datetime;
			
			db_update_secret_code($set,$where);
			
			redirect("ads");
		}
	}
	
	function leadsync_login()
	{
		$data['title'] = "LeadSync Login";
		$this->load->view('leadsync_login_view',$data);
	}
	
	function adsync_login()
	{
		$data['title'] = "AdSync Login";
		$this->load->view('adsync_login_view',$data);
	}
	
	function adsync_authenticate()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$user_where['username'] = $username;
		@$user = db_select_user($user_where);
		
		$role = $user['role'];
		//echo $role;
		if (empty($user['password']))
		{
			echo "Invalid credentials";
		}
		elseif ($user['password'] == $password)
		{
			$this->session->set_userdata('user_id', $user['id']);
			$this->session->set_userdata('first_name', $user["first_name"]);
			$this->session->set_userdata('last_name', $user["last_name"]);
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('role', $user['role']);
			$this->session->set_userdata('is_active', $user['is_active']);
			$this->session->set_userdata('referral_id', $user['referral_id']);
				
			if($role == "admin" || $role == "affiliate" || $role == "manager")
			{
				redirect("ads");
			}
			else
			{
				redirect("leads");
			}
		}
		else
		{
			echo "Invalid credentials";
		}
	}
	
	function authenticate()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		//$sql = "SELECT * FROM people WHERE Username = ?";
		//$query = $this->db->query($sql,array($username));
		
		$user_where['username'] = $username;
		@$user = db_select_user($user_where);
		
		$role = $user['role'];
		
		if (empty($user['password']))
		{
			echo "Invalid credentials";
		}
		elseif ($user['password'] == $password)
		{
			$this->session->set_userdata('user_id', $user['id']);
			$this->session->set_userdata('first_name', $user["first_name"]);
			$this->session->set_userdata('last_name', $user["last_name"]);
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('role', $user['role']);
			$this->session->set_userdata('is_active', $user['is_active']);
			$this->session->set_userdata('referral_id', $user['referral_id']);
				
			if($role == "admin" || $role == "recruiter" || $role == "caller" || $role == "manager")
			{
				redirect("leads");
			}
			else
			{
				redirect("ads");
			}
		}
		else
		{
			echo "Invalid credentials";
		}
	}
	
	function logout()
	{
		$this->session->sess_destroy();
        redirect(base_url("index.php/login"));
	}
	
	function new_user()
	{
		$where = null;
		$where = "1 = 1";
		$markets = db_select_markets($where,"name ASC");
		
		$data['markets'] = $markets;
		$data['title'] = "New User";
		$this->load->view('new_user_view',$data);
	}
	
}
?>