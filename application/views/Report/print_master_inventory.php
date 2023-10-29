<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
            
    <?=$assets?>
  
<?php include_once 'inc/footer.inc'?>   
<script>
    $(document).ready(function()
    {        
        print_content();
        document.body.onmousemove = onPrintCompleteCancel;    
    }); 
</script>
    
</body>
</html>