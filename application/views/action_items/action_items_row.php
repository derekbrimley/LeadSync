<?php
	$where = null;
	$where['id'] = $action_item['lead_id'];
	$lead = db_select_lead($where);
	$lead_name = $lead["first_name"]." ".$lead["last_name"];
	date_default_timezone_set('America/Denver');
	$today_date = date("Y-m-d H:i:s");
	
?>
<table style="font-size:14px;table-layout:fixed;">
	<tr style="line-height:25px;">
		<td style="width:73px" class="ellipsis" title="<?=$lead_name?>">
			<?=$lead_name?>
		</td>
		<td style="width:74px" class="ellipsis" title="<?=$lead["phone_number"]?>">
			<?=$lead["phone_number"]?>
		</td>
		<td style="width:58px" class="ellipsis" title="<?=date('m/d/y',strtotime($lead["application_datetime"]))?>">
			<?=date('m/d/y',strtotime($lead["application_datetime"])) ?>
		</td>
		<td style="width:62px" class="ellipsis" title="<?=$lead["why_called_in"]?>">
			<?=$lead["why_called_in"]?>
		</td>
		<td style="width:26px" class="ellipsis" title="<?=$lead["current_state"]?>">
			<?=$lead["current_state"]?>
		</td>
		<td style="width:55px" class="ellipsis" title="<?=$lead["lead_status"]?>">
			<?=$lead["lead_status"]?>
		</td>
		<td style="width:191px" class="ellipsis" title="<?=$action_item['note']?>">
			<?=$action_item['note']?>
		</td>
		<td class="ellipsis" title="<?=date('m/d/y', strtotime($action_item['due_date']))?>" style="width:62px;
			<?php if(date('Y-m-d H:i:s', strtotime($action_item['due_date']))<=$today_date && $action_item['completion_date'] == ""):?>
				color:red;
			<?php endif ?>
		">
			<?=date('m/d/y', strtotime($action_item['due_date']))?>
		</td>
		<td id="td_<?=$action_item['id']?>" style="width:70px" class="ellipsis" >
			<?php if($action_item['completion_date'] == ""):?>
				<div>
					<img title="Click to complete action item" id="complete_action_item_<?=$action_item['id']?>" data-lead_id="<?=$lead["id"]?>" onclick="action_complete_action_item(<?=$action_item['id']?>)" src="<?=base_url("images/nextgen_action_item_button_icon.png")?>" style="height:20px;cursor:pointer;float:right;padding-right:45px;"/>
				</div>
			<?php else: ?>
				<div title="<?=date('m/d/y', strtotime($action_item['completion_date']))?>">
					<?=date('m/d/y', strtotime($action_item['completion_date']));?>
				</div>
			<?php endif ?>
		</td>
		<td style="width:29px;cursor:pointer;" class="ellipsis" title="<?=$lead['applicant_status_log']?>">
			<?php if(!empty($lead['applicant_status_log'])):?>
				<img onclick="open_notes(<?=$action_item['lead_id']?>)" src="<?= base_url("images/nextgen_notes_filled_360.png")?>" style="height:20px;position:relative;float:right;"
				title="<?php echo $lead['applicant_status_log']?>"
				/>
			<?php else:?>
				<img onclick="open_notes(<?=$action_item['lead_id']?>)" src="<?= base_url("images/nextgen_notes_empty_360.png")?>" style="height:20px;position:relative;float:right;"
					title="<?php echo $lead['applicant_status_log']?>"
				/>
			<?php endif ?>
		</td>
	</tr>
</table>