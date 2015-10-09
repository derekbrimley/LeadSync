<script>
	setInterval(function()
	{
		console.log("refreshed");
		//AJAX
		$.ajax({
			url: "leads/get_daily_stats/",
			type: "POST",
			cache: false,
			statusCode: {
				200: function(response){
					$("#daily_stats_div").html(response);
				},
				404: function(){
					alert('Page not found');
				},
				500: function(){
					alert("500 error! "+response);
				},
			}
		})//ajax
	},30000);
</script>
<div id="title_bar" style="padding-top:4px;padding-bottom:1px;font-weight:bold;font-size:16px;">
	<span style="padding-left:8px;">
		<img style="height:20px;"src="<?=base_url("images/logo1.png")?>"/>
		<span style="position:relative;bottom:5px;"><a style="text-decoration:none;color:#4E86FC;" href="<?=base_url("index.php/leads/")?>">LeadSync</a></span>
	</span>
	<span style="float:right;">
		Welcome, <?=$this->session->userdata('first_name');?> | 
		<span><a style="color:#4E86FC;text-decoration:none;padding-right:8px;" href="<?=base_url('index.php/login/logout')?>">Logout</a></span>
	</span>
</div>
<div class="top-menu"
	style="width:1300px; 
		background-color:#ffa500; 
		margin-bottom:10px;
		height:50px;
		box-shadow: 0px 8px 17px 0 rgba(0, 0, 0, 0.2);" 
	>
	<ul id="top-list" 
		style="list-style-type:none;
			margin:0;
			padding:0;
			overflow:hidden;">
		<li style="float:left;"><a id="leads_tab" class="main_menu_tab" href="<?=base_url('index.php/leads')?>">Leads</a></li>
		<li style="float:left;"><a id="call_form_tab" class="main_menu_tab" href="<?=base_url('index.php/call_form')?>">Call Form</a></li>
		<li style="float:left;"><a id="calls_tab" class="main_menu_tab" href="<?=base_url('index.php/calls')?>">Calls</a></li>
		<li style="float:left;"><a id="action_items_tab" class="main_menu_tab" href="<?=base_url('index.php/action_items')?>">Action Items</a></li>
		<li style="float:left;"><a id="settings_tab" class="main_menu_tab" href="<?=base_url('index.php/settings')?>">Settings</a></li>
		<li style="float:right;">
			<div id="daily_stats_div" style="margin-right:4px;margin-top:10px;">
				<?php if($this->session->userdata('role')=="caller"): ?>
					<span title="Calls taken by <?=$this->session->userdata('username') ?>">
						<img style="height:22px;padding-right:4px;padding-bottom:2px;" src="<?=base_url("images/phone_white_360.png") ?>"/>
						<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$call_count?></span>
					</span>
					<span title="Leads submitted by <?=$this->session->userdata('username') ?>">
						<img style="height:20px;padding-right:4px;padding-bottom:1px;" src="<?=base_url("images/transfer_icon_360.png") ?>"/>
						<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$transfer_count?></span>
					</span>
				<?php elseif($this->session->userdata('role')=="recruiter" || $this->session->userdata('role')=="manager" || $this->session->userdata('role')=="admin" ): ?>
					<span title="Total calls taken">
						<img style="height:22px;padding-right:4px;padding-bottom:2px;" src="<?=base_url("images/phone_white_360.png") ?>"/>
						<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$total_call_count?></span>
					</span>
					<span title="Total calls submitted">
						<img style="height:20px;padding-right:4px;padding-bottom:1px;" src="<?=base_url("images/transfer_icon_360.png") ?>"/>
						<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$total_transfer_count?></span>
					</span>
				<?php endif ?>
			</div>
		</li>
	</ul>
</div>
