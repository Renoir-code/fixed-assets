<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Welcome to the Fixed Assets System</h1>
    
    <?php include_once 'inc/Main/search_header.inc'?>
    
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