<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>  

<?php 
	//function to test if a user is a supervisor in order to provide them with relevant options. Also to stop unauthorized users from gaining access to functionality above their user level 
	$user = $this->user_model->getUserById($_SESSION['fa_user_id']);
?>
  
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Manage Asset Codes</h1>   
    
    <div class="col-lg-3" style="margin-right: -14px; float: right;">
            <input type="text" placeholder="Filter results" value="<?=set_value('filter')?>" 
               name="filter" class="form-control" id="filter"
               title="You can search by Asset Code or Description" />
    </div>
    
    <table id="mytables" class="table table-bordered">
        <thead><tr>
                <th>Asset Code</th>
                <th>Description</th>
                <th>Asset Count</th>
                <th>Last Modified</th>
                <th>Modified By</th>
        </tr></thead>
        <tbody>
            <?php
                foreach($assets as $row)
                {
                ?>
                    <tr>
                        <td>
							<?php if($user['user_level'] != 4){?>
								<a href="<?=base_url('main/edit_asset/'.$row['asset_code_id'])?>"><?=$row['asset_code']?></a>
							<?php }else echo $row['asset_code'];
							?>
                        </td>
                        <td><?=$row['description']?></td>	
                        <td><?=$row['asset_count']?></td> 
                        <td> 
                            <?php if($row['last_modified'] == '0000-00-00 00:00:00')
                                    echo '';
                                else
                                    echo date_format(new DateTime($row['last_modified']),"d-M-Y");
                            ?>                                
                        </td>         
                        <td><?=$row['modified_by']?></td>
                    </tr>
                <?php
                }  
                ?>
        </tbody>		
    </table>
</div>

<?php include_once 'inc/footer.inc'?>   
    

<script>
    $('#filter').keyup(function(){
        var filter = $(this).val();

        if(filter != '' && filter != ' '){
                filterAssetCode(filter).done(function(data){
                       //fill the table with the results that have been returned
                        data = JSON.parse(data);                        
                        var table = '<tr><th>Asset Code</th><th>Description</th><th>Asset Count</th><th>Last Modified</th><th>Modified By</th></tr>';
                        
                        if(data !== 'empty') 
                        {
                            for(var i in data)
                            {
                                var url="<?=base_url('main/edit_asset').'/'?>";
                                table += '<td><a href="'+url+data[i].asset_code_id+'">'  +data[i].asset_code+'</a></td><td>'+data[i].description+'</td><td>'+data[i].asset_count+'</td>';
                                
                                if(data[i].last_modified == "0000-00-00 00:00:00")
                                    table += '<td></td>';
                                else
                                     table += '<td>'+$.datepicker.formatDate("d-M-yy",new Date(data[i].last_modified))+'</td>';
                                
                                table += '<td>'+data[i].modified_by+'</td></tr>';
                            }
                        }
                        else
                            table += '<tr><td></td><td></td><td></td><td></td><td></td></tr>';
                        $('#mytables').html(table);
                });
        }
    });

    function filterAssetCode(filter){
        return $.ajax({
                url: 'filterAssetCode',
                data: {filter: filter},
                async: true
        });
    }
</script>    
    
</body>
</html>