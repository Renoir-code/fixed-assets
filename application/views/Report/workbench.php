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
            <div class="row">
               <div class="col-lg-12">
                   <div id="print_content"> 
                   <center><h4>COURT ADMINISTRATION DIVISION<br/>
                           WorkBench View</h4></center>                                     
                   
                   <table id="mytables" class="table table-bordered tableToPrint">
                        <thead><tr>
                                <th>Assigned User</th> 
                                <th>Asset Type&emsp; &emsp; &emsp; &emsp; &emsp;&emsp; &emsp;&emsp;Serial&emsp; &emsp; &emsp; &emsp;&emsp; &emsp; &emsp;Asset Tag</th>  
                        </tr></thead>
                        <tbody>
                            <?php                                
                                foreach($assigned_users as $outer_row)
                                {
                                ?>
                                    <tr>                                        
                                        <td><?='<b>'.$outer_row['user'].'</b><br/>'.$outer_row['location_name'].'<br/>'.$outer_row['division_name']?></td>       
                                        <td>                                                
                                             <?php
                                                   $asset = $this->asset_model->searchFixedAssetByAssignedUser($outer_row['user'],$outer_row['division_id'])->result_array();
                                                   
                                                   foreach($asset as $inner_row)
                                                    {
                                                       
                                                    ?> 
                                                     <table id="workbench_table">                                                         
                                                    <tbody>
                                                        <tr>                                        
                                                            <td width="33%"><?=$inner_row['description']?></td> 	
                                                             <td width="33%">
                                                                 <a href="<?=base_url('main/asset_detail/'.$inner_row['asset_number'])?>"><?=$inner_row['serial_number']?></a>
                                                            </td>  
                                                            <td width="34%">
                                                                 <a href="<?=base_url('main/asset_detail/'.$inner_row['asset_number'])?>"><?=$inner_row['asset_tag']?></a>
                                                            </td> 
                                                        </tr>
                                                    </tbody>
                                            </table>
                                                    <?php
                                                    }  
                                                    ?>
                                        </td>                                        
                                    </tr>
                                <?php
                                }  
                                ?>
                            
                        </tbody>                         
                    </table>
                   
               </div> 
           </div><!--END second ROW-->   
            
        </div>
     </div>
    
    

  
<?php include_once 'inc/footer.inc'?>    
   
    
</body>


</html>