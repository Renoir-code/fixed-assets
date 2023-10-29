<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {
    public $offsetAmount = 20;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('asset_model');
        //session_start();
        $this->user_model->isUserLoggedIn();
            
    }
    public function pagination($url)
    {
        // for pagination
	$config['base_url'] = base_url().$url;
	
	$config['per_page'] = $this->offsetAmount; 
	$config['uri_segment'] = 3;
	$config['num_links'] = 5;        
	$config['full_tag_open'] = '<div id = "pagination">';
	$config['full_tag_close'] = '</div>';
	$config['first_link'] = 'First';
	$config['last_link'] = 'Last';
	$config['next_link'] = '&gt;';
	$config['prev_link'] = '&lt;';
                
        return $config;
    }
    
    public function index()
    {
        $this->load->view('Report/report_main');
    }
    
    public function fixed_asset_record()
    {
        $data['currentURL'] = base_url().'report/fixed_asset_record'; 
        //die( $data['currentURL']);
        $this->form_validation->set_rules('value', 'Search Value', 'required|xss_clean');
        $this->form_validation->set_message('required','Please enter a search value.');
		
        if($this->form_validation->run() == FALSE)
            $this->load->view('Report/fixed_assets_record', $data);
        else
        {                
            $data['asset'] = $this->asset_model->searchFixedAssetBySerial($this->input->post('value'));
            
            if(!empty($data['asset']))
                $data['repair_result'] = $this->asset_model->getFixedAssetRepairData($data['asset'][0]['asset_number']); 
            
            $this->load->view('Report/fixed_assets_record', $data);
        }
    }
    
    public function location_record()
    {
        $data['currentURL'] = base_url().'report/location_record';
        $this->form_validation->set_rules('division_name', 'Search Value', 'required|xss_clean');
        $this->form_validation->set_message('required','Please enter a search value.');
        
        //$data['division'] = $this->asset_model->getAlldivisions();
        $data['parish'] = $this->asset_model->getParish();
        $data['location_type'] = $this->asset_model->getlocationType();            

        if($this->input->post('parish'))
        {
            $data['location'] = $this->asset_model->getLocation($this->input->post('parish'),$this->input->post('location_type'));               
        }

        if($this->input->post('location_name'))
        {
            $data['division'] = $this->asset_model->getDivision($this->input->post('location_name'));               
        }
        
        if($this->form_validation->run() == FALSE )
            $this->load->view('Report/location_record',$data);
        else
        {   
            $parish = $this->input->post('parish');
            $location_type = $this->input->post('location_type');
            $location_name = $this->input->post('location_name');
            $division_name = $this->input->post('division_name');
            $data['asset'] = $this->asset_model->searchFixedAssetByPLtLD($parish, $location_type, $location_name, $division_name);
            $data['totalcost_location'] = $this->asset_model->totalcost_location($parish, $location_type, $location_name);
            $data['totalcost_division'] = $this->asset_model->totalcost_division($parish, $location_type, $location_name, $division_name);
            
            $this->load->view('Report/location_record', $data);
        }
    }
    
    public function fixed_asset_register_by_date()
    {
        $data['currentURL'] = base_url().'report/fixed_asset_register_by_date';
        $data['asset_list'] = $this->asset_model->getAssetCode();
        $this->form_validation->set_rules('start_date', 'Search Value', 'required|xss_clean');
        $this->form_validation->set_rules('end_date', 'Search Value', 'xss_clean');
        $this->form_validation->set_message('asset_items[]','Asset Items', 'xss_clean');
        $this->form_validation->set_message('required','Please enter a search value.');
                
        if($this->form_validation->run() == FALSE)
            $this->load->view('Report/fixed_asset_register_by_date', $data);
        else
        {   
            if(!empty($this->input->post('end_date')))
                $end_date = $this->input->post('end_date');
            else
                $end_date = date("Y-m-d",time());
            $asset_selected = $this->input->post('asset_items[]');
            if (empty($asset_selected)){
                $data['asset'] = $this->asset_model->searchFixedAssetByDate($this->input->post('start_date'),$end_date);
                $data['totalcost'] = $this->asset_model->totalCostFixedAssetByDate($this->input->post('start_date'),$end_date);
            } 
            else{
                $data['asset'] = $this->asset_model->searchFixedAssetByDateAndID($this->input->post('start_date'),$end_date, $asset_selected);
                $data['totalcost'] = $this->asset_model->totalCostFixedAssetByDateAndID($this->input->post('start_date'),$end_date,$asset_selected);
            }

            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $end_date;
            // $asset_names = $this->asset_model->getAssetCodeNameList($asset_selected);
            // $data['asset_names'] = $asset_names;
            $this->load->view('Report/fixed_asset_register_by_date', $data);
        }
    }    
    
    public function fixed_asset_log()
    {
        $data['currentURL'] = base_url().'report/fixed_asset_log';
        $this->form_validation->set_rules('start_date', 'Search Value', 'required|xss_clean');
        $this->form_validation->set_rules('end_date', 'Search Value', 'xss_clean');
        $this->form_validation->set_message('required','Please enter a search value.');
                
        if($this->form_validation->run() == FALSE)
            $this->load->view('Report/fixed_asset_log', $data);
        else
        {   
            if(!empty($this->input->post('end_date')))
                $end_date = $this->input->post('end_date');
            else
                $end_date = date("Y-m-d",time());
            
            $data['asset'] = $this->asset_model->searchFixedAssetLogByDate($this->input->post('start_date'),$end_date); 
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $end_date;
            $this->load->view('Report/fixed_asset_log', $data);
        }
    }

    public function monthly_log_report()
    {
        $data['currentURL'] = base_url().'report/monthly_log_report';
        $this->form_validation->set_rules('start_date', 'Search Value', 'required|xss_clean');
        $this->form_validation->set_rules('end_date', 'Search Value', 'xss_clean');
        $this->form_validation->set_message('required','Please enter a search value.');
                
        if($this->form_validation->run() == FALSE)
            $this->load->view('Report/monthly_log_report', $data);
        else
        {   
            if(!empty($this->input->post('end_date')))
                $end_date = $this->input->post('end_date');
            else
                $end_date = date("Y-m-d",time());
            
            $data['asset'] = $this->asset_model->searchFixedAssetMonthlyLogReportByDate($this->input->post('start_date'),$end_date);
            $data['asset_count'] = count($data['asset']);
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $end_date;
            $this->load->view('Report/monthly_log_report', $data);
        }
    }   
    
    
    public function individual_master_inventory_record()
    {
        $data['currentURL'] = base_url().'report/individual_master_inventory_record';
        $this->form_validation->set_rules('asset_code', 'Search Value', 'required|xss_clean');
        $this->form_validation->set_message('required','Please enter a search value.');
        
        $data['asset_code'] = $this->asset_model->getAssetCodesTotal();
        
        if($this->form_validation->run() == FALSE)
            $this->load->view('Report/individual_master_inventory_record',$data);
        else
        {                
            //$data['asset'] = $this->asset_model->searchFixedAssetByAssetCode($this->input->post('asset_code'));
            
            $asset = $this->asset_model->searchFixedAssetByAssetCode($this->input->post('asset_code'));
            $data['asset'] = $asset->result_array();
            $data['num_rows'] = $asset->num_rows();
            
            $this->load->view('Report/individual_master_inventory_record', $data);
        }
    }    
       
    public function master_inventory_records()
    {
        $data['currentURL'] = base_url().'report/master_inventory_records';
       //die($data['currentURL']);
        $config = $this->pagination('report/master_inventory_records/');
            
        $config['total_rows'] = count($this->asset_model->getAssetCodesTotal()); 

        $this->pagination->initialize($config);
	//end of pagination
        
        //$data['assets'] = $this->asset_model->getAssetCodesPaged($this->uri->segment(3),$this->offsetAmount);         
        $data['assets'] = $this->asset_model->getAssetCodesTotal();
        $this->load->view('Report/master_inventory_records', $data);        
    }
    
    public function generate_print_version()
    {
        //using ajax
        $assetsForPrint = $this->asset_model->getAssetCodesTotal();
        $data['assets'] = $this->master_inventory_records_print_version($assetsForPrint);
        //echo json_encode($printData);        
        $this->load->view('Report/print_master_inventory', $data);
        //echo "<script type='text/javascript'> window.onload=print_content(print_content); </script>";
        //$this->master_inventory_records();        
        
    }
    
    public function master_inventory_records_print_version($assets)
    {
        $printData = '<div id="print_content">
            <div class="row">
               <div class="col-lg-12">
                   <center><h4>COURT ADMINISTRATION DIVISION<br/>
                       Master Inventory Records</h4></center>
                   
                   <table id="mytables" class="table table-bordered">
                        <thead><tr>
                                <th>Asset Code</th>
                                <th>Description</th>                                
                                <th>Division/Location &emsp; &emsp; Asset Count</th>  
                                <th>Asset Total</th>                                 
                        </tr></thead>
                        <tbody>';
                    
                                $grand_total = 0;
                                foreach($assets as $outer_row)
                                {                                
                    $printData .= '     <tr>                                        
                                        <td>'.$outer_row["asset_code"].'</td> 	
                                        <td>'.$outer_row["description"].'</td>                                        
                                        <td> 
                                    ';
                                           
                                                
                                             
                                                   $asset = $this->asset_model->searchFixedAssetByAssetCode2($outer_row["asset_code_id"])->result_array();;
                                                   
                                                   $total_asset_count = 0;
                                                   
                                                   foreach($asset as $inner_row)
                                                    {
                                                       
                                                     
                            $printData .= '     <table style="width:100%">
                                                    <tbody>
                                                        <tr>                                        
                                                            <td  width="80%">'.$inner_row["division_name"].'</td> 	
                                                            <td width="20%">'.$inner_row["asset_count"].'</td> 
                                           ';
                                                            $total_asset_count += $inner_row["asset_count"];
                                                            $grand_total += $inner_row['asset_count']; 
                            $printData .= '             </tr>
                                                    </tbody>
                                            </table>
                                            ';        
                                                    }  
                                                    
                            $printData .= ' </td>
                                        <td>'.$total_asset_count.'</td>
                                    </tr>
                                ';
                                }  
                                
        $printData .= '  </tbody>		
                    </table>  
                    <b>Grand Total: '.$grand_total.'</b>
               </div> 
           </div><!--END second ROW-->
           </div>';
        
         return $printData;
    }

    public function written_off_assets()
    {        
        $data['currentURL'] = base_url().'report/written_off_assets';
        //die(date('Y-m-d H:i:s', strtotime('+1 day', time())));
        $this->form_validation->set_rules('serial', 'Serial', 'xss_clean');
        $this->form_validation->set_rules('start_date', 'Start Date', 'xss_clean');
        $this->form_validation->set_rules('end_date', 'End Date', 'xss_clean');        
        
        if($this->form_validation->run() == FALSE)
        {
			if($this->input->post('start_date')== '' || $this->input->post('start_date')== FALSE)
                $start_date = '0000-00-00 00:00:00';
            else
                $start_date = $this->input->post('start_date');
            
            if($this->input->post('end_date')=='' || $this->input->post('end_date')== FALSE)
                $end_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));
            else
                $end_date = $this->input->post('end_date');
			
            //load all written off assets by default
            $data['asset'] = $this->asset_model->searchWrittenOffAssets($this->input->post('serial'),$this->input->post('start_date'),$this->input->post('end_date')); 
            $this->load->view('Report/written_off_assets', $data);
        }
        else
        {    
            if($this->input->post('start_date')== '')
                $start_date = '0000-00-00 00:00:00';
            else
                $start_date = $this->input->post('start_date');
            
            if($this->input->post('end_date')=='')
                $end_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));
            else
                $end_date = $this->input->post('end_date');
            
            //die('ed '.$end_date);
            
            $data['asset'] = $this->asset_model->searchWrittenOffAssets($this->input->post('serial'),$start_date,$end_date); 
            $data['serial'] = $this->input->post('serial');
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;            
            $this->load->view('Report/written_off_assets', $data);
        }
    }    
    
    public function board_of_surveyed()
    {        
        $data['currentURL'] = base_url().'report/board_of_surveyed';
        //die(date('Y-m-d H:i:s', strtotime('+1 day', time())));
        $this->form_validation->set_rules('serial', 'Serial', 'xss_clean');
        $this->form_validation->set_rules('start_date', 'Start Date', 'xss_clean');
        $this->form_validation->set_rules('end_date', 'End Date', 'xss_clean');
                        
        if($this->input->post('start_date')== '' || $this->input->post('start_date')== FALSE)
                $start_date = '0000-00-00 00:00:00';
        else
            $start_date = $this->input->post('start_date');

        if($this->input->post('end_date')=='' || $this->input->post('end_date')== FALSE)
            $end_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));
        else
            $end_date = $this->input->post('end_date');
        
        if($this->form_validation->run() == FALSE)
        {
            /*
           if($this->input->post('start_date')== '' || $this->input->post('start_date')== FALSE)
                $start_date = '0000-00-00 00:00:00';
            else
                $start_date = $this->input->post('start_date');
            
            if($this->input->post('end_date')=='' || $this->input->post('end_date')== FALSE)
                $end_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));
            else
                $end_date = $this->input->post('end_date');
            */
            //load all written off assets by default
            $data['asset'] = $this->asset_model->searchBOSAssets($this->input->post('serial'),$start_date,$end_date); 
            //print('<pre>');
            //print_r($data['asset']);
            //print('<pre>');
            //die('test');
            $this->load->view('Report/board_of_surveyed', $data);
        }
        else
        {    /*
            if($this->input->post('start_date')== '')
                $start_date = '0000-00-00 00:00:00';
            else
                $start_date = $this->input->post('start_date');
            
            if($this->input->post('end_date')=='')
                $end_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));
            else
                $end_date = $this->input->post('end_date');
            */
            //die('ed '.$end_date);
            
            $data['asset'] = $this->asset_model->searchBOSAssets($this->input->post('serial'),$start_date,$end_date); 
            $data['serial'] = $this->input->post('serial');
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;            
            $this->load->view('Report/board_of_surveyed', $data);
        }
    } 
    
    public function lost_stolen()
    {        
        $data['currentURL'] = base_url().'report/lost_stolen';
            
        $data['asset'] = $this->asset_model->searchLostStolenAssets(); 
                      
        $this->load->view('Report/lost_stolen', $data);
       
    } 
    
    public function workbench()
    {
        $data['currentURL'] = base_url().'report/workbench';
               
        //$data['assets'] = $this->asset_model->getAssetCodesPaged($this->uri->segment(3),$this->offsetAmount);         
        $data['assigned_users'] = $this->asset_model->getAssignedUsers();
        $this->load->view('Report/workbench', $data);        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */