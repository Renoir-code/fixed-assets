<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class asset_model extends CI_Model 
{
    public function getAssets($offsetStart, $offsetAmount)
    {
        $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
        
        if($offsetStart == 0)
            $offsetStart = 0;
        
        $query = "SELECT * FROM `fixed_asset` fa where written_off = 'n' and lost_stolen = 'n' ";
                $query .= "order by asset_number desc "
                //. "inner join location f on fa.location_id = f.location_id "
                //. "inner join division r on fa.division_id = r.division_id "
                //. "inner join asset_code ac on ac.asset_id = fa.asset_id "
                //. "inner join make m on m.make_id=fa.make_id "
                . "LIMIT {$offsetStart},{$offsetAmount}";
        
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    
    public function getToBeWrittenOffAssets()
    {
        $query = "SELECT * FROM `fixed_asset` fa where written_off = 'y' and board_of_surveyed='n' "
                . "order by last_modified desc ";
        
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    //for pagination
	public function countAsset()
	{
            $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
            
            $query = "SELECT * FROM fixed_asset where written_off = 'n' ";
		
		$result = $this->db->query($query);
		
		return $result->num_rows();
	}

    public function countNewlyPurchasedAssetsItems($newly_purchased_asset_id, $count)
	{
        // $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
        // $query = "SELECT * FROM newly_purchased_asset_items WHERE newly_purchased_asset_id = $id";
		// $result = $this->db->query($query);
		// return $result->num_rows();
        $query = "UPDATE newly_purchased_asset SET npa_count_items = $count WHERE newly_purchased_asset_id=? ";
        if ($this->db->query($query,array($newly_purchased_asset_id)))
            return true;
        return false;
	}
        
        public function getAssetCode()
        {
            $query = "SELECT * from asset_code order by asset_code_id desc ";
            return $this->db->query($query)->result_array();
        }

        public function getAssetCodeNameList($asset_id_list)
        {
            $asset_ids = join("','", $asset_id_list);
            $query = "SELECT description from asset_code WHERE asset_code_id IN('$asset_ids') order by asset_code_id desc ";
            return $this->db->query($query)->result_array();
        }
        
        public function getLocationDivision()
        {
            $query = "SELECT * from  division d "
                    . "inner join location l on l.location_id = d.location_id "
                    . "inner join parish p on p.parish_id = l.parish_id "
                    . "inner join location_type lt on lt.location_type_id = l.location_type_id";
            
            return $this->db->query($query)->result_array();
        }
        
        public function getAssetCodeById($asset_id)
        {
            $query = "SELECT * from asset_code where asset_code_id=? ";
            return $this->db->query($query,array($asset_id))->first_row('array');
        }
        
        public function getLastAssetCode()
        {
            $query = "SELECT max(asset_code) as asset_code FROM `asset_code`";
            return $this->db->query($query)->first_row('array')['asset_code'];    
        }
        
        public function getLocationDivisionByDivId($div_id)
        {
            $query = "SELECT * from  division d "
                    . "inner join location l on l.location_id = d.location_id "
                    . "inner join parish p on p.parish_id = l.parish_id "
                    . "inner join location_type lt on lt.location_type_id = l.location_type_id "
                    . "where division_id =? ";
            return $this->db->query($query,array($div_id))->first_row('array');
        }
        
        public function update_all_asset_tag_by_division($search_string,$replace_string,$div_id)
        {
            $query = "update fixed_asset set asset_tag = replace(asset_tag, ?, ?) where division = ? ";
            if ($this->db->query($query,array($search_string,$replace_string,$div_id)))
                return true;
            return false;
        }
        
        public function getParish()
        {
            $query = "SELECT parish_id,parish from parish order by parish ";
            return $this->db->query($query)->result_array();
        }

        public function getFileNames($id)
        {
            $query = "SELECT asset_pictures from fixed_asset WHERE serial_number = ? Order by asset_pictures";
            return $this->db->query($query,array($id))->result_array();
        }
        
        public function getlocationType()
        {
            $query = "SELECT location_type_id, location_type from location_type order by location_type ";
            return $this->db->query($query)->result_array();
        }
        
        public function getLocation($pid,$ltid)
        {
            $query = "SELECT location_id, location_abbre,location_name from location where parish_id = ? and location_type_id = ? order by location_abbre ";
            return $this->db->query($query,array($pid,$ltid))->result_array();
        }

        public function getLocationByParishId($pid)
        {
            $query = "SELECT * from location where parish_id = ? order by location_type_id, location_name";
            return $this->db->query($query,array($pid))->result_array();
        }
                                
        public function getDivision($id)
        {           
            $query = "SELECT * from division where location_id=? order by division_name";            
            return $this->db->query($query,array($id))->result_array();
        }
        
        public function incrementAsset($asset_code_id)
        {
            $query = "UPDATE asset_code SET asset_count = asset_count + 1 WHERE asset_code_id=? ";
            if ($this->db->query($query,array($asset_code_id)))
                return true;
            return false;
        }
                        
        public function store_reserved_asset_assigned_number($asset_code_id, $assigned_number)
        {            
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            date_default_timezone_set('America/Bogota');
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `reserved_asset_assigned_number`"
                    . "( `asset_code_id`, `assigned_number`, `last_modified`, `modified_by`)"
                    . " VALUES "
                    . "(?,?,?,?)";
            
            if($this->db->query($query,array($asset_code_id, $assigned_number,$time_created,$modified_by)))
                    return true;
            return false;
            
        } 
        
        public function removeReservedNumber($asset_code_id, $assigned_number)
        {   
            $query = "DELETE FROM `reserved_asset_assigned_number` WHERE asset_code_id=? and assigned_number=? ";
            
            if($this->db->query($query,array($asset_code_id, $assigned_number)))
                    return true;
            return false;
            
        }   
        
        public function getReservedAssetAssignedNumber($asset_code_id)
        {
            $query = "SELECT assigned_number from reserved_asset_assigned_number where asset_code_id =? ";
            return $this->db->query($query,array($asset_code_id))->first_row('array');
        }
        
        
        public function returnAssetCodeCount($asset_code_id)
        {
            $query = "SELECT * from asset_code where asset_code_id =? ";
            return $this->db->query($query,array($asset_code_id))->first_row('array');
        }
        /*
        public function returnAssetCodeAndAssignedIdFromFixedAsset($asset_id)
        {
            $query = "SELECT * from fixed_asset where asset_number =? ";
            return $this->db->query($query,array($asset_id))->first_row('array');
        }*/              
        
        public function getNewAssetCodeId()
        {            
            $query = "SELECT max(asset_code_id) as asset_code_id FROM `asset_code`";
            return $this->db->query($query)->first_row('array')['asset_code_id'];            
        }
        
        public function retrieveLastCreatedFixedAsset()
        {            
            $query = "SELECT max(asset_number) as asset_number FROM `fixed_asset`";
            return $this->db->query($query)->first_row('array')['asset_number'];            
        }
        
        public function getNewLocationId()
        {            
            $query = "SELECT max(location_id) as location_id FROM `location`";
            return $this->db->query($query)->first_row('array')['location_id'];            
        }
        
        public function getNewDivisionId()
        {            
            $query = "SELECT max(division_id) as division_id FROM `division`";
            return $this->db->query($query)->first_row('array')['division_id'];            
        }
        
        public function returnDivisionAbbr($division)
        {
            //die('<br/>division in model '.$division);
            $query = "SELECT division_abbre from division where division_id =? ";
            return $this->db->query($query,array($division))->first_row('array')['division_abbre'];            
        }
        
        public function returnLocationAbbr($location)
        {
            //die('<br/>division in model '.$division);
            $query = "SELECT location_abbre from location where location_id =? ";
            return $this->db->query($query,array($location))->first_row('array')['location_abbre'];            
        }
        
        public function insertNewAssetCode($asset_code,$description)
        {
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            date_default_timezone_set('America/Bogota');
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `asset_code`"
                    . "( `asset_code`, `description`, `asset_count`, `last_modified`, `modified_by`)"
                    . " VALUES "
                    . "(?,?,?,?,?)";
            
            if($this->db->query($query,array($asset_code,$description,0,$time_created,$modified_by)))
                    return true;
            return false;
        }
        
        public function insertNewLocation($location_abbre,$location_name,$pid,$ltid)
        {
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            date_default_timezone_set('America/Bogota');
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `location`"
                    . "( `location_abbre`, `location_name`, `parish_id`, `location_type_id`, `modified_by`)"
                    . " VALUES "
                    . "(?,?,?,?,?)";
            
            if($this->db->query($query,array($location_abbre,$location_name,$pid,$ltid,$modified_by)))
                    return true;
            return false;
        }
        
        public function insertNewDivision($division_abbre,$division_name,$lid)
        {
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            date_default_timezone_set('America/Bogota');
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `division`"
                    . "( `division_abbre`, `division_name`, `location_id`, `modified_by`)"
                    . " VALUES "
                    . "(?,?,?,?)";
            
            if($this->db->query($query,array($division_abbre,$division_name,$lid,$modified_by)))
                    return true;
            return false;
        }
        
        public function insertFixedAsset($make,$model,$serial_number,$description1,$date_purchased,
                $date_writeoff,$supplier,$cost,$acct_ref,$location,$division,$asset_code_id,$asset_tag,
                $user,$description2,$assigned_number,$filename)
        {
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `fixed_asset`"
                    . "( `make`, `model`, `serial_number`, `description1`, `date_purchased`, "
                    . "`date_writeoff`, `supplier`, `cost`, `acct_ref`, `location`, `division`, "
                    . "`asset_code_id`, `asset_tag`, `user`, `description2`, `assigned_number`,`time_created`,`created_by`,`asset_pictures`)"
                    . " VALUES "
                    . "(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

           // echo var_dump($query);die();
            
            if($this->db->query($query,array($make,$model,$serial_number,$description1,$date_purchased,
                $date_writeoff,$supplier,$cost,$acct_ref,$location,$division,$asset_code_id,$asset_tag,
                $user,$description2,$assigned_number,$time_created,$modified_by,$filename)))
                    return true;
            return false;
        }
        
        public function insertFixedAssetLog($asset_id, $action_done)
        {
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `fixed_asset_log`"
                    . "( `fixed_asset_id`, `action`, `time_modified`,`modified_by`)"
                    . " VALUES "
                    . "(?,?,?,?)";
            
            if($this->db->query($query,array($asset_id, $action_done,$time_created,$modified_by)))
                    return true;
            return false;
        }
        
        public function insertFixedAssetAttachment($asset_id,$filename)
        {
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            $time_created = date("Y-m-d H:i:s",time());
            //die('time '.$time_created);
            
            $query = "INSERT INTO `fixed_asset_attachment`"
                    . "( `fixed_asset_id`, `filename`, `time_created`,`modified_by`)"
                    . " VALUES "
                    . "(?,?,?,?)";
            
            if($this->db->query($query,array($asset_id, $filename,$time_created,$modified_by)))
                    return true;
            return false;
        }
        
        public function removeEmptyFixedAssetLog()
        {   
            $query = "DELETE FROM `fixed_asset_log` WHERE action='' ";
            
            if($this->db->query($query))
                    return true;
            return false;
        }
        
        
        
        public function getAsset($id)
        {
            //$query = "SELECT * FROM `fixed_asset` where asset_number=? "; 
            $query = "SELECT * FROM `fixed_asset` fa inner join location f on f.location_id = fa.location inner join division d on d.division_id = fa.division inner join parish p on p.parish_id = f.parish_id inner join asset_code a on a.asset_code_id = fa.asset_code_id where asset_number=? ";
        
            $result = $this->db->query($query,array($id))->result_array();
            return $result[0];
        }
        
        public function getFixedAssetRepairData($id)
        {
            //$query = "SELECT * FROM `fixed_asset` where asset_number=? "; 
            $query = "SELECT * FROM `fixed_asset_repair` far inner join fixed_asset fa on fa.asset_number = far.fixed_asset_id where fixed_asset_id=? ";
        
            $result = $this->db->query($query,array($id))->result_array();
            return $result;
        }
        
        public function getFixedAssetLogData($id)
        {
            //$query = "SELECT * FROM `fixed_asset` where asset_number=? "; 
            $query = "SELECT * FROM `fixed_asset_log` fal inner join fixed_asset fa on fa.asset_number = fal.fixed_asset_id where fixed_asset_id=? order by fixed_asset_log_id desc ";
        
            $result = $this->db->query($query,array($id))->result_array();
            return $result;
        }
        
        public function getFixedAssetAttachment($id)
        {
            //$query = "SELECT * FROM `fixed_asset` where asset_number=? "; 
            $query = "SELECT * FROM `fixed_asset_attachment` faa inner join fixed_asset fa on fa.asset_number = faa.fixed_asset_id where fixed_asset_id=?  ";
        
            $result = $this->db->query($query,array($id))->result_array();
            return $result;
        }
        
        public function searchFixedAssetBySerial($search)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $filter = '%'.$search; 
            $filter2 = '%'.$search.'%';
            $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
            
            $query = "SELECT * from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where (fa.serial_number=? or fa.serial_number like ? or fa.make like ? or fa.model like ? or fa.user like ? or fa.asset_tag like ? or fa.acct_ref like ? )
                      and board_of_surveyed = 'n' ";
            
            return $this->db->query($query,array($search,$filter,$filter2,$filter2,$filter2,$filter2,$filter2))->result_array();
        }
        
        public function getAlldivisions()
        {
            $query = "SELECT * from division order by division_abbre ";
            return $this->db->query($query)->result_array();
        }        
        
        public function getAssetCodesTotal()
        {
            $query = "SELECT * from asset_code order by description ";
            return $this->db->query($query)->result_array();
        }  
        
        public function getAssetCodesPaged($offsetStart, $offsetAmount)
        {
             if($offsetStart == 0)
                $offsetStart = 0;
             
            $query = "SELECT * from asset_code order by description LIMIT {$offsetStart},{$offsetAmount} ";
            return $this->db->query($query)->result_array();
        } 
        
        public function searchFixedAssetByPLtLD($parish,$location_type,$location,$division)
        {            
            $query = "SELECT * from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where p.parish_id=? and lt.location_type_id=? 
                      and l.location_id=? and d.division_id=?
                      and fa.written_off  = 'n'
                      and fa.board_of_surveyed = 'n'
                      and lost_stolen = 'n' 
					  and ac.asset_code <> '075A'
                      order by ac.description ";
            
            return $this->db->query($query,array($parish,$location_type,$location,$division))->result_array();
        }

        public function totalcost_location($parish,$location_type,$location)
        {            
            $query = "SELECT SUM(cost) as totalcost from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where p.parish_id=? and lt.location_type_id=? 
                      and l.location_id=?
                      and fa.written_off  = 'n'
                      and fa.board_of_surveyed = 'n'
                      and lost_stolen = 'n' 
					  and ac.asset_code <> '075A'";
            
            return $this->db->query($query,array($parish,$location_type,$location))->first_row('array')['totalcost'];
        }

        public function totalcost_division($parish,$location_type,$location,$division)
        {            
            $query = "SELECT SUM(cost) as totalcost from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      inner join division d on fa.division = d.division_id
                      where p.parish_id=? and lt.location_type_id=? 
                      and l.location_id=? and fa.division =?
                      and fa.written_off  = 'n'
                      and fa.board_of_surveyed = 'n'
                      and lost_stolen = 'n' 
					  and ac.asset_code <> '075A'";
            
            return $this->db->query($query,array($parish,$location_type,$location,$division))->first_row('array')['totalcost'];
        }
        
        public function searchWrittenOffAssets($serial,$start_date,$end_date)
        {   
            $query = "SELECT * from fixed_asset fa                        
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      where fa.written_off  =?                       
                      and fa.date_writeoff >= ? and fa.date_writeoff <=? ";
            if(!empty($serial))
            {
                $query .= "and fa.serial_number=? ";
                return $this->db->query($query,array('y',$start_date,$end_date,$serial))->result_array();
            }
            else
                return $this->db->query($query,array('y',$start_date,$end_date))->result_array();
        }
        
        public function searchBOSAssets($serial,$start_date,$end_date)
        {   
            //var_dump($serial,$start_date,$end_date);
           // die('test');
            $query = "SELECT * from fixed_asset fa                        
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      where fa.board_of_surveyed =?                     
                      and fa.date_writeoff >= ? and fa.date_writeoff <=? ";
            if(!empty($serial))
            {
                $query .= " and fa.serial_number=? ";
                return $this->db->query($query,array('y',$start_date,$end_date,$serial))->result_array();
            }
            else
                return $this->db->query($query,array('y',$start_date,$end_date))->result_array();
        }
        
        public function searchLostStolenAssets()
        {               
            $query = "SELECT * from fixed_asset fa                        
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      where fa.lost_stolen =? ";                    
                      
                return $this->db->query($query,array('y'))->result_array();
        }
        
        public function searchFixedAssetByDate($start_date,$end_date)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $query = "SELECT * from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where CAST(fa.date_purchased AS DATE) >= ? and CAST(fa.date_purchased AS DATE) <= ?";
            
            return $this->db->query($query,array($start_date,$end_date))->result_array();
        }

        public function searchFixedAssetByDateAndID($start_date,$end_date,$assets)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";'
            $asset_ids = join("','", $assets);
            $query = "SELECT * from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where CAST(fa.date_purchased AS DATE) >= ? and CAST(fa.date_purchased AS DATE) <= ? and fa.asset_code_id IN('$asset_ids')";
            
            return $this->db->query($query,array($start_date,$end_date))->result_array();
        }

        public function totalCostFixedAssetByDate($start_date,$end_date)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";'
            $query = "SELECT SUM(COST) as totalcost from fixed_asset fa 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      where CAST(fa.date_purchased AS DATE) >= ? and CAST(fa.date_purchased AS DATE) <= ?";
            
            return $this->db->query($query,array($start_date,$end_date))->first_row('array')['totalcost'];
        }

        public function totalCostFixedAssetByDateAndID($start_date,$end_date,$assets)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";'
            $asset_ids = join("','", $assets);
            $query = "SELECT SUM(COST) as totalcost from fixed_asset fa 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      where CAST(fa.date_purchased AS DATE) >= ? and CAST(fa.date_purchased AS DATE) <= ?
                      and fa.asset_code_id IN('$asset_ids')";
            
            return $this->db->query($query,array($start_date,$end_date))->first_row('array')['totalcost'];
        }

        public function searchFixedAssetLogByDate($start_date,$end_date)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $query = "SELECT fa.asset_number, fal.action, fal.time_modified, fal.modified_by, assc.description, fa.make, fa.model, fa.asset_tag, l.location_name, d.division_name, fa.serial_number  
                        FROM `fixed_asset_log` fal 
                        inner join fixed_asset fa on fa.asset_number = fal.fixed_asset_id 
                        inner join asset_code assc on assc.asset_code_id = fa.asset_code_id 
                        inner join division d on d.division_id = fa.division 
                        inner join location l on l.location_id = fa.location 
                        where CAST(fal.time_modified AS DATE) >= ? 
                        and CAST(fal.time_modified AS DATE) <= ?
                        order by fal.time_modified desc";
            
            return $this->db->query($query,array($start_date,$end_date))->result_array();
        }
        
        public function searchFixedAssetMonthlyLogReportByDate($start_date,$end_date)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $query = "SELECT fa.asset_number, fa.time_created, fa.serial_number, fa.asset_tag, fa.user, 
                      p.parish, l.location_name, d.division_name, ac. description from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where CAST(fa.time_created AS DATE) >= ? and CAST(fa.time_created AS DATE) <= ?";
            
            return $this->db->query($query,array($start_date,$end_date))->result_array();
        }
        
        public function searchFixedAssetByAssetCode($asset_code)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $query = "SELECT * from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where fa.board_of_surveyed='n' and fa.lost_stolen='n' and fa.asset_code_id=?";
            
            return $this->db->query($query,array($asset_code));
        }
        
        public function searchFixedAssetByAssetCode2($asset_code)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $query = "SELECT distinct fa.asset_code, d.division_abbre, d.division_name, l.location_name,
            (SELECT count(*) from fixed_asset fa2                      
                      inner join division d2 on fa2.division = d2.division_id 
                      where fa2.division = fa.division 
     				  and fa2.asset_code_id = fa.asset_code_id
                ) as asset_count
            from fixed_asset fa
                                 inner join division d on fa.division = d.division_id
                                 inner join location l on d.location_id = l.location_id                     
                                 where fa.written_off='n' and fa.lost_stolen='n' and fa.asset_code_id=?";
            
            return $this->db->query($query,array($asset_code));
        }
        
        public function updateFixedAsset($make,$model,$serial_number,$description1,$date_purchased,
                $date_writeoff,$supplier,$cost,$acct_ref,$location,$division,$asset_code,$asset_tag,
                $user,$description2,$assigned_number,$written_off,$board_of_surveyed,$lost_stolen,$filename,$asset_id)
        {
            //die('written of'. $written_off);
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            date_default_timezone_set('America/Bogota');
            $time_modified = date("Y-m-d H:i:s",time());
            //$division_abbre = $this->returndivisionAbbr($division_abbre);
            
            $query = "UPDATE `fixed_asset` "
                    . "SET `make`=?, "
                    . "`model`=?, "
                    . "`serial_number`=?, "
                    . "`description1`=?, "
                    . "`date_purchased`=?, "
                    . "`date_writeoff`=?, "
                    . "`supplier`=?, "
                    . "`cost`=?, "
                    . "`acct_ref`=?, "
                    . "`location`=?, "
                    . "`division`= ?, "
                    . "`asset_code_id`= ?, "
                    . "`asset_tag`=?, "
                    . "`user`=?, "
                    . "`description2`=?, "
                    . "`assigned_number`=?, "
                    . "`written_off`=?, "
                    . "`board_of_surveyed`=?, "
                    . "`lost_stolen`=?, " 
                    . "`asset_pictures`=?, "                  
                    . "`last_modified`=?, "
                    . "`created_by`=? "
                    . " WHERE asset_number=?";
            
            if($this->db->query($query,array($make,$model,$serial_number,$description1,$date_purchased,
                $date_writeoff,$supplier,$cost,$acct_ref,$location,$division,$asset_code,$asset_tag,
                $user,$description2,$assigned_number,$written_off,$board_of_surveyed,$lost_stolen,$filename,$time_modified,$modified_by,$asset_id)))
                    return true;
            return false;
        }
        
        /*
        public function checkAllInstanceOfUser($prev_user, $asset_id)
        {                       
            $query = "SELECT * from fixed_asset 
                      where user=? and user <> '' and asset_number <> ?";
            
            return $this->db->query($query,array($prev_user, $asset_id))->result_array();
        }
        
        public function updateAllInstanceOfUser($new_user,$prev_user)
        {                       
            $query = "UPDATE `fixed_asset` SET `user`=? WHERE user=? and user <> '' ";
            
            if($this->db->query($query,array($new_user,$prev_user)))
                    return true;
            return false;
        }
        */
        public function insert_fixed_asset_repair_data($asset_id,$repair_date,$nature_of_repair,$from_date,$to_date,$repair_cost)
        {
            $query = "INSERT INTO `fixed_asset_repair`"
                    . "(`fixed_asset_id`, `repair_date`, `nature_of_repair`, `from_date`, `to_date`, `repair_cost`)"
                    . " VALUES"
                    . " (?,?,?,?,?,?)";
            
            if($this->db->query($query,array($asset_id,$repair_date,$nature_of_repair,$from_date,$to_date,$repair_cost)))
                    return true;
            return false;
        }
        
        public function update_fixed_asset_repair_data($asset_id,$repair_date,$nature_of_repair,$from_date,$to_date,$repair_cost,$repair_id)
        {  
            $query = "UPDATE `fixed_asset_repair` "
                    . "SET `fixed_asset_id`=?, "                    
                    . "`repair_date`=?, "
                    . "`nature_of_repair`=?, "                    
                    . "`from_date`=?, "
                    . "`to_date`=?, "
                    . "`repair_cost`=? "
                    . " WHERE fixed_asset_repair_id=?";
            
            if($this->db->query($query,array($asset_id,$repair_date,$nature_of_repair,$from_date,$to_date,$repair_cost,$repair_id)))
                    return true;
            return false;
        }
        
        public function updateAssetCode($asset_code,$description,$asset_count,$last_modified,$modified_by,$asset_id)
        {  
            $query = "UPDATE `asset_code` "
                    . "SET `asset_code`=?, "                    
                    . "`description`=?, "
                    . "`asset_count`=?, "                    
                    . "`last_modified`=?, "
                    . "`modified_by`=? "
                    . " WHERE asset_code_id=?";
            
            if($this->db->query($query,array($asset_code,$description,$asset_count,$last_modified,$modified_by,$asset_id)))
                    return true;
            return false;
        }
        
        public function updateLocation($location_name,$last_modified,$modified_by,$location_id)
        {  
            $query = "UPDATE `location` "
                    . "SET `location_name`=?, " 
                    . "`time_modified`=?, "
                    . "`modified_by`=? "
                    . " WHERE location_id=?";
            
            if($this->db->query($query,array($location_name,$last_modified,$modified_by,$location_id)))
                    return true;
            return false;
        }
        
        public function updateDivision($division_abbre,$division_name,$last_modified,$modified_by,$division_id)
        {  
            $query = "UPDATE `division` "
                    . "SET `division_abbre`=?, " 
                    . "`division_name`=?, "
                    . "`time_modified`=?, "
                    . "`modified_by`=? "
                    . " WHERE division_id=?";
            
            if($this->db->query($query,array($division_abbre,$division_name,$last_modified,$modified_by,$division_id)))
                    return true;
            return false;
        }
        
        public function testAssetDesc($str)
        {
            $query = "SELECT * FROM asset_code WHERE description = ? LIMIT 1;";
            return $this->db->query($query, array($str))->result_array();
        }
        
        public function testAssetCode($str)
        {
            $query = "SELECT * FROM asset_code WHERE asset_code = ? LIMIT 1;";
            return $this->db->query($query, array($str))->result_array();
        }
        
        public function testLocation($str)
        {
            $query = "SELECT * FROM location WHERE location_abbre = ? LIMIT 1;";
            return $this->db->query($query, array($str))->result_array();
        }
        
        public function testSerial($str)
        {
            $query = "SELECT * FROM `fixed_asset` WHERE serial_number = ? LIMIT 1;";
            return $this->db->query($query, array($str))->result_array();
        }
                
        public function getFilteredAssetCode($filter)
        {
            $filter = '%'.$filter.'%';            
            
            $query = "SELECT * FROM `asset_code` where asset_code like ? or description like ? or asset_count like ? ";

            return $this->db->query($query, array($filter,$filter,$filter))->result_array();
	}
        
        public function getFilteredLocDiv($filter)
        {
            $filter = '%'.$filter.'%';            
            
            $query = "SELECT * from  division d "
                    . "inner join location l on l.location_id = d.location_id "
                    . "inner join parish p on p.parish_id = l.parish_id "
                    . "inner join location_type lt on lt.location_type_id = l.location_type_id "
                    . "where l.location_name like ? or d.division_name like ? or l.location_abbre like ? or d.division_abbre like ? ";

            return $this->db->query($query, array($filter,$filter,$filter,$filter))->result_array();
	}
        
        public function getAssignedUsers()
        {
            $query = "SELECT DISTINCT fa.user, l.location_name, d.division_name, d.division_id, lt.location_type from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where fa.written_off='n' and fa.lost_stolen='n' order by fa.user ";
            return $this->db->query($query)->result_array();
        } 
        
        public function searchFixedAssetByAssignedUser($assigned_user,$division_id)
        {
            //$query = "SELECT * from fixed_asset where serial_number=?";
            $query = "SELECT * from fixed_asset fa 
                      inner join location l on fa.location = l.location_id
                      inner join division d on fa.division = d.division_id 
                      inner join asset_code ac on ac.asset_code_id = fa.asset_code_id
                      inner join parish p on p.parish_id = l.parish_id
                      inner join location_type lt on lt.location_type_id = l.location_type_id
                      where fa.written_off='n' and fa.lost_stolen='n' and fa.user=? and d.division_id=? ";
            
            return $this->db->query($query,array($assigned_user,$division_id));
        }
	
	public function getNotificationCount(){
		return $this->db->query("SELECT * FROM notifications WHERE update_flag=0")->num_rows();
	}

    public function getNewlyPurchasedAssetCount(){
		return $this->db->query("SELECT * FROM newly_purchased_asset WHERE update_flag=0")->num_rows();
	}

	public function saveNotification($asset_type, $make, $model_number, $serial_number, $assigned_user, $asset_tag, $reason, $username, $from_loc="", $from_div="", $to_loc="", $to_div=""){
		$this->db->query("INSERT INTO `notifications`(`asset_type`, `make`, `model_number`, `serial_number`, `assigned_user`, `asset_tag`, `reason`, `username`, `from_location`, `from_division`, `to_location`, `to_division`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)", array($asset_type, $make, $model_number, $serial_number, $assigned_user, $asset_tag, $reason, $username, $from_loc, $from_div, $to_loc, $to_div));
	}

    public function saveNewlyPurchasedAssets($npa_name, $npa_submitted_date, $npa_officer, $npa_invoice){
		$this->db->query("INSERT INTO `newly_purchased_asset`(`npa_name`, `npa_submitted_date`, `npa_officer`, `npa_invoice`) VALUES(?,?,?,?)", array($npa_name, $npa_submitted_date, $npa_officer, $npa_invoice));
	}

    public function saveNewlyPurchasedAssetItems($newly_purchased_asset_id, $npa_assigned_user, $parish_id, $location_id, $division_id){
		$this->db->query("INSERT INTO `newly_purchased_asset_items`(`newly_purchased_asset_id`, `npa_assigned_user`, `parish_id`, `location_id`, `division_id`) VALUES(?,?,?,?,?)", array($newly_purchased_asset_id, $npa_assigned_user, $parish_id, $location_id, $division_id));
	}
	
	public function getNotifications(){
		return $this->db->query("SELECT * FROM notifications WHERE update_flag=0 ORDER BY notification_id DESC")->result_array();
	}

    public function getNewlyPurchasedAssets(){
		return $this->db->query("SELECT * FROM newly_purchased_asset WHERE update_flag=0 ORDER BY newly_purchased_asset_id DESC")->result_array();
	}

    public function getNewlyPurchasedAssetsItems(){
		return $this->db->query("SELECT newly_purchased_asset_id, npa_assigned_user, parish, location_name, division_name FROM newly_purchased_asset_items inner JOIN parish ON newly_purchased_asset_items.parish_id = parish.parish_id inner JOIN location ON newly_purchased_asset_items.location_id = location.location_id inner JOIN division ON newly_purchased_asset_items.division_id = division.division_id")->result_array();
	}
	
	public function updateNotifications($updateNotifications){
		//die('test');
		$this->db->trans_begin();
		
		foreach($updateNotifications as $update){
			$this->db->query("UPDATE notifications SET update_flag=1 WHERE notification_id=?", array($update));
		}
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

    public function updateNewlypurchasedassets($updateNewlypurchasedassets){
		//die('test');
		$this->db->trans_begin();
		
		foreach($updateNewlypurchasedassets as $update){
			$this->db->query("UPDATE newly_purchased_asset SET update_flag=1 WHERE newly_purchased_asset_id=?", array($update));
		}
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

    public function retrieveLastCreatedNewlyPurchasedAsset()
        {            
            $query = "SELECT max(newly_purchased_asset_id) as newly_purchased_asset_id FROM `newly_purchased_asset`";
            return $this->db->query($query)->first_row('array')['newly_purchased_asset_id'];            
        }
}