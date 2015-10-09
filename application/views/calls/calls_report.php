<?php
	$i = 0;
?>
<?php if(!empty($calls)):?>
	<?php foreach($calls as $call):?>
		<?php
			$row = $call['id'];
			
			$background_color = "";
			if($i%2 == 0)
			{
				$background_color = "background:#F2F2F2;";
			}
			
			$i++;
		?>
		<div id="tr_<?=$row?>" name="tr_<?=$row?>"  style="margin-left:5px;margin-right:10px;min-height:30px;<?=$background_color?>">
			<?php include("calls_row.php")?>
		</div>
		<div id="row_details_<?=$row?>" style="width:1073px;display:none; padding:1px; <?=$background_color?> margin:0 5px;padding-top:20px;padding-bottom:20px;" class="lead_detail_row">
			<!-- AJAX GOES HERE !-->
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