<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body> 

<?php 
	//function to test if a user is a supervisor in order to provide them with relevant options. Also to stop unauthorized users from gaining access to functionality above their user level 
	$user = $this->user_model->getUserById($_SESSION['fa_user_id']);
?>
   
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Manage Locations and Divisions</h1>      
    
    <div class="col-lg-3" style="margin-right: -14px; float: right;">
            <input type="text" placeholder="Filter results" value="<?=set_value('filter')?>" 
               name="filter" class="form-control" id="filter"
               title="You can search by Location/Division" />
    </div>
    
    <table id="mytables" class="table table-bordered">
        <thead><tr>
                <th>Parish</th>
                <th>Location Type</th>
                <th>Location Abbre.</th>
                <th>Location Name</th>
                <th>Division Abbre.</th>
                <th>Division Name</th>
                <th>Last Modified</th>
                <th>Modified By</th>
        </tr></thead>
        <tbody>
            <?php
                foreach($locDiv as $row)
                {
                ?>
                    <tr>
                        <td><?=$row['parish']?></td>
                        <td><?=$row['location_type']?></td>
                        <td>
							<?php if($user['user_level'] != 4){?>
								<a href="<?=base_url('main/edit_loc_div/'.$row['division_id'])?>"><?=$row['location_abbre']?></a>
							<?php }else echo $row['location_abbre'];
							?>
                        </td>                        
                        <td><?=$row['location_name']?></td>
                        <td>
							<?php if($user['user_level'] != 4){?>
								<a href="<?=base_url('main/edit_loc_div/'.$row['division_id'])?>"><?=$row['division_abbre']?></a>
							<?php }else echo $row['division_abbre'];
							?>
                        </td>  
                        <td><?=$row['division_name']?></td>  
                        <td> 
                            <?php if($row['time_modified'] == '0000-00-00 00:00:00')
                                    echo '';
                                else
                                    echo date_format(new DateTime($row['time_modified']),"d-M-Y");
                            ?>                                
                        </td> 
                        <td><?=$row['modified_by']?></td>
                    </tr>
                <?php
                }  
                ?>
        </tbody>		
    </table>
</div>

<?php include_once 'inc/footer.inc'?>   
    

<script>
    $('#filter').keyup(function(){
        var filter = $(this).val();

        if(filter != '' && filter != ' '){
                filterLocDiv(filter).done(function(data){
                       //fill the table with the results that have been returned
                        data = JSON.parse(data);                        
                        var table = '<tr><th>Parish</th><th>Location Type</th><th>Location Abbre.</th><th>Location Name</th><th>Division Abbre.</th><th>Division Name</th><th>Last Modified</th><th>Modified By</th></tr>';
                        
                        if(data !== 'empty') 
                        {
                            for(var i in data)
                            {
                                var url="<?=base_url('main/edit_loc_div').'/'?>";
                                table += '<td>'+data[i].parish+'</td><td>'+data[i].location_type+'</td><td><a href="'+url+data[i].division_id+'">'  +data[i].location_abbre+'</a></td><td>'+data[i].location_name+'</td><td><a href="'+url+data[i].division_id+'">'  +data[i].division_abbre+'</a></td><td>'+data[i].division_name+'</td>';
                                
                                if(data[i].time_modified == "0000-00-00 00:00:00")
                                    table += '<td></td>';
                                else
                                     table += '<td>'+$.datepicker.formatDate("d-M-yy",new Date(data[i].time_modified))+'</td>';
                               
                                table += '<td>'+data[i].modified_by+'</td></tr>';
                            }
                        }
                        else
                            table += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                        $('#mytables').html(table);
                });
        }
    });

    function filterLocDiv(filter){
        return $.ajax({
                url: 'filterLocDiv',
                data: {filter: filter},
                async: true
        });
    }
</script>    
    
</body>
</html>