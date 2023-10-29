<div class="col-sm-2 sidebar">
	<p class="tollbar">CAD-FA</p>
	<ul>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/fixed_asset_record'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/fixed_asset_record/", "Office Machines & Equipment Record", array( "title"=>"Fixed Asset Records" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/location_record'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/location_record/", "Location Record", array( "title"=>"Location Record" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/fixed_asset_register_by_date'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/fixed_asset_register_by_date/", "Fixed Asset Register By Date", array( "title"=>"Fixed Asset Register By Date" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/fixed_asset_log'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/fixed_asset_log/", "Fixed Asset Log", array( "title"=>"Fixed Asset Log" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/individual_master_inventory_record'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/individual_master_inventory_record/", "Individual Master Inventory Record", array( "title"=>"Individual Master Inventory Record" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/master_inventory_records'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/master_inventory_records/", "Master Inventory Records", array( "title"=>"Master Inventory Records" ) ) ?>
            </li> 
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/workbench'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/workbench/", "Workbench", array( "title"=>"Workbench" ) ) ?>
            </li> 
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/written_off_assets'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/written_off_assets/", "To Be Written Off", array( "title"=>"To Be Written Off" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/board_of_surveyed'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/board_of_surveyed/", "Board of Surveyed", array( "title"=>"Board of Surveyed" ) ) ?>
            </li>
             <li <?php if(isset($currentURL) && $currentURL == base_url('report/lost_stolen'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/lost_stolen/", "Lost/Stolen", array( "title"=>"Lost/Stolen" ) ) ?>
            </li>
            <li <?php if(isset($currentURL) && $currentURL == base_url('report/monthly_log_report'))echo 'class="activeLink"'?> >
                    <?= anchor( "report/monthly_log_report/", "Monthly Log Report", array( "title"=>"Monthly Log Report" ) ) ?>
            </li>
	</ul>
        
        <?php if(isset($currentURL) && $currentURL == base_url('report/master_inventory_records')) 
        {
        ?>
            <form action="<?=base_url("report/generate_print_version")?>" method="post">
            <button id="mir" class="btn-danger form-control" >Print</button>                    
            </form>    
        <?php 
        }
        else
        {
            echo '<button class="btn-danger form-control" onclick="print_content()">Print</button>';
        }        
        ?>        
</div>