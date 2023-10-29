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
		<form action="<?=base_url('main/addAsset')?>" method="post" enctype="multipart/form-data">                   
		<?php include_once 'inc/Main/add_asset_body.inc'?>
                <div class="form-group col-lg-2 previous-btn">
			<input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Previous" />
		</div>
		<div class="form-group row col-lg-2">
			<input type="submit" name="btnAddAsset" id="btnAddAsset" class="form-control btn-info" value="Save" />
		</div>
	</form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>
    
<script>
$(document).ready(function()
{   
    $('#location_name').change(function()
    {
        $('#location_abbre2').val(""); 
        $('#location2').val("");
        
        var location_abbre = $('#location_name').val(); 
        getDivisions(location_abbre).done(function(data)
        {
            var divisions = $.parseJSON(data);
            var sel = $('#division_name');
            sel.empty();
            sel.append('<option value=-1>-- Select --</option>'	);
            for(var i = 0; i < divisions.length; i++) 
            {
                sel.append('<option value="'+divisions[i]["division_id"]+'" >'+divisions[i]["division_name"]+'</option>');
            }
        });
    }); 

    function getDivisions(location_id)
    {
        return $.ajax({
        url: '<?=base_url("main/getDivisionsByLocationAbbre")?>',
        data: {location_id: location_id},
        async: false
        });
    }
    
    $('#parish').change(function()
    {
        $('#location_type').val("-1"); 
        $('#location_name').val("-1"); 
        $('#division_name').val("-1");
    });       
    
    $('#location_type').change(function()
    {
        $('#division_name').val("-1");
        var parish_id = $('#parish').val();
        var location_type_id = $('#location_type').val();        
        
        getLocations(parish_id,location_type_id).done(function(data)
        {               
            var locations = $.parseJSON(data);             
            var sel = $('#location_name');
            sel.empty();
            sel.append('<option value=-1>-- Select --</option>'	);
            
            for(var i = 0; i < locations.length; i++) 
            {
                sel.append('<option value="'+locations[i]["location_id"]+'" >'+locations[i]["location_abbre"]+' | '+locations[i]["location_name"]+'</option>');               
            }            
        });
    }); 
    
    function getLocations(parish_id,location_type_id)
    {
        return $.ajax({
        url: '<?=base_url("main/getLocationsByParishIdAndLocationTypeId")?>',
        data: {parish_id:parish_id,location_type_id:location_type_id},
        async: false
        });
    }  
    
    $('#location_abbre2').keyup(function(){ 
        $('#location_name').val("-1");
        $('#division_name').val("-1");
        $('#division_abbre2').val(""); 
        $('#division2').val("");
    });
    
    $('#location2').keyup(function(){ 
        $('#location_name').val("-1");
        $('#division_name').val("-1");
        $('#division_abbre2').val(""); 
        $('#division2').val("");
    });
    
    $('#division_abbre2').keyup(function(){        
        $('#division_name').val("-1");
        
    });
    
    $('#division2').keyup(function(){        
        $('#division_name').val("-1");        
    });
    
    
    $('#asset_code2').keyup(function(){        
        $('#asset_code').val("-1");        
    });
    
    $('#asset_description2').keyup(function(){        
        $('#asset_code').val("-1");        
    });
    
    $('#asset_code').change(function(){        
        $('#asset_code2').val(""); 
        $('#asset_description2').val(""); 
    });
    
    $('#division_name').change(function(){        
        $('#division_abbre2').val(""); 
        $('#division2').val(""); 
    });
    
    
   $('#written_off').click(function() {
        $("#bos").toggle(this.checked);
        $('#date_writeoff').val("");
    });
    
    $('#board_of_surveyed').click(function() {
        //$("#bos").toggle(this.checked);
        $('#date_writeoff').val("").toggle(this.checked);
    });
    
     //Adding Unlimited Form Fields With JQuery and Saving to a Database
    var count = 0;
    $(function(){
        $('p#add_field').click(function(){
            count += 1;
            var table = '<table id="repair_table"><tr>';
                table +='<th rowspan="2">Date</th>';
                table +='<th rowspan="2">Nature of Repairs or Service</th>';
                table +='<th colspan="2">Period out of use</th>';
                table +='<th rowspan="2">Cost</th>';
                table +='</tr>';
                table +='<tr>';    
                table +='<th>From</th>';
                table +='<th>To</th>';    
                table +='</tr>';
                
                var row = '<tr>' +
                    '<input type="hidden" name="repair_ids[]" value="0"/>' +
                    '<td><input id="date_field_' + count + '" name="date_fields[]' + '" type="date" />' +
                    '<td><input id="repair_field_' + count + '" name="repair_fields[]' + '" type="text" />' +
                    '<td><input id="from_field_' + count + '" name="from_fields[]' + '" type="date" />' +
                    '<td><input id="to_field_' + count + '" name="to_fields[]' + '" type="date" />' +
                    '<td><input id="cost_field_' + count + '" name="cost_fields[]' + '" type="number" step="0.01"/>' +
                    '</tr>';
                
                if($('#repair_table').length == 0)
                {
                    $('#repair_container').append( table + row + '</table>');
                }
                else
                {
                    $('#repair_table').append( row );
                }                

        });
    });
    //end of unlimited fields
    
});

</script>
    
</body>
</html>