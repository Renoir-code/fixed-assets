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
	<div class="error_holder"><?=validation_errors()?></div>
	<div class="error_holder"><?=$this->session->flashdata('emailExist')?></div>
		<form action="<?=base_url('user/addUser')?>" method="post">                   
				
		<div class="panel panel-info">
                    <div class="panel-heading">Use this form to add a new user to the system</div>
                    <div class="panel-body">

                        <div class="form-group row">
                                <label for="first_name" class="col-sm-2 form-label">First Name</label>
                                <div class="col-sm-4">
                                        <input type="text" required value="<?php echo set_value('first_name'); ?>" name="first_name" class="form-control" id="first_name" />
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="last_name" class="col-sm-2 form-label">Last Name</label>
                                <div class="col-sm-4">
                                        <input type="text" required value="<?php echo set_value('last_name'); ?>" name="last_name" class="form-control" id="last_name" />
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="username" class="col-sm-2 form-label">Username/Email</label>
                                <div class="col-sm-4">
                                        <input type="text" required value="<?php echo set_value('username'); ?>" name="username" class="form-control" id="username" />
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="password" class="col-sm-2 form-label">Password</label>
                                <div class="col-sm-4">
                                        <input type="password" required name="password" class="form-control" id="password" />
                                </div>
                        </div>		
                        <div class="form-group row">
                                <label for="user_level" class="col-sm-2 form-label">User Level</label>
                                <div class="col-sm-4">
                                        <select required name="user_level" class="form-control" id="user_level">
                                            <option value="4" <?php if(isset($_POST['user_level']) && $_POST['user_level']==4) echo ' selected';?> >View Only</option>
                                            <option value="1" <?php if(isset($_POST['user_level']) && $_POST['user_level']==1) echo ' selected';?> >Regular User</option>
                                            <option value="2" <?php if(isset($_POST['user_level']) && $_POST['user_level']==2) echo ' selected';?> >Supervisor</option>
                                            <option value="3" <?php if(isset($_POST['user_level']) && $_POST['user_level']==3) echo ' selected';?> >Administrator</option>                                            
                                        </select>
                                </div>
                        </div>
                    </div>
		</div>
                    
                <div class="form-group col-lg-2 previous-btn">
			<input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Cancel" />
		</div>
		
		<div class="form-group row col-lg-2">
			<input type="submit" name="btnAddUser" id="btnAddUser" class="form-control btn-info" value="Add User" />
		</div>
	</form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>