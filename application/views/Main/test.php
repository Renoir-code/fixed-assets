<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

    
</head>
<body>
<?php include_once 'inc/nav.inc'?>    
 
    
<div class="container">	
	<div class="content">
	<div class="error_holder"><?=validation_errors()?></div>
	<div class="error_holder"><?=$this->session->flashdata('add_work_bnch_message')?></div>
		<form action="<?=base_url('main/addAsset')?>" method="post" name="form1">                   
		
                    <div class="form-group row">
			<label for="location_name" class="col-sm-4 form-label">location</label>
			<div class="col-sm-8">
				<select name="location_name" id="location_name" class="form-control">
					<option value=-1>-- Select --</option>
				<?php
				foreach($location as $option){
					echo '<option value="'.$option["location_abbre"].'"';
					
					if($id == $option['location_abbre'])
						echo 'selected';
					
					//if(isset($result) && $option['location_abbre'] == $result['location_abbre'])
						//echo ' selected';
						
					echo '>'.$option["location_name"].'</option>';
				}
				?>                                       
				</select>
			</div>
		</div>
                    
                   
                <div class="form-group row">
			<label for="division_name" class="col-sm-4 form-label">division</label>
			<div class="col-sm-8">
				<select name="division_name" id="division_name" class="form-control">
					<option value=-1>-- Select --</option>				
				</select>
			</div>
		</div>
                    
		<div class="form-group row col-lg-2">
			<input type="submit" name="btnAddAsset" id="btnAddAsset" class="form-control btn-info" value="Next" />
		</div>
	</form>
	</div>
</div>
    
    <?php
    if(isset($_POST['location_abbre']))
        echo 'source id: '.$_POST['location_abbre'];
            ?>
    <!--
    <script type="text/javascript">
        $("#location_name3").change(function(){            
            $.ajax({
               type : 'POST',
               data : 'location_abbre='+ $(this).val(),               
               url : 'main/addAsset',               
               success : function(data){
                   //alert(data);
                   //this.form.submit()
                           $('#division_name').val(data); 
                           //$('#form1').submit();
               },
               error: function() {
                   alert('error');
               }
           });
        //alert($(this).val());
          });
    </script>
    
    <script>
    $('#location_name2').change(function(e) {   
        //var server = <?=base_url('main/addAsset/')?>;
        //$('#dropdown2').load('main/addAsset/'+this.value);
        window.location='http://localhost/fixed-assets/main/addAsset/'+this.value;
        //window.location=server + this.value;
        //self.location +='/' + this.value;
        //alert('values: '+ $(this).val());
    });
</script>

<script>
    $(document).ready(function(){

    $('#location_name').change(function(e){
        $this = $(e.target);
        $.ajax({
            type: "POST",
            url: "main/getOptions", // Don't know asp/asp.net at all so you will have to do this bit
            data: " location_abbre=" + $(this).val() ,
            success:function(data){
                $('#division_name').html(data);
                //alert(data);
            }
        });
    });

});
</script>
    -->
<?php include_once 'inc/footer.inc'?>

<script>
$(document).ready(function()
{
    $('#location_name').change(function()
    {
        var location_abbre = $('#location_name').val();
        trnExistInDatabase(location_abbre).done(function(data)
        {
            var divisions = $.parseJSON(data);
            var sel = $('#division_name');
            sel.empty();
            sel.append('<option value=-1>-- Select --</option>'	);
            for(var i = 0; i < divisions.length; i++) 
            {
                sel.append('<option val="'+divisions[i]["division_abbre"]+'">'+divisions[i]["division_name"]+'</option>');
            }
        });
    });

    function trnExistInDatabase(location_abbre)
    {
        return $.ajax({
        url: '<?=base_url("main/getdivisionsBylocationAbbre")?>',
        data: {location_abbre: location_abbre},
        async: false
        });
    }
});
</script>


</body>
</html>