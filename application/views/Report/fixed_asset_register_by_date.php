<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once 'inc/head.inc'?>
</head>

<body>
    <div class="container">
        <?php include_once 'inc/nav.inc' ?>
        <?php include_once 'inc/Report/report_sidebar.inc' ?>

        <div class="report_content">
            <div class="error_holder"><?= validation_errors() ?></div>
            <form action="<?= base_url('report/fixed_asset_register_by_date') ?>" method="post">
                <div class="form-group row search_area">
                    <label for="start_date" id="start_date" class="col-sm-2 form-label">Start Date</label>
                    <div class="col-sm-3">
                        <input type="date" value="<?php
                                                    if (!isset($start_date))
                                                        echo set_value('start_date');
                                                    else
                                                        echo $start_date;
                                                    ?>" name="start_date" class="form-control" id="start_date" />
                    </div>

                    <label for="end_date" id="end_date" class="col-sm-2 form-label">End Date</label>
                    <div class="col-sm-3">
                        <input type="date" value="<?php
                                                    if (!isset($end_date))
                                                        echo set_value('end_date');
                                                    else
                                                        echo $end_date;
                                                    ?>" name="end_date" class="form-control" id="end_date" />
                    </div>

                </div>
                <div class="form-group row search_area">
                    <label for="end_date" id="end_date" class="col-sm-2 form-label">Item Description</label>
                    <select data-placeholder="Item Description" multiple class="col-sm-5 chosen-select" tabindex="-1" name="asset_items[]">
                        <option value=""></option>
                        <?php foreach ($asset_list as $asset_item) : ?>
                            <option value="<?= $asset_item['asset_code_id'] ?>"><?= $asset_item['description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group row search_area">
                    <div class="col-sm-2">
                        <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
                    </div>
                </div>
            </form>

            <?php

            //if(!isset($value) && empty($value))
            //  ;
            if (isset($asset)) {
            ?>

                <div class="row">
                    <div class="col-lg-12">
                        <div id="print_content">
                            <center>
                                <h4>COURT ADMINISTRATION DIVISION<br />
                                    FIXED ASSET INVENTORY RECORDS - BY DATE<br />
                                    Period: <?= date_format(new DateTime(set_value('start_date')), "d-M-y") ?> To <?= date_format(new DateTime(set_value('end_date')), "d-M-y") ?>
                                </h4>
                            </center>
                            <strong><?= "Total Cost = $".number_format((float)$totalcost, 2, '.', ',') ?></strong><p></p>

                            <?php
                            if (isset($asset) && empty($asset))
                                echo '<h3>No results found</h3>';

                            else {
                                if (!empty($asset)) {
                            ?>

                                    <table id="mytables" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Date of Purchase</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Serial Number</th>
                                                <th>Invoice Number</th>
                                                <th>Supplier</th>
                                                <th>Cost</th>
                                                <th>Parish</th>
                                                <th>Location</th>
                                                <th>Division</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($asset as $row) {
                                            ?>
                                                <tr>
                                                    <td><?= $row['description'] ?></td>
                                                    <td><?= date_format(new DateTime($row['date_purchased']), "d-M-Y") ?></td>
                                                    <td><?= $row['make'] ?></td>
                                                    <td><?= $row['model'] ?></td>
                                                    <td><?= $row['serial_number'] ?></td>
                                                    <td><?= $row['acct_ref'] ?></td>
                                                    <td><?= $row['supplier'] ?></td>
                                                    <td>$<?= number_format((float)$row['cost'], 2, '.', ',') ?></td>
                                                    <td><?= $row['parish'] ?></td>
                                                    <td><?= $row['location_name'] ?></td>
                                                    <td><?= $row['division_name'] ?></td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <strong><?= "Total Cost = $".number_format((float)$totalcost, 2, '.', ',') ?></strong>
                        </div>
                    </div>
                    <!--END second ROW-->
                </div>
    <?php
                                }
                            }
                        }
    ?>



        </div>

        <?php include_once 'inc/footer.inc' ?>
        <script>$(".chosen-select").chosen();</script>
</body>

</html>