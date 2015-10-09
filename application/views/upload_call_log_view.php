<!DOCTYPE html>
<html>
	<div>
		<h2>Upload Call Log</h2>
		<?php $attributes = array('name'=>'call_log_upload_form','id'=>'call_log_upload_form', )?>
		<?php echo form_open_multipart('call_log/upload_call_log/',$attributes);?>
			<div style="margin-top:25px;">
				<input class="" type="file" name="userfile" />
			</div>
			<div>
				<button onclick="" style="">Upload</button>
			</div>
		</form>
	</div>
</html>