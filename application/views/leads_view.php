<!DOCTYPE html>
<html lang="en">
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
			#leads_tab{
				background-color: white;
				color:orange;
			}
		</style>
	</head>
	<?php include("leads/lead_scripts.js"); ?>
	<body>
		
		<div id="main_content" class="main_content">
			<?php include("main_menu.php"); ?>
			<div id="left_nav_filters" class="left_nav shadow_card scrollable_div">
				<div class="filter_form_container"style="padding-top:10px;padding-left:15px;padding-right:15px;">
					<form id="filter_form">
						Search
						<hr>
						<input onchange="load_report()" id="search_filter" name="search_filter" class="filter_input" type="text" placeholder="Search by name or number"/><br><br>
						Sort
						<hr>
						<select onchange="load_report()" id="sort_filter" name="sort_filter" class="filter_input">

							<option>Contact Date</option>
							<option>Action Date</option>
						</select><br><br>
						<div style="font-weight:bold;color:#4E86FC;">
							<span style="font-size:20px;">Filters</span>
							<hr>
						</div>
						Lead Status
						<hr>
						<select onchange="load_report()" id="lead_status_filter" name="lead_status_filter" class="filter_input">
							<option>Show Active</option>
							<option value="">Show All</option>
							<option>Call Back</option>
							<option>Book Travel</option>
							<option>Home Prep</option>
							<option>In Route</option>
							<option>In School</option>
							<option>In Truck</option>
							<option>Sold</option>
							<option>Not Interested</option>
						</select><br><br>
						Lead Type
						<hr>
						<select onchange="load_report()" id="lead_type_filter" name="lead_type_filter" class="filter_input" >
							<option value="">All</option>
							<option value="School">School</option>
							<option value="Class A">Class A</option>
							<option value="Other">Other</option>
						</select><br><br>
						Lead Source
						<hr>
						<select onchange="load_report()" id="lead_source_filter" name="lead_source_filter" class="filter_input" >
							<option value="">All</option>
							<option>Craigslist</option>
							<option>BackPage</option>
							<option>Rhino</option>
							<option>Google/websearch</option>
							<option>Indeed</option>
							<option>Other</option>
						</select><br><br>
						Submitted To
						<hr>
						<select onchange="load_report()" id="submitted_to_filter" name="submitted_to_filter" class="filter_input" >
							<option value="">All</option>
							<option>Lobos School</option>
							<option>Lobos CDL</option>
							<option>Get Trucker Jobs CDL</option>
							<option>Get Trucker Jobs School</option>
							<option>Knight or CRST Dedicated</option>
							<option>Other</option>
						</select><br><br>
						Contact Date
						<hr>
						<input onchange="load_report()" id="contact_after_date_filter" name="contact_after_date_filter" type="text" class="filter_input"  placeholder="After"/>
						<input onchange="load_report()" id="contact_before_date_filter" name="contact_before_date_filter" type="text" class="filter_input"  placeholder="Before"/><br><br>
						Availability Date
						<hr>
						<input onchange="load_report()" id="availability_after_date_filter" name="availability_after_date_filter" class="filter_input" type="text" placeholder="After"/>
						<input onchange="load_report()" id="availability_before_date_filter" name="availability_before_date_filter" class="filter_input" type="text" placeholder="Before"/><br><br>
						Action Date
						<hr>
						<input onchange="load_report()" id="action_after_date_filter" name="action_after_date_filter" class="filter_input" type="text" placeholder="After"/>
						<input onchange="load_report()" id="action_before_date_filter" name="action_before_date_filter" class="filter_input" type="text" placeholder="Before"/><br><br>
					</form>
				</div>
			</div>
			<div class="leads_header shadow_card" style="background-color:#4E86FC;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
				<table style="height:30px;">
					<tr style="bottom:10px;height:35px;">
						<td style="width:50px;">
							<img id="refresh_leads_btn" title="Reset Filters" onclick="reset_filters()" style="padding-left:15px;cursor:pointer;height:20px;padding-top:4px;" src="<?=base_url("images/refresh_icon_white_360.png")?>" />
							<img id="refresh_leads_gif" style="cursor:pointer;height:25px;padding-left:12px;padding-top:3px;" onclick="reset_filters()" src="<?=base_url("images/3.GIF")?>" />
						</td>
						<td style="font-size:20px;font-weight:bold;padding-bottom:3px;">Leads</td>
						<td style="float:right;">
							<div style="float:right;padding-top:4px;" id="count_div">
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div id="leads_container" class="leads_container shadow_card" style="border-top-left-radius:0px;border-top-right-radius:0px;">
				<table style="table-layout:fixed;width:1100px;padding-left:13px;">
					<tr class="table_header" style="line-height:16px;">
						<td style="width:49px;">Contact Date</td>
						<td style="width:53px;">Availability Date</td>
						<td style="width:53px;">Lead Type</td>
						<td style="width:92px;">Name</td>
						<td style="width:90px;">Phone #</td>
						<td style="width:85px;">Recruiter</td>
						<td style="width:83px;">Submitted To</td>
						<td style="width:30px;">State</td>
						<td style="width:56px;">Action Date</td>
						<td style="width:108px;">Status</td>
						<td style="width:36px;">Notes</td>
					</tr>
				</table>
				<div id="report_container" class="scrollable_div" style="padding-left:10px;font-size:14px;width:99%;height:95%;overflow-x:hidden;">
					<div>
						<img id="loading_icon" src="<?=base_url("images/ajax-loader.gif") ?>" style="margin-top:20px;margin-left:500px;display:none"/>
					</div>
				</div>
			</div>
		</div>
	</body>
	<div id="add_notes" title="Add Note" style="padding:10px; display:none;">
        <div>
            <span id="notes_header" style="font-weight:bold;font-size:14px;">Lead Notes</span>
            <br>
            <br>
            <div id="notes_ajax_div" style="height:148px; overflow:auto;font-size:12px;">
                <!-- AJAX WILL POPULATE THIS !-->
            </div>
        </div>
        <div style="position:absolute; bottom:0">
            <?php $attributes = array('name'=>'add_note_form','id'=>'add_note_form', )?>
            <?=form_open('leads/save_note/',$attributes);?>
                <div style="font-size:14px;">Add Note:</div>
                <input type="hidden" id="lead_id" name="lead_id">
                <textarea style="width:400px;" rows="3" id="new_note" name="new_note"></textarea>
            </form>
        </div>
    </div>
</html>