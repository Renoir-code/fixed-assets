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
      
            <form action="<?=base_url('main/edit_asset/'.$asset['asset_code_id'])?>" method="post"> 
                <div class="panel panel-info">
                <div class="panel-heading">Use this form to modify an Asset Code in the system</div>
                <div class="panel-body">

                    <div class="form-group row">
                            <label for="asset_code" class="col-sm-2 form-label">Asset Code</label>
                            <div class="col-sm-4">
                                <input type="text" title="Altering the Asset Code would mean that all Devices associated with the code would have to be Remarked..." required readonly="readonly" value="<?=$asset['asset_code']?>"
                                    name="asset_code" class="form-control" id="asset_code" />
                            </div>
                    </div>

                    <div class="form-group row">
                            <label for="description" class="col-sm-2 form-label">Description</label>
                            <div class="col-sm-4">
                                <input type="text" required value="<?=$asset['description']?>"
                                name="description" class="form-control" id="description" />
                            </div>
                    </div>

                    <div class="form-group row">
                            <label for="asset_count" class="col-sm-2 form-label">Asset Count</label>
                            <div class="col-sm-4">
                                    <input type="text" required readonly="readonly" value="<?=$asset['asset_count']?>"
                                   name="asset_count" class="form-control" id="asset_count" />
                            </div>
                    </div>  
                </div>
            </div>

            <div class="form-group col-lg-2 previous-btn">
                    <input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Previous" />
            </div>

            <div class="form-group row col-lg-4">
                    <input type="submit" name="btnUpdateAssetCode" id="btnUpdateAssetCode" class="form-control btn-info" value="Update Asset Code" />
            </div>
	</form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>