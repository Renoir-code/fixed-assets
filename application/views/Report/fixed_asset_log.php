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
        <div class="error_holder"><?=validation_errors()?></div>
	<form action="<?=base_url('report/fixed_asset_log')?>" method="post">	
            <div class="form-group row search_area">
                <label for="start_date" id="start_date" class="col-sm-2 form-label">Start Date</label>
                <div class="col-sm-3">
                    <input type="date" value="<?php 
				if(!isset($start_date))
                                    echo set_value('start_date'); 
				else 
                                    echo $start_date;	
				?>"  
			name="start_date" class="form-control" id="start_date" />                   
                </div>
                
                <label for="end_date" id="end_date" class="col-sm-2 form-label">End Date</label>
                <div class="col-sm-3">
                    <input type="date" value="<?php 
				if(!isset($end_date))
                                    echo set_value('end_date'); 
				else 
                                    echo $end_date;	
				?>"  
			name="end_date" class="form-control" id="end_date" />                   
                </div>
                <div class="col-sm-2">
                    <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
                </div>			
            </div>
        </form> 
        
        <?php  
        
        //if(!isset($value) && empty($value))
          //  ;
        if(isset($asset) )
        {
        ?>   
            
            <div class="row">
               <div class="col-lg-12">
                   <div id="print_content"> 
                   <center><h4>COURT ADMINISTRATION DIVISION<br/>
                       FIXED ASSET INVENTORY RECORDS LOG - BY DATE<br/>
                       Period:   <?=  date_format(new DateTime(set_value('start_date')),"d-M-y")?>    To   <?=date_format(new DateTime(set_value('end_date')),"d-M-y")?>  
                       </h4>
                   </center> 
                   
                   
                <?php  
                 if(isset($asset) && empty($asset))
                     echo'<h3>No results found</h3>';

                 else
                 {           
                     if(!empty($asset))
                     {
                 ?>   
                   
                   <table id="mytables" class="table table-bordered">
                        <thead><tr>
                                <th>Description</th>
                                <!-- <th>Make</th>
                                <th>Model</th> -->
                                <th>Serial Number</th>
                                <th>Asset Tag</th>
                                <th>Location</th> 
                                <th>Division</th> 
                                <th>Action</th>
                                <th>Modified By</th> 
                                <th>Time Modified</th>
                        </tr></thead>
                        <tbody>
                            <?php
                                foreach($asset as $row)
                                {
                                ?>
                                    <tr>                                        
                                        <td><?=$row['description']?></td>
                                        <td><a href="<?= base_url('main/asset_detail/'.$row['asset_number']) ?>"><?=$row['serial_number']?></a></td>                                                                              
                                        <td><?=$row['asset_tag']?></td> 
                                        <td><?=$row['location_name']?></td>   
                                        <td><?=$row['division_name']?></td>   
                                        <td><?=$row['action']?></td>  
                                        <td><?=$row['modified_by']?></td>    
                                        <td><?=date_format(new DateTime($row['time_modified']),"d-M-Y")?></td>  
                                        
                                    </tr>
                                <?php
                                }  
                                ?>
                        </tbody>		
                    </table>
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
    
</body>
</html>