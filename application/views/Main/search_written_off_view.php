<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Fixed Assets To Be Written Off</h1>
    
    <form action="<?=base_url('main/search_written_off')?>" method="post">	
        <div class="form-group row search_area">
            <label for="value" id="search_label" class="col-sm-2 form-label">Search For Serial</label>
            <div class="col-sm-3">
                <input type="text" value="<?=set_value('value')?>" id="value" name="value" class="form-control" />
            </div>
            <div class="col-sm-2">
                <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
            </div>			
        </div>
    </form>  
    
    <?php 
        if(isset($asset))
        {
            include_once 'inc/Main/asset_body.inc';
        }  
    ?>
    
</div>

<?php include_once 'inc/footer.inc'?>   
    
</body>
</html>