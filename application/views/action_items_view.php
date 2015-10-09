<?php
	$where = null;
	$where = "1 = 1";
	$leads = db_select_leads($where);
?>

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
			#action_items_tab{
				background-color: white;
				color:orange;
			}
		</style>
	</head>
	<?php include("action_items/action_items.js"); ?>
	<body>
		<div id="main_content" class="main_content">
			<?php include("main_menu.php"); ?>
			<div class="action_item_filters shadow_card" style="width:180px;height:100%;float:left;">
				<div style="padding:10px 15px;">
					<div class="filter_title" style="padding-bottom:5px;">
						<div style="color:#4E86FC;font-size:20px;font-weight:bold;">Filters</div>
						<hr>
					</div>
					<div>
						<form id="action_items_filter">
							Lead
							<hr>
							<?php
							$lead_names = array();
							$lead_names[''] = "All";
							foreach($leads as $lead):
								$lead_names[$lead['first_name']." ".$lead['last_name']] = $lead['first_name']." ".$lead['last_name'];
							endforeach;
							?>
							<?php echo form_dropdown('lead_filter',$lead_names,"All",'onchange="load_action_items_report()" id="lead_filter" class="action_filter_input"');?>
							<br><br>
							Due Date
							<hr>
							<input onchange="load_action_items_report()" id="due_date_after_filter" name="due_date_after_filter" type="text" class="action_filter_input dp" placeholder="After"/>
							<input onchange="load_action_items_report()" id="due_date_before_filter" name="due_date_before_filter" type="text" class="action_filter_input dp" placeholder="Before"/>
							<br><br>
							Availability Date
							<hr>
							<input onchange="load_action_items_report()" id="availability_date_after_filter" name="availability_date_after_filter" type="text" class="action_filter_input dp" placeholder="After"/>
							<input onchange="load_action_items_report()" id="availability_date_before_filter" name="availability_date_before_filter" type="text" class="action_filter_input dp" placeholder="Before"/>
							<br><br>
							Lead Type
							<hr>
							<?php
							$lead_names = array(
								"" => "Select",
								"Class A" => "Class A",
								"School" => "School",
								"Other" => "Other",
							);
							?>
							<?php echo form_dropdown('lead_type_filter',$lead_names,$lead['lead_status'],'onchange="load_action_items_report()" id="lead_type_filter" class="action_filter_input"');?>
							<br><br>
							Lead Status
							<hr>
							<select onchange="load_action_items_report()" id="action_lead_status_filter" name="action_lead_status_filter" class="action_filter_input">
								<option>Show Active</option>
								<option value="">Show All</option>
								<option>Call Back</option>
								<option>Book Travel</option>
								<option>In Route</option>
								<option>In School</option>
								<option>In Truck</option>
								<option>Sold</option>
								<option>Not Interested</option>
							</select>
						</form>
					</div>
				</div>
			</div>
			<div class="action_item_report shadow_card" style="width:1100px;height:100%;float:right;">
				<div style="border-top-right-radius:2px;border-top-left-radius:2px;padding-top:5px;padding-left:5px;height:35px;background-color:#4E86FC;color:white;font-size:20px;">
					<table>
						<tr>
							<td style="width:40px;">
								<img id="refresh_actions_btn" title="Reset Filters" onclick="reset_action_filters()" style="padding-left:10px;cursor:pointer;height:20px;padding-top:4px;" src="<?=base_url("images/refresh_icon_white_360.png")?>" />
								<img id="refresh_actions_gif" style="cursor:pointer;height:25px;padding-left:7px;" src="<?=base_url("images/3.GIF")?>" />
							</td>
							<td style="height:25px;padding-bottom:4px;font-weight:bold;">Action Items</td>
							<td style="float:right;padding-right:15px;">
								<div id="action_item_count">
									Count
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div style="padding:10px 15px;">
					<div class="report_header" style="padding-bottom:5px;">
						<table style="font-size:14px;table-layout:fixed;color:#4E86FC;">
							<tr>
								<td style="width:68px;">Name</td>
								<td style="width:69px;">Phone Number</td>
								<td style="width:54px;">Contact Date</td>
								<td style="width:58px;">Lead Type</td>
								<td style="width:26px;">State</td>
								<td style="width:50px;">Status</td>
								<td style="width:179px;">Action Item</td>
								<td style="width:51px;">Due Date</td>
								<td style="width:79px;">Completion Date</td>
								<td style="width:26px;">Notes</td>
							</tr>
						</table>
					</div>
					<div id="action_items_report_container" style="height:700px;overflow:auto;">
						<img id="loading_icon" src="<?=base_url("images/ajax-loader.gif") ?>" style="margin-top:20px;margin-left:425px;"/>
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
            <div id="notes_ajax_div" style="height:215px; overflow:auto;font-size:12px;">
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