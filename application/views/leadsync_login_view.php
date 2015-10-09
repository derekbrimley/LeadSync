<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?=$title?></title>
		<link type="text/css" href="<?php echo base_url("css/index.css"); ?>" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo base_url("js/jquery-1.11.3.min.js"); ?>" ></script>
		<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="<?=base_url("/favicon-16x16.png")?>" />
		<style>
			.login_btn{
				margin-left:80px;
				margin-top:40px;
				height:50px;
				width:244px;
				background-color:#6295FC;
				color:#FFFFFF;
				border-radius:3px;
				border:0px;
				font-size:18px;
				cursor:pointer;
			}
			.login_btn:hover{
				
				background-color:#4E86FC;
			}
			.login_btn:active{
				
				background-color:#4e78fc;
			}
		</style>
	</head>
	<body>
		<div style="width:100%;margin:0;color:white;background-color:orange;" class="slide">
			<table>
				<tr style="font-size:30px;">
					<td style="width:20px;">
						<img style="height:35px;padding-top:5px;padding-left:15px;" src="<?=base_url("images/blue_logo.png")?>"/>
					</td>
					<td style="padding-bottom:3px;padding-left:5px;">LeadSync</td>
					<td style="float:right;padding-right:15px;margin-top:5px;"><a style="text-decoration:none;color:white" href="http://nextgenmarketingsolutions.com/">NextGen Marketing Solutions</a></td>
				</tr>
			</table>
		</div>
		<div class="login_form slide" style="margin:0 auto;padding:20px;margin-top:150px;width:400px;">
			<div style="text-align:center;padding-bottom:10px;padding-top:10px;font-size:53px;">Sign In</div>
			<?php $attributes = array('name'=>'login_form','id'=>'login_form','style'=>'margin-bottom:50px;' )?>
			<?=form_open_multipart(base_url('index.php/login/authenticate/'),$attributes);?>
				<table>
					<tr>
						<td><input placeholder="Username" style="margin-left:80px;margin-bottom:15px;width:240px;height:40px;border-radius:3px;border:border: solid 1px #c9c9c9;" type="text" id="username" name="username"/></td>
					</tr>
					<tr>
						<td><input placeholder="Password" style="margin-left:80px;width:240px;height:40px;border-radius:3px;border:border: solid 1px #c9c9c9;" type="password" id="password" name="password"/></td>
					</tr>
					<tr>
						<td><input class="login_btn" id="login_btn" type="submit" value="Sign In"></td>
					</tr>
					<br>
					<br>
				</table>
			</form>
		</div>
	</body>
</html>