<!DOCTYPE html>
<html>
	<head>
		<title><?=$title?></title>
		<link type="text/css" href="<?php echo base_url("css/index.css"); ?>" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url("css/jquery-ui.min.css"); ?>" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo base_url("js/jquery-1.11.3.min.js"); ?>" ></script>
		<script type="text/javascript" src="<?php echo base_url("js/jquery-ui.min.js"); ?>" ></script>
		<script src="<?php echo base_url("scripts/index.js"); ?>" ></script>
		<link rel="shortcut icon" href="<?=base_url("/favicon-16x16.png")?>" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
		<style>
			#settings_tab{
				background-color: white;
				color:orange;
			}
		</style>
	</head>
		<?php include("settings/settings_script.js"); ?>
	<body>
		<div id="main_content" class="main_content">
			<?php include("main_menu.php"); ?>
			<div id="filter_setting_box" style="font-size:20px;position:relative;padding:0px 15px;padding-top:10px;width:150px" name="filter_setting_box" class="left_nav shadow_card scrollable_div">	
				<span style="padding-top:10px;">Settings Type</span>
				<hr>
				<select class="action_filter_input">
					<option>Filters</option>
					<option>Other</option>
				</select><br><br>
				<span>Filters</span>
				<hr>
			</div>
			<div class="leads_header" style="margin-left:200px;float:none;background-color:#4E86FC;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
				<span style="font-size:20px;font-weight:bold;position:relative;left:23px;top:5px;">Settings</span>
				<span style="float:right;">
					<img onclick="edit_settings()" style="cursor:pointer;height:25px;padding-top:5px;"id="edit_icon" name="edit_icon" src="<?=base_url("images/edit_white_360.png")?>"/>
					<img onclick="save_settings()" style="cursor:pointer;display:none;height:25px;padding-top:5px;" id="save_icon" name="save_icon" src="<?=base_url("images/save_white_and_blue_360.png")?>"/>
					<img style="display:none;height:25px;padding-top:5px;" id="loading_icon" name="loading_icon" src="<?=base_url("images/white_loading.gif")?>"/>
				</span>
			</div>
			<div id="settings_box" name="settings_box" style="margin-left:200px;height:100%;" class="shadow_card">
				<img id="loading_icon" src="<?=base_url("images/ajax-loader.gif") ?>" style="margin-top:20px;margin-left:500px;"/>
			</div>
		</div>
	</body>
</html>