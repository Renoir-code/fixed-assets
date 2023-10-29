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
		<form action="<?=base_url('notification/newlypurchasedassets')?>" method="post">    
			<table id="mytables" class="table table-bordered">
				<thead><tr>
					<th>Name</th>
					<th>Submitted Date</th>
					<th>Officer</th>
					<th>Invoice</th>
					<th>Items</th>	
					<?php if($user['user_level'] != 4) echo '<th>Updated</th>';?>	
				</tr></thead>
				<tbody>
					<?php 
					if(!empty($NewlyPurchasedAssetList)){
						
					foreach($NewlyPurchasedAssetList as $key => $row){
					?>
						<tr>
							<td><?=$row['npa_name']?></td>
							<td><?=$row['npa_submitted_date']?></td>	
							<td><?=$row['npa_officer']?></td>
							<td><a href="<?=base_url("uploadedFiles/".$row['npa_invoice'])?>"><?=$row['npa_invoice']?></a></td>
							<td><a data-toggle="collapse" href="#collapseExample_<?=$row['newly_purchased_asset_id']?>" role="button" aria-expanded="false" aria-controls="collapseExample_<?=$row['newly_purchased_asset_id']?>"><?= $row['npa_count_items'] ?> <?= ($row['npa_count_items'] > 1) ? "Items" : "Item"; ?></a>
								<div class="collapse" id="collapseExample_<?=$row['newly_purchased_asset_id']?>">
									<?php foreach($NewlyPurchasedAssetsItems as $key => $items): ?>
										<?php if($items['newly_purchased_asset_id'] == $row['newly_purchased_asset_id']): ?>
											<p>________________________________</p>
											<p>Assigned User/Comments: <?=$items['npa_assigned_user']?></p>
											<p>Parish: <?=$items['parish']?></p>
											<p>Location: <?=$items['location_name']?></p>
											<p>Division: <?=$items['division_name']?></p>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</td>
							<?php if($user['user_level'] != 4){?>
							<td><input type="checkbox" class="updateNots" title="Check if this notification has been updated" name="updatedNotifications[]" id="updatedNotifications" value="<?=$row['newly_purchased_asset_id']?>"></td>
							<?php } ?>
						</tr>
					<?php }
					} ?>
				</tbody>		
			</table>
			<?php if(empty($NewlyPurchasedAssetList)) echo "<h1>No records found</h1>"; ?>
		<?php 
		if(!empty($NewlyPurchasedAssetList)) 
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