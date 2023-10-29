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
                           Lost/Stolen</h4></center>
                                      
                   <table id="mytables" class="table table-bordered">
                        <thead><tr>
                                <th>Description</th>                                
                                <th>Make</th>
                                <th>Model</th>
                                <th>Serial Number</th>                                
                                <th>Supplier</th> 
                                <th>Cost</th> 
                                <th>Attachment</th> 
                        </tr></thead>
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
                                        <td><?=$row['supplier']?></td>                                        
                                        <td>$<?=number_format((double)$row['cost'],2,'.',',')?></td>
                                        <td>
                                            <?php
                                                   $attachment = $this->asset_model->getFixedAssetAttachment($row['asset_number']);
                                                   $to_replace = '/uploadedFiles/'; 
                                                   foreach($attachment as $file)
                                                   {
                                                       $filename = str_replace($to_replace, '', $file["filename"]); 
                                                       echo '* <a href="'.base_url("main/openFile/{$filename}").'">'.$filename.'</a> <br/>';
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
    
</div>

<?php include_once 'inc/footer.inc'?>   
    
</body>
</html>