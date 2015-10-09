<?php
	$i = 0;
?>
<?php if(!empty($action_items)): ?>
	<?php foreach($action_items as $action_item): ?>
		<?php
			$id = $action_item['id'];
			$lead_id = $action_item['lead_id'];
			$due_date = date('m/d/y',strtotime($action_item['due_date']));
			$end_date = date('m/d/y',strtotime($action_item['completion_date']));
			if(strtotime($end_date) == 0 || $end_date == '000-00-00 00:00:00')
			{
				$completion_date = "";
			}
			else
			{
				$completion_date = date('m/d/y',strtotime($action_item['completion_date']));
			}
			//echo $completion_date;
			$note = $action_item['note'];
			
			$background_color = "";
			if($i%2 == 0)
			{
				$background_color = "background:#F2F2F2;";
			}
			
			$i++;
		?>
		<div id="tr_<?= $id ?>" style="<?=$background_color ?>">
			<?php include("action_items_row.php") ?>
		</div>
		
	<?php endforeach ?>
<?php else: ?>
	<table  style="table-layout:fixed; font-size:12px;">
		<tr>
			<td style="font-weight:bold; padding-left:40px;">
				There are no results for this filter set
			</td>
		</tr>
	</table>
<?php endif; ?>
