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
	 <?php include_once 'inc/Report/search_header.inc'?>
        
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
                           Office Machines & Equipment Record</h4></center>
                   
                   <table id="report_table">
                   <tr>
                       <th>Asset Number</th>
                       <th>Make</th>  
                       <th>Model</th>
                       <th>Serial Number</th>
                     </tr>
                     <tr>
                       <td><?=$asset[0]['asset_number']?></td>
                       <td><?=$asset[0]['make']?></td>  
                       <td><?=$asset[0]['model']?></td>
                       <td><?=$asset[0]['serial_number']?></td>
                     </tr>
                     <tr>
                       <th colspan="4">General Description</th>    
                     </tr>
                     <tr>
                       <td colspan="4" height="100"><?=$asset[0]['description1']?></td>    
                     </tr>
                     <tr>
                       <th>Date Purchased</th>
                       <th>Supplier</th>  
                       <th>Cost</th>
                       <th>Invoice Number</th>
                     </tr>
                     <tr>
                       <td><?=date_format(new DateTime($asset[0]['date_purchased']),"d-M-Y")?></td>
                       <td><?=$asset[0]['supplier']?></td>                        
                       <td>$<?=number_format((double)$asset[0]['cost'],2,'.',',')?></td>
                       <td><?=$asset[0]['acct_ref']?></td>
                     </tr>
                     <tr>
                       <th colspan="2">Parish</th>  
                       <th colspan="2">Location Type</th>    
                     </tr>
                     <tr>
                       <td colspan="2"><?=$asset[0]['parish']?></td>  
                       <td colspan="2"><?=$asset[0]['location_type']?></td>      
                     </tr>
                     <tr>
                       <th>Location</th>
                       <th>Division</th>  
                       <th>Asset Code</th>
                       <th>Code Description</th>
                     </tr>
                     <tr>
                       <td><?=$asset[0]['location_name']?></td>
                       <td><?=$asset[0]['division_name']?></td>  
                       <td><?=$asset[0]['asset_code']?></td>
                       <td><?=$asset[0]['description']?></td>
                     </tr>
                     <tr>
                       <th colspan="2">User</th>  
                       <th colspan="2">Asset Tag</th>    
                     </tr>
                     <tr>
                       <td colspan="2"><?=$asset[0]['user']?></td>  
                       <td colspan="2"><?=$asset[0]['asset_tag']?></td>      
                     </tr>
                     <tr>
                         <th colspan="4">Remarks</th>    
                     </tr>
                     <tr>
                       <td colspan="4" height="100"><?=$asset[0]['description2']?></td>    
                     </tr> 
               </table>
               
               <div id="repair_table_div"><br/>
                   <b><center>PARTICULARS OF REPAIRS AND SERVICING</center></b>
                    <table id="repair_table" width="100%"><tr>
                      <th rowspan="2">Date</th>
                      <th rowspan="2">Nature of Repairs or Service</th>
                      <th colspan="2">Period out of use</th>
                      <th rowspan="2">Cost</th>
                      </tr>
                      <tr>    
                      <th>From</th>
                      <th>To</th>    
                      </tr>
                      <?php
                      if(!empty($repair_result))
                      {
                          foreach($repair_result as $repair)
                          {
                              echo '<tr>';
                              echo '<td>'.date_format(new DateTime($repair['repair_date']),"d-M-Y").'</td>';
                              echo '<td>'.$repair['nature_of_repair'].'</td>';
                              echo '<td>'.date_format(new DateTime($repair['from_date']),"d-M-Y").'</td>';
                              echo '<td>'.date_format(new DateTime($repair['to_date']),"d-M-Y").'</td>';
                              echo '<td>$'.number_format($repair['repair_cost']).'</td>';
                              echo '</tr>';
                          }
                      }
                      ?>                                
                   </table>                         
               </div>
                   
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