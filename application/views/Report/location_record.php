<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <?php include_once 'inc/Report/report_sidebar.inc'?>
    
    <div class="report_content">
	<form action="<?=base_url('report/location_record')?>" method="post">	
            <div class="form-group row search_area"> 
                <table id="location_table" width="100%">
                    <tr>
                        <td><b>Parish: </b></td>
                        <td>   
                                <select name="parish" id="parish" class="form-control">
                                        <option value=-1>-- Select --</option>
                                <?php
                                $parish_name = '';
                                foreach($parish as $option)
                                {
                                    echo '<option value="'.stripslashes($option["parish_id"]).'"';

                                    if(isset($_POST['parish']) && $_POST['parish']==$option["parish_id"])
                                    {
                                        echo ' selected';  
                                        $parish_name = $option["parish"];
                                    }

                                    echo '>'.$option["parish"].'</option>';                                        
                                }
                                ?>
                                </select>
                            
                        </td>
                        <td><b>Location Type: </b></td>                            
                        <td>    
                                    <select name="location_type" id="location_type" class="form-control">
                                            <option value=-1>-- Select --</option>
                                    <?php
                                    $location_type_name = '';
                                    foreach($location_type as $option)
                                    {
                                        echo '<option value="'.stripslashes($option["location_type_id"]).'"';

                                        if(isset($_POST['location_type']) && $_POST['location_type']==$option["location_type_id"])
                                        {
                                            echo ' selected';   
                                            $location_type_name = $option["location_type"];
                                        }                                            

                                        echo '>'.$option["location_type"].'</option>';                                        
                                    }
                                    ?>
                                    </select>
                            
                        </td>
                    </tr>
                    <tr>
                        <td><b>Location: </b></td>
                        <td>   
                                <select name="location_name" id="location_name" class="form-control">
                                        <option value=-1>-- Select --</option>
                                <?php
                                $location_name = '';
                                foreach($location as $option)
                                {
                                    echo '<option value="'.stripslashes($option["location_id"]).'"';

                                    if(isset($_POST['location_name']) && $_POST['location_name']==$option["location_id"])
                                    {
                                        echo ' selected';   
                                        $location_name = $option["location_name"];
                                    }

                                    echo '>'.$option["location_name"].'</option>';                                        
                                }
                                ?>
                                </select>
                            
                        </td>
                        <td><b>Division: </b></td>                            
                        <td>    
                            <select name="division_name" id="division_name" class="form-control">
                                <option value=-1>-- Select --</option>
                                <?php
                                $division_name='';
                                foreach($division as $option)
                                {
                                    echo '<option value="'.$option["division_id"].'"';

                                    if(isset($_POST['division_name']) && $_POST['division_name']==$option["division_id"])
                                    {
                                        echo ' selected'; 
                                        $division_name = $option["division_name"];
                                    }

                                    echo '>'.$option["division_name"].'</option>';
                                }
                                ?>
                            </select>                            
                        </td>
                    </tr>
                </table>
                
                <div class="col-sm-2">
                    <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
                </div>			
            </div>
        </form> 
        
        <?php  
                   
        if(isset($asset))
        {
         ?>        
           
            <div class="row">
               <div class="col-lg-12">
                   <div id="print_content"> 
                   <center><h4>COURT ADMINISTRATION DIVISION<br/>
                           Location Record</h4></center>
                     
                  
                   <?php  
                   
                    if(isset($asset) && empty($asset))
                        echo'<h3>No results found</h3>';

                    else
                    {
                        if(!empty($asset))
                        {
                    ?>  
                   
                   <table id="mytables" class="table table-bordered">
                        <thead>
                            <tr>
                                <td  colspan="6">
                                    <table id="table_header" width="100%">
                                        <tr>                                
                                            <td width="7%"> <b>Parish: </b> </td>  
                                            <td> <input type="text" value="<?=$parish_name?>" style='width:100%'></td>
                                            <td width="12%"> <b>Location Type: </b> </td>
                                            <td> <input type="text" value="<?=$location_type_name?>" style='width:100%'/></td>
                                        </tr>
                                         <tr>                                
                                            <td><b>Location: </b></td> 
                                            <td><input type="text" value="<?=$location_name?>" style='width:100%'></td> 
                                            <td><b>Division: </b></td>
                                            <td><input type="text" value="<?=$division_name?>" style='width:100%'></td>
                                        </tr>
										<tr>
                                            <td colspan="2">
                                            Total Cost for <strong><?= $division_name ?></strong>: $<?= number_format((double)$totalcost_division,2,'.',',') ?>
                                            </td>
											<td colspan="2">
                                            Total Cost for <strong><?= $location_name ?></strong>: $<?= number_format((double)$totalcost_location,2,'.',',') ?>
											</td>
										</tr>
                                    </table>
                                </td>
                            </tr>
                                <tr>
                                    <td colspan="6"></td>
                                </tr>                            
                                <tr>
                                    <th>Description</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Serial Number</th>
                                    <th>Asset Tag</th>									
                                    <th>Remarks</th>                                
                                </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($asset as $row)
                                {
                                ?>
                                    <tr>                                        
                                        <td><?=$row['description']?></td>	
                                        <td><?=$row['make']?></td>                                        
                                        <td><?=$row['model']?></td>
                                        <td><?=$row['serial_number']?></td>
                                        <td><?=$row['asset_tag']?></td>	        
                                        <td><?=$row['description2']?></td>                                       
                                    </tr>
                                <?php
                                }  
								for($i=0; $i<5; $i++)
								{
									echo '<tr height="30"><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
								}
                                ?>
								 
                                    
                        </tbody>		
                    </table>
                   <?=$this->pagination->create_links();?>
               </div>            
           </div><!--END second ROW-->  
          </div>
        <?php
        }
        }
        }
        ?>
                
       
    
</div>

<?php include_once 'inc/footer.inc'?>   
    
<script>
$(document).ready(function()
{   
    $('#location_name').change(function()
    {
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
                sel.append('<option value="'+locations[i]["location_id"]+'" >'+locations[i]["location_name"]+'</option>');               
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
});

</script>
    
</body>
</html>