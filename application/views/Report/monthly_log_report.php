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
	<form action="<?=base_url('report/monthly_log_report')?>" method="post">	
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
                       FIXED ASSET INVENTORY RECORDS (Monthly Log) - BY DATE<br/>
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
                                <th>Date Created</th>
                                <th>Description</th>                                
                                <th>User</th> 
                                <th>Location</th> 
                                <th>Division</th> 
                                <th>Action</th>
                        </tr></thead>
                        <tbody>
                            <?php
                                foreach($asset as $outer_row)
                                {
                                ?>
                                    <tr>                                        
                                        <td><?=date_format(new DateTime($outer_row['time_created']),"d-M")?></td>                                         
                                        <td> <a href="<?=base_url('main/asset_detail/'.$outer_row['asset_number'])?>"><?=$outer_row['description']?></a></td>
                                        <td><?=$outer_row['user']?></td>  
                                        <td><?=$outer_row['location_name']?></td>   
                                        <td><?=$outer_row['division_name']?></td>  
                                        <td width="45%">                                                
                                             <?php
                                                   $asset = $this->asset_model->getFixedAssetLogData($outer_row['asset_number']);
                                                   foreach($asset as $inner_row)
                                                    {
                                                       
                                                    ?> 
                                                     <table id="monthly_report_inner_table" style="width:100%">
                                                    <tbody>
                                                        <tr>                                        
                                                            <td  width="80%"><?=$inner_row['action']?></td> 	
                                                            <td width="20%"><?=date_format(new DateTime($inner_row['time_modified']),"d-M")?></td> 
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
                       <?='<b>Grand Total: '.$asset_count.'</b>'?>
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