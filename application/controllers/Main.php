<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
    public $offsetAmount = 20;
    public $regular_user = 1;
    public $supervisor = 2;
    public $admin = 3;
    public $view_only= 4;
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
        //testing this
        		  
        public function test()
        {
            $var = base64_encode($_SESSION['username']);
            
            //foreach($var as $loc=>$item)
                //$var[$loc] = base64_decode($item);
            $var2 = base64_decode($var);
            echo 'var: '.$var;
            echo '<br/>var2: '.$var2;
            die('<br/>test');
        }
        
        public function loadGrid()
        {
            $config = $this->pagination('main/index/');
		
            $config['total_rows'] = $this->asset_model->countAsset();

            $this->pagination->initialize($config);
            //end of pagination

            $data['asset'] = $this->asset_model->getAssets($this->uri->segment(3),$this->offsetAmount);
            $this->load->view('Main/welcome',$data);
        }
        
        public function writtenOff()
        {
           // $config = $this->pagination('main/index/');
		
            //$config['total_rows'] = $this->asset_model->countAsset();

            //$this->pagination->initialize($config);
            //end of pagination

            //$data['asset'] = $this->asset_model->getAssets($this->uri->segment(3),$this->offsetAmount);
            $data['asset'] = $this->asset_model->getToBeWrittenOffAssets();
            $this->load->view('Main/to_be_written_off',$data);
        }
        
	public function index()
	{            
            $this->loadGrid();            
	}
        
        public function getLocationsByParishIdAndLocationTypeId()
        {            
            echo json_encode($this->asset_model->getLocation($_GET['parish_id'],$_GET['location_type_id']));             
        } 

        public function getLocationsByParishId()
        {            
            echo json_encode($this->asset_model->getLocationByParishId($_GET['parish_id']));             
        } 
        
        public function getDivisionsByLocationAbbre()
        {
            echo json_encode($this->asset_model->getDivision($_GET['location_id']));            
        }                
        
        public function addAsset()
        {   
            $this->user_model->hasRole($this->user_model->getUserById($_SESSION['fa_user_id'])['user_level'], array($this->regular_user,$this->supervisor,$this->admin));
            $this->validateForm();
            if(!empty(trim($this->input->post('division_name'))) && trim($this->input->post('division_name')) != -1)
            {
                $division_abbre = $this->asset_model->returnDivisionAbbr(trim($this->input->post('division_name')));
                if(strtolower($division_abbre) == 'b.o.s.')
                    $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'required|regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
                else
                    $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
            }  
            
            $written_off = 'n';
            $board_of_surveyed = 'n';
            
            if(!empty($_POST['written_off']))
            {
                if(!empty($_POST['board_of_surveyed']))
                {
                    $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'required|regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
                    $board_of_surveyed = 'y';                    
                }
                
                $written_off = 'y';            
            }
            else
                $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
            
            //retrieve asset codes
            $data['assetCode'] = $this->asset_model->getAssetCode();
            $data['parish'] = $this->asset_model->getParish();
            $data['location_type'] = $this->asset_model->getlocationType(); 
            $data['last_asset_code']= $this->asset_model->getLastAssetCode();
            
            //the repair fields are created dynamically in javascript, incase the user posts, recreate the 
            //fields prepopulated. No repair data would exist in the database on first creating so 0 is passed
            $repair_result = $this->asset_model->getFixedAssetRepairData(0); 
            $data['repair_table'] = $this->generate_dynamic_table($repair_result);
           
            if(trim($this->input->post('parish')))
            {
                $data['location'] = $this->asset_model->getLocation(trim($this->input->post('parish')),trim($this->input->post('location_type')));               
            }
            
            if(trim($this->input->post('location_name')))
            {
                $data['division'] = $this->asset_model->getDivision(trim($this->input->post('location_name')));               
            }
            
            if($this->form_validation->run() === false)
            {
            
                $this->load->view('Main/add_asset',$data);                  
            }
            else
            {   //if the user inputted a new asset code, add to database
                $asset_code = $this->checkIfNewAssetCodeWasAdded();
                
                //if the user inputted a new location, add to database
                $location = $this->checkIfNewLocationWasAdded();                
                
                //if the user inputted a new division, add to database
                $division = $this->checkIfNewDivisionWasAdded($location);
                 
                //madness
                $num_records= $this->input->post('num_records');                
                
               
                for($count=1; $count<=$num_records; $count++)
                {                
                    //check if a reserved number exists for an asset type 
                    //if one does not exist increment asset count and retrieve latest number
                    $reserved_number = $this->asset_model->getReservedAssetAssignedNumber($asset_code);

                    //retrieve location and division abbre to add to asset tag                
                    $location_abbre = $this->asset_model->returnLocationAbbr($location);
                    $division_abbre = $this->asset_model->returnDivisionAbbr($division);


                    //if there is no reserved number, increment asset count
                    if(!$reserved_number)
                    {                   
                        //increment asset in asset code table
                        $this->asset_model->incrementAsset($asset_code); 

                        //retrieve asset code and count based on code after asset incremented
                        $result = $this->asset_model->returnAssetCodeCount($asset_code);
                        $asset_count = $result['asset_count'];

                        //if user didnt provide serial, generate one using asset code and count
                        $serial_number = trim($this->input->post('serial_number'));
                        //the user should enter the letter q to get serial generated
                        if(strtolower(trim($this->input->post('serial_number')))== 'q')
                        {                     
                            $serial_number ='sysgen-'.$result['asset_code'].'-'.$result['asset_count'];                    
                        }   

                        //generate asset tag - CMS/Year/location abbre/division abbre/asset code/count
                        $asset_tag = 'CMS/'.date("Y", strtotime ($this->input->post('date_purchased'))).'/'.$location_abbre.'/'.$division_abbre.'/'.$result['asset_code'].'/'.$result['asset_count'];
                    }
                    else
                    {
                        //retrieve asset code 
                        $result = $this->asset_model->returnAssetCodeCount($asset_code);

                        $asset_count = $reserved_number['assigned_number'];
                        //if user didnt provide serial, generate one using asset code and count
                        $serial_number = trim($this->input->post('serial_number'));
                        //the user should enter the letter q to get serial generated
                        if(strtolower(trim($this->input->post('serial_number')))== 'q')
                        {                     
                            $serial_number ='sysgen-'.$result['asset_code'].'-'.$reserved_number['assigned_number'];                    
                        }                       

                        //generate asset tag - CMS/Year/location abbre/division abbre/asset code/count
                        $asset_tag = 'CMS/'.date("Y", strtotime ($this->input->post('date_purchased'))).'/'.$location_abbre.'/'.$division_abbre.'/'.$result['asset_code'].'/'.$reserved_number['assigned_number'];
                    }

                    //Added 10/23/2023 Renoir Elliott

                   
                    
                    $ext = "";
                    $name = $_FILES["asset_pictures"]["name"];
                    $tmp = explode(".", $name); # extra () to prevent notice
                    $ext = end($tmp);

                    $config['upload_path'] = 'uploadedFiles/asset_images';
                    $config['allowed_types'] = 'doc|docx|pdf|jpg|jpeg|png';
                    $config['file_name'] =  $serial_number.'.'. $ext;

                    $this->load->library('upload', $config);
                    $data['error'] = "";
                        //echo 'dffg'; die();


                    if(!$this->upload->do_upload('asset_pictures'))
                {   
                    $data['error'] = $this->upload->display_errors();
                   //echo var_dump($config); die();
                    //print_r($this->input->post('asset_pictures'));die();
                    //$this->load->view('Main/add_asset',$data);
                  //  echo 'success';die();
                    //return;
                    $filename =  $config['upload_path'] .'/'. 'noimage.jpg' ;
                }
                else  
                {
                // echo  'success';die();

                    $myfile = $this->upload->data();
                    //$filename = file_get_contents($myfile['full_path']);
                    $filename =  $config['upload_path'] .'/'. $config['file_name'] ;
                }
            
              

                 //   echo 'successful move';die();


                    if($this->asset_model->insertFixedAsset(                        
                            trim(stripslashes($this->input->post('make'))),
                            trim(stripslashes($this->input->post('model'))),
                            stripslashes($serial_number),
                            trim(stripslashes($this->input->post('description1'))),
                            trim($this->input->post('date_purchased')),
                            trim($this->input->post('date_writeoff')),
                            trim(stripslashes($this->input->post('supplier'))),
                            trim(stripslashes($this->input->post('cost'))),
                            trim(stripslashes($this->input->post('acct_ref'))),
                            $location,
                            $division, 
                            $asset_code,
                            $asset_tag,
                            trim(stripslashes(strtoupper($this->input->post('user')))),
                            trim(stripslashes($this->input->post('description2'))),
                            $asset_count,
                            $filename
                            )
                        )  
                    {
                        //retrieve id of fixed asset created
                        $asset_id = $this->asset_model->retrieveLastCreatedFixedAsset();

                        if($reserved_number)
                        {
                            $this->asset_model->removeReservedNumber($asset_code, $asset_count);
                        }

                        $this->add_repair($asset_id);
                        //redirect('main/index');
                    }
                    else 
                        echo 'error ';                    
                        // echo 'error '.mysql_error();                    
                        //$this->load->view('Main/add_asset',$data);  
                    
                    if(strtolower(trim($this->input->post('serial_number')))!= 'q')
                        break;
                }
                redirect('main/index');
            }            
             
        }
    
    
        
        public function addNewlyPurchasedAssets()
        {   
            $config['upload_path'] = 'uploadedFiles/';
            $config['allowed_types'] = 'doc|docx|pdf|jpg|jpeg|png';
            $this->load->library('upload', $config);
            $data['error'] = "";

            $this->user_model->hasRole($this->user_model->getUserById($_SESSION['fa_user_id'])['user_level'], array($this->regular_user,$this->supervisor,$this->admin));
            // $this->validateForm();
            $this->form_validation->set_rules('npa_name', 'Subject/Items', 'required');
            $this->form_validation->set_rules('npa_submitted_date', 'Date Created', 'required');
            $this->form_validation->set_rules('npa_officer', 'Officer', 'required');

            $data['username'] = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);

            $data['parish'] = $this->asset_model->getParish();
           
            if(trim($this->input->post('parish')))
                $data['location'] = $this->asset_model->getLocationByParishId(trim($this->input->post('parish')));               
            
            if(trim($this->input->post('location_name')))
                $data['division'] = $this->asset_model->getDivision(trim($this->input->post('location_name')));               
            
            if($this->form_validation->run() === false)
                $this->load->view('Main/add_newly_purchased_assets',$data);                  
            
            else
            {
                if(!$this->upload->do_upload('npa_invoice'))
                {
                    $data['error'] = $this->upload->display_errors();
                    $this->load->view('Main/add_newly_purchased_assets',$data);
                    return;
                }
                else
                {
                    $myfile = $this->upload->data();
                    $filename = $myfile['file_name'];
                }

                $this->asset_model->saveNewlyPurchasedAssets(                        
                    trim(stripslashes($this->input->post('npa_name'))),
                    trim(stripslashes($this->input->post('npa_submitted_date'))),
                    trim(stripslashes($this->input->post('npa_officer'))),
                    $filename
                );

                //retrieve id of fixed asset created
                $newly_purchased_asset_id = $this->asset_model->retrieveLastCreatedNewlyPurchasedAsset();

                $count = $this->input->post('count');
                $npa_count_items = 0;
                for ($i=0; $i <= $count; $i++) {
                    $npa_assigned_user = trim(stripslashes($this->input->post('npa_assigned_user_'.$i)));
                    if(!empty($npa_assigned_user)){
                        $this->asset_model->saveNewlyPurchasedAssetItems(
                            $newly_purchased_asset_id,
                            $npa_assigned_user,
                            trim(stripslashes($this->input->post('parish_'.$i))),
                            trim(stripslashes($this->input->post('location_name_'.$i))),
                            trim(stripslashes($this->input->post('division_name_'.$i)))
                        );
                        $npa_count_items++;
                    }
                }
                $this->asset_model->countNewlyPurchasedAssetsItems($newly_purchased_asset_id, $npa_count_items);
                redirect('main/index');
            }            
             
        }  
        
        public function checkIfNewAssetCodeWasAdded()
        {
            
            //die('test2');
        
            if(!empty(trim($this->input->post('asset_code2'))) && !empty(trim($this->input->post('asset_description2'))))
            {  
                $this->asset_model->insertNewAssetCode(                        
                    trim(stripslashes($this->input->post('asset_code2'))),
                    trim(stripslashes($this->input->post('asset_description2'))));

                //get id of newly created location which would be the last id created
                return $this->asset_model->getNewAssetCodeId();
            }
            else
                return $this->input->post('asset_code');
        }
        
         //server side validation of email address for users. This function checks the database for the email address entered by the user to see if it already exists
    public function asset_code_exist($code)
    {	
        $result = $this->asset_model->testAssetCode($code);
        //die('test');
        if(count($result) >= 1)
        {
            $this->form_validation->set_message('asset_code_exist', 'Asset Code already exists...');
            return false;
        }        
        else
        {
           return true;
        }
    }
    
    public function location_exist($abbre)
    {	
        $result = $this->asset_model->testLocation($abbre);
        //die('test');
        if(count($result) >= 1)
        {
            $this->form_validation->set_message('location_exist', 'Location Abbreviation already exists...');
            return false;
        }        
        else
        {
           return true;
        }
    }
        
        public function checkIfNewLocationWasAdded()
        {
            if(!empty(trim($this->input->post('location_abbre2'))) && !empty(trim($this->input->post('location2'))))
            {  
                $this->asset_model->insertNewLocation(                        
                    trim(stripslashes($this->input->post('location_abbre2'))),
                    trim(stripslashes($this->input->post('location2'))),
                    trim($this->input->post('parish')),
                    trim($this->input->post('location_type')));

                //get id of newly created location which would be the last id created
                return $this->asset_model->getNewLocationId();
            }
            else
                return $this->input->post('location_name');
        }
        
        public function checkIfNewDivisionWasAdded($location)
        {
            //if the user inputted a new division, add to database
            if(!empty(trim($this->input->post('division_abbre2'))) && !empty(trim($this->input->post('division2'))))
            {  
                $this->asset_model->insertNewDivision(                        
                    trim(stripslashes($this->input->post('division_abbre2'))),
                    trim(stripslashes($this->input->post('division2'))),
                    $location);

                //get id of newly created division which would be the last id created
                return $this->asset_model->getNewDivisionId();
            }
            else
            {                    
                return $this->input->post('division_name');
            }
        }

        public function asset_detail($asset_id)
        {            
           //$this->get_asset_detail($asset_id);
            $data['result'] = $this->asset_model->getAsset($asset_id); 
            /*print '<pre>';
            print_r($data['result']);
            print '<pre>';
            die('test');*/
            //the repair fields are created dynamically in javascript, incase the user posts, recreate the 
            //fields prepopulated. Also, retrieve existing repair data if exists 
            $repair_result = $this->asset_model->getFixedAssetRepairData($asset_id);
            $data['fixed_asset_log'] = $this->asset_model->getFixedAssetLogData($asset_id);
			
            $data['fixed_asset_attachment'] = $this->asset_model->getFixedAssetAttachment($asset_id);           
			$data['repair_table'] = $this->generate_dynamic_table($repair_result);
            //retrieve asset codes
            $data['assetCode'] = $this->asset_model->getAssetCode();
			
            $data['parish'] = $this->asset_model->getParish();
            $data['location_type'] = $this->asset_model->getlocationType();
            $data['location'] = $this->asset_model->getlocation($data['result']['parish_id'],$data['result']['location_type_id']);  
            $data['division'] = $this->asset_model->getdivision(stripslashes($data['result']['location'])); 
			$data['last_asset_code']= $this->asset_model->getLastAssetCode();
            
            $this->load->view('Main/asset_detail',$data);
        }
        
        public function search()
        {
            $this->form_validation->set_rules('value', 'Search Value', 'required|xss_clean');
            $this->form_validation->set_message('required','Please enter a search value.');
		
            if($this->form_validation->run() == FALSE)
				$this->loadGrid();
            
            
            //$this->form_validation->set_rules('approve', 'Search Value', 'required|xss_clean');
            elseif(isset($_POST['assets']) && !empty($_POST["assets"]) && isset($_POST['btnGenerate']) && $_POST['btnGenerate'])
            {    
                require_once 'inc/PHPWord/PHPWord.php';
                require_once 'inc/PHPWord/PHPWord/IOFactory.php';

                $asset = $this->asset_model->getAssetCode();
                $PHPWord = new PHPWord();
                
                date_default_timezone_set('America/Bogota');
                $today = strtoupper(date("d-M-Y",time()));
                
               
                //$document = $PHPWord->loadTemplate('/mnt/stor10-wc2-dfw1/544037/573621/www.cms.gov.jm/web/content/fixed-assets/inc/DispatchSheet.docx');
                
				$document = $PHPWord->loadTemplate('/data/www/vhosts/cad.gov.jm/httpdocs/fixed-assets/inc/DispatchSheet.docx');
				
                $data['asset'] = $this->asset_model->searchFixedAssetBySerial(trim(stripslashes($this->input->post('value'))));
				$this->load->view('Main/search_view', $data);
                
                $countChecked = count($_POST['assets']);  
				
				
				$checked_id = $_POST['assets'][0];
				//print('jaja '.$checked_id);
				
				
				$first_checked_asset_arr = $this->asset_model->getAsset($checked_id);
/*
				print "<pre>";
				
				print_r($first_checked_asset_arr);
				print "</pre>";
				die('jaja');
*/
                $document->setValue('created_by',htmlspecialchars($first_checked_asset_arr["created_by"]));
                $document->setValue('dept',  htmlspecialchars(strtoupper($first_checked_asset_arr["location_name"])).' / '.htmlspecialchars(strtoupper($data['asset'][0]["division_name"])));
                $document->setValue('assigned_user',htmlspecialchars($first_checked_asset_arr["user"]));
                $document->setValue('today',$today);
                
                for($count=0; $count < $countChecked; $count++)
                {
                    $assets = $this->asset_model->getAsset($_POST['assets'][$count]);
                    
                    $document->setValue('asset_type_'.($count+1), htmlspecialchars($assets["description"]));
                    $document->setValue('serial_'.($count+1), htmlspecialchars($assets["serial_number"]));
                    $document->setValue('make_'.($count+1), htmlspecialchars($assets["make"]));
                    $document->setValue('asset_tag_'.($count+1), htmlspecialchars($assets["asset_tag"]));
                }
                
                for($count=$countChecked; $count < 12; $count++)
                {                    
                    $document->setValue('asset_type_'.($count+1), "");
                    $document->setValue('serial_'.($count+1), "");
                    $document->setValue('make_'.($count+1), "");
                    $document->setValue('asset_tag_'.($count+1), "");
                }  

                $filename = 'DISPATCH - '.htmlspecialchars($first_checked_asset_arr["user"]).'.docx';
                $document->save($filename);

                header("Content-Type: application/vnd.ms-word");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");                
                header("Content-disposition: attachment; filename={$filename}");

                ob_clean();
                flush();
                readfile($filename);

                unlink($filename);
                
            }        
            
            elseif(isset($_POST['btnSearch']) && $_POST['btnSearch'])
            {      
                //die("else if 2");
                $data['asset'] = $this->asset_model->searchFixedAssetBySerial(trim(stripslashes($this->input->post('value'))));
				$this->load->view('Main/search_view', $data);
            }
            
            else
            {    
                //die("else");
                $data['asset'] = $this->asset_model->searchFixedAssetBySerial(trim(stripslashes($this->input->post('value'))));
				$this->load->view('Main/search_view', $data);
            }
		}
        
        public function search_written_off()
        {
            $this->form_validation->set_rules('value', 'Search Value', 'required|xss_clean');
            $this->form_validation->set_message('required','Please enter a search value.');
		
            if($this->form_validation->run() == FALSE)
		$this->loadGrid();
            else
            {                
                $data['asset'] = $this->asset_model->searchFixedAssetBySerial(trim(stripslashes($this->input->post('value'))));
		$this->load->view('Main/search_written_off_view', $data);
            }
	}
        
        public function check_dynamic_fields()
        {
            if ($_POST['date_fields'] && $_POST['repair_fields'] &&$_POST['from_fields'] &&$_POST['to_fields'] &&$_POST['cost_fields'])
            {   
                $error = '';
                foreach ( $_POST['date_fields'] as $i=>$value )
                {
                    //detect if any field is empty
                    if(empty($_POST['date_fields'][$i]) || empty($_POST['repair_fields'][$i]) || empty($_POST['from_fields'][$i]) || empty($_POST['to_fields'][$i]) || empty($_POST['cost_fields'][$i]))
                    {
                        $error .= 'Please fill out all fields in row: '.($i+1).'<br/>';                        
                    }
                    
                     //validating dates
                    if($_POST['from_fields'][$i] > $_POST['to_fields'][$i])
                    {
                        $error .= 'From date cannot be greater than To date for row: '.($i+1).'<br/>'; 
                    }
                }
                
                if($error != '')
                {
                    $this->form_validation->set_message('check_dynamic_fields',$error);
                    return false;
                }                
                
                return true;  
            }
        }


        public function validateForm($ser='')
        {            
            $this->form_validation->set_rules('asset_code', 'Asset Code', 'callback_select_validate[Asset Code]|xss_clean');
            $this->form_validation->set_rules('make', 'Make', 'xss_clean');
            $this->form_validation->set_rules('model', 'Model', 'xss_clean');
            
            //$this->form_validation->set_rules('serial_number', 'serial number', 'callback_check_serial|xss_clean');
            /*
            if(trim($this->input->post('serial_number'))== ''  )
            {
                $this->form_validation->set_rules('serial_number', 'serial number', 'callback_check_serial|xss_clean');
            }*/
            if(trim($this->input->post('serial_number'))== $ser && $ser != ''  )
            {
                $this->form_validation->set_rules('serial_number', 'serial number', 'xss_clean');
            }
            else
                $this->form_validation->set_rules('serial_number', 'serial number', 'callback_check_serial|xss_clean');
            
            $this->form_validation->set_rules('description1', 'Description 1', 'xss_clean');
            
            $this->form_validation->set_rules('parish', 'parish', 'callback_select_validate[parish]|xss_clean');
            $this->form_validation->set_rules('location_type', 'Location Type', 'callback_select_validate[Location Type]|xss_clean');
            $this->form_validation->set_rules('location_name', 'location', 'callback_select_validate[location]|xss_clean');
            $this->form_validation->set_rules('division_name', 'division', 'callback_select_validate[division]|xss_clean');
            
            $this->form_validation->set_rules('date_purchased', 'Date Purchased', 'regex_match[/^\d{4}-\d{2}-\d{2}$/]|required|xss_clean');
            //$this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'regex_match[/^\d{4}-\d{2}-\d{2}$/]|xss_clean');
            $this->form_validation->set_rules('supplier', 'supplier', 'xss_clean');
            $this->form_validation->set_rules('cost', 'cost', 'xss_clean');
            $this->form_validation->set_rules('acct_ref', 'acct_ref', 'xss_clean');
            $this->form_validation->set_rules('user', 'user', 'xss_clean');
            $this->form_validation->set_rules('description2', 'description2', 'xss_clean');
            	
            $this->form_validation->set_rules('asset_code2', 'Asset Code', 'callback_asset_code_exist|xss_clean');
            $this->form_validation->set_rules('location_abbre2', 'Location', 'callback_location_exist|xss_clean');
            $this->form_validation->set_rules('written_off', 'Write-Off', 'xss_clean');
            //$this->form_validation->set_message('greater_than','Please select an option from the dropdown menu');
            
            if (isset($_POST['repair_ids']) && $_POST['repair_ids'] )
                $this->form_validation->set_rules('repair_ids', 'Repair', 'callback_check_dynamic_fields');
            
            
        }
        
        public function check_serial()
        {
            if(empty(trim($this->input->post('serial_number'))))
            {
                $this->form_validation->set_message('check_serial','Serial is required...If you wish to generate a serial, Enter q');
                return false;                
            }
            
            $result = $this->asset_model->testSerial(trim($this->input->post('serial_number')));        
            
            if(count($result) >= 1)
            {
                $this->form_validation->set_message('check_serial', 'Serial already exists...');
                return false;
            }        
            return true;
        }

        public function select_validate($value,$name)
        {
            if(($name =='Asset Code' && $value == -1) && ( empty(trim($this->input->post('asset_code2'))) || empty(trim($this->input->post('asset_description2'))) ))
            {
                $this->form_validation->set_message('select_validate',$name.' is required. Please select from the available list or enter the Asset Code if it is not provided');
                return false;
            }
            elseif($name =='parish' && $value == -1)
            {
                $this->form_validation->set_message('select_validate',$name.' is required');
                return false;
            }
            elseif($name =='Location Type' && $value == -1)
            {
                $this->form_validation->set_message('select_validate',$name.' is required');
                return false;
            }
            elseif(($name == 'location' && $value == -1) && ( empty(trim($this->input->post('location_abbre2'))) || empty(trim($this->input->post('location2'))) ))
            {
                $this->form_validation->set_message('select_validate',$name.' is required. Please select from the available list or enter the location if it is not provided');
                return false;                
            }
            elseif(($name == 'division' && $value == -1) && ( empty(trim($this->input->post('division_abbre2'))) || empty(trim($this->input->post('division2'))) ))
            {
                $this->form_validation->set_message('select_validate',$name.' is required. Please select from the available list or enter the division if it is not provided');
                return false;                
            }
            return true;
        }
        
        public function updateAsset($asset_id) // changed
        {            
            //prepopulate variables from database
            $data['result'] = $this->asset_model->getAsset($asset_id); 
            
            $this->validateForm($data['result']['serial_number']);            
            
            if(!empty(trim($this->input->post('division_name'))) && trim($this->input->post('division_name')) != -1)
            {
                $division_abbre = $this->asset_model->returnDivisionAbbr(trim(stripslashes($this->input->post('division_name'))));
                if(strtolower($division_abbre) == 'b.o.s.')
                    $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'required|regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
                else
                    $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
            }              
            
            $written_off = 'n';
            $board_of_surveyed = 'n';
            
            if(!empty($_POST['written_off']))
            {
                if(!empty($_POST['board_of_surveyed']))
                {
                    $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'required|regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
                    $board_of_surveyed = 'y';                    
                }
                
                $written_off = 'y';            
            }
            else
                $this->form_validation->set_rules('date_writeoff', 'Date Writeoff', 'regex_match[/^(\d{4}-\d{2}-\d{2})?$/]|xss_clean');
            
            
            $lost_stolen = 'n';
            
            if(!empty($_POST['lost_stolen']))
            {
                $this->form_validation->set_rules('attachment', 'Attachment', 'xss_clean');
                $lost_stolen = 'y';                    
            }
            
            //prepopulate variables from database
            $data['result'] = $this->asset_model->getAsset($asset_id); 
           
            $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
            if($user['user_level'] <= 2 && $data['result']['written_off']=='y')            
                $written_off = 'y';  
            
            if($data['result']['lost_stolen']=='y')            
                $lost_stolen = 'y';    
            //test
                        
            
            //Retrieve existing repair data if exists 
            $repair_result = $this->asset_model->getFixedAssetRepairData($asset_id);
            
            $data['assetCode'] = $this->asset_model->getAssetCode();
            $data['parish'] = $this->asset_model->getParish();
            $data['location_type'] = $this->asset_model->getlocationType();
            $data['location'] = $this->asset_model->getlocation($data['result']['parish_id'],$data['result']['location_type_id']);  
            $data['division'] = $this->asset_model->getdivision(stripslashes($data['result']['location']));
            $data['asset_pictures'] = $this->asset_model->getFileNames(($data['result']['asset_pictures']));
            //echo var_dump($data['asset_pictures']);die();
            
            if($this->form_validation->run() == false)
            {   
                //the repair fields are created dynamically in javascript, incase the user posts, recreate the 
                //fields prepopulated. 
                $data['repair_table'] = $this->generate_dynamic_table($repair_result);
                $this->load->view('Main/asset_detail',$data);
            }
            else
            {  
                //this section deals with uploading of files
                $check_attachment = $this->asset_model->getFixedAssetAttachment($asset_id); 
                /*
                if(!empty($check_attachment))
                    die('-');
                else
                    die('+');
                */
                if(!empty($_POST['lost_stolen']) && empty($check_attachment))
                {
                    $config['upload_path'] = 'uploadedFiles/';
                    $config['allowed_types'] = 'doc|docx|pdf';
                    $this->load->library('upload', $config);

                    $filename = '';
                    //print_r($_FILES);
                    //die('test');
                    foreach($_FILES as $file)
                    {
                        $file['name'] = str_replace(' ', '___', $file['name']);
                        if($file['name'] == '')
                        {
                            $data['message'] = 'File Required <br/>';
                            $this->load->view('Main/asset_detail',$data);
                             return;
                        }

                        if(!$this->upload->do_upload('attachment'))
                        {
                            //$this->form_validation->set_message('message', 'The allowed file types are .doc, .docx and .pdf');
                            $data['message'] = $this->upload->display_errors().' The allowed file types are .doc, .docx and .pdf';
                            //$data['pageLabel'] = $this->maintenance_model->getLabel($_SESSION['tempDataID']);
                            $this->load->view('Main/asset_detail',$data);
                            //die('filename -:'. $filename);
                            return;
                        }
                        else
                        {
                            $myfile = $this->upload->data();
                            $filename = $myfile['file_name'];
                             //die('filename +:'. $filename);
                        }
                    }


                }

                if(!empty($_POST['asset_pictures'])){

                $ext = "";
                    $name = $_FILES["asset_pictures"]["name"];
                    $tmp = explode(".", $name); # extra () to prevent notice
                    $ext = end($tmp);

                   $serial_number = trim($this->input->post('serial_number'));
                   

                    $config['upload_path'] = 'uploadedFiles/asset_images';
                    $config['allowed_types'] = 'doc|docx|pdf|jpg|jpeg|png';
                    $config['file_name'] =  $serial_number.'.'. $ext;

                    $this->load->library('upload', $config);
                    $data['error'] = "";

                    if(!$this->upload->do_upload('asset_pictures'))
                {   
                    
                    $data['error'] = $this->upload->display_errors();
                   //echo var_dump($config); die();
                   //print_r($data['error']);die();
                    $this->load->view('Main/add_asset',$data);
                   return;
                }
                else  
                {
                  // echo 'success';die();

                    $myfile = $this->upload->data();
                    //$filename = file_get_contents($myfile['full_path']);
                    $filename =  $config['upload_path'] .'/'. $config['file_name'] ;
                }
            }

               
                
                
               // die('filename :'. $filename);
                
                //if the user inputted a new location, add to database
                $location = $this->checkIfNewLocationWasAdded();                
                
                //if the user inputted a new division, add to database
                $division = $this->checkIfNewDivisionWasAdded($location);
                
                //retrieve location and division abbre to add to asset tag                
                $location_abbre = $this->asset_model->returnLocationAbbr($location);
                $division_abbre = $this->asset_model->returnDivisionAbbr($division);
                
                //detect if user changed asset code. If changed would have to decrement previous count and increment for the new code
                
                if($data['result']['asset_code_id'] == $this->input->post('asset_code'))
                {
                    //retrieve asset code and count based on code. The code and count would be the same as previous
                    $result = $this->asset_model->returnAssetCodeCount($data['result']['asset_code_id']);
                    //$asset_tag = $data['result']['asset_tag'];
                    $assigned_number = $data['result']['assigned_number'];
                    //generate asset tag - CMS/Year/location abbre/division abbre/asset code/count
                    $asset_tag = 'CMS/'.date("Y", strtotime ($this->input->post('date_purchased'))).'/'.$location_abbre.'/'.$division_abbre.'/'.$result['asset_code'].'/'.$assigned_number;
                    //$asset_tag = 'CMS/'.date("Y", TIME()).'/'.$location_abbre.'/'.$division_abbre.'/'.$result['asset_code'].'/'.$result['asset_count'];
                
                    //die('result: '.$data['result']['asset_code_id'].'<br/>post: '.$this->input->post('asset_code'));
                    
                }
                else
                {   //user changed asset code, keep track of the assigned number to use for the next added asset
                    $this->asset_model->store_reserved_asset_assigned_number($data['result']['asset_code_id'],$data['result']['assigned_number']);                       
                    
                    //increment asset for new code in asset code table
                    $this->asset_model->incrementAsset(trim($this->input->post('asset_code'))); 
                    
                    //retrieve asset code and count based on code
                    $result = $this->asset_model->returnAssetCodeCount(trim($this->input->post('asset_code')));
                    
                    //generate asset tag - CMS/Year/location abbre/division abbre/asset code/count
                    //$asset_tag = 'CMS/'.date("Y", TIME()).'/'.$location_abbre.'/'.$division_abbre.'/'.$result['asset_code'].'/'.$result['asset_count'];   
                    $asset_tag = 'CMS/'.date("Y", strtotime ($this->input->post('date_purchased'))).'/'.$location_abbre.'/'.$division_abbre.'/'.$result['asset_code'].'/'.$result['asset_count'];
                    $assigned_number = trim($result['asset_count']);
                /*                    
                    //detect serial
                    //retrieve the original asset code and count that was stored for the asset and compare it with the serial to see if the serial was generated.
                    $prev_result = $this->asset_model->returnAssetCodeCount($data['result']['asset_code_id']);
                    
                    $serial = trim($this->input->post('serial_number'));
                    $suffix = strrchr($serial, "-"); 
                    $pos = strpos($serial,$suffix);
                    $beginning = substr($serial, 0, $pos);
                    $end = substr($serial, $pos+1);
                    
                    if(($beginning == $prev_result['asset_code'] && $end == $prev_result['asset_count']) || strtolower(trim($this->input->post('serial_number')))== 'q')
                    {
                        $serial_number ='sysgen-'.$result['asset_code'].'-'.$result['asset_count']; 
                        //die('if');
                    }
                    else 
                    {
                        $serial_number = trim($this->input->post('serial_number'));
                         //die('else');
                    }
                    */
                }  
                //if the user changed asset code, empty serial field using javascript(found in Main/asset_detail.php)
                if(strtolower(trim($this->input->post('serial_number')))== 'q')
                {
                    $serial_number ='sysgen-'.$result['asset_code'].'-'.$result['asset_count'];                        
                }
                else 
                {
                    $serial_number = trim($this->input->post('serial_number'));                         
                }
 
                //die('Prev writeoff'.date('Y-m-d',  strtotime ($data['result']['date_writeoff'])).' date purc'.trim($this->input->post('date_writeoff')));


            
           
                if($this->asset_model->updateFixedAsset(                         
                        trim(stripslashes($this->input->post('make'))),
                        trim(stripslashes($this->input->post('model'))), 
                        stripslashes($serial_number),
                        trim(stripslashes($this->input->post('description1'))),
                        trim($this->input->post('date_purchased')),
                        trim($this->input->post('date_writeoff')),
                        trim(stripslashes($this->input->post('supplier'))),
                        trim($this->input->post('cost')),
                        trim(stripslashes($this->input->post('acct_ref'))),
                        $location,
                        $division, 
                        trim($this->input->post('asset_code')),
                        $asset_tag,
                        trim(stripslashes(strtoupper($this->input->post('user')))),
                        trim(stripslashes($this->input->post('description2'))),
                        $assigned_number,
                        $written_off,
                        $board_of_surveyed,
                        $lost_stolen, 
                        $filename,                        
                        trim($this->input->post('asset_id')),
                       
                        )
                    ) 
                {   
                    /*
                    $otherUsersUpdated = $this->asset_model->checkAllInstanceOfUser($data['result']['user'],trim($this->input->post('asset_id')));
                    
                    if($otherUsersUpdated)
                    {
                        $this->asset_model->updateAllInstanceOfUser(trim(stripslashes(strtoupper($this->input->post('user')))),$data['result']['user']);
                    
                        $action_done = '<b>Previous User:</b> '.$data['result']['user'].'<br/> ';
                        
                        foreach($otherUsersUpdated as $logUsers)
                        {
                            $this->asset_model->insertFixedAssetLog($logUsers['asset_number'],$action_done);
                        }
                        
                    }
                    */
                    
                    $this->add_repair($asset_id);
                    
                    if(!empty($_POST['lost_stolen']) && empty($check_attachment))                
                        $this->asset_model->insertFixedAssetAttachment($asset_id,$filename);                   
                                        
                    //audit updates made 
                    $action_done = '';
                    if($data['result']['make'] != trim($this->input->post('make')))
                        $action_done = '<b>Previous Make:</b> '.$data['result']['make'].'<br/> ';
                    if($data['result']['model'] != trim($this->input->post('model')))
                        $action_done .= '<b>Previous Model:</b> '.$data['result']['model'].'<br/> ';
                    if($data['result']['serial_number'] != $serial_number)
                        $action_done .= '<b>Previous Serial #:</b> '.$data['result']['serial_number'].'<br/> ';
                    if($data['result']['description1'] != trim($this->input->post('description1')))
                        $action_done .= '<b>Previous Description:</b> '.$data['result']['description1'].'<br/> ';
                    if(date('Y-m-d',  strtotime ($data['result']['date_purchased'])) != trim($this->input->post('date_purchased')))
                        $action_done .= '<b>Previous Date Purchased:</b> '.$data['result']['date_purchased'].'<br/> ';
                    if($this->input->post('date_writeoff') && date('Y-m-d',  strtotime ($data['result']['date_writeoff'])) != trim($this->input->post('date_writeoff')))
                        $action_done .= '<b>Previous BOS Date:</b> '.$data['result']['date_writeoff'].'<br/> ';
                    if($data['result']['supplier'] != trim($this->input->post('supplier')))
                        $action_done .= '<b>Previous Supplier:</b> '.$data['result']['supplier'].'<br/> ';
                    if($data['result']['cost'] != trim($this->input->post('cost')))
                        $action_done .= '<b>Previous Cost:</b> '.$data['result']['cost'].'<br/> ';
                    if($data['result']['acct_ref'] != trim($this->input->post('acct_ref')))
                        $action_done .= '<b>Previous Invoice #:</b> '.$data['result']['acct_ref'].'<br/> ';
                    if($data['result']['location'] != $location)
                        $action_done .= '<b>Previous Location:</b> '.$data['result']['location_name'].'<br/> ';
                    if($data['result']['division'] != $division)
                        $action_done .= '<b>Previous Division:</b> '.$data['result']['division_name'].'<br/> ';
                    if($data['result']['asset_code_id'] != trim($this->input->post('asset_code')))
                        $action_done .= '<b>Previous Asset Code:</b> '.$data['result']['asset_code'].'|'.$data['result']['description'].'<br/> ';
                    if($data['result']['asset_tag'] != $asset_tag)
                        $action_done .= '<b>Previous Asset Tag:</b> '.$data['result']['asset_tag'].'<br/> ';
                    if($data['result']['user'] != trim($this->input->post('user')))
                        $action_done .= '<b>Previous User:</b> '.$data['result']['user'].'<br/> ';
                    if($data['result']['description2'] != trim($this->input->post('description2')))
                        $action_done .= '<b>Previous Remarks:</b> '.$data['result']['description2'].'<br/> ';
                    if($data['result']['written_off'] != $written_off)
                        $action_done .= '<b>Previously Written Off:</b> '.$data['result']['written_off'].'<br/> ';
                    if($data['result']['board_of_surveyed'] != $board_of_surveyed)
                        $action_done .= '<b>Previously BOS:</b> '.$data['result']['board_of_surveyed'].'<br/> ';
                    if($data['result']['lost_stolen'] != $lost_stolen)
                        $action_done .= '<b>Previously Lost/Stolen:</b> '.$data['result']['lost_stolen'].'<br/> ';
                    
                    $this->asset_model->insertFixedAssetLog($asset_id,$action_done);
                    $this->asset_model->removeEmptyFixedAssetLog();
                    
                    redirect('main/index');
                }
                else 
                    echo 'error ';                    
                    // echo 'error '.mysql_error();                    
                    //$this->load->view('Main/add_asset',$data);                
            }          
        }
        
        public function generate_dynamic_table($repair_result)
        {           
            $table = '<table id="repair_table"><tr>';
            $table .='<th rowspan="2">Date</th>';
            $table .='<th rowspan="2">Nature of Repairs or Service</th>';
            $table .='<th colspan="2">Period out of use</th>';
            $table .='<th rowspan="2">Cost</th>';
            $table .='</tr>';
            $table .='<tr>';    
            $table .='<th>From</th>';
            $table .='<th>To</th>';    
            $table .='</tr>';
            
            if (isset($_POST['date_fields']) && $_POST['date_fields'])
            {  
                foreach ( $_POST['date_fields'] as $i=>$value )
                {
                    $table .= '<tr>'.
                    '<input type="hidden" name="repair_ids[]" value="'.$_POST['repair_ids'][$i].'" />'.
                    '<td><input id="date_field_' . $i . '" name="date_fields[]" value="'.$_POST['date_fields'][$i].'" type="date" />' .
                    '<td><input id="repair_field_' . $i . '" name="repair_fields[]" value="'.stripslashes($_POST['repair_fields'][$i]).'"  type="text" />' .
                    '<td><input id="from_field_' . $i . '" name="from_fields[]" value="'.$_POST['from_fields'][$i].'"  type="date" />' .
                    '<td><input id="to_field_' . $i . '" name="to_fields[]" value="'.$_POST['to_fields'][$i].'" type="date" />' .
                    '<td><input id="cost_field_' . $i . '" name="cost_fields[]" value="'.$_POST['cost_fields'][$i].'" type="number" step="0.01" />' .
                    '</tr>';
                }
                
                return $table.'</table>';
                
            }
            elseif(isset($repair_result) && !empty($repair_result))
            {
               foreach ( $repair_result as $row )
                {
                     $table .= '<tr>'.
                    '<input type="hidden" name="repair_ids[]" value="'.$row['fixed_asset_repair_id'].'"/>'.
                    '<td><input name="date_fields[]" value="'.date('Y-m-d',  strtotime($row['repair_date'])).'" type="date" />' .
                    '<td><input name="repair_fields[]" value="'.$row['nature_of_repair'].'"  type="text" />' .
                    '<td><input name="from_fields[]" value="'.date('Y-m-d',  strtotime($row['from_date'])).'"  type="date" />' .
                    '<td><input name="to_fields[]" value="'.date('Y-m-d',  strtotime($row['to_date'])).'" type="date" />' .
                    '<td><input name="cost_fields[]" value="'.$row['repair_cost'].'" type="number" step="0.01" />' .
                    '</tr>';
                }
                return $table.'</table>';
            }
            else
                return '';
        }
        
        public function add_repair($asset_id)
        {            
            if(isset($_POST['repair_ids']) && $_POST['repair_ids'])
            {
                foreach ( $_POST['date_fields'] as $i=>$value )
                {
                    //echo $_POST['date_fields'][$i].'||'.$_POST['repair_fields'][$i].'|'.$_POST['from_fields'][$i].'|'.$_POST['to_fields'][$i].'|'.$_POST['cost_fields'][$i].'<br/>'; 
                    if($_POST['repair_ids'][$i] != 0)
                    {
                        $this->asset_model->update_fixed_asset_repair_data(
                                $asset_id,
                                $_POST['date_fields'][$i],
                                trim(stripslashes($_POST['repair_fields'][$i])),
                                $_POST['from_fields'][$i],
                                $_POST['to_fields'][$i],
                                $_POST['cost_fields'][$i],
                                $_POST['repair_ids'][$i]
                            );
                    }
                    else
                    {
                        $this->asset_model->insert_fixed_asset_repair_data(
                                $asset_id,
                                $_POST['date_fields'][$i],
                                trim(stripslashes($_POST['repair_fields'][$i])),
                                $_POST['from_fields'][$i],
                                $_POST['to_fields'][$i],
                                $_POST['cost_fields'][$i]
                            );
                    }
                }
            }
            
        }


        public function viewAssetCodes()
        {
            $data['assets'] = $this->asset_model->getAssetCode();
            $this->load->view('Main/list_assets_view',$data);
        }
        
        public function edit_asset($asset_id)
        {            
            $this->form_validation->set_rules('asset_code', 'Asset Code', 'required|xss_clean');
            $this->form_validation->set_rules('description', 'Description', 'required|callback_description_exist|xss_clean');
            $this->form_validation->set_rules('asset_count', 'Asset Count', 'required|xss_clean');
            
            $data['asset'] = $this->asset_model->getAssetCodeById($asset_id);            

            if($this->form_validation->run() == FALSE)
            {
                $this->load->view('Main/modifyAssetCode_view',$data);
            }
            else
            {            
                $last_modified = date("Y-m-d H:i:s",time());
                $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);

                if($this->asset_model->updateAssetCode(                        
                    trim(stripslashes($this->input->post('asset_code'))),
                    strtoupper(stripslashes(trim($this->input->post('description')))),                        
                    trim($this->input->post('asset_count')),                    
                    $last_modified,
                    $modified_by,
                    $asset_id)
                ) 
                {                  
                    redirect('main/viewAssetCodes');
                }
            }
        }
        
        //server side validation of email address for users. This function checks the database for the email address entered by the user to see if it already exists
    public function description_exist($desc)
    {	
        $result = $this->asset_model->testAssetDesc($desc);
        
        if(count($result) >= 1)
        {
            $this->form_validation->set_message('description_exist', 'No Change Detected...');
            return false;
        }
        
        else
        {
           return true;
        }
    }
    
    public function filterAssetCode()
    {
        $filter = mysqli_real_escape_string($this->user_model->get_mysqli(),$_GET['filter']);

        $data = $this->asset_model->getFilteredAssetCode($filter);        

        if(empty($data)) 
            echo json_encode('empty');
        else 
            echo json_encode($data);
    }
    
    public function filterLocDiv()
    {
        $filter = mysqli_real_escape_string($this->user_model->get_mysqli(),$_GET['filter']);

        $data = $this->asset_model->getFilteredLocDiv($filter);        

        if(empty($data)) 
            echo json_encode('empty');
        else 
            echo json_encode($data);
    }
    
    public function viewLocationDivision()
    {
        $data['locDiv'] = $this->asset_model->getLocationDivision();
        $this->load->view('Main/list_locationDivision_view',$data);
    }
    
    public function edit_loc_div($div_id)
    {            
        $this->form_validation->set_rules('division_abbre', 'Division Abbre', 'required|xss_clean');
        $this->form_validation->set_rules('location_name', 'Location Name', 'required|xss_clean');
        $this->form_validation->set_rules('division_name', 'Division Name', 'required|callback_description_exist|xss_clean');
        
        $data['locDiv'] = $this->asset_model->getLocationDivisionByDivId($div_id);            

        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('Main/modifyLocDiv_view',$data);
        }
        else
        {            
            $last_modified = date("Y-m-d H:i:s",time());
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            
            //detect if location/division field was changed to know which id to pass            
            
            if($data['locDiv']['location_name'] != trim($this->input->post('location_name')))
            {
                if($this->asset_model->updateLocation(                        
                    trim(stripslashes($this->input->post('location_name'))),                                   
                    $last_modified,
                    $modified_by,
                    $data['locDiv']['location_id'])
                ) 
                {
                    
                }
                
            }
            
            //if($data['locDiv']['division_name'] != trim($this->input->post('division_name')))
            {
                if($this->asset_model->updateDivision(  
                    trim(stripslashes($this->input->post('division_abbre'))),
                    trim(stripslashes($this->input->post('division_name'))),                                   
                    $last_modified,
                    $modified_by,
                    $data['locDiv']['division_id'])
                ) 
                {                  
                   
                }
            }  
            
            //if user changed division abbreviation, update all associated asset tags to reflect
            if($data['locDiv']['division_abbre'] != trim($this->input->post('division_abbre')))
            {
                $this->asset_model->update_all_asset_tag_by_division(
                                $data['locDiv']['division_abbre'],                                
                                trim(stripslashes($this->input->post('division_abbre'))),
                                $div_id
                            );
            }
             redirect('main/viewLocationDivision');
        }
    }
    
    public function openFile($file=false){
		if(!$file) 
                    redirect('main/index');
		//$filename = base_url().'uploadedFiles/'.$file;
                $filename = 'uploadedFiles/'.$file;
               // die($filename);
		if(file_exists($filename)){
			header("Content-Description: File Transfer");
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: inline; filename='.$file);
			readfile($filename);
                        //die($filename);
			exit;
		}
                die('test '.$filename);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */