<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    
    //password,Snq3r@321,Qwerty1@3
    public $default_password1 = '29d96d3461a7e7ee306340b9bd07d6deacf42e1f3eb6cefaacf6a54d8e1908f1';
    public $default_password2 = 'd8bd620b7660e6f3280f33027ff97c6f620e76602481e662c93f34e77751075d'; 
    public $default_password3 = '1b66ee117bfb9a5e9defb85c7b10f618eccaf03171e23d312224cf9fe198791b';
    public $default_passwords;
	
    public function __construct()
    {
        parent::__construct();
        //session_start();
	$this->load->model('asset_model');
        $this->load->model('user_model');
        $this->default_passwords = array($this->default_password1,$this->default_password2,$this->default_password3);
        //$this->user_model->isUserLoggedIn();
    }

    public function index()
    {		
	//check if user is logged in from session
        $this->form_validation->set_rules('username', 'Username', 'required|valid_email|xss_clean');
	$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|regex_match[/^[!@#$%&\(\)\w]{5,30}$/]|xss_clean');	
	
		//decode username from url and check if user has access to application.
		if(isset($_GET['username']))	
		{   
			foreach($_GET as $loc=>$item)
				$_GET[$loc] = urldecode(base64_decode($item));
			
			$result2 = $this->user_model->checkUserFromURL($_GET[$loc]);
			if(count($result2)== 0)
			{
				$this->session->set_flashdata('message','Invalid username or password');
				echo '<script type="text/javascript">alert("You don\'t have privilege to view this application")</script>';
				echo '<script type="text/javascript">history.back();</script>';
				//redirect('http://cms.gov.jm/computer-inventory/home');				
			}
			else 
			{   				
				$_SESSION['fa_user_id'] = $result2['user_id'];            
				redirect('main');
			}
		}

        if($this->form_validation->run() == FALSE)
            $this->load->view('User/login_view');
        else
        {
            $hash_password = hash('haval256,4', 'x(93g'.$this->input->post("password").'4$b7@');
            $result = $this->user_model->login($this->input->post('username'),$hash_password);
            
            if(count($result) == 0)
            {
                $this->session->set_flashdata('message','Invalid username or password');
                //$this->load->view('User/login_view');
                redirect('user');
            }
            else 
            {
                $row = $result[0];
                $_SESSION['fa_user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                
                //if user password is a default password, keep redirecting to change password
                if(in_array($row['password'], $this->default_passwords))
                {
                    $this->session->set_flashdata('message', 'URGENT - Please Change your Current Password!!!');
                    redirect('user/changePassword');  
                }
                else
                    redirect("main");
            }
        }
        
    }
	
    public function computer_inventory()
    {
        //redirect('http://cms.gov.jm/computer-inventory/');
        if(isset($_SESSION['username']))
            redirect('http://cms.gov.jm/computer-inventory/users?username='.urlencode(base64_encode($_SESSION['username'])));
    }
	
	public function stock_system()
    {
        //redirect('http://cms.gov.jm/computer-inventory/');
        if(isset($_SESSION['username']))
            redirect('http://cms.gov.jm/stock/user?username='.urlencode(base64_encode($_SESSION['username'])));
    }
    
    public function user_detail($user_id)
    {
        $this->user_model->isUserLoggedIn();
        $this->form_validation->set_rules('username', 'Username', 'required|valid_email|callback_validCMSEmail|xss_clean');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('user_level', 'User Level', 'required|xss_clean');
        $this->form_validation->set_rules('account_enabled', 'account_enabled', 'required|xss_clean');
        
        $data['person'] = $this->user_model->getUserById($user_id);
        
        /*
        print "<pre>";
        print_r($data['user']);
        print "</pre>";
        die('test');
        */    
        
        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('User/modifyUser_view',$data);
        }
        else
        {   
            date_default_timezone_set('America/Bogota');
            $last_modified = date("Y-m-d H:i:s",time());
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
                    
            if($this->user_model->updateUser(                        
                $this->input->post('first_name'),
                $this->input->post('last_name'),                        
                $this->input->post('user_level'),
                $this->input->post('account_enabled'),
                $last_modified,
                $modified_by,
                $user_id)
            ) 
            {                  
                redirect('user/viewUsers');
            }
        }
    }
    
    public function viewUsers()
    {
        $this->user_model->isUserLoggedIn();
        $data['users'] = $this->user_model->getUsers();
        $this->load->view('User/list_users_view',$data);
    }
    
    public function addUser()
    {           
        $this->user_model->isUserLoggedIn();
        //function to test if a user is a supervisor in order to provide them with relevant options. Also to stop unauthorized users from gaining access to functionality above their user level 
        $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
        
        if($user['user_level'] != 2 && $user['user_level'] != 3)
        {            
            //$this->session->set_flashdata('message','You are not authorized to view this page');
            redirect('/');
        }
            
        $this->form_validation->set_rules('username', 'Username', 'required|valid_email|callback_emailExist|callback_validCMSEmail|xss_clean');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('user_level', 'User Level', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[16]|xss_clean');

        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('User/addUser_view');
        }
        else
        {   
            $modified_by = $this->user_model->getCurrentUsername($_SESSION['fa_user_id']);
            //salt added to password to increase complexity of the hash
            $password = hash('haval256,4', 'x(93g'.$this->input->post("password").'4$b7@');

            if($this->user_model->addUser($this->input->post('username'),$this->input->post('first_name'), $this->input->post('last_name'), $password, $this->input->post('user_level'),$modified_by))
			{		
				$fields = array('first_name'=>$this->input->post('first_name'), 'last_name'=>$this->input->post('last_name'), 'username'=>$this->input->post('username'), 'password'=>$password);
			
				//send users info to the computer-inventory system to be added as a viewing officer
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://cms.gov.jm/computer-inventory/notification/addUser");
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				
				$result = curl_exec($ch);
				if($result === FALSE) {
					//log in table to be send manually
					$this->user_model->logFailedAddUser($this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('username'), $password);
				}
				curl_close($ch);
				
                    redirect('user/viewUsers');
			}
            else
                    redirect('user/addUser');            
        }
    }
    /*
    public function isUserLoggedIn()
    {
        if(!isset($_SESSION['fa_user_id']) || empty($_SESSION['fa_user_id']) )
            redirect( "user" );
    }     
     */
    
    //function to test if a user is a supervisor in order to provide them with relevant options. Also to stop unauthorized users from gaining access to functionality above their user level 
    private function isSupervisor()
    {
        //$this->isUserLoggedIn();
        $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
        
        if($user['user_level'] != 2) 
            redirect("/");
    }   
    
    private function isAdmin()
    {
        //$this->isUserLoggedIn();
        $user = $this->user_model->getUserById($_SESSION['fa_user_id']);
        
        if($user['user_level'] != 3)
            redirect("/");
    } 
    
    //server side validation of email address for users. This function checks the database for the email address entered by the user to see if it already exists
    public function emailExist($email)
    {	
        $result = $this->user_model->testEmail($email);

        /*
        if(empty($result) || $result == array())
        {
            return true;
        }
        elseif($result[0]['user_id'] == $this->uri->segment(3))
        {
            return true;	
        }*/
        if(count($result) >= 1)
        {
            $this->form_validation->set_message('emailExist', 'There is already a user with that email');
            return false;
        }
        
        else
        {
           return true;
        }
    }
    
    public function changePassword()
    {
        $this->user_model->isUserLoggedIn();
        $this->form_validation->set_rules('cur_pass', 'Current Password', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[16]|regex_match[/^[!@#$%&\(\)\w]{5,16}$/]|xss_clean');
        $this->form_validation->set_rules('confirm_pass', 'Confirm Password', 'required|matches[password]|xss_clean');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('User/change_password');
        }
        else
        {
            if($this->input->post('cur_pass') == $this->input->post('password'))
            {
                //$data['message'] = "New password and current password should not be the same<br /><br />";
                $this->session->set_flashdata('message', 'New password and current password should not be the same');
                redirect('user/changePassword');
            }
            else
            {		
                $cur_pass = hash('haval256,4', 'x(93g'.$this->input->post("cur_pass").'4$b7@');
                $isvalid = $this->user_model->testPassword($_SESSION['fa_user_id'], $cur_pass);

                if(count($isvalid) == 0)
                {
                    //$data['message'] = "Incorrect password entered<br />";
                    $this->session->set_flashdata('message', 'Incorrect password entered');
                     redirect('user/changePassword');
                }
                else
                {
                    $pass = hash('haval256,4', 'x(93g'.$this->input->post("password").'4$b7@');
                    $result = $this->user_model->changeUserPassword($_SESSION['fa_user_id'], $pass);

                    if(!$result)
                    {
                        //$data['message'] = "Unable to change password<br />";
                        $this->session->set_flashdata('message', 'Unable to change password');
                         redirect('user/changePassword');
                    }
                    else
                    {
						//get username from signed in user to test if same username exists in applications
						$user_result = $this->user_model->getUserById($_SESSION['fa_user_id']);
						$email = $user_result['username'];
						
						$fields = array('username'=>$email, 'password'=>$pass);				
												
						//send user info to the computer-inventory and stock system to have password changed
						
						// create both cURL resources
						$ch_1 = curl_init();
						$ch_2 = curl_init();
						
						// set URL and other appropriate options
						curl_setopt($ch_1, CURLOPT_URL, "http://cms.gov.jm/computer-inventory/notification/changePassword");
	
						curl_setopt($ch_2, CURLOPT_URL, "http://cms.gov.jm/stock/notification/changePassword");
						
						curl_setopt($ch_1, CURLOPT_POST, TRUE);
						curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch_1, CURLOPT_POSTFIELDS, $fields);
						
						curl_setopt($ch_2, CURLOPT_POST, TRUE);
						curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch_2, CURLOPT_POSTFIELDS, $fields);
											
						/*
						$result = curl_exec($ch_1);
						$result2 = curl_exec($ch_2);
						
						curl_close($ch_1);
						curl_close($ch_2);						
						*/
						
						 // build the multi-curl handle, adding both $ch
						  $mh = curl_multi_init();
						  curl_multi_add_handle($mh, $ch_1);
						  curl_multi_add_handle($mh, $ch_2);
						  
						  // execute all queries simultaneously, and continue when all are complete
						  $running = null;
						  do {
							curl_multi_exec($mh, $running);
						  } while ($running);
						
                        //$data['message'] = "Your password was changed successfully<br />";
                        $this->session->set_flashdata('message', 'Your password was changed successfully');
                        redirect('main');
                    }
                }
            }
        }
    }
    
    //function to verify that a valid cms email was entered by the user. The email must end with @cms.gov.jm
    public function validCMSEmail($email)
    {
        if(!preg_match('/^[^\s]*\.[^\s]*@(cad.gov.jm|cms.gov.jm)$/',$email))
        {
            $this->form_validation->set_message('validCMSEmail', 'Please enter a valid email address');
            return false;
        }
        return true;
    }
    
    public function logout()
    {
        session_destroy();
        redirect('user');
    }
	
	public function forgotPassword()
    {
        date_default_timezone_set('America/Bogota');
        $this->form_validation->set_rules('email','Email Address','required|valid_email|callback_validCMSEmail|xss_clean');

        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('User/forgotPassword');
        }
        else
        {			
            //$myconfig['mailtype'] = 'html';
            //$this->load->library('email', $myconfig);
            //$this->email->set_newline( '\r\n' );

            //use this line to find if the email address entered corresponds to an account in the system.
            $emp = $this->user_model->getUserByEmailAddress($this->input->post('email'));

            if(empty($emp) || $emp == array())
            {
                $data['message'] = 'No such user exists';
                $this->load->view('User/forgotPassword', $data);
            }
            else
            {
                $token = hash('haval256,4', 'Yxs4pg'.time().'48BeEd');

                //save the request in the database
                if($this->user_model->saveResetEmailRequest($token, $this->input->post('email')))
                {
                    $link = '<a href="'.base_url('user/reset_password').'/'.$this->db->insert_id().'/'.$token.'">Reset Password</a>';
					//'http://cms.gov.jm'.
					
					$config['mailtype'] = 'html';
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = 'secure.emailsrvr.com';
					$config['smtp_port'] = '465';
					$config['smtp_user'] = 'website.admin@cad.gov.jm';
					$config['smtp_pass'] = 'dEvMqRpA8wX9oNw';
					$config['smtp_crypto'] = 'ssl';
					$config['smtp_timeout'] = '20';
					$config['charset'] = 'iso-8859-1';		

					$this->email->initialize($config);

					// Get user information from the session
					//$user_info = $this->get_user_info();

					// Set Email Variables
					$from_name = 'System Administrator';
					$from_emailaddress = 'webmaster@cms.gov.jm'; 
					$to = trim($this->input->post('email')); 
					
					$subject = "Fixed Asset Reset Password";

					$message = 'Good day,<br/><br/>'
                    .'A password reset was requested for your account. Please ignore this message if it was not sent                       by you.<br><br>Please click the link below to reset your password. It is only valid for 45                             minutes. <br/><br/>';
                    $message .= $link;                    
                    $message .= '<br/><br/>Regards <br/><br/>'
                    .'System Administrator';

					// Run Email methods
					$this->email->from($from_emailaddress, $from_name);
					$this->email->to($to);

					$this->email->subject($subject);
					$this->email->message($message);
					
					// Send Email
					$sent = $this->email->send();

					// Check for errors
					if($sent)
					{
						$data['message'] = 'Please access your email to reset your password.';
						$this->load->view('User/forgotPassword', $data);
					}
					else
					{
						$data['message'] = 'Possible database error. Please try again.';
						$this->load->view('User/forgotPassword', $data);
					}
					
				}    
                   
            }				
        }
    }
	
    public function reset_password()
    {
        date_default_timezone_set('America/Bogota');
        $reset_pass = $this->user_model->getResetPasswordRequestById($this->uri->segment(3));

        if(!empty($reset_pass) || $reset_pass != array())
        {
            //if a request with this id was found in the database

            //ensure that the tokens are the same, status is new, and the time is less than 10 minutes
            if($this->uri->segment(4)==$reset_pass['token'] && $reset_pass['status']=='New' && date('Y-m-d H:i:s', strtotime('+45 minutes', strtotime($reset_pass['date_created']))) >= date('Y-m-d H:i:s'))
            {

                $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[16]|regex_match[/^[!@#$%&\(\)\w]{6,16}$/]|xss_clean');
                $this->form_validation->set_rules('confirm_pass', 'Confirm Password', 'required|matches[password]|xss_clean');

                if($this->form_validation->run() === FALSE)
                {
                    $this->load->view('User/reset_password');
                }
                else
                {
                    $pass = hash('haval256,4', 'x(93g'.$this->input->post("password").'4$b7@');
                    $completed = $this->user_model->reset_password($reset_pass['email'], $pass, $this->uri->segment(3));

                    if($completed)
                    {
                        //$this->load->view('User/reset_password', array('message'=>'Your password was successfully changed. You can now login'));						
									
						$fields = array('username'=>$reset_pass['email'],'password'=>$pass);
						//send user info to the computer-inventory and stock system to have password changed
						
						// create both cURL resources
						$ch_1 = curl_init();
						$ch_2 = curl_init();
						
						// set URL and other appropriate options
						curl_setopt($ch_1, CURLOPT_URL, "http://cms.gov.jm/computer-inventory/notification/changePassword");
	
						curl_setopt($ch_2, CURLOPT_URL, "http://cms.gov.jm/stock/notification/changePassword");
						
						curl_setopt($ch_1, CURLOPT_POST, TRUE);
						curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch_1, CURLOPT_POSTFIELDS, $fields);
						
						curl_setopt($ch_2, CURLOPT_POST, TRUE);
						curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch_2, CURLOPT_POSTFIELDS, $fields);
											
						/*
						$result = curl_exec($ch_1);
						$result2 = curl_exec($ch_2);
						
						curl_close($ch_1);
						curl_close($ch_2);						
						*/
						
						 // build the multi-curl handle, adding both $ch
						  $mh = curl_multi_init();
						  curl_multi_add_handle($mh, $ch_1);
						  curl_multi_add_handle($mh, $ch_2);
						  
						  // execute all queries simultaneously, and continue when all are complete
						  $running = null;
						  do {
							curl_multi_exec($mh, $running);
						  } while ($running);
						
						
						$this->session->set_flashdata('message','Your password was successfully changed. You can now login');
						//$this->load->view('User/login_view');
						redirect('user');
                    }
                    else
                    {
                        $this->load->view('User/reset_password', array('message'=>'Error encountered while trying to reset your password. Please try again or contact ICT Administrator if issue persists'));
                    }
                }				
            }
            else
            {
                //One of the checks above failed
                $data['message'] = 'Invalid link.';
                $this->load->view('User/forgotPassword', $data);
            }			
        }
        else
        {
            //No request found
            $data['message'] = 'Invalid link.';
            $this->load->view('User/forgotPassword', $data);
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */