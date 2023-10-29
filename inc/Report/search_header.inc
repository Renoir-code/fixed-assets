<form action="<?=base_url('report/fixed_asset_record')?>" method="post">	
        <div class="form-group row search_area">
            <label for="value" id="search_label" class="col-sm-6 form-label">Search For Serial</label>
            <div class="col-sm-3">
                <input type="text" value="<?=set_value('value')?>" id="value" name="value" class="form-control" />
            </div>
            <div class="col-sm-2">
                <input type="submit" id="btnSearch" name="btnSearch" value="Search" class="form-control btn-info" />
            </div>			
        </div>
    </form>    