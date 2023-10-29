<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Fixed Assets To Be Written Off & Lost/Stolen</h1>
    <div class="error_holder"><?=validation_errors()?></div>
    <div class="error_holder"><?=$this->session->flashdata('message')?></div>
    
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
    
        <table id="mytables" class="table table-bordered">
		<thead><tr>
			<th>Asset Code</th>
			<th>Make</th>
			<th>Model</th>
			<th>Serial Number</th>
                        <th>Asset Tag</th>			
			<th>Date Purchased</th>			
			<th>Supplier</th>
			<th>Cost</th>                        
                        <th>Assigned User</th>			
			<th>Assigned Number</th>
			<!--<th>Date Created</th>
                        <th>Created By</th>-->
		</tr></thead>
		<tbody>
                    <?php 
                        foreach($asset as $row)
                        {
			?>
                            <tr>
				<td>
                                    <a href="<?=base_url('main/asset_detail/'.$row['asset_number'])?>"><?=$row['asset_number']?></a>
				</td>
				<td><?=$row['make']?></td>	
                                <td><?=$row['model']?></td>
                                <td>
                                    <a href="<?=base_url('main/asset_detail/'.$row['asset_number'])?>"><?=$row['serial_number']?></a>
                                </td>
                                <td>
                                    <a href="<?=base_url('main/asset_detail/'.$row['asset_number'])?>"><?=$row['asset_tag']?></a>
                                </td>                               
                                <td> <?php if($row['date_purchased'] == '0000-00-00 00:00:00')
                                                echo '';
                                            else
                                                echo date_format(new DateTime($row['date_purchased']),"d-M-Y");
                                    ?>                                
                                </td>                                
                                <td><?=$row['supplier']?></td>
                                <td><?=number_format($row['cost'])?></td>                                
                                <td><?=$row['user']?></td>                                
                                <td><?=$row['assigned_number']?></td>
                                 
                                <!--<td> <?php if($row['time_created'] == '0000-00-00 00:00:00')
                                                echo '';
                                            else
                                                echo date_format(new DateTime($row['time_created']),"d-M-Y");
                                    ?>                                
                                </td>                                     
                                <td><?=$row['modified_by']?></td>-->
                            </tr>
                        <?php
                        }  
                        ?>
		</tbody>		
            </table>

<?php  
if(isset($asset) && empty($asset))
    echo'<h3>No results found</h3>';
?>
    
</div>

<?php include_once 'inc/footer.inc'?>   
    
</body>
</html>