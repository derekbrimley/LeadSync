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
			#calls_tab{
				background-color: white;
				color:orange;
			}
		</style>
	</head>
	<?php include("calls/call_scripts.js"); ?>
	<body>
		<div id="main_content" class="main_content">
			<?php include("main_menu.php"); ?>
			<div id="left_nav_filters" class="left_nav shadow_card scrollable_div">
				<div class="filter_form_container"style="padding-top:10px;padding-left:15px;padding-right:15px;">
					<form id="filter_form">
						Search
						<hr>
						<input onchange="load_report()" id="search_filter" name="search_filter" class="filter_input" type="text" placeholder="Search by phone number"/><br><br>
						<div style="font-weight:bold;color:#4E86FC;">
							<span style="font-size:20px;">Filters</span>
							<hr>
						</div>
						Contact Date
						<hr>
						<input onchange="load_report()" id="contact_after_date_filter" name="contact_after_date_filter" type="text" class="filter_input dp"  placeholder="After"/>
						<input onchange="load_report()" id="contact_before_date_filter" name="contact_before_date_filter" type="text" class="filter_input dp"  placeholder="Before"/><br><br>
						Answered By
						<hr>
						<?php echo form_dropdown('answered_by_filter',$users,"",'onchange="load_report()" id="answered_by_filter" class="filter_input"');?>
						<br><br>
						Call Source
						<hr>
						<select onchange="load_report()" id="call_source_filter" name="call_source_filter" class="filter_input" >
							<option value="">All</option>
							<option value="1">Craigslist</option>
							<option value="2">BackPage</option>
							<option value="3">Rhino</option>
							<option value="4">Other</option>
						</select><br><br>
						Outcome
						<hr>
						<select onchange="load_report()" id="outcome_filter" name="outcome_filter" class="filter_input" >
							<option value="">All</option>
							<option>Lobos School</option>
							<option>Lobos CDL</option>
							<option>Knight or CRST Dedicated</option>
							<option>Get Trucker Jobs</option>
							<option>Other</option>
						</select><br><br>
						Transferred To
						<hr>
						<?php echo form_dropdown('transferred_to_filter',$users,"",'onchange="load_report()" id="transferred_to_filter" class="filter_input"');?>
						<br><br>
					</form>
				</div>
			</div>
			<div class="leads_header shadow_card" style="background-color:#4E86FC;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
				<table style="height:30px;">
					<tr style="bottom:10px;height:35px;">
						<td style="width:50px;">
							<img id="refresh_leads_btn" title="Reset Filters" onclick="load_report()" style="padding-left:15px;cursor:pointer;height:20px;position:relative;top:2px;" src="<?=base_url("images/refresh_icon_white_360.png")?>" />
							<img id="refresh_leads_gif" style="cursor:pointer;height:25px;padding-left:12px;position:relative;top:2px;" onclick="reset_filters()" src="<?=base_url("images/3.GIF")?>" />
						</td>
						<td style="font-size:20px;font-weight:bold;">Calls</td>
						<td style="float:right;">
							<div style="float:right;padding-top:4px;" id="count_div">
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div id="calls_container" class="leads_container shadow_card" style="border-top-left-radius:0px;border-top-right-radius:0px;">
				<table style="table-layout:fixed;width:1100px;padding-left:13px;padding-top:5px;margin-bottom:6px;">
					<tr class="table_header" style="line-height:16px;">
						<td style="width:49px;">Call Date</td>
						<td style="width:53px;">Phone Number</td>
						<td style="width:92px;">Lead Name</td>
						<td style="width:85px;">Call Taken By</td>
						<td style="width:83px;">Transferred To</td>
						<td style="width:56px;">Source</td>
						<td style="width:108px;">Outcome</td>
					</tr>
				</table>
				<div id="report_container" class="scrollable_div" style="padding-left:10px;font-size:14px;width:99%;height:95%;">
					<div>
						<img id="loading_icon" src="<?=base_url("images/ajax-loader.gif") ?>" style="margin-top:20px;margin-left:425px;display:none"/>
					</div>
				</div>
			</div>
			
		</div>
	</body>
</html>