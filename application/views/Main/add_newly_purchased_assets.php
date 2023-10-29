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
	<div class="error_holder"><?=$error?></div>
		<form action="<?=base_url('main/addNewlyPurchasedAssets')?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label for="officer_name" class="col-sm-4 form-label">Officer Name</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?= $username ?>" class="form-control" name="npa_officer" readonly="true" required />
                        </div>
                    </div>
                                
                            <div class="form-group row">
                        <label for="name" class="col-sm-4 form-label">Subject/Item</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="npa_name" required />
                        </div>
                    </div>	
                </div><!--END LEFT COLUMN-->		
                        
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label for="date_created" class="col-sm-4 form-label">Date Created</label>
                        <div class="col-sm-8">
                            <input type="text" maxlength="10" value="<?= date('Y-m-d');?>" name="npa_submitted_date" class="form-control" readonly="true" required />
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label for="npa_invoice" class="col-sm-4 form-label">Invoice</label>
                        <div class="col-sm-8">
                            <input type="file" name="npa_invoice" id="npa_invoice" class="form-control" required />
                        </div>
                    </div>
                </div><!--END RIGHT COLUMN--> 
        
            </div><!--END CONTAINING ROW-->     
            <div class="row">
                <div class="col-md-12" id="requisition_container_div">
                    
                    <div class="form-group row" id="requisition_container">
                        
                        <br>
                        <table id="requisition_table" class="table table-bordered">
                    <thead><tr>
                        <th>Row</th>
                        <th>Parish</th>
                        <th>Location</th>
                        <th>Division</th>
                        <th>Assigned User/Comments</th>                        
                    </tr></thead>
                    <tbody>
                        <tr id="row_0">
                            <input type="hidden" name="count" value="1"/>
                            <td>1</td>
                            <td>
                                <select required name="parish_0" id="parish" class="form-control chzn-select" style="" required>
                                    <option  value="">-- Select --</option>
                                    <?php
                                    foreach($parish as $option){
                                        echo '<option value="'.stripslashes($option["parish_id"]).'">'.$option["parish"].'</option>';                                        
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="location_name_0" id="location_name" class="form-control chzn-select" required>
                                    <option value="">-- Select --</option>
                                </select>
                            </td> 
                            <td>
                                <select name="division_name_0" id="division_name" class="form-control" required>
                                    <option  value="">-- Select --</option>
                                </select> 
                            </td>
                            <td><input type="text" value="" name="npa_assigned_user_0" id="npa_assigned_user" class="form-control" required /> <a onclick="ClearRow()">clear row</a></td>                   
                        </tr>   
                    </tbody>		
                        </table>
                        
                            <a id="add_field"><span style="background: blue; color: white; padding: 5px;">&raquo; Add More Items </span></a> 
                        
                        <?php            
                            if(isset($requisition_table) && $requisition_table != '')
                                echo $requisition_table;
                        ?>
                    </div> 
                </div> 
            </div>          

            <div class="form-group col-lg-2 previous-btn">
                <input type="button" onclick="window.history.back()" name="btnBackToHead" id="btnBackToHead" class="form-control btn-info" value="Back" />
            </div>
            <div class="form-group row col-lg-2">
                <input type="submit" class="form-control btn-info" value="Save" />
            </div>
        </form>
	</div>
</div>
<?php include_once 'inc/footer.inc'?>
<style>
    select[readonly] option, 
    select[readonly] optgroup {
        display: none;
    }

    .tooltip-inner{
        /* display:none; */
        background-color:white;
        color: black;
        border:1px solid #747474;
        padding:10px 10px;
        font-size:13px;
        -moz-box-shadow: 2px 2px 11px #666;
        -webkit-box-shadow: 2px 2px 11px #666;
        max-width: max-content;
    }

    .tooltip-arrow{
        margin-left: -5px !important;
        border-width: 0 10px 10px !important;
        border-bottom-color: #2a2929 !important;
        top: -5px !important;
        left: 20px !important;
    }

    .tooltip-arrow:after {
        content: '';
        display: block;  
        position: absolute;
        top: -9px !important;
        left: -10px;
        border: 10px solid transparent;
        border-bottom-color: white;
    }

    .tooltip-text{
        background:#ff9900; 
        color: white;
        padding: 3px 9px;
        font-size: 15px;
        font-weight: bolder;
    }
</style>
<script>
$(document).ready(function()
{   
    $('#parish').change(function()
    {
        $('#location_name').val(""); 
        $('#division_name').val("");

        var parish_id = $('#parish').val();
        
        getLocations(parish_id).done(function(data)
        {               
            var locations = $.parseJSON(data);             
            var sel = $('#location_name');
            sel.empty();
            sel.append('<option value="">-- Select --</option>'	);
            
            for(var i = 0; i < locations.length; i++) 
            {
                if(locations[i]["location_type_id"] == "2")
                sel.append('<option value="'+locations[i]["location_id"]+'" > CAD | '+locations[i]["location_name"]+'</option>');               

                else
                sel.append('<option value="'+locations[i]["location_id"]+'" >'+locations[i]["location_name"]+'</option>');               
            }            
        });
    });
    
    $('#location_name').change(function()
    {
        var location_abbre = $('#location_name').val(); 
        getDivisions(location_abbre).done(function(data)
        {
            var divisions = $.parseJSON(data);
            var sel = $('#division_name');
            sel.empty();
            sel.append('<option value="">-- Select --</option>'	);
            for(var i = 0; i < divisions.length; i++) 
            {
                sel.append('<option value="'+divisions[i]["division_id"]+'" >'+divisions[i]["division_name"]+'</option>');
            }
        });
    }); 

    
});
 //Adding Unlimited Form Fields With JQuery and Saving to a Database
var count = 0;
$(function(){
    $('a#add_field').click(function(){
        if ($("#parish_"+count).val() !== "" && $("#location_name_"+count).val() !== "") {
            $("#parish_"+count).attr("readonly", "true");
            $("#location_name_"+count).attr("readonly", "true");
            var rownum = ($('#requisition_table tr').length);
            count++;

            $('#requisition_table').append('<tr id="row_'+count+'"> <input type="hidden" name="count" value="'+count+'" /> <td>'+rownum+'</td> <td> <select required name="parish_'+count+'" id="parish_'+count+'" class="form-control chzn-select" style="" required> <option value="">-- Select --</option> <?php foreach($parish as $option){ echo '<option value="'.stripslashes($option["parish_id"]).'">'.$option["parish"].'</option>'; } ?> </select> </td> <td> <select name="location_name_'+count+'" id="location_name_'+count+'" class="form-control chzn-select" required> <option value="">-- Select --</option> </select> </td> <td> <select name="division_name_'+count+'" id="division_name_'+count+'" class="form-control"> <option value="">-- Select --</option> </select> </td> <td><input type="text" value="" name="npa_assigned_user_'+count+'" class="form-control" required /> <a onclick="DeleteRow('+count+')">delete row</a></td> </tr>');

            $('#parish_'+count).change(function()
            {
                $('#location_name_'+count).val(""); 
                $('#division_name_'+count).val("");

                var parish_id = $('#parish_'+count).val();
                
                getLocations(parish_id).done(function(data)
                {               
                    var locations = $.parseJSON(data);             
                    var sel = $('#location_name_'+count);
                    sel.empty();
                    sel.append('<option value="">-- Select --</option>'	);
                    
                    for(var i = 0; i < locations.length; i++) 
                    {
                        if(locations[i]["location_type_id"] == "2")
                        sel.append('<option value="'+locations[i]["location_id"]+'" > CAD | '+locations[i]["location_name"]+'</option>');               

                        else
                        sel.append('<option value="'+locations[i]["location_id"]+'" >'+locations[i]["location_name"]+'</option>');               
                    }            
                });
            });
            $('#location_name_'+count).change(function()
            {
                var location_abbre = $('#location_name_'+count).val(); 
                getDivisions(location_abbre).done(function(data)
                {
                    var divisions = $.parseJSON(data);
                    var sel = $('#division_name_'+count);
                    sel.empty();
                    sel.append('<option value="">-- Select --</option>'	);
                    for(var i = 0; i < divisions.length; i++) 
                    {
                        sel.append('<option value="'+divisions[i]["division_id"]+'" >'+divisions[i]["division_name"]+'</option>');
                    }
                });
            });
        }
        else if($("#parish_"+count).val() == ""){
            $("#parish_"+count).tooltip({placement: 'bottom',trigger: 'manual', html: 'true', title: '<span class="tooltip-text">!</span> &nbsp; Please enter the parish!'}).tooltip('show');
            $(this).delay(2000).queue(function() {
                    $("#parish_"+count).tooltip('destroy');
                    $(this).dequeue();
            });
        }

        else{
            $("#location_name_"+count).tooltip({placement: 'bottom',trigger: 'manual', html: 'true', title: '<span class="tooltip-text">!</span> &nbsp; Please enter the location!'}).tooltip('show');
            $(this).delay(2000).queue(function() {
                    $("#location_name_"+count).tooltip('destroy');
                    $(this).dequeue();
            });
        }
    });
});  

function getLocations(parish_id)
{
    return $.ajax({
    url: '<?=base_url("main/getLocationsByParishId")?>',
    data: {parish_id:parish_id},
    async: false
    });
} 

function getDivisions(location_id)
{
    return $.ajax({
    url: '<?=base_url("main/getDivisionsByLocationAbbre")?>',
    data: {location_id: location_id},
    async: false
    });
}

function DeleteRow(row_id)
{
    if (confirm("Are you sure you want to delete this row?")) {
        if(row_id != 0)
            $('#row_'+row_id).remove();
            var rownum = ($('#requisition_table tr').length);
        for (j = 1; j < rownum; j++) {
            $('#requisition_table tr:nth-child('+j+') td:nth-child(2)').text(j);
    }
    }
}

function ClearRow()
{
    if (confirm("Are you sure you want to clear this row?")) {
        $('#parish').val(""); 
        $('#location_name').val(""); 
        $('#division_name').val("");
        $('#npa_assigned_user').val("");
    }
}
</script>
    
</body>
</html>