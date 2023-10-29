<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <?php include_once 'inc/Report/report_sidebar.inc'?>
        
    <div class="report_content" id="restore_page">           
            <div class="row">
               <div class="col-lg-12">
                   <center><h4>COURT ADMINISTRATION DIVISION<br/>
                           Master Inventory Records</h4></center>                                     
                   
                   <table id="mytables" class="table table-bordered">
                        <thead><tr>
                                <th>Asset Code</th>
                                <th>Description</th>                                
                                <th>Division/Location &emsp; &emsp; &emsp; &emsp; Asset Count</th>  
                                <th>Asset Total</th>                                 
                        </tr></thead>
                        <tbody>
                            <?php
                                $grand_total = 0;
                                foreach($assets as $outer_row)
                                {
                                ?>
                                    <tr>                                        
                                        <td><?=$outer_row['asset_code']?></td> 	
                                        <td><?=$outer_row['description']?></td>                                        
                                        <td>
                                           
                                                
                                             <?php
                                                   $asset = $this->asset_model->searchFixedAssetByAssetCode2($outer_row['asset_code_id'])->result_array();
                                                   
                                                   $total_asset_count = 0;
                                                   
                                                   foreach($asset as $inner_row)
                                                    {
                                                       
                                                    ?> 
                                                     <table style="width:100%">
                                                    <tbody>
                                                        <tr>                                        
                                                            <td  width="80%"><?=$inner_row['division_name']?> <span style="font-size:0.9rem">(<?=$inner_row['location_name']?>)</span></td> 	
                                                            <td width="20%"><?=$inner_row['asset_count']?></td> 

                                                            <?php $total_asset_count += $inner_row['asset_count']; ?>
                                                            <?php $grand_total += $inner_row['asset_count']; ?>
                                                        </tr>
                                                    </tbody>
                                            </table>
                                                    <?php
                                                    }  
                                                    ?>
                                        </td>
                                        <td><?=$total_asset_count?></td>
                                    </tr>
                                <?php
                                }  
                                ?>
                        </tbody>		
                    </table>
                   <?='<b>Grand Total: '.$grand_total.'</b>'?>
                   
               </div> 
           </div><!--END second ROW-->      
        </div>
    
    

  
<?php include_once 'inc/footer.inc'?>   
        
    
</body>
</html>