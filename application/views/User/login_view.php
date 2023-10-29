<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>
<div class="container">
	<div class="jumbotron">
            <h2><center>Welcome to the Fixed Assets System</center></h2>
            <h2><center>Login Screen</center></h2><br/>
            <h4>Instruction:<br/>
            - Please ensure that your username is a registered valid official Court email address<br />
            - Password should be between 6 and 15 characters in length</h4>
	</div>
	
	<div class="error_holder"><?=validation_errors()?><?=$this->session->flashdata('message')?></div>
	<form  class="loginArea" action="<?=base_url('user/index')?>" method="POST">
		<div class="panel panel-info">
		<div class="panel-heading">LOGIN</div>
		<div class="panel-body">
		<div class="form-group row">
			<label for="username" class="col-sm-2 form-label">Username</label>
			<div class="col-sm-10">
				<input type="text" required name="username" class="form-control" id="username" />
			</div>
		</div>
		
		<div class="form-group row">
			<label for="password" class="col-sm-2 form-label">Password</label>
			<div class="col-sm-10">
                            <input type="password" required name="password" class="form-control" id="password" />
			</div>
		</div>
		
		<div class="form-group row col-lg-5">
			<input type="submit" name="btnlogin" id="btnlogin" class="form-control btn-info" value="Login" />
		</div>
            
		<label style="float: right;"><a href="<?=base_url('user/forgotPassword')?>">Forgotten Password</a></label>
		
		</div>
		</div>		
		
	</form>
</div>

<?php include_once 'inc/footer.inc'?>
</body>
</html>