<?php if($this->session->userdata('role')=="caller"): ?>
	<img style="height:22px;padding-right:4px;padding-bottom:2px;" src="<?=base_url("images/phone_white_360.png") ?>"/>
	<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$call_count?></span>
	<img style="height:20px;padding-right:4px;padding-bottom:1px;" src="<?=base_url("images/transfer_icon_360.png") ?>"/>
	<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$transfer_count?></span>
<?php elseif($this->session->userdata('role')=="recruiter" || $this->session->userdata('role')=="manager" || $this->session->userdata('role')=="admin" ): ?>
	<img style="height:22px;padding-right:4px;padding-bottom:2px;" src="<?=base_url("images/phone_white_360.png") ?>"/>
	<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$total_call_count?></span>
	<img style="height:20px;padding-right:4px;padding-bottom:1px;" src="<?=base_url("images/transfer_icon_360.png") ?>"/>
	<span style="position:relative;bottom:3px;padding-right:25px;color:white;font-size:28px;"><?=$total_transfer_count?></span>
<?php endif ?>