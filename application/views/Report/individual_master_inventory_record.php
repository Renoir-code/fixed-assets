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
	<form action="<?=base_url('report/individual_master_inventory_record')?>" method="post">	
            <div class="form-group row search_area">
                <label for="asset_code" class="col-sm-3 form-label">Select Asset Code</label>
                <div class="col-sm-3">
                    <select name="asset_code" id="division_name" class="form-control">
			<option value=-1>-- Select --</option>
			<?php
			foreach($asset_code as $option)
                        {
			    echo '<option value="'.$option["asset_code_id"].'"';
					
                            if(set_value('asset_code') == $option['asset_code_id'])
				echo 'selected';
					
				//if(isset($result) && $option['division_abbre'] == $result['division_abbre'])
                                //    echo ' selected';
						
				echo '>'.$option["description"].' | '.$option["asset_code"].'</option>';
			}
			?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
                </div>			
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
                           Individual Master Inventory Record</h4></center>                   
                   
                   <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="asset_code" class="col-sm-4 form-label displayInLine">Asset Code</label>
                            <div class="col-sm-8 displayInLine">
                                <?=$asset[0]['asset_code']?>
                            </div>
                        </div>
                   </div>
                   <div class="col-lg-6">
                        <div class="form-group row" >
                            <label for="asset_desc" class="col-sm-4 form-label displayInLine">Description</label>
                            <div class="col-sm-8 displayInLine">
                                 <?=$asset[0]['description']?>
                            </div>
                        </div>
                   </div>
                   
                   <table id="mytables" class="table table-bordered">
                        <thead><tr>
                                <th>Date Purchased</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Serial Number</th>
                                <th>Asset Tag</th>
                                <th>Division / Location</th>  
                                <th>Invoice Number</th>
                                <th>Assigned User</th>    
                        </tr></thead>
                        <tbody>
                            <?php
                                foreach($asset as $row)
                                {
                                ?>
                                    <tr>                                        
                                        <td> <?php if($row['date_purchased'] == '0000-00-00 00:00:00')
                                                echo '';
                                            else
                                                echo date_format(new DateTime($row['date_purchased']),"d-M-Y");
                                            ?>                                
                                        </td>     	
                                        <td><?=$row['make']?></td>                                        
                                        <td><?=$row['model']?></td>
                                        <td><?=$row['serial_number']?></td>
                                        <td>
                                            <a href="<?=base_url('main/asset_detail/'.$row['asset_number'])?>"><?=$row['asset_tag']?></a>
                                        </td>                                       
                                        <td><?=$row['division_name']?></td>
                                        <td><?=$row['acct_ref']?></td>  
                                        <td><?=$row['user']?></td>  
                                    </tr>
                                <?php
                                }  
                                ?>
                        </tbody>		
                    </table>
               </div>  
                
                <div class="col-lg-8">
                        <div class="form-group row">
                            <label for="asset_grand_total" class="col-sm-6 form-label displayInLine">Asset Grand Total:</label>
                            <div class="col-sm-2 displayInLine">
                                <?=$num_rows?>
                            </div>
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