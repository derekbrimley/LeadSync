<?php
	$where = null;
	$where['id'] = $call['lead_id'];
	$lead = db_select_lead($where);
	$lead_name = $lead['first_name']." ".$lead['last_name'];
	$phone_number = $lead['phone_number'];
	$formatted_phone_number = "(".substr($phone_number,0,3).") ".substr($phone_number,3,3)."-".substr($phone_number,6,4);
	
	$where = null;
	$where['id'] = $call['user_id'];
	$call_taken_by_user = db_select_user($where);
	$call_taken_by_name = $call_taken_by_user['first_name']." ".$call_taken_by_user['last_name'];

	$where = null;
	$where['id'] = $call['transfer_user_id'];
	$transfer_user = db_select_user($where);
	$transfer_user_name = $transfer_user['first_name']." ".$transfer_user['last_name'];
	
	$where = null;
	$where['id'] = $call['source'];
	$source = db_select_lead_source($where);
	$source_name = $source['source_name'];
?>

<table style="height:30px;">
	<tr>
		<td title="<?= date('m/d/y h:i:s',strtotime($call['call_time'])) ?>" style="width:98px;"><?= date('m/d/y',strtotime($call['call_time'])) ?></td>
		<td style="width:111px;"><?= $formatted_phone_number ?></td>
		<td style="width:191px;"><?= $lead_name ?></td>
		<td style="width:175px;"><?= $call_taken_by_name ?></td>
		<td style="width:169px;"><?= $transfer_user_name ?></td>
		<td style="width:118px;"><?= $source_name ?></td>
		<td style="width:206px;"><?= $call['outcome'] ?></td>
	</tr>
</table>