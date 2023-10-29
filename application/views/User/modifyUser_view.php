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
	<div class="error_holder"><?=$this->session->flashdata('add_work_bnch_message')?></div>
       
		<form action="<?=base_url('user/user_detail/'.$person['user_id'])?>" method="post">                   
				
		<div class="panel panel-info">
                    <div class="panel-heading">Use this form to modify a user in the system</div>
                    <div class="panel-body">

                        <div class="form-group row">
                                <label for="username" class="col-sm-2 form-label">Username/Email</label>
                                <div class="col-sm-4">
                                    <input type="text"  required readonly="readonly" value="<?=$person['username']?>"
                                        name="username" class="form-control" id="username" />
                                </div>
                        </div>
                        
                        <div class="form-group row">
                                <label for="first_name" class="col-sm-2 form-label">First Name</label>
                                <div class="col-sm-4">
                                    <input type="text" required value="<?=$person['firstname']?>"
                                    name="first_name" class="form-control" id="first_name" />
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="last_name" class="col-sm-2 form-label">Last Name</label>
                                <div class="col-sm-4">
                                        <input type="text" required value="<?=$person['lastname']?>"
                                       name="last_name" class="form-control" id="last_name" />
                                </div>
                        </div>                        
                       
                        <div class="form-group row">
                                <label for="password" class="col-sm-2 form-label">User Level</label>
                                <div class="col-sm-4">
                                        <select required name="user_level" class="form-control" id="user_level">
                                            <option value="4" <?php if($person['user_level']==4) echo ' selected';?> >View Only</option>
                                            <option value="1" <?php if($person['user_level']==1) echo ' selected';?> >Regular User</option>
                                            <option value="2" <?php if($person['user_level']==2) echo ' selected';?> >Supervisor</option>
                                            <option value="3" <?php if($person['user_level']==3) echo ' selected';?> >Administrator</option>
                                        </select>
                                </div>
                        </div>
                        
                        <div class="form-group row">
                                <label for="account_enabled" class="col-sm-2 form-label">Account Enabled</label>
                                <div class="col-sm-4">
                                        <select required name="account_enabled" class="form-control" id="account_enabled">
                                                <option value="yes" <?php if($person['active']=='yes') echo ' selected';?> >Yes</option>
                                                <option value="no" <?php if($person['active']=='no') echo ' selected';?> >No</option>                                                
                                        </select>
                                </div>
                        </div>
                        
                    </div>
		</div>
                    
                <div class="form-group col-lg-2 previous-btn">
			<input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Previous" />
		</div>
		
		<div class="form-group row col-lg-2">
			<input type="submit" name="btnUpdateUser" id="btnUpdateUser" class="form-control btn-info" value="Update User" />
		</div>
	</form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>