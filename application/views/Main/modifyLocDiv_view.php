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
      
            <form action="<?=base_url('main/edit_loc_div/'.$locDiv['division_id'])?>" method="post"> 
                <div class="panel panel-info">
                <div class="panel-heading">Use this form to modify Locations/Divisions in the system</div>
                <div class="panel-body">

                    <div class="form-group row">
                            <label for="parish" class="col-sm-2 form-label">Parish</label>
                            <div class="col-sm-4">
                                <input type="text" required readonly="readonly" value="<?=$locDiv['parish']?>"
                                    name="parish" class="form-control" id="parish" />
                            </div>
                    </div>

                    <div class="form-group row">
                            <label for="location_type" class="col-sm-2 form-label">Location Type</label>
                            <div class="col-sm-4">
                                <input type="text" required readonly="readonly" value="<?=$locDiv['location_type']?>"
                                name="location_type" class="form-control" id="location_type" />
                            </div>
                    </div>                    
                    
                    <div class="form-group row">
                            <label for="location_abbre" class="col-sm-2 form-label">Location Abbre.</label>
                            <div class="col-sm-2">
                                <input type="text" required readonly="readonly" value="<?=$locDiv['location_abbre']?>"
                                name="location_abbre" class="form-control" id="location_abbre" />
                            </div>
                            
                             <label for="location_name" class="col-sm-2 form-label">Location Name</label>
                            <div class="col-sm-6">
                                <input type="text" required value="<?=$locDiv['location_name']?>"
                                name="location_name" class="form-control" id="location_name" />
                            </div>
                    </div>
                   
                    <div class="form-group row">
                            <label for="division_abbre" class="col-sm-2 form-label">Division Abbre.</label>
                            <div class="col-sm-2">
                                <input type="text" required value="<?=$locDiv['division_abbre']?>"
                                name="division_abbre" class="form-control" id="division_abbre" />
                            </div>
                             <label for="division_name" class="col-sm-2 form-label">Division Name</label>
                            <div class="col-sm-6">
                                <input type="text" required value="<?=$locDiv['division_name']?>"
                                name="division_name" class="form-control" id="division_name" />
                            </div>
                    </div>        
                </div>
            </div>

            <div class="form-group col-lg-2 previous-btn">
                    <input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Previous" />
            </div>

            <div class="form-group row col-lg-4">
                    <input type="submit" name="btnUpdateLocDiv" id="btnUpdateLocDiv" class="form-control btn-info" value="Update Location/Division" />
            </div>
	</form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>