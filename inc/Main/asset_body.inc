<table id="mytables" class="table table-bordered">
		<thead><tr>
			<th>Asset Code</th>
			<th>Make</th>
			<th>Model</th>
			<th>Serial Number</th>
                        <th>Asset Tag</th>
			<!--<th>General Description</th>-->
			<th>Date Purchased</th>			
			<th>Supplier</th>
			<th>Cost</th>
                        <!--<th>Invoice Number</th>-->
                        <th>Assigned User</th>			
			<th>Assigned Number</th>
            <th>Asset Image </th>
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
                                    <?php $user = $this->user_model->getUserById($_SESSION['fa_user_id']); 
                                    if($user['user_level'] < 4 )
                                    {
                                   ?>
                                        <input type="checkbox" name="assets[]" class="selectedAssets" value="<?=$row['asset_number']?>"<br>
                                    <?php } ?>
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
                                <!--<td><?=$row['description1']?></td>-->
                                <td> <?php if($row['date_purchased'] == '0000-00-00 00:00:00')
                                                echo '';
                                            else
                                                echo date_format(new DateTime($row['date_purchased']),"d-M-Y");
                                    ?>                                
                                </td>                                
                                <td><?=$row['supplier']?></td>
                                <td>$<?=number_format((double)$row['cost'],2,'.',',')?></td>	
                                <!--<td><?=$row['acct_ref']?></td>-->
                                <td><?=$row['user']?></td>                                
                                <td><?=$row['assigned_number']?></td>
                                <td>
                                <?php
                                $imagePath = base_url() . '/' . $row['asset_pictures'];
                                if (!empty($row['asset_pictures'])) {
                                    echo '<a href="' . $imagePath . '" target="_blank"><img src="' . $imagePath . '" height="24" width="24" alt=""></a>';
                                }
                                ?>
                            </td>
                                
                                
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
	</form>

<?php  
if(isset($asset) && empty($asset))
    echo'<h3>No results found</h3>';
?>