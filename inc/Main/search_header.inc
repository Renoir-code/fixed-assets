<form action="<?=base_url('main/search')?>" method="post" name="generate_dispatch_sheet_form" id="generate_dispatch_sheet_form">	
        <div class="form-group row search_area">
            <label for="value" id="search_label" class="col-sm-2 form-label">Search For</label>
            <div class="col-sm-4">
                <input type="text" value="<?=set_value('value')?>" id="value" name="value" class="form-control"placeholder="Serial, Make, Model or Assigned User" />
            </div>
            <div class="col-sm-2">
                <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
            </div>		
        
             <?php $user = $this->user_model->getUserById($_SESSION['fa_user_id']); 
             if($user['user_level'] < 4 )
             {
            ?>
            
                <div class="col-sm-3">
                    <input type="submit" id="btnGenerate" name="btnGenerate" value="Generate Dispatch Sheet" class="form-control btn-info" />
                </div> 
             <?php } ?>
        </div>
    <!--</form> --> 
