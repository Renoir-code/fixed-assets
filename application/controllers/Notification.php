<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//session_start();
		$this->load->library('form_validation');
		//$this->load->library('session');
		$this->load->model('user_model');
		$this->load->model('asset_model');
	}
	
	public function addUser()
	{		
		$user_level = 4; //default user to view only
		$modified_by = 'System Administrator';
		
		$this->user_model->addUser($this->input->post('email'),$this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('password'), $user_level,$modified_by);		
		
	}
	
	public function changePassword()
	{
		$user_id = $this->user_model->getUserId($this->input->post('username'));
		$this->user_model->changeUserPassword($user_id, $this->input->post('password'));
	}
	
	public function receiveNotification(){
		if(!isset($_POST['from_loc']))
			$this->asset_model->saveNotification($_POST["asset_type"], $_POST["make"], $_POST["model_number"], $_POST["serial_number"], $_POST["assigned_user"], $_POST["asset_tag"], $_POST["reason"], $_POST["username"]);
		else
			$this->asset_model->saveNotification($_POST["asset_type"], $_POST["make"], $_POST["model_number"], $_POST["serial_number"], $_POST["assigned_user"], $_POST["asset_tag"], $_POST["reason"], $_POST["username"], $_POST["from_loc"], $_POST["from_div"], $_POST["to_loc"], $_POST["to_div"]);
	}
	
	public function notifications(){
		if(!isset($_SESSION['fa_user_id'])) redirect('/');
		
		$updatedNotifications = $this->input->post('updatedNotifications');
		//$this->form_validation->set_rules('updatedNotifications','UpdatedNotifications','callback_ensureNumeric');
		
		$this->form_validation->set_rules('updatedNotifications','UpdatedNotifications','xss_clean');
		
		if($this->form_validation->run() === FALSE){
			
			$data['notificationsList'] = $this->asset_model->getNotifications();			
			$this->load->view('Main/showNotifications', $data);
		}else{
			//save the information by setting the update_flag to 1 for each of the selected notifications
			
			$completed = $this->asset_model->updateNotifications($updatedNotifications);
			
			if($completed){
				$this->session->set_flashdata('message','Notifications where successfully updated..');
				redirect('notification/notifications');
			}else{
				$this->session->set_flashdata('message','Notifications where not updated. Please try again');
				redirect('notification/notifications');
			}
		}		
	}
	
	public function ensureNumeric($updatedNotifications){
		//var_dump($updatedNotifications);
		//print_r($updatedNotifications);
		//die('test');
		
		if(count($updatedNotifications) <= 0){
			$this->form_validation->set_message('ensureNumeric','At least one notification needs to be selected!!!');
			return false;
		}
		
		foreach($updatedNotifications  as $updated){
			if(!is_numeric($updated)){
				$this->form_validation->set_message('ensureNumeric','Invalid value submitted for notifications');
				return false;
			} 
		}
		return true;
	}
	
	public function newlypurchasedassets(){
		if(!isset($_SESSION['fa_user_id'])) redirect('/');
		
		$updatedNotifications = $this->input->post('updatedNotifications');
			
		$data['NewlyPurchasedAssetList'] = $this->asset_model->getNewlyPurchasedAssets();			
		$data['NewlyPurchasedAssetsItems'] = $this->asset_model->getNewlyPurchasedAssetsItems();
		
		$this->form_validation->set_rules('updatedNotifications','UpdatedNotifications','xss_clean');
		
		if($this->form_validation->run() === FALSE){			
			$this->load->view('Main/showNewlyPurchasedAssets', $data);
		}else{
			//save the information by setting the update_flag to 1 for each of the selected notifications
			
			$completed = $this->asset_model->updateNewlypurchasedassets($updatedNotifications);
			
			if($completed){
				$this->session->set_flashdata('message','Notifications where successfully updated..');
				redirect('notification/newlypurchasedassets');
			}else{
				$this->session->set_flashdata('message','Notifications where not updated. Please try again');
				redirect('notification/newlypurchasedassets');
			}
		}		
	}
}