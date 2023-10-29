<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>
<div class="container">
		<div class="jumbotron">
			<h1 style="color:black !important;"><center>Fixed Assets System</center></h1>
			<h4>Instruction:<br/>
			Password should be between 6 and 16 characters in length</h4>
		</div>
	<form class="loginArea" action="<?=base_url('user/reset_password').'/'.$this->uri->segment(3).'/'.$this->uri->segment(4)?>" method="post">
		<div class="error_holder"><?=validation_errors()?><?php if(isset($message) && $message != '') echo $message; ?></div>
		<div class="panel panel-info">
		<div class="panel-heading">Reset Password</div>
		<div class="panel-body">
		<div class="form-group row">
			<label for="password" class="col-sm-2 form-label">Password</label>
			<div class="col-sm-10">
				<input type="password" name="password" class="form-control" id="password" />
			</div>
		</div>
		
		<div class="form-group row">
			<label for="confirm_pass" class="col-sm-2 form-label">Confirm Password</label>
			<div class="col-sm-10">
				<input type="password" name="confirm_pass" class="form-control" id="confirm_pass" />
			</div>
		</div>
		
		<div class="form-group row col-lg-5">
			<input type="submit" name="btnlogin" id="btnlogin" class="form-control btn-info" value="Reset Password" />
		</div>
		
		<!--<label style="float: right;"><a href="<?=base_url('/')?>">Go to login</a></label>-->
		</div>
		</div>
	</form>	
</div>
<?php include_once 'inc/footer.inc'?>
<!--<script src="<?=base_url('javascript/login.js')?>"></script>-->
</body>
</html>