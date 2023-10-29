<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>    
</head>
<body>
<?php include_once 'inc/nav.inc'?> 

<?php 
	//function to test if a user is a supervisor in order to provide them with relevant options. Also to stop unauthorized users from gaining access to functionality above their user level 
	$user = $this->user_model->getUserById($_SESSION['fa_user_id']);
?>
    
<div class="container">	
	<div class="error_holder"><?=validation_errors()?></div>	
		<form action="<?=base_url('notification/notifications')?>" method="post">    
			<table id="mytables" class="table table-bordered">
				<thead><tr>
					<th>Asset Type</th>
					<th>Make</th>
					<th>Model</th>
					<th>Serial Number</th>
					<th>Asset Tag</th>
					<th>Assigned User</th>	
					<th>Reason</th>	
					<th>Edited By</th>	
					<th title="Components that where only set to BOS will be listed as BOS. Components that where moved and/or set for BOS will be listed with the movement information.">Action</th>	
					<?php if($user['user_level'] != 4) echo '<th>Updated</th>';?>	
				</tr></thead>
				<tbody>
					<?php 
					if(!empty($notificationsList)){
						
					foreach($notificationsList as $key => $row){
						$start = strpos($row['reason'], '_E__N_CO_DED_');
						$array = array("for <b>BOS</b>", "<b>BOS</b>");
						
						if($start === FALSE) {
							$start = strpos($row['reason'], '_D__E_CO_DED_');
							$array = array("as <b>Malfunctioning</b>", "<b>Malfunctioning</b>");
						}
						
						if($start !== FALSE){
							//this means that the component was set for BOS or Malfunctioning and moved
							$row["reason"] = substr($row['reason'], 0, $start);
						}
					?>
						<tr>
							<td><?=$row['asset_type']?></td>
							<td><?=$row['make']?></td>	
							<td><?=$row['model_number']?></td>
							<td><?=$row['serial_number']?></td>
							<td><?=$row['asset_tag']?></td>
							<td><?=$row['assigned_user']?></td>
							<td><?=$row['reason']?></td>
							<td><?=$row['username']?></td>
							<td><?php
							
							if(!empty($row['from_location'])){//if the component was moved to a new location
								if($start !== FALSE)
									echo 'Set '.$array[0].' and moved from ';
								else echo 'From '.$row['from_division'].', '.$row['from_location'].' to '.$row['to_division'].', '.$row['to_location'];
							} else echo $array[1];//if the components was not moved but set for BOS or as Malfunctioning
							
							?></td>
							<?php if($user['user_level'] != 4){?>
							<td><input type="checkbox" class="updateNots" title="Check if this notification has been updated" name="updatedNotifications[]" id="updatedNotification" value="<?=$row['notification_id']?>"></td>
							<?php } ?>
						</tr>
					<?php }
					} ?>
				</tbody>		
			</table>
			<?php if(empty($notificationsList)) echo "<h1>No records found</h1>"; ?>
		<?php 
		if(!empty($notificationsList)) 
		{
			if($user['user_level'] != 4)
			{
		?>
				<div class="form-group row col-lg-3">
					
					<input type="submit" name="btnAddAsset" id="btnAddAsset" class="form-control btn-info" value="Remove Updated Notifications"/>
				</div>
		<?php 
			}
		} ?>
	</form>
</div>
<?php include_once 'inc/footer.inc'?>  
<script>
$('#btnAddAsset').click(function(){
	var checked = false;
	$('.updateNots').each(function(){
		if($(this).is(':checked')) checked = true;
	});
	
	if(!checked){
		alert('At least one notification needs to be selected!!!');
		return false;
	}
	
	var proceed = confirm('Are you sure you want to update the selected notifications? This action cannot be undone!!!'); 
	
	if(!proceed) return false;
});  
</script>  
</body>
</html>