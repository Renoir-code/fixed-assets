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
	<form action="<?=base_url('report/written_off_assets')?>" method="post">	
            <div class="form-group row search_area">              
                <table>
                    <tr>
                        <td><label for="start_date" id="start_date" class="col-sm-12 form-label">Start Date</label></td>
                        <td><div class="col-sm-12">
                        <input type="date" value="<?php 
                                    if(!isset($start_date))
                                        echo set_value('start_date'); 
                                    else 
                                        echo $start_date;	
                                    ?>"  
                            name="start_date" class="form-control" id="start_date" />                   
                    </div>   </td>
                        <td><label for="end_date" id="end_date" class="col-sm-12 form-label">End Date</label></td>
                        <td><input type="date" value="<?php 
                                    if(!isset($end_date))
                                        echo set_value('end_date'); 
                                    else 
                                        echo $end_date;	
                                    ?>"  
                            name="end_date" class="form-control" id="end_date" /> </td>
                        <td><label for="serial" id="search_label" class="col-sm-12 form-label">Serial</label></td>
                        <td><input type="text" value="<?=set_value('serial')?>" id="serial" name="serial" class="form-control" /></td>
                        <td><input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" /></td>
                    </tr>
                </table>
            </div>
        </form> 
        
        <?php  
        
        //if(!isset($value) && empty($value))
          //  ;
        if(isset($asset) && empty($asset))
            echo'<h3>No results found</h3>';
        
        else
        {
            if(!empty($asset))
            {
        ?>   
             
            <div class="row">
               <div class="col-lg-12">
                   <div id="print_content">
                   <center><h4>COURT ADMINISTRATION DIVISION<br/>
                       WRITTEN OFF ASSETS<br/>
                       Period:  
                     <?php 
                        if(isset($start_date,$end_date,$serial))
                        {   if($start_date == '0000-00-00 00:00:00')
                                echo 'Start';
                            else
                                echo date_format(new DateTime($start_date),"d-M-y");
                            
                            echo' To '.date_format(new DateTime($end_date),"d-M-y");
                                
                        }
                        else 
                        {
                            echo 'Start To End';
                        }?>  
                       </h4></center>
                                      
                   <table id="mytables" class="table table-bordered">
                        <thead><tr>
                                <th>Description</th>
                                <th>Date Written Off</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Serial Number</th>
                                <th>Invoice Number</th>
                                <th>Supplier</th> 
                                <th>Cost</th>                                
                        </tr></thead>
                        <tbody>
                            <?php
                                foreach($asset as $row)
                                {
                                ?>
                                    <tr>                                        
                                        <td><?=$row['description']?></td>
                                        <td>
                                            <?php
                                             if($row['date_writeoff'] == '0000-00-00 00:00:00')
                                                echo '';
                                             else 
                                                echo date('Y-m-d',  strtotime ($row['date_writeoff']));  
                                            ?>                                        
                                        </td>  
                                        <td><?=$row['make']?></td>                                        
                                        <td><?=$row['model']?></td>
                                        <td><?=$row['serial_number']?></td>                                                                              
                                        <td><?=$row['acct_ref']?></td>    
                                        <td><?=$row['supplier']?></td>                                        
                                        <td>$<?=number_format((double)$row['cost'],2,'.',',')?></td>                                                                               
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
        ?>
                
       
    
</div>

<?php include_once 'inc/footer.inc'?>   
    
</body>
</html>