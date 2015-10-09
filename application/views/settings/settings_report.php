
<form id="settings_form" name="settings_form">
	<div style="background-color:#F2F2F2;padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;">
				<td style="width:164px;">Minimum Age - CDL</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php 
						$age_options = array(
							"20" => "20",
							"21" => "21",
							"22" => "22",
							"23" => "23",
							"24" => "24",
							"25" => "25",
							"26" => "26",
							"27" => "27",
							"28" => "28",
							"29" => "29",
							"30" => "30",
							"31" => "31",
							"32" => "32",
							"33" => "33",
							"34" => "34",
							"35" => "35",
							"36" => "36",
							"37" => "37",
							"38" => "38",
							"39" => "39",
							"40" => "40",
							"41" => "41",
							"42" => "42",
							"43" => "43",
							"44" => "44",
							"45" => "45",
							"46" => "46",
							"47" => "47",
							"48" => "48",
							"49" => "49",
							"50" => "50",
							"51" => "51",
							"52" => "52",
							"53" => "53",
							"54" => "54",
							"55" => "55",
							"56" => "56",
							"57" => "57",
							"58" => "58",
							"59" => "59",
							"60" => "60",
						)
					?>
					<?php echo form_dropdown('min_age_cdl_drowpdown',$age_options,$min_age_setting_cdl["setting_value"],'id="min_age_cdl_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$min_age_setting_cdl['setting_value'] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$min_age_setting_cdl['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;">
				<td style="width:164px;">Maximum Age - CDL</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php echo form_dropdown('max_age_cdl_drowpdown',$age_options,$max_age_setting_cdl["setting_value"],'id="max_age_cdl_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$max_age_setting_cdl["setting_value"] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$max_age_setting_cdl['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="background-color:#F2F2F2;padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;">
				<td style="width:164px;">Minimum Age - School</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php 
						$age_options = array(
							"20" => "20",
							"21" => "21",
							"22" => "22",
							"23" => "23",
							"24" => "24",
							"25" => "25",
							"26" => "26",
							"27" => "27",
							"28" => "28",
							"29" => "29",
							"30" => "30",
							"31" => "31",
							"32" => "32",
							"33" => "33",
							"34" => "34",
							"35" => "35",
							"36" => "36",
							"37" => "37",
							"38" => "38",
							"39" => "39",
							"40" => "40",
							"41" => "41",
							"42" => "42",
							"43" => "43",
							"44" => "44",
							"45" => "45",
							"46" => "46",
							"47" => "47",
							"48" => "48",
							"49" => "49",
							"50" => "50",
							"51" => "51",
							"52" => "52",
							"53" => "53",
							"54" => "54",
							"55" => "55",
							"56" => "56",
							"57" => "57",
							"58" => "58",
							"59" => "59",
							"60" => "60",
						)
					?>
					<?php echo form_dropdown('min_age_school_drowpdown',$age_options,$min_age_setting_school["setting_value"],'id="min_age_school_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$min_age_setting_school['setting_value'] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$min_age_setting_school['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;">
				<td style="width:164px;">Maximum Age - School</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php echo form_dropdown('max_age_school_drowpdown',$age_options,$max_age_setting_school["setting_value"],'id="max_age_school_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$max_age_setting_school["setting_value"] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$max_age_setting_school['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="background-color:#F2F2F2;padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;background-color:#F2F2F2;">
				<td style="width:164px;">Maximum Number of Tickets</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php 
						$ticket_options = array(
							"0" => "0",
							"1" => "1",
							"2" => "2",
							"3" => "3",
							"4" => "4",
							"5" => "5",
							"6" => "6",
							"7" => "7",
							"8" => "8",
							"9" => "9",
							"10" => "10",
						)
					?>
					<?php echo form_dropdown('max_tickets_drowpdown',$ticket_options,$max_tickets_setting["setting_value"],'id="max_tickets_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$max_tickets_setting["setting_value"] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$max_tickets_setting['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;">
				<td style="width:164px;">Minimum Credit Score</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php echo form_dropdown('credit_drowpdown',$credit_options,$credit_setting["setting_value"],'id="credit_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$credit_setting["setting_value"] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$credit_setting['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="background-color:#F2F2F2;padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;background-color:#F2F2F2;">
				<td style="width:164px;">Maximum Travel Cost</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php echo form_dropdown('travel_drowpdown',range(0,500),$location_setting["setting_value"],'id="travel_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?=$location_setting["setting_value"] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$location_setting['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
	<div style="padding:5px 10px;width:1000px;margin-left:10px;">
		<table>
			<tr style="height:30px;">
				<td style="width:164px;">CDL Drivers Needed</td>
				<td class="edit_setting_value" style="display:none;width:40px;">
					<?php
						$drivers_options = array(
							"TRUE" => "TRUE",
							"FALSE" => "FALSE"
						)
					?>
					<?php echo form_dropdown('drivers_drowpdown',$drivers_options,$drivers_needed_setting["setting_value"],'id="drivers_drowpdown" style="width:100px;" class="setting_form_input" ');?>
				</td>
				<td class="display_setting_value" style="width:40px;">
					<?= $drivers_needed_setting["setting_value"] ?>
				</td>
				<td style="text-align:right;width:600px;"><?=$drivers_needed_setting['setting_notes'] ?></td>
			</tr>
		</table>
	</div>
</form>