<?php
    if(empty($this->uri->segment(3)))
    {
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
                <label for="make" class="col-sm-3 form-label">Number of Records: </label>
                <div class="col-sm-2">
                        <select name="num_records" id="num_records" class="form-control">                        
                            <?php
                            for($i=1; $i<=100; $i++)
                            {
                                    echo '<option value="'.$i.'"';

                                    if(set_value('num_records') == $i)
                                        echo ' selected';

                                    echo '>'.$i.'</option>';                                        
                            }
                            ?>
                        </select>                
                </div>  
                <div class="col-sm-7">
                    If the serial number is not automatically generated, only one (1) record will be saved regardless of the number of records selected.
                </div>
            </div>
        </div>    
    </div>
    <div class="customHr">.</div>
<?php 
}?>
     <?php  ?>
    <div class="row">
		<div class="col-lg-6">
		<div class="form-group row">
			<label for="asset_code" class="col-sm-4 form-label">Asset Type</label>
			<div class="col-sm-7">
                            <select name="asset_code" id="asset_code" class="form-control" 
                                <?php  if(isset($result))echo 'readonly="readonly"'; ?> >
					<option value=-1>-- Select --</option>
				<?php
				foreach($assetCode as $option){
					echo '<option value="'.stripslashes($option["asset_code_id"]).'"';
					
					if(set_value('asset_code') == stripslashes($option['asset_code_id']))
						echo ' selected';
					
					if(isset($result) && stripslashes($option['asset_code_id']) == stripslashes($result['asset_code_id']))
						echo ' selected';
						
					echo '>'.$option["description"].' | '.$option["asset_code"].'</option>';
				}  
				?>
				</select>
			</div>
                        
                        <div class="col-sm-1">
                            <a href="#" title="Click to Add a New Asset Code" onclick="toggle_visibility('asset_code_div');">
                                <img border="0" src="<?=base_url()?>/images/plus.png" width="15" height="15">
                           </a>
                        </div>                        
                    </div>                      
                    <div id="asset_code_div">
                        
                        <?php                        
                        if(($this->input->post("asset_code2") && $this->input->post("asset_code2")!='') || ($this->input->post("asset_description2") && $this->input->post("asset_description2")!=''))
                        {                            
                            echo '<script> $("#asset_code_div").css("display", "block"); </script>'; 
                        }
                        ?>
                        
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type="text" placeholder="code" value="<?php if($this->input->post("asset_code2")) echo $this->input->post("asset_code2");?>" name="asset_code2" class="form-control" id="asset_code2" />                                
                            </div>
                            
                            <div class="col-sm-8">
                                <input type="text" placeholder="name" value="<?php if($this->input->post("asset_description2")) echo $this->input->post("asset_description2");?>" name="asset_description2" class="form-control" id="asset_description2" />
                            </div>
                        </div>
                        Last Asset Code: <?=$last_asset_code?>
                    </div>
                    
                    
		
		<div class="form-group row">
			<label for="make" class="col-sm-4 form-label">Make</label>
			<div class="col-sm-8">
				<input type="text" value="<?php 
				if(!isset($result))
                                    echo set_value('make'); 
				else 
                                    echo $result['make'];	
				?>" 
                                name="make" value="<?php echo 'test'; ?>" class="form-control" id="make" />
			</div>
		</div>
                    
                <div class="form-group row">
			<label for="model" class="col-sm-4 form-label">Model</label>
			<div class="col-sm-8">
				<input type="text" value="<?php 
				if(!isset($result))
                                    echo set_value('model'); 
				else 
                                    echo $result['model'];	
				?>" 
				name="model" class="form-control" id="make" />
			</div>
		</div>
                    
                <div class="form-group row">
			<label for="serial_number" class="col-sm-4 form-label">Serial Number</label>
			<div class="col-sm-8">
				<input type="text" value="<?php 
				if(!isset($result))
                                    echo set_value('serial_number'); 
				else                                 
                                    echo $result['serial_number'];?>" name="serial_number" class="form-control" id="serial_number" />
			</div>
		</div>
		
                <div class="form-group row">
			<label for="description1" class="col-sm-4 form-label">General Description</label>
			<div class="col-sm-8">
				<textarea rows="3" name="description1" id="device_com" class="form-control"><?php
				if(isset($result)) echo $result['description1'];
                                else echo set_value('description1');
				?></textarea>
			</div>
		</div>
                    
                <div class="form-group row">
			<label for="parish" class="col-sm-4 form-label">Parish</label>
			<div class="col-sm-8">
				<select name="parish" id="parish" class="form-control">
					<option value=-1>-- Select --</option>
				<?php
				foreach($parish as $option){
					echo '<option value="'.stripslashes($option["parish_id"]).'"';
					
					if(set_value('parish') == stripslashes($option['parish_id']))
                                            echo ' selected';   
					
					if(isset($result) && stripslashes($option['parish_id']) == stripslashes($result['parish_id']))
                                            echo ' selected';                                                
						
					echo '>'.$option["parish"].'</option>';                                        
				}
				?>
				</select>
			</div>
		</div>  
                    
                <div class="form-group row">
			<label for="location_type" class="col-sm-4 form-label">Location Type</label>
			<div class="col-sm-8">
				<select name="location_type" id="location_type" class="form-control">
					<option value=-1>-- Select --</option>
				<?php
				foreach($location_type as $option){
					echo '<option value="'.stripslashes($option["location_type_id"]).'"';
					
					if(set_value('location_type') == stripslashes($option['location_type_id']))
                                            echo ' selected';   
					
					if(isset($result) && stripslashes($option['location_type_id']) == stripslashes($result['location_type_id']))
                                            echo ' selected';                                                
						
					echo '>'.$option["location_type"].'</option>';                                        
				}
				?>
				</select>
			</div>
		</div>             
                    
                <div class="form-group row">
			<label for="location_name" class="col-sm-4 form-label">Location</label>
			<div class="col-sm-7">
				<select name="location_name" id="location_name" class="form-control">
					<option value=-1>-- Select --</option>
				<?php
				foreach($location as $option){
					echo '<option value="'.stripslashes($option["location_id"]).'"';
					
					if(set_value('location_name') == stripslashes($option['location_id']))
                                            echo ' selected';   
					
					if(isset($result) && stripslashes($option['location_id']) == stripslashes($result['location']))
                                            echo ' selected';                                                
						
					echo '>'.$option["location_name"].' | '.$option["location_abbre"].'</option>';                                        
				}
				?>
				</select>
                        </div> 
                        <div class="col-sm-1">
                            <a href="#" title="Click to Add Location" onclick="toggle_visibility('location_div');">
                                <img border="0" src="<?=base_url()?>/images/plus.png" width="15" height="15">
                           </a>
			</div>                        
		</div>                      
                    <div id="location_div">
                        
                        <?php                        
                        if(($this->input->post("location_abbre2") && $this->input->post("location_abbre2")!='') || ($this->input->post("location2") && $this->input->post("location2")!=''))
                        {                            
                            echo '<script> $("#location_div").css("display", "block"); </script>'; 
                        }
                        ?>
                        
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type="text" placeholder="abbre" value="<?php if($this->input->post("location_abbre2")) echo $this->input->post("location_abbre2");?>" name="location_abbre2" class="form-control" id="location_abbre2" />
                            </div>
                            
                            <div class="col-sm-8">
                                <input type="text" placeholder="name" value="<?php if($this->input->post("location2")) echo $this->input->post("location2");?>" name="location2" class="form-control" id="location2" />
                            </div>
                        </div>
                    </div>
               
                    
                <div class="form-group row">
			<label for="division_name" class="col-sm-4 form-label">Division</label>
			<div class="col-sm-7">
				<select name="division_name" id="division_name" class="form-control">
					<option value=-1>-- Select --</option>
				<?php
				foreach($division as $option){
					echo '<option value="'.stripslashes($option["division_id"]).'"';
					
										
                                        if(isset($result) && stripslashes($option['division_id']) == stripslashes($result['division']))
                                            echo ' selected';
                                        
                                        if(set_value('division_name') == stripslashes($option['division_id']))
						echo ' selected';
                                        
					echo '>'.$option["division_name"].'</option>';
				}
				?>
				</select>
			</div>
                        
                        <div class="col-sm-1">
                            <a href="#" title="Click to Add Division" onclick="toggle_visibility('division_div');">
                                <img border="0" src="<?=base_url()?>/images/plus.png" width="15" height="15">
                           </a>
			</div>                        
		</div>   
                    
                    <div id="division_div">
                        <?php                        
                        if(($this->input->post("division_abbre2") && $this->input->post("division_abbre2")!='') || ($this->input->post("division2") && $this->input->post("division2")!=''))
                        {                            
                            echo '<script> $("#division_div").css("display", "block"); </script>'; 
                        }
                        ?>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type="text" placeholder="abbre" value="<?php if($this->input->post("division_abbre2")) echo $this->input->post("division_abbre2");?>" name="division_abbre2" class="form-control" id="division_abbre2" />
                            </div>
                            
                            <div class="col-sm-8">
                                <input type="text" placeholder="name" value="<?php if($this->input->post("division2")) echo $this->input->post("division2");?>" name="division2" class="form-control" id="division2" />
                            </div>
                        </div>
                    </div>
               
                    
		
		</div><!--END LEFT COLUMN-->		
                 
                
		<div class="col-lg-6">
                    
                <div class="form-group row">
			<label for="date_purchased" class="col-sm-4 form-label">Date Purchased</label>
			<div class="col-sm-8">
				<input type="Date" maxlength="10" value="<?php 
				if(!isset($result['date_purchased'])) 
                                    echo set_value('date_purchased');
				else
                                {
                                    if($result['date_purchased'] == '0000-00-00 00:00:00')
                                        echo '';
                                    else 
                                        echo date('Y-m-d',  strtotime ($result['date_purchased']));                                    
                                }
				?>" name="date_purchased" class="form-control" id="date_purchased" />
			</div>
		</div>
                    
		<div class="form-group row">
			<label for="supplier" class="col-sm-4 form-label">Supplier</label>
			<div class="col-sm-8">
				<input type="text" value="<?php
				if(!isset($result)) echo set_value('supplier'); 
				else echo $result['supplier'];	
				?>" name="supplier" class="form-control" id="supplier" />
			</div>
		</div>
		
		<div class="form-group row">
			<label for="cost" class="col-sm-4 form-label">Cost</label>
			<div class="col-sm-8">
                            <input type="number" step="0.01" value="<?php
				if(!isset($result)) echo set_value('cost'); 
				else echo $result['cost'];	
				?>" name="cost" class="form-control" id="cost" />
			</div>
		</div>
		
		<div class="form-group row">
			<label for="acct_ref" class="col-sm-4 form-label">Invoice Number</label>
			<div class="col-sm-8">
				<input type="text" value="<?php
				if(!isset($result)) echo set_value('acct_ref'); 
				else echo $result['acct_ref'];	
				?>" name="acct_ref" class="form-control" id="acct_ref" />
			</div>
		</div>
                    
                <div class="form-group row">
			<label for="user" class="col-sm-4 form-label">Assigned User</label>
			<div class="col-sm-8">
				<input type="text" value="<?php
				if(!isset($result)) echo set_value('user'); 
				else echo $result['user'];	
				?>" name="user" class="form-control" id="user" />                                
			</div>
                        <!--<label style="font-size:smaller;color: green">&emsp;&emsp;*Once user name has been changed, all reference to that user will be updated</label>-->
		</div>
                    
                <div class="form-group row">
			<label for="description2" class="col-sm-4 form-label">Remarks</label>
			<div class="col-sm-8">
				<textarea rows="3" name="description2" id="device_com" class="form-control"><?php
				if(isset($result)) echo $result['description2'];
                                else echo set_value('description2'); 
				?></textarea>
			</div>

            <br>


            <div class="form-group row">
			<label for="" class="col-sm-4 form-label">Upload File</label>
			<div class="col-sm-8">
				<input type="file" size="50" value="<?php
				if(!isset($result)) echo set_value('asset_pictures'); 
				else echo $result['asset_pictures'];	
                                ?>" name="asset_pictures" class="form-control"/>
			</div>

		</div>
                    
                <div class="form-group row">
			<label for="asset_tag" class="col-sm-4 form-label">Asset Tag</label>
			<div class="col-sm-8">
				<input type="text" value="<?php
				if(!isset($result)) echo set_value('asset_tag'); 
				else echo $result['asset_tag'];	
                                ?>" name="asset_tag" disabled class="form-control" id="asset_tag" />
			</div>
		</div>
                    
                <div class="form-group row">
			<label for="date_created" class="col-sm-4 form-label">Date Created</label>
			<div class="col-sm-8">
				<input type="Date" maxlength="10" value="<?php 
				if(isset($result['time_created'])) 
                                    echo date('Y-m-d',  strtotime ($result['time_created']));				
				?>" name="date_created" disabled class="form-control" id="date_created" />
			</div>
		</div>
                    
                <div class="form-group row">
			<label for="created_by" class="col-sm-4 form-label">Created By</label>
			<div class="col-sm-8">
				<input type="text" value="<?php
				if(isset($result)) echo $result['created_by'];	
                                ?>" name="created_by" disabled class="form-control" id="created_by" />
			</div>
		</div>
                    
                    
                <?php
                if(isset($result))
                {
                ?>
                <div class="form-group row">
                    
                    <div class="col-sm-4">
                            <input type="checkbox" name='lost_stolen' id="lost_stolen" value="y" <?php 
                            
                            $user = $this->user_model->getUserById($_SESSION['fa_user_id']);                        
                            
                            if(!empty($_POST['lost_stolen']))
                                echo 'checked';
                            
                            elseif(isset($result) && $result['lost_stolen'] =='y' )
                            {
                                echo 'checked';
                                
                                if($user['user_level'] >=1 )
                                    echo ' disabled="disabled"';
                            }
                            
                            
                            
                            ?>> Lost/Stolen
                    </div>
                    </div> 
                <?php
                }
                ?>
                    
                    <div style="display:none" id="lost_stolen_div"> 
                  
                        <?php  
                            if((isset($result) && $result['lost_stolen'] =='y') || !empty($_POST['lost_stolen']) )
                                echo '<script> $("#lost_stolen_div").css("display", "block"); </script>';
                        ?>
                        
                        <?php 
                            
                            if(!empty($fixed_asset_attachment))
                            {
                                $to_replace = base_url().'uploadedFiles/'; 
                                
                                echo '<div class="form-group row">';
                                echo '<ul>';
                                
                                foreach($fixed_asset_attachment as $file)
                                {
                                    $filename = str_replace($to_replace, '', $file["filename"]); 
                                    echo '<li> <a href="'.base_url("main/openFile/{$filename}").'">'.$filename.'</a> </li>';
                                }
                                
                                 echo '</ul>';
                                 echo '</div>';
                            }
                        ?>

                        <div class="form-group row">
                            <label for="attachment" class="col-sm-1 form-label">Attachment</label>
                            <br/>
                            <div class="col-sm-8">
                                    <input type="file" title="Only Word and PDF documents can be added here." value="<?=set_value('attachment')?>" name="attachment" class="form-control" id="attachment" />
                            </div>
                        </div>
                    </div>
                    
		
                
                    
                    
                    
                <div class="form-group row">
                    
                    <div class="col-sm-8">
                            <input type="checkbox" name='written_off' id="written_off" value="y" <?php 
                            
                            $user = $this->user_model->getUserById($_SESSION['fa_user_id']);                        
                            
                            if(!empty($_POST['written_off']))
                                echo 'checked';
                            
                            elseif(isset($result) && $result['written_off'] =='y' )
                            {
                                echo 'checked';
                                
                                //if($user['user_level'] <=2 )
                                    //echo ' disabled="disabled"';
                            }
                            
                            
                            
                            ?>> To Be Written-Off 
                    </div>
		</div> 
                <div style="display:none" id="bos"> 
                  
                    <?php  
                        if((isset($result) && $result['written_off'] =='y') || !empty($_POST['written_off']) )
                            echo '<script> $("#bos").css("display", "block"); </script>';
                    ?>  
                    
                     <div class="form-group row">
                    
                        <div class="col-sm-8">
                                <input type="checkbox" name='board_of_surveyed' id="board_of_surveyed" value="y" <?php 

                                $user = $this->user_model->getUserById($_SESSION['fa_user_id']);                        

                                if(!empty($_POST['board_of_surveyed']))
                                    echo 'checked';

                                elseif(isset($result) && $result['board_of_surveyed'] =='y' )
                                {
                                    echo 'checked';

                                    if($user['user_level'] <=2 )
                                        echo ' disabled="disabled"';
                                }
                                ?>> Add to Board of Survey 
                        </div>
                    </div> 
                    
                    <div class="form-group row" >
                        <label for="date_writeoff" class="col-sm-4 form-label">Board of Surveyed Date</label>
                        <div class="col-sm-8">
                                <input type="Date" maxlength="10" value="<?php 
                                if(!isset($result['date_writeoff'])) 
                                    echo set_value('date_writeoff');
                                else
                                {
                                    if($result['date_writeoff'] == '0000-00-00 00:00:00')
                                        echo '';
                                    else 
                                        echo date('Y-m-d',  strtotime ($result['date_writeoff']));                                    
                                }
                                ?>" name="date_writeoff" class="form-control" id="date_writeoff" />
                        </div>
                    </div> 
                </div>
		</div><!--END RIGHT COLUMN-->                                
                
                <div class="col-lg-10" id="repair_container_div">
                    <div class="form-group row" id="repair_container">
                        
                        <?php 
                            
                            $user = $this->user_model->getUserById($_SESSION['fa_user_id']);      
                            //view only users cannot add repair data
                            if($user['user_level'] !=4)
                            {
                            ?>                        
                                <p id="add_field"><a href="#"><span>&raquo; Add Particulars of Repairs & Servicing.....</span></a></p> 
                            <?php
                            }
                            if(isset($repair_table) && $repair_table != '')
                                echo $repair_table;
                        ?>
                    </div> 
                </div>                
		</div><!--END CONTAINING ROW-->               
                