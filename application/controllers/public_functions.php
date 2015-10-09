<?php		

	
class Public_functions extends CI_Controller 
{
	//CRON JOBS
	function refresh_post_board()
	{
		
		date_default_timezone_set('America/Denver');
		$current_datetime = strtotime(date("Y-m-d H:i:s"));
		
		$where = null;
		$where['setting_name'] = "currently live";
		$currently_live_setting = db_select_setting($where);
		
		$where = null;
		$where['setting_name'] = "48-72";
		$three_days_setting = db_select_setting($where);
		
		$where = null;
		$where['setting_name'] = "72-96";
		$four_days_setting = db_select_setting($where);
		
		$where = null;
		$where['setting_name'] = "96-120";
		$five_days_setting = db_select_setting($where);
		
		$where = null;
		$where['setting_name'] = "120-30";
		$month_setting = db_select_setting($where);
		
		$where = null;
		$where['setting_name'] = ">30";
		$over_month_setting = db_select_setting($where);
		
		$where = null;
		$where = "1 = 1";
		$ad_spots = db_select_ad_spots($where);
		
		if(!empty($ad_spots))
		{
			foreach($ad_spots as $ad_spot)
			{
				$where = null;
				$where['id'] = $ad_spot['id'];
				db_delete_ad_spot($where);
			}
			
		}
		
		$where = null;
		$where = "1 = 1";
		$ad_requests = db_select_ad_requests($where);
		
		
		if(!empty($ad_requests))
		{
			foreach($ad_requests as $ad_request)
			{
				$where = null;
				$where['ad_request_id'] = $ad_request['id'];
				$posts = db_select_posts($where);
				
				$post_expense = $ad_request['post_expense'];
				if(!empty($posts))
				{
					foreach($posts as $post)
					{
						$post_datetime = $post['post_datetime'];
						
						//IF DATE POSTED IS LESS THAN 48 HOURS FROM CURRENT DATETIME
						if(strtotime($post_datetime." + 48 hours") >= $current_datetime)
						{
							//MULTIPLY BY CURRENTLY LIVE SETTING
							$post_expense = $post_expense * $currently_live_setting['setting_value'];
							// echo "48";
						}
						//IF DATE POSTED IS LESS THAN 72 HOURS FROM CURRENT DATETIME
						else if(strtotime($post_datetime." + 72 hours") >= $current_datetime)
						{
							//MULTIPLY BY CURRENTLY LIVE SETTING
							$post_expense = $post_expense * $three_days_setting['setting_value'];
							// echo $three_days_setting['setting_value'];
						}
						//IF DATE POSTED IS LESS THAN 96 HOURS FROM CURRENT DATETIME
						else if(strtotime($post_datetime." + 96 hours") >= $current_datetime)
						{
							//MULTIPLY BY CURRENTLY LIVE SETTING
							$post_expense = $post_expense * $four_days_setting['setting_value'];
							// echo "96";
						}
						//IF DATE POSTED IS LESS THAN 120 HOURS FROM CURRENT DATETIME
						else if(strtotime($post_datetime." + 120 hours") >= $current_datetime)
						{
							//MULTIPLY BY CURRENTLY LIVE SETTING
							$post_expense = $post_expense * $five_days_setting['setting_value'];
							// echo "120";
						}
						//IF DATE POSTED IS LESS THAN 30 DAYS FROM CURRENT DATETIME
						else if(strtotime($post_datetime." + 30 days") >= $current_datetime)
						{
							//MULTIPLY BY CURRENTLY LIVE SETTING
							$post_expense = $post_expense * $month_setting['setting_value'];
							// echo "month";
						}
						//IF DATE POSTED IS MORE THAN 30 DAYS FROM CURRENT DATETIME
						else if(strtotime($post_datetime." + 30 days") >= $current_datetime)
						{
							//MULTIPLY BY CURRENTLY LIVE SETTING
							$post_expense = $post_expense * $over_month_setting['setting_value'];
							// echo "more than month";
						}
						echo $post_expense."<br>";
					}
				}
				
				$ad_spot = null;
				$ad_spot['ad_request_id'] = $ad_request['id'];
				$ad_spot['value'] = $post_expense;
				
				db_insert_ad_spot($ad_spot);
			}
			
		}
		
	}

	function clean_up_post_board()
	{
		
	}
}