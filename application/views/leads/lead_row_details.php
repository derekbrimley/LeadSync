<?php
	//echo $lead['first_name'];
	$id = $lead['id'];
	$phone_number = $lead["phone_number"];
	if(empty($phone_number))
	{
		$formatted_phone_number = "";
	}
	else
	{
		$formatted_phone_number = "(".substr($phone_number,0,3).") ".substr($phone_number,3,3)."-".substr($phone_number,6,4);
	}
	
	$where = null;
	$where['id'] = $lead['assigned_recruiter_id'];
	$recruiter = db_select_user($where);
	$recruiter_name = $recruiter['first_name']." ".$recruiter['last_name'];
?>
<script>
		$("#datepicker_due_date_<?=$lead['id']?>").datepicker();
		
		$(".detail_form_input").change(function(){
			edit_lead_details("<?=$lead['id']?>");
		})
		
		$("#availability_date_input_<?=$lead['id']?>").datepicker();
		$("#dob_input_<?=$lead['id']?>").datepicker();
		
		function prevent_submit(e)
		{
			var key = e.keyCode || e.which;
			if(key==13)
			{
				add_note(<?=$lead['id']?>);
				return false;
			}
		}
</script>
<div style="font-size:14px;width:304px;height:255px;float:left;border:1px solid #4468B0;" class="lead_detail_box" id="lead_detail_<?=$lead["id"]?>">
	<div style="font-size:16px;padding-left:10px;padding-top:2px;padding-bottom:2px;height:20px;color:#FFFFFF;background-color:#ffa500;">
		Details
		<img onclick="edit_lead_details(<?=$id?>)" id="save_details_icon" src="<?=base_url("images/save_icon_360.png")?>" style="display:none;cursor:pointer;height:80%;float:right;padding-top:2px;padding-right:5px;"/>
	</div>
	<div style="height:230px;overflow-x:hidden;" class="scrollable_div">
		<form id="driver_details_form_<?=$id?>">
			<input id="id_input" name="id_input" type="hidden" value="<?=$id?>"/>
			<table style="width:100%;padding-left:7px;">
				<tr style="height:25px;">
					<td>First Name</td>
					<td style="text-align:left;">
						<input placeholder="Enter First Name..." id="first_name_input" name="first_name_input" class="detail_form_input" type="text" value="<?=$lead['first_name']?>" style="width:155px;position:relative;left:5px;text-align:left;border:none;background-color:transparent;margin-right:5px;"/>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Last Name</td>
					<td style="text-align:left;">
						<input placeholder="Enter Last Name..." id="last_name_input" name="last_name_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['last_name'] ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Address</td>
					<td style="text-align:left;">
						<input placeholder="Enter Address..." id="address_input" name="address_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['current_address'] ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>City</td>
					<td style="text-align:left;">
						<input placeholder="Enter City..." id="city_input" name="city_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['current_city'] ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>State</td>
					<td style="text-align:left;">
						<?php
							$tickets_options =array(
								"" => "Select",
								"AL" => "AL",
								"AK" => "AK",
								"AZ" => "AZ",
								"AR" => "AR",
								"CA" => "CA",
								"CO" => "CO",
								"CT" => "CT",
								"DE" => "DE",
								"FL" => "FL",
								"GA" => "GA",
								"HI" => "HI",
								"ID" => "ID",
								"IL" => "IL",
								"IN" => "IN",
								"IA" => "IA",
								"KS" => "KS",
								"KY" => "KY",
								"LA" => "LA",
								"ME" => "ME",
								"MD" => "MD",
								"MA" => "MA",
								"MI" => "MI",
								"MN" => "MN",
								"MS" => "MS",
								"MO" => "MO",
								"MT" => "MT",
								"NE" => "NE",
								"NV" => "NV",
								"NH" => "NH",
								"NJ" => "NJ",
								"NM" => "NM",
								"NY" => "NY",
								"NC" => "NC",
								"ND" => "ND",
								"OH" => "OH",
								"OK" => "OK",
								"OR" => "OR",
								"PA" => "PA",
								"RI" => "RI",
								"SC" => "SC",
								"SD" => "SD",
								"TN" => "TN",
								"TX" => "TX",
								"UT" => "UT",
								"VT" => "VT",
								"VA" => "VA",
								"WA" => "WA",
								"WV" => "WV",
								"WI" => "WI",
								"WY" => "WY",
							);
						?>
						<?php echo form_dropdown('state_input',$tickets_options,$lead["current_state"],' id="state_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Zip Code</td>
					<td style="text-align:left;">
						<input placeholder="Enter Zip Code..." id="zip_code_input" name="zip_code_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['current_zip_code'] ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Phone Number</td>
					<td style="text-align:left;">
						<input placeholder="Enter Phone Number..." id="phone_number_input" name="phone_number_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$formatted_phone_number ?>" ?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Email</td>
					<td style="text-align:left;">
						<input placeholder="Enter Email..." id="email_input" name="email_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['email'] ?>" ?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Availability Date</td>
					<td style="text-align:left;">
						<input onchange="edit_lead_details(<?=$lead['id']?>)" placeholder="Enter Date..." id="availability_date_input_<?=$lead['id']?>" name="availability_date_input"  style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" 
							value="<?php if(!is_null($lead['availability_date'])):?><?=date('m/d/Y', strtotime($lead['availability_date'])); ?><?php endif ?>"/>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Number of Tickets</td>
					<td style="text-align:left;">
						<?php
							$tickets_options =array(
								"" => "Select",
								"0" => "0",
								"1" => "1",
								"2" => "2",
								"3" => "3",
								"4" => "4",
								"5" => "5",
								"6" => "6",
								"7" => "7",
								"8" => "8",
							);
						?>
						<?php echo form_dropdown('number_of_tickets_input',$tickets_options,$lead["number_of_tickets"],' id="number_of_tickets_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Number of Accidents</td>
					<td style="text-align:left;">
						<?php
							$accidents_options =array(
								"" => "Select",
								"0" => "0",
								"1" => "1",
								"2" => "2",
								"3" => "3",
								"4" => "4",
								"5" => "5",
								"6" => "6",
								"7" => "7",
								"8" => "8",
							);
						?>
						<?php echo form_dropdown('number_of_accidents_input',$accidents_options,$lead['number_of_accidents'],' id="number_of_accidents_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Credit Score</td>
					<td style="text-align:left;">
						<?php echo form_dropdown('credit_score_input',$credit_options,$lead['credit_score'],' id="credit_score_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>CDL</td>
					<td style="text-align:left;">
						<?php
							$cdl_options =array(
								"" => "Select",
								"Yes" => "Yes",
								"No" => "No",
							);
						?>
						<?php echo form_dropdown('cdl_input',$cdl_options,$lead['cdl'],' id="cdl_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Date of Birth</td>
					<td style="text-align:left;">
						<input placeholder="Enter Date of Birth..." id="dob_input_<?=$lead['id']?>" name="dob_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?php if(!is_null($lead['dob'])):?><?=date('m/d/Y', strtotime($lead['dob'])); ?><?php endif ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>License Number</td>
					<td style="text-align:left;">
						<input placeholder="Enter License Number..." id="license_number_input" name="license_number_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['current_license_number'] ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>License State</td>
					<td style="text-align:left;">
						<input placeholder="Enter License State..." id="license_state_input" name="license_state_input" class="detail_form_input" style="width:155px;position:relative;left:5px;border:none;background-color:transparent;text-align:left;margin-right:5px;"type="text" value="<?=$lead['current_license_state'] ?>" />
					</td>
				</tr>
				<tr style="height:25px;">
					<td>OK With Teams</td>
					<td style="text-align:left;">
						<?php
							$team_options =array(
								"" => "Select",
								"Yes" => "Yes",
								"No" => "No",
							);
						?>
						<?php echo form_dropdown('ok_w_teams_input',$team_options,$lead['drive_team'],' id="ok_w_teams_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>OK With 6 Weeks</td>
					<td style="text-align:left;">
						<?php
							$otr_options =array(
								"" => "Select",
								"Yes" => "Yes",
								"No" => "No",
							);
						?>
						<?php echo form_dropdown('ok_w_otr_input',$otr_options,$lead['drive_otr'],' id="ok_w_otr_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Call Type</td>
					<td style="text-align:left;">
						<?php
							$call_type_options =array(
								"" => "Select",
								"Inbound" => "Inbound",
								"Outbound" => "Outbound",
							);
						?>
						<?php echo form_dropdown('call_type_input',$call_type_options,$lead['inbound_or_outbound'],' id="call_type_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Lead Type</td>
					<td style="text-align:left;">
						<?php
							$lead_type_options =array(
								"" => "Select",
								"Class A" => "Class A",
								"School" => "School",
								"Other" => "Other",
							);
						?>
						<?php echo form_dropdown('lead_type_input',$lead_type_options,$lead['why_called_in'],' id="lead_type_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Lead Source</td>
					<td style="text-align:left;">
						<?php
							$lead_type_options =array(
								"" => "Select",
								"1" => "Craigslist",
								"2" => "BackPage",
								"3" => "Rhino",
								"5" => "Google/websearch",
								"6" => "Indeed",
								"4" => "Other",
							);
						?>
						<?php echo form_dropdown('lead_source_input',$lead_type_options,$lead['lead_source_id'],' id="lead_source_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Submitted to</td>
					<td style="text-align:left;">
						<?php
							$submitted_options =array(
								"" => "Select",
								"Lobos CDL" => "Lobos CDL",
								"Lobos School" => "Lobos School",
								"Get Trucker Jobs CDL" => "Get Trucker Jobs CDL",
								"Get Trucker Jobs School" => "Get Trucker Jobs School",
								"Knight or CRST Dedicated" => "Knight or CRST Dedicated",
								"Other" => "Other",
								"Non-Recruiting Call" => "Non-Recruiting Call",
							);
						?>
					<?php echo form_dropdown('submitted_to_input',$submitted_options,$lead['submitted_to'],' id="submitted_to_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Assigned Recruiter</td>
					<td>
						<?php echo form_dropdown('assigned_recruiter',$recruiter_options,$lead["assigned_recruiter_id"],'id="assigned_recruiter" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
				<tr style="height:25px;">
					<td>Lead Status</td>
					<td style="text-align:left;">
						<?php
							$status_options =array(
								"" => "Select",
								"Call Back" => "Call Back",
								"Book Travel" => "Book Travel",
								"Home Prep" => "Home Prep",
								"In Route" => "In Route",
								"In School" => "In School",
								"In Truck" => "In Truck",
								"Sold" => "Sold",
								"Not Interested" => "Not Interested",
							);
						?>
						<?php echo form_dropdown('lead_status_input',$status_options,$lead['lead_status'],' id="lead_status_input" class="detail_form_input" style="width:155px;position:relative;border:none;background-color:transparent; text-align:left;margin-right:5px;"');?>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div style="font-size:14px;width:450px;margin-left:3px;height:255px;float:left;border:1px solid #4468B0;" class="action_item_box" id="action_items_<?=$lead["id"]?>">
	<div style="font-size:16px;padding-left:10px;padding-top:2px;padding-bottom:2px;height:20px;color:#FFFFFF;background-color:#ffa500;">
		Action Items
		<img onclick="update_action_items(<?=$id?>)" id="save_action_items_icon" src="<?=base_url("images/save_icon_360.png")?>" style="display:none;cursor:pointer;height:80%;float:right;padding-top:2px;padding-right:5px;") />
	</div>
	<div style="height:25px;" >
		<table>
			<tr>
				<td style="width:310px;font-weight:bold;padding-left:7px;">Action Item</td>
				<td style="width:75px;font-weight:bold;">Due Date</td>
				<td style="width:75px;font-weight:bold;">Completed</td>
			</tr>
		</table>
	</div>
	<div style="height:135px;margin-bottom:5px;" class="scrollable_div">
		<?php if(!empty($action_items)):?>
			<?php foreach($action_items as $action_item):?>
				<?php 
					$action_item_id = $action_item['id'];
					$action_item_due_date = $action_item['due_date'];
					date_default_timezone_set('America/Denver');
					$today_date = date("Y-m-d H:i:s");
				?>
				<form id="action_item_form_<?=$action_item_id?>">
					<table>
						<input id="action_item_id" name="action_item_id" type="hidden" value="<?=$action_item_id?>"/>
						<input name="lead_id_input" id="lead_id_input" type="hidden" value="<?=$lead["id"]?>" />
						<tr style="height:25px;">
							<td style="">
								<input onchange="update_action_item(<?=$action_item_id?>)" id="action_item_note_input_<?=$action_item_id?>" name="action_item_note_input" type="text" class="action_item_form_input" value="<?=$action_item['note']?>" title="<?=$action_item['note']?>" style="border:none;background-color:transparent;width:279px;padding-left:7px;" />
							</td>
							<td style="">
								<script>$("#due_date_input_<?=$action_item_id?>").datepicker();</script>
								<input onchange="update_action_item(<?=$action_item_id?>)" id="due_date_input_<?=$action_item_id?>" name="due_date_input" type="text" class="action_item_form_input" value="<?=date('m/d/Y', strtotime($action_item['due_date']));?>" style="border:none;background-color:transparent;width:75px;
								<?php if(date('Y-m-d H:i:s', strtotime($action_item_due_date))<=$today_date && is_null($action_item['completion_date'])):?>
									color:red;
								<?php endif ?>
								" />
							</td>
							<td style="">
							<?php if(is_null($action_item['completion_date'])):?>
								<div style="width:65px;">
									<img title="Click to complete action item" id="complete_action_item_<?=$action_item_id?>" data-lead_id="<?=$lead["id"]?>" onclick="complete_action_item(<?=$action_item_id?>)" src="<?=base_url("images/nextgen_action_item_button_icon.png")?>" style="height:20px;cursor:pointer;padding-left:19px;"/>
								</div>
							<?php else: ?>
								<div class="action_item_form_input" style="width:75px;">
									<?=date('m/d/Y', strtotime($action_item['completion_date']));?>
								</div>
							<?php endif ?>
							</td>
						</tr>
					</table>
				</form>
			<?php endforeach ?>
		<?php endif ?>
	</div>
	<div style="height:70px;">
		<form id="action_item_form">
			<input id="action_item_input_<?=$lead['id']?>" style="margin-right:20px;margin-left:10px;margin-bottom:10px;width:315px;" type="text" placeholder="New Action Item"/>
			<input id="datepicker_due_date_<?=$lead['id']?>" style="margin-right:10px;width:80px;" type="text" placeholder="Due Date" />
		</form>
		<button style="margin-left:10px;" onclick="add_action_item(<?= $lead['id'] ?>)" class="add_action_btn">Add Action Item</button>
	</div>
</div>
<div style="font-size:14px;float:left;width:303px;margin-left:3px;border:1px solid #4468B0;height:255px;">
	<div style="font-size:16px;padding-left:10px;padding-top:2px;padding-bottom:2px;height:20px;color:#FFFFFF;background-color:#ffa500;">
		Notes
	</div>
	<div>
		<div style="font-size:12px;height:145px;padding:10px;" class="scrollable_div">
			<?php 
				$notes = nl2br($lead['applicant_status_log']);
			?>
			<?= $notes ?>
		</div>
	</div>
	<div style="height:70px;">
		<form id="add_note_form" >
			<input id="note_input_<?=$lead['id']?>" onkeypress="return prevent_submit(event)" style="margin-right:20px;margin-left:10px;margin-bottom:10px;width:277px;" type="text" placeholder="New Note"/>
		</form>
		<button style="margin-left:10px;" onclick="add_note(<?= $lead['id'] ?>)" class="add_note_btn">Add Note</button>
	</div>
</div>