<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

    
</head>
<body>
<?php include_once 'inc/nav.inc'?>    
 
    
<div class="container">	
	<div class="main_content">
	<div class="error_holder"><?=validation_errors()?><?=$this->session->flashdata('message')?></div>
        <div class="success_holder"><?=$this->session->flashdata('success')?></div>
	    <form action="<?=base_url('user/changePassword')?>" method="post">                  
				
            <div class="panel panel-info">
                <div class="panel-heading">Use this form to change your password</div>
                <div class="panel-body">

                    <div class="form-group row">
                        <label for="cur_pass" class="col-sm-2 form-label">Current Password</label>
                        <div class="col-sm-4">
                        <input type="password" required name="cur_pass" class="form-control" id="cur_pass" />
                        </div>
                    </div>
		
                    <div class="form-group row">
                        <label for="new_pass" class="col-sm-2 form-label">New Password</label>
                        <div class="col-sm-4">
                                <input type="password" required name="password" class="form-control" id="password" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="confirm_pass" class="col-sm-2 form-label">Confirm Password</label>
                        <div class="col-sm-4">
                                <input type="password" required name="confirm_pass" class="form-control" id="confirm_pass" />
                        </div>
                    </div>
                    
                <div class="form-group col-lg-2 previous-btn">
			<input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Cancel" />
		</div>
		
		<div class="form-group row col-lg-4">
			<input type="submit" name="change" id="change" class="form-control btn-info" value="Change Password" />
		</div>
	</form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>