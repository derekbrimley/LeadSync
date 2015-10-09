<?php		

	
class MY_Controller extends CI_Controller 
{
	
	function MY_Controller()
	{
		parent::__construct();
		
		$current_user = $this->session->userdata('username');
		
		if ($current_user == "")
		{
			redirect(base_url("/index.php/login"));
			//redirect("www.google.com");
		}
	}
	function time_out()
	{
		date_default_timezone_set('America/Denver');
		if(null!==$this->session->userdata('last_activity') && (time() - $this->session->userdata('last_activity') > 60*60))
		{
			$this->logout();
		}
		$this->session->userdata('last_activity');
	}

}

?>