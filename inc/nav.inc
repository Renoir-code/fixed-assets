<?php

//$this->user_model->isUserLoggedIn();
$name = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
$notifications = $this->asset_model->getNotificationCount();
$notificationCount = '<div id="notificationCount">'.$notifications.'</div>';
$newlypurchasedassets = $this->asset_model->getNewlyPurchasedAssetCount();
$newlypurchasedassetCount = '<div id="notificationCount">'.$newlypurchasedassets.'</div>';
?>
<div class="fixedbar">
<div class="navbar navbar-default navbar-fixed-top">
<div class="container">
	<a href="<?=base_url('main')?>" class="navbar-brand logo_box"><img src="<?=base_url('images/logo.jpg')?>"></a>
	<a class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</a>
	
	<div class="navbar-collapse collapse navHeaderCollapse">
		<ul class="nav navbar-nav navbar-right">
			<?php //if($notifications > 0) { ?>
				<li><?= anchor( "notification/notifications", "Not ".$notificationCount, array( "title"=>"These notifications need your attention" ) ) ?></li>
			<?php //} ?>
			<?php //if($newlypurchasedassets > 0) { ?>
				<li><?= anchor( "notification/newlypurchasedassets", "NPA ".$newlypurchasedassetCount, array( "title"=>"These notifications for Newly Purchased Assets need your attention" ) ) ?></li>
			<?php //} ?>
			<?php
                        //function to test if a user is a supervisor in order to provide them with relevant options. Also to stop unauthorized users from gaining access to functionality above their user level 
                        $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
                        
                        
                        if($user['user_level'] == 3)
                        {
                        ?>
                        <li class="dropdown">
				<a href="" class="dropdown-toggle" data-toggle="dropdown">Users<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><?= anchor( "user/addUser", "Add User", array( "title"=>"Add User" ) ) ?></li>
					<li><?= anchor( "user/viewUsers", "View User", array( "title"=>"View User" ) ) ?></li>
				</ul>
			</li>
                        <?php
                        }
                        ?>
                        
                        
                        <li class="dropdown">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown">Fixed Assets <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?= anchor( "main/index", "View Assets", array( "title"=>"View all the components now in the system" ) ) ?></li>
                                <li><?= anchor( "main/writtenOff", "To Be Written Off", array( "title"=>"View all assets to be written off" ) ) ?></li>
                                
                                <?php
                                if($user['user_level'] != 4)
                                {
                                ?>
                                    <li><?= anchor( "main/addAsset", "Add Asset", array( "title"=>"Add an Asset" ) ) ?></li>
                                
                                    <li><?= anchor( "main/addNewlyPurchasedAssets", "Add Newly Purchased Asset", array( "title"=>"Add a Newly Purchased Asset" ) ) ?></li>
                                <?php
                                }
                                ?>
                            </ul>
			</li>
                       
                        <li class="dropdown">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown">Manage Data <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?= anchor( "main/viewAssetCodes", "Fixed Assets", array( "title"=>"View all the components now in the system" ) ) ?></li>
				<li><?= anchor( "main/viewLocationDivision", "Location/Division", array( "title"=>"Add an Asset" ) ) ?></li>                                
                            </ul>
			</li>
                                               
			<li><?= anchor( "report", "Report", array( "title"=>"Report" ) ) ?></li>    	
			
			 <li class="dropdown">
				<a href="" class="dropdown-toggle" data-toggle="dropdown">Related Systems <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><?= anchor( "user/computer_inventory", "Computer Inventory", array( "title"=>"Computer Inventory" ) ) ?></li>
					<li><?= anchor( "user/stock_system", "Stock System", array( "title"=>"Stock System" ) ) ?></li>
				</ul>
			</li>
                        
                        <li class="dropdown">
				<a href="" class="dropdown-toggle" data-toggle="dropdown">Logged in as: <?=$name?> <b class="caret"></b></a>
    		<ul class="dropdown-menu">
    			<li><?= anchor( "user/changePassword", "Change Password", array( "title"=>"Change Password" ) ) ?></li>
				<li><?= anchor( "user/logout", "Logout", array( "title"=>"Logout" ) ) ?></li>
    		</ul>
			</li>
			
			
			
		</ul>
	</div>
</div>
</div>
</div>