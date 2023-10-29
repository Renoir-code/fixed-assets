<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>
<body>    
<div class="container">
    <?php include_once 'inc/nav.inc'?>
    <h1>Welcome to the Fixed Assets System</h1>     
    
    <table id="mytables" class="table table-bordered">
        <thead><tr>
                <th>Username</th>
                <th>Firstname</th>
                <th>Surname</th>                
                <th>User Level</th>
                <th>Account Enabled</th>
                <th>Date Created</th>
                <th>Last Modified</th>
                <th>Modified By</th>
        </tr></thead>
        <tbody>
            <?php
                foreach($users as $row)
                {
                ?>
                    <tr>
                        <td>
                            <a href="<?=base_url('user/user_detail/'.$row['user_id'])?>"><?=$row['username']?></a>
                        </td>
                        <td><?=$row['firstname']?></td>	
                        <td><?=$row['lastname']?></td>
                        <td>
                            <?php
                            if($row['user_level']== 1)
                                echo 'Regular User';
                            elseif($row['user_level']== 2)
                                echo 'Supervisor';
                            elseif($row['user_level']== 3)
                                echo 'Administrator';
                            else
                                echo 'View Only';
                            ?>
                        </td>
                        <td><?=$row['active']?></td>
                        <td><?=date_format(new DateTime($row['date_created']),"d-M-Y")?></td> 
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
    
</body>
</html>