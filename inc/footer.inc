<div id="margin_footer">
<div class="navbar navbar-default navbar-fixed-bottom">
	<div class="container">
	    <p class="navbar-text pull-left">&copy; Copyright <?php echo date("Y", TIME())?>. </p>
	    <p class="navbar-text pull-right">Developed by: CAD ICT Division</p>
	</div>
</div>
</div>

<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="<?=base_url('js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('js/validation.js')?>"></script>
<script src="<?=base_url('js/chosen.jquery.js')?>"></script>

<script>
$('#btnGenerate').click(function(){
    
    var selected = 0;
	var checked = false;
	$('.selectedAssets').each(function(){
		if($(this).is(':checked')) 
                {
                    checked = true;
                    selected++;
                }
	});
	
	if(!checked){
		alert('At least one Fixed Asset needs to be selected!!!');
	location.reload();	
        return false;
    }
        if(selected > 12){
		alert('Only a maximum of 12 records can be selected!!!');
	location.reload();	
        return false;
                
	}
	
//	var proceed = confirm('Are you sure you want to approve the selected requisitions? This action cannot be undone!!!'); 
//	
//	if(!proceed)
//        {
//            location.reload();
//            return false;
//        }
});  
 
</script>   