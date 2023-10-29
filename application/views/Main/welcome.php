<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Welcome to the Fixed Assets System</h1>
    <div class="error_holder"><?=validation_errors()?></div>
    <div class="error_holder"><?=$this->session->flashdata('message')?></div>
    
    <?php include_once 'inc/Main/search_header.inc'?>
    
    
    <?php include_once 'inc/Main/asset_body.inc'?>
    <?=$this->pagination->create_links();?>
    
</div>

<?php include_once 'inc/footer.inc'?>   
    
</body>
</html>